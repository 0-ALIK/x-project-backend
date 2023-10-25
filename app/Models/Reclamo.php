<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    use HasFactory;
    protected $table = 'reclamo';
    protected $primaryKey = 'id_reclamo';
    protected $fillable = [
        'cliente_id',
        'pedido_id',
        'categoria_id',
        'descripcion',
        'evidencia',
        'prioridad_id'
    ];

    protected $attributes = [
        'estado_id' => 1,
    ];
}
