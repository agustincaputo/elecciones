<?php

namespace App\Services;

use App\Models\Lista;

class ListaService
{
    public function crearLista(array $data)
    {
        $lista = Lista::create([
            'eleccion_id' => $data['eleccion_id'],
            'nombre' => $data['nombre'],
            'numero' => $data['numero'],
        ]);

        return $lista;
    }

    public function index(array $filters = [])
    {
        $query = Lista::with('eleccion');
        $perPage = $filters['per_page'] ?? 20;

        if (isset($filters['nombre'])) {
            $query->where('nombre','like', $filters['nombre']);
        }

        if (isset($filters['numero'])) {
            $query->where('numero','like', $filters['numero']);
        }

        return $query->paginate();
    }

    public function update($id, array $data)
    {
        $lista = Lista::find($id);

        if (!$lista) {
            throw new \Exception('Lista no encontrada.');
        }

        $lista->update($data);

        return $lista->load('eleccion');
    }
}
