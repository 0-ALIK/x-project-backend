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
        Schema::create('producto', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('marca_id');
            $table->unsignedBigInteger('categoria_id');
            $table->string('nombre');
            $table->decimal('precio_unit', 10, 2);
            $table->integer('cantidad_por_caja');
            $table->string('foto');
            $table->integer('punto_reorden');
            $table->integer('cantidad_caja');
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
        Schema::dropIfExists('producto');
    }
};
