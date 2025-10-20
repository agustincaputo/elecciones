<?php

namespace App\Http\Controllers;

use App\Services\ListaService;
use Illuminate\Http\Request;

class ListaController extends Controller
{
    protected $listaService;

    public function __construct(ListaService $listaService)
    {
        $this->listaService = $listaService;
    }

    public function crearLista(Request $request)
    {
        $data = $request->validate([
            'eleccion_id' => 'required|integer|exists:elecciones,id',
            'nombre' => 'required|string|max:255',
            'numero' => 'required|integer',
        ]);

        $lista = $this->listaService->crearLista($data);

        return response()->json([
            'message' => 'Lista creada exitosamente.',
            'lista' => $lista,
        ], 201);
    }

    public function index(Request $request)
    {
        $listas = $this->listaService->index($request->all());

        return response()->json([
            'listas' => $listas,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'sometimes|string|max:255',
                'numero' => 'sometimes|string|max:50',
                'cantidad_escanios' => 'sometimes|integer|min:0',
                'cantidad_votos' => 'sometimes|integer|min:0',
                'eleccion_id' => 'sometimes|exists:elecciones,id',
            ]);

            $lista = $this->listaService->update($id, $validated);

            return response()->json([
                'message' => 'Lista actualizada correctamente.',
                'lista' => $lista
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la lista: ' . $e->getMessage()
            ], 400);
        }
    }
}
