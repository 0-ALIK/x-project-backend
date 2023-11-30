<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('marca_id'); //FK
            $table->unsignedBigInteger('categoria_id'); //FK
            $table->unsignedInteger('cantidad_por_caja'); 
            $table->unsignedInteger('cantidad_cajas'); 
            $table->integer('punto_reorden'); 
            $table->double('precio_unit'); 
            $table->string('nombre'); 
            $table->string('foto');

            $table->timestamps();

            // FOREIGN KEYS
            $table->foreign('categoria_id')->references('id_categoria')->on('categoria');
            $table->foreign('marca_id')->references('id_marca')->on('marca');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
