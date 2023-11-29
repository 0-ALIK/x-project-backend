<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id('id_admin');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('permisos_id');
            $table->string('cedula')->unique();
            $table->string('apellido');
            $table->char('genero', 1);
            $table->json('permisos');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('usuario_id')->references('id_usuario')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
