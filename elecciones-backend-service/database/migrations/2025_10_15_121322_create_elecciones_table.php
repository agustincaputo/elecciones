<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('elecciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('descripcion', 255); 
            $table->string('puesto_elegido', 100)->nullable();
            $table->string('region', 100)->nullable(); 
            $table->integer('cantidad_votantes')->nullable(); 
            $table->integer('cantidad_escanios')->nullable(); 
            $table->string('estado_eleccion', 50)->default('pendiente');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elecciones');
    }
};
