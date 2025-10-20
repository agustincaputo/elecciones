<?php

namespace App\Services;

use App\Models\Eleccion;
use Illuminate\Support\Facades\DB;

class EleccionService
{
    public function crearEleccion(array $data)
    {
        $eleccion = Eleccion::create([
            'fecha' => $data['fecha'],
            'descripcion' => $data['descripcion'],
            'cantidad_escanios' => $data['cantidad_escanios'],
            'puesto_elegido' => $data['puesto_elegido'],
            'region' => $data['region'],
            'estado_eleccion' => 'Creada',
        ]);

        return $eleccion;
    }

    public function update(array $data)
    {
        $eleccion = Eleccion::find($data['id']);

        if (!$eleccion) {
            return null;
        }

        $eleccion->update($data);

        return $eleccion;
    }

    public function index(array $filters = [])
    {
        $query = Eleccion::with('listas');
        $perPage = $filters['per_page'] ?? 20;

        if (isset($filters['fecha_desde'])) {
            $query->where('fecha', '>=', $filters['fecha_desde']);
        }

        if (isset($filters['fecha_hasta'])) {
            $query->where('fecha', '<=', $filters['fecha_hasta']);
        }

        if (isset($filters['region'])) {
            $query->where('region', 'like', $filters['region']);
        }

        if (isset($filters['puesto_elegido'])) {
            $query->where('puesto_elegido', 'like', $filters['puesto_elegido']);
        }

        return $query->paginate();
    }

    public function show($id)
    {
        return Eleccion::findorFail($id);
    }

    public function iniciarEleccion($id)
    {
        $eleccion = Eleccion::withCount('listas')->find($id);

        if (!$eleccion) {
            throw new \Exception('Elección no encontrada.');
        }

        if ($eleccion->listas_count < 2) {
            throw new \Exception('La elección debe tener al menos dos listas para poder iniciarse.');
        }

        $eleccion->estado_eleccion = 'Iniciada';
        $eleccion->save();

        return $eleccion;
    }

    public function cerrarEleccion($id)
    {
        $eleccion = Eleccion::where('id', $id)
                            ->where('estado_eleccion', 'Iniciada')
                            ->first();

        if (!$eleccion) {
            throw new \Exception('Elección no encontrada.');
        }

        $eleccion->estado_eleccion = 'Cerrada';
        $eleccion->save();

        return $eleccion;
    }

    public function asignarEscanios($id)
    {
        return DB::transaction(function () use ($id) {
            $eleccion = Eleccion::with('listas')->lockForUpdate()->find($id);

            if (!$eleccion) {
                throw new \Exception('Elección no encontrada.');
            }

            if ($eleccion->estado_eleccion !== 'Cerrada') {
                throw new \Exception('La elección debe estar en estado "Cerrada" para asignar escaños.');
            }

            if ($eleccion->listas->count() < 2) {
                throw new \Exception('La elección debe tener al menos dos listas.');
            }

            $this->metodoDhondt($eleccion);

            $eleccion->cantidad_votantes = $eleccion->listas->sum('cantidad_votos');
            $eleccion->estado_eleccion = 'Finalizada';
            $eleccion->save();

            return $eleccion;
        });
    }

    private function metodoDhondt(Eleccion $eleccion)
    {
        $listas = $eleccion->listas;
        $escaniosTotales = $eleccion->cantidad_escanios;

        foreach ($listas as $lista) {
            $lista->cantidad_escanios = 0;
        }

        $resultados = [];

        foreach ($listas as $lista) {
            for ($i = 1; $i <= $escaniosTotales; ++$i) {
                $resultados[] = [
                    'lista_id' => $lista->id,
                    'valor' => $lista->cantidad_votos / $i,
                ];
            }
        }

        usort($resultados, fn ($a, $b) => $b['valor'] <=> $a['valor']);

        $top = array_slice($resultados, 0, $escaniosTotales);

        $asignaciones = [];
        foreach ($top as $item) {
            $asignaciones[$item['lista_id']] = ($asignaciones[$item['lista_id']] ?? 0) + 1;
        }

        foreach ($listas as $lista) {
            $lista->cantidad_escanios = $asignaciones[$lista->id] ?? 0;
            $lista->save();
        }
    }
}
