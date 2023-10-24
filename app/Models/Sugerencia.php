<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sugerencia extends Model
{
    use HasFactory;
    protected $table = "sugerencia";

    protected $fillable = [
        'contenido',
        'cliente_id',
        'valoracion'
    ];
}