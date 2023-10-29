<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    //declaramos el nombre de la tabla
    protected $table = 'usuario';

    //declaramos el pk
    protected $primaryKey = 'id_usuario';

    //declaramos los campos que se pueden escribir
    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'pass',
        'rol',
        'foto',
        'detalles'
    ];

    // Relacionamento 1:1 usuario - empresa
    public function Empresa(){
        return $this->hasOne(Empresa::class,'usuario_id', 'id_usuario');
    }
    //relacionamento 1:1 usuario - cliente
    public function Cliente(){
        return $this->hasOne(Cliente::class, 'usuario_id', 'id_usuario');
    }
}
