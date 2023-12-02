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

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function categoria()
    {
        return $this->belongsTo(RCategoria::class, 'categoria_id', 'id_r_categoria');
    }

    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'prioridad_id', 'id_r_prioridad');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id_r_estado');
    }
}
