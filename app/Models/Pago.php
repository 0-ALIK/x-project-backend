<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pago';

    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'pedido_id',
        'forma_pago_id',
        'monto',
        'fecha',
    ];

    // Relación con Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id', 'pedido_id');
    }

    // Relación con FormaPago
    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_id', 'id_forma_pago');
    }
}

