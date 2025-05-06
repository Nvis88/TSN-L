<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'base_datos',
        'plan_id',
        'contacto_nombre',
        'contacto_email',
        'contacto_tel',
        'activo',
        'fecha_vencimiento'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
