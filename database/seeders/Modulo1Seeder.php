<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;



class Modulo1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        #VALORES POR DEFECTO
        Categoria::create([
            'nombre'=> 'soda'
            ]);

        Marca::create([
            'nombre'=>'Femsa',
            'descripcion'=>'esto es una marca de sodas',
            'logo'=>'sin-logo',
        ]);

        Producto::create([
            'marca_id'=>2,
            'categoria_id'=>2,
            'nombre'=> 'Coca-2lt',
            'precio_unit'=> 2.00,
            'cantidad_por_caja'=>6,
            'foto'=>'sin-foto',
            'punto_reorden'=>100,
            'cantidad_cajas'=>100
        ]);
    }
}
