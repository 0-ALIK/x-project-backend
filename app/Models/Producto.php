<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca_id',
        'categoria_id',
        'cantidad_por_caja',
        'cantidad_cajas',
        'punto_reorden',
        'precio_unit',
        'nombre',
        'foto',
    ];

    protected $table = 'producto'; // Asegúrate de que coincida con el nombre de tu tabla
    protected $primaryKey = 'id_producto'; // Asegúrate de que coincida con el nombre de tu clave primaria

    // Define las relaciones con otras tablas si es necesario
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id_categoria');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id', 'id_marca');
    }
}
