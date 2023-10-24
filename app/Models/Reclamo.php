<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    use HasFactory;
    protected $table = 'reclamo';

    protected $fillable = [
        'cliente_id',
        'pedido_id',
        'categoria',
        'descripcion',
        'evidencia',
        'prioridad_id'
    ];

    protected $attributes = [
        'estado' => 'espera',
    ];
}
