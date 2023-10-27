<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    //declaramos el nombre de la tabla
    protected $table = 'cliente';

    //declaramos el pk
    protected $primaryKey = 'id_cliente';

    // Relacionamento 1:M empresa - cliente
    public function Cliente(){
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id_empresa');
    } 
    // Relacionamento 1:1 usuario - cliente
    public function Usuario(){
        return $this->belongsTo(usuario::class, 'usuario_id', 'id_usuario');
    }
    // Relacionamento 1:M cliente - direccion
    public function Cliente_direcciones(){
        return $this->hasMany(Cliente_direcciones::class, 'cliente_id', 'id_cliente');
    }
}
