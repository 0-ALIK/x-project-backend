<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoEstado extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pedido_estado';

    protected $table = 'pedido_estado'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre',
    ];

    // Relación con la tabla de pedidos
    public function pedido()
    {
        return $this->hasMany(Pedido::class, 'estado_id');
    }
}
