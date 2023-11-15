<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $table = 'marca';
    protected $primaryKey = 'id_marca';
    protected $fillable = [
        'nombreMarca',
        'descripcion',
        'logo'
    ];

    public function productos(){
        return $this->hasMany(Producto::class, 'id_marca');
    }
}
