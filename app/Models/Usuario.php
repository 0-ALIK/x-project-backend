<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{

    use HasFactory;
    protected $primaryKey = 'id_usuario';
    protected $table = 'usuario';
    protected $fillable = [
        'nombre',
        'correo',
        'pass',
        'rol',
        'foto',
        'telefono',
        'detalles',
    ];
}
