<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id('id_permisos');
            $table->boolean('gestion_inventario');
            $table->boolean('gestion_clientes');
            $table->boolean('gestion_ventas');
            $table->boolean('gestion_soporte');
            $table->boolean('gestion_analitica');
            $table->boolean('gestion_permisos');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
