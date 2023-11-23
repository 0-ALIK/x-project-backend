<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'logo',
    ];

    protected $table = 'marca'; // Asegúrate de que coincida con el nombre de tu tabla
    protected $primaryKey = 'id_marca'; // Asegúrate de que coincida con el nombre de tu clave primaria

    // Define las relaciones con otras tablas si es necesario
    public function productos()
    {
        return $this->hasMany(Producto::class, 'marca_id', 'id_marca');
    }
}
