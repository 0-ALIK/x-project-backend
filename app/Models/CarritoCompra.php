<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoCompra extends Model
{
    use HasFactory;
    protected $table = 'compra';

    protected $fillable = [
        'usuario_id',
        'fecha',
        'producto_id',
        'cantidad',
        'precio_init',
        'detalles',
    ];

    // Relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con el modelo Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Método para calcular el precio total del producto en el carrito
    public function getTotal()
    {
        return $this->cantidad * $this->producto->precio;
    }
}

