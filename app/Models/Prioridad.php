<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model
{
    use HasFactory;
    protected $table = 'reclamo_prioridad';
    protected $primaryKey = 'id_prioridad';

    protected $fillable = ['prioridad'];

}
