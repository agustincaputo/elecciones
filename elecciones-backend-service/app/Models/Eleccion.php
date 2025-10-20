<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lista;

class Eleccion extends Model
{
    protected $table = 'elecciones';

    protected $fillable = [
        'id',
        'fecha',
        'descripcion',
        'puesto_elegido',
        'region',
        'cantidad_votantes',
        'cantidad_escanios',
        'estado_eleccion',
    ];

    public function listas()
    {
        return $this->hasMany(Lista::class);
    }
}
