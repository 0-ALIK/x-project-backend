<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa_direcciones', function (Blueprint $table) {

            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('direccion_id');

            $table->primary(['empresa_id','direccion_id']); //PK
            $table->string('nombre'); //nombre de la sucursal

            $table->timestamps();
            
            // FKS
            $table->foreign('empresa_id')->references('id_empresa')->on('empresa');
            $table->foreign('direccion_id')->references('id_direccion')->on('direccion');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_direcciones');
    }
};
