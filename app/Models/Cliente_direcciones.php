<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente_direcciones extends Model
{
    use HasFactory;

    //declaramos el nombre de la tabla
    protected $table = 'cliente_direcciones';

    //declaramos el pk
    protected $primaryKey = ['cliente_id', 'direccion_id'];
    public $incrementing = false;

    protected $fillable = [
        'cliente_id',
        'direccion_id'
    ];

    public function Direccion(){
        return $this->belongsTo(Direccion::class, 'id_direccion', 'direccion_id');
    }

    public function Cliente(){
        return $this->belongsTo(Cliente::class, 'id_cliente', 'cliente_id');
    }
}
