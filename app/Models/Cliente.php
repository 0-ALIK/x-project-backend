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
    // En el modelo Cliente
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id_empresa')->select(['id_empresa', 'ruc','razon_social','documento', 'estado']);
    }


    // Relacionamiento 1:1 usuario - cliente
    // En el modelo Cliente
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario')->select(['id_usuario', 'nombre', 'correo','foto','telefono', 'detalles']);
    }


    // Relacionamiento 1:M cliente - direccion
    public function clienteDirecciones()
    {
        return $this->hasMany(ClienteDirecciones::class, 'cliente_id', 'id_cliente');
    }
}

