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
        'detalles',
    ];

    // Relación con la tabla provincia
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }
}
