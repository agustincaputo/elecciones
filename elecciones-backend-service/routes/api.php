<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EleccionController;
use App\Http\Controllers\ListaController;

Route::prefix('elecciones')->group(function () {
    Route::post('/', [EleccionController::class, 'crearEleccion']);
    Route::get('/', [EleccionController::class, 'index']);
    Route::get('/{id}', [EleccionController::class, 'show']);
    Route::put('/{id}', [EleccionController::class, 'update']);
    Route::post('/{id}/iniciar', [EleccionController::class, 'iniciarEleccion']);
    Route::post('/{id}/cerrar', [EleccionController::class, 'cerrarEleccion']);
    Route::post('/{id}/asignar-escanios', [EleccionController::class, 'asignarEscanios']);


});

Route::prefix('listas')->group(function () {
    Route::post('/', [ListaController::class, 'crearLista']);
    Route::get('/', [ListaController::class, 'index']);
    Route::put('/{id}', [ListaController::class, 'update']);
});
