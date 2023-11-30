<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Modelo Empresa
class Empresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_empresa';
    protected $table = 'empresa';
    protected $fillable = [
        'usuario_id',
        'ruc',
        'razon_social',
        'documento',
        'estado',
    ];

    // Relacionamiento 1:1 empresa - usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }

}
