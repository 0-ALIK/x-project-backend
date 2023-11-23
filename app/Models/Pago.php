<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $table = 'pago';

    protected $fillable = ['pedido_id', 'forma_pago_id', 'monto', 'fecha'];

    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
