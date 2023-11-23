<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';
    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'cedula',
        'genero',
        'apellido',
        'documento',
        'estado'
    ];

    // Relacionamiento 1:M empresa - cliente
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id_empresa');
    }

    // Relacionamiento 1:1 usuario - cliente
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }

    // Relacionamiento 1:M cliente - direccion
    public function clienteDirecciones()
    {
        return $this->hasMany(ClienteDirecciones::class, 'cliente_id', 'id_cliente');
    }
}

