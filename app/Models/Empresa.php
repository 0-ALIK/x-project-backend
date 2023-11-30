<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    //declaramos el nombre de la tabla
    protected $table = 'empresa';

    //declaramos el pk
    protected $primaryKey = 'id_empresa';

    //declaramos los campos que se pueden escribir
    protected $fillable = [
        'usuario_id',
        'razon_social',
        'ruc',
        'documento',
        'estado'
    ];
    
    // Relacionamento 1:M empresa - cliente
    public function Cliente(){
        return $this->hasMany(Cliente::class, 'empresa_id', 'id_empresa');
    }
    // Relacionamento 1:1 usuario - empresa
    public function Usuario(){
        return $this->belongsTo(usuario::class, 'usuario_id', 'id_usuario');
    } 
    // Relacionamento 1:M empresa - direccion
    public function Direcciones(){
        return $this->hasMany(Empresa_direcciones::class, 'empresa_id', 'id_empresa');
    }
}
