<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
     //declaramos el nombre de la tabla
     protected $table = 'admin';

     //declaramos el pk
     protected $primaryKey = 'id_admin';

     protected $casts = [
        'permisos' => 'array'
    ];
 
     //declaramos los campos que se pueden escribir
     protected $fillable = [
         'usuario_id',
         'cedula',
         'genero',
         'apellido',
         'permisos',
         'permisos_id'
     ];
 
     // Relacionamento 1:1 usuario - cliente
     public function Usuario(){
         return $this->belongsTo(usuario::class, 'usuario_id', 'id_usuario');
     }
}
