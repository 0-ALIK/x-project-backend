<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    use HasFactory;
    protected $table = 'forma_pago';
    protected $fillable = ['nombre'];

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
