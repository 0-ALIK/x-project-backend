<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->id('id_empresa');
            $table->unsignedBigInteger('usuario_id');
            $table->BigInteger('ruc')->unique();
            $table->string('razon_social')->unique();
            $table->string('documento');
            $table->string('estado');

            $table->timestamps();

            // foreign keys
            $table->foreign('usuario_id')->references('id_usuario')->on('usuario'); 

        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
