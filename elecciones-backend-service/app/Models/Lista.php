<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Eleccion;

class Lista extends Model
{
    protected $table = 'listas';

    protected $fillable = [
        'id',
        'eleccion_id',
        'nombre',
        'numero',
        'cantidad_escanios',
        'cantidad_votos',
    ];

    public function eleccion()
    {
        return $this->belongsTo(Eleccion::class);
    }
}
