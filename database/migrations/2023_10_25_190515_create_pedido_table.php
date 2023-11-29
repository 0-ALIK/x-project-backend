<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('pedido', function (Blueprint $table) {

            $table->id('id_pedido');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('direccion_id');
            $table->string('detalles');
            $table->dateTime('fecha');
            $table->dateTime('fecha_cambio_estado');

            $table->timestamps();

            // FKS
            $table->foreign('cliente_id')->references('id_cliente')->on('cliente');
            $table->foreign('estado_id')->references('id_pedido_estado')->on('pedido_estado');
            $table->foreign('direccion_id')->references('id_direccion')->on('direccion');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
