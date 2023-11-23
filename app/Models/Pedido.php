<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Pedido extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pedido'; // o el nombre real de tu clave primaria

    protected $table = 'pedido'; // o el nombre real de tu tabla


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

    // app/Models/Pedido.php

    public function estado()
    {
        return $this->belongsTo(PedidoEstado::class, 'estado_id', 'id_pedido_estado');
    }




    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
    public function empresa()
    {
        // Verificar si hay un cliente antes de intentar acceder a la empresa
        if ($this->cliente) {
            return $this->cliente->empresa;
        }

        return null;
    }
}


