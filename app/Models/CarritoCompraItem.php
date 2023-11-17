<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoCompraItem extends Model
{
    use HasFactory;
    protected $table = 'compras';

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
        return $this->belongsTo(Cliente::class, 'usuario_id');
    }

    // Relación con el modelo Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
