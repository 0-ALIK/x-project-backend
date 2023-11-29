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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('id_mensaje');

            $table->unsignedBigInteger('reclamo_id');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('mensaje');

            $table->timestamps();

            //FROENKYE
            $table->foreign('reclamo_id')->references('id_reclamo')->on('reclamo');
            $table->foreign('cliente_id')->references('id_cliente')->on('cliente');
            $table->foreign('admin_id')->references('id_admin')->on('admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
