<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->integer('marca_id');
            $table->integer('categoria_id');
            $table->string('nombre');
            $table->money('precio_unit');
            $table->integer('cantidad_por_caja');
            $table->string('foto');
            $table->integer('punto_reorden');
            $table->integer('cantidad_cajas');

            $table->timestamps();

            //Foreign Key constraints
            $table->foreign('marca_id')->references('id_marca')->on('marca');
            $table->foreign('categoria_id')->references('id_categoria')->on('categoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
