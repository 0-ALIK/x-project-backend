<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direccion', function (Blueprint $table) {
            $table->id('id_direccion');
            $table->unsignedBigInteger('provincia_id'); 
            $table->unsignedInteger('codigo_postal'); 
            $table->string('telefono'); 
            $table->string('detalles');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('provincia_id')->references('id_provincia')->on('provincia');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direccion');
    }
};
