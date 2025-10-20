<?php

namespace App\Http\Controllers;

use App\Services\EleccionService;
use Illuminate\Http\Request;

class EleccionController extends Controller
{
    protected $eleccionService;

    public function __construct(EleccionService $eleccionService)
    {
        $this->eleccionService = $eleccionService;
    }

    public function crearEleccion(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'cantidad_escanios' => 'required|numeric|min:0',
            'descripcion' => 'required|string|max:255',
            'puesto_elegido' => 'required|string|max:100',
            'region' => 'required|string|max:100',
        ]);

        $eleccion = $this->eleccionService->crearEleccion($request->all());

        return response()->json([
            'message' => 'Elección creada exitosamente.',
            'eleccion' => $eleccion,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:255',
            'puesto_elegido' => 'required|string|max:100',
            'region' => 'required|string|max:100',
        ]);

        $eleccion = $this->eleccionService->update($request->all());

        return response()->json([
            'message' => 'Elección actualizada exitosamente.',
            'eleccion' => $eleccion,
        ], 200);
    }

    public function index(Request $request)
    {
        $elecciones = $this->eleccionService->index($request->all());

        return response()->json([
            'elecciones' => $elecciones,
        ], 200);
    }

    public function show($id)
    {
        $eleccion = $this->eleccionService->find($id);

        return response()->json([
            'eleccion' => $eleccion,
        ], 200);
    }

    public function iniciarEleccion($id)
    {
        try {
            $eleccion = $this->eleccionService->iniciarEleccion($id);

            return response()->json([
                'message' => 'Elección iniciada correctamente',
                'eleccion' => $eleccion,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function cerrarEleccion($id)
    {
        try {
            $eleccion = $this->eleccionService->cerrarEleccion($id);

            return response()->json([
                'message' => 'Elección cerrada correctamente',
                'eleccion' => $eleccion,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function asignarEscanios($id)
    {
        try {
            $eleccion = $this->eleccionService->asignarEscanios($id);

            return response()->json([
                'message' => 'Escaños asignados correctamente.',
                'eleccion' => $eleccion,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
