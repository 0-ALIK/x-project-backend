<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    //declaramos el nombre de la tabla
    protected $table = 'provincia';

    //declaramos el pk
    protected $primaryKey = 'id_provincia';

    //declaramos los campos que se pueden escribir
    protected $fillable = [
        'nombre'
    ];

    // Relacionamento 1:M provincia - direccion
    public function Direccion(){
        return $this->hasMany(Direccion::class, 'provincia_id', 'id_provincia');
    }
}
