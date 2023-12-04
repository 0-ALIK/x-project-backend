<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RCategoria extends Model
{
    use HasFactory;
    protected $table = 'reclamo_categoria';
    protected $primaryKey = 'id_categoria';

    protected $fillable = ['categoria'];
}
