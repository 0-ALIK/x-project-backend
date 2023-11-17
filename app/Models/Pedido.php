<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Pedido extends Model
{
    use HasFactory;
    protected $table = 'pedido'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'cliente_id',
        'estado_id',
        'direccion_id',
        'detalles',
        'fecha',
        'fecha_cambio_estado',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function estado()
    {
        return $this->belongsTo(PedidoEstado::class, 'estado_id');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }
}


