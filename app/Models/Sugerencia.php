<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Sugerencia extends Model
{
    use HasFactory;
    protected $table = "sugerencia";

    protected $fillable = [
        'contenido',
        'cliente_id',
        'valoracion'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->validate();
        });
    }

    public function validate()
    {
        $rules = [
            'valoracion' => ['required', Rule::in([1, 2, 3, 4, 5])]
        ];

        $validator = Validator::make($this->attributes, $rules);

        if ($validator->fails()) {
            throw new \Exception('Valoración fuera del rango permitido (1-5). Por favor, ingrese un valor válido.');
        }
    }

    public static function obtenerSugerencia()
    {
        return self::get();
    }
}