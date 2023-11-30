<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_direcciones', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('direccion_id');

            $table->primary(['cliente_id','direccion_id']); //PK

            $table->timestamps();

            // FKS
            $table->foreign('cliente_id')->references('id_cliente')->on('cliente');
            $table->foreign('direccion_id')->references('id_direccion')->on('direccion');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_direcciones');
    }
};
