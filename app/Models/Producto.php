<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    protected $fillable = [
        'marca_id',
        'categoria_id',
        'nombre',
        'precio_unit',
        'cantidad_por_caja',
        'foto',
        'punto_reorden',
        'cantidad_cajas'
    ];
}
