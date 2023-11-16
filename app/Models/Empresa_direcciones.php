<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa_direcciones extends Model
{
    use HasFactory;

    //declaramos el nombre de la tabla
    protected $table = 'empresa_direcciones';

    //declaramos el pk
    protected $primaryKey = 'direccion_id';
    public $incrementing = false;

    protected $fillable =[
            'empresa_id',
            'direccion_id',
            'nombre'
    ];

    public function Direccion(){
        return $this->belongsTo(Direccion::class, 'id_direccion', 'direccion_id');
    }

    public function Empresa(){
        return $this->belongsTo(Empresa::class, 'id_empresa', 'empresa_id');
    }
}
