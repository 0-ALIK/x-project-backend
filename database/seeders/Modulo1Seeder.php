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
        $categorias = [
            ['nombre' => 'Bebidas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Medicamentos', 'created_at' => now(), 'updated_at' => now()],
        ];

        Categoria::insert($categorias);

        $marcas = [
            [
                'nombre' => 'e-pura',
                'descripcion' => 'Descripción de la Marca A',
                'logo' => 'url/al/logo1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'pfizer',
                'descripcion' => 'Descripción de la Marca B',
                'logo' => 'url/al/logo2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Marca::insert($marcas);

        $productos = [
            [
                'marca_id' => 1, // ID de Samsung según el seeder de marcas
                'categoria_id' => 1, // ID de Smartphones según el seeder de categorías
                'cantidad_por_caja' => 10,
                'cantidad_cajas' => 50,
                'punto_reorden' => 20,
                'precio_unit' => 0.75,
                'nombre' => 'Botella de 1 litro',
                'foto' => 'https://url-de-la-imagen.com/galaxy_s21.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'marca_id' => 1, // ID de Samsung según el seeder de marcas
                'categoria_id' => 1, // ID de Smartphones según el seeder de categorías
                'cantidad_por_caja' => 10,
                'cantidad_cajas' => 50,
                'punto_reorden' => 20,
                'precio_unit' => 1.0,
                'nombre' => 'Botella de 2 litros',
                'foto' => 'https://url-de-la-imagen.com/galaxy_s21.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'marca_id' => 2, // ID de Nike según el seeder de marcas
                'categoria_id' => 2, // ID de Calzado deportivo según el seeder de categorías
                'cantidad_por_caja' => 20,
                'cantidad_cajas' => 30,
                'punto_reorden' => 15,
                'precio_unit' => 2.5,
                'nombre' => 'Vacuna del covid',
                'foto' => 'https://url-de-la-imagen.com/air_max_270.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        Producto::insert($productos);
    }
}
