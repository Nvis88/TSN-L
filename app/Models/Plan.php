<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'nombre',
        'limite_usuarios',
        'limite_espacio',
        'precio_mensual'
    ];

    public function estudios()
    {
        return $this->hasMany(Estudio::class);
    }
}
