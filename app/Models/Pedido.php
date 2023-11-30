<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pedido';
    protected $table = 'pedido';
    protected $fillable = [
        'cliente_id',
        'estado_id',
        'direccion_id',
        'detalles',
        'fecha',
        'fecha_cambio_estado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function estado()
    {
        return $this->belongsTo(PedidoEstado::class, 'estado_id', 'id_pedido_estado');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }

    public function pago()
    {
        return $this->hasMany(Pago::class, 'pedido_id', 'id_pedido');
    }
    public function pedido_productos()
    {
        return $this->hasMany(PedidoProducto::class, 'pedido_id', 'id_pedido')->with('producto');
    }


    public function empresa()
    {
        if ($this->cliente) {
            return $this->cliente->empresa;
        }

        return null;
    }

}
