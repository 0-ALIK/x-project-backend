<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Cliente_direcciones;
use App\Models\Empresa_direcciones;
use App\Models\Direccion;

class Modulo3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //crea 3 empresas con 10 clientes cada uno
            Empresa::factory()
            ->forUsuario(['rol' => 'empresa'])
            ->hasCliente(10)
            ->create();

            Empresa::factory()
            ->forUsuario(['rol' => 'empresa'])
            ->hasCliente(10)
            ->create();

            Empresa::factory()
            ->forUsuario(['rol' => 'empresa'])
            ->hasCliente(10)
            ->create();

            Direccion::factory()
            ->count(3)
            ->create();

            Cliente_direcciones::create([
                'cliente_id' => 2,
                'direccion_id' => 1,
            ]);

            Empresa_direcciones::create([
                'empresa_id' => 1,
                'direccion_id' => 1,
            ]);
    }
}