<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('cedula')->unique();
            $table->string('apellido');
            $table->char('genero', 1);
            $table->string('estado');

            $table->timestamps();

            // FKS
            $table->foreign('empresa_id')->references('id_empresa')->on('empresa');
            $table->foreign('usuario_id')->references('id_usuario')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
