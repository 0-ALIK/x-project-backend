<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Direccion extends Model
{
    use HasFactory;
    protected $table = 'direccion'; // Asegúrate de que el nombre de la tabla sea el correcto
    protected $primaryKey = 'id_direccion';
    protected $fillable = [
        'provincia_id',
        'codigo_postal',
        'telefono',
        'detalles'
    ];

    // Relacionamento 1:M provincia - direccion
    public function Provincia(){
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id_provincia');
    }

    public function Empresa_direcciones(){
        return $this->hasMany(Empresa_direcciones::class, 'direccion_id', 'id_direccion');
    }

    public function Cliente_direcciones(){
        return $this->hasMany(Cliente_direcciones::class, 'direccion_id', 'id_direccion');
    }
}
