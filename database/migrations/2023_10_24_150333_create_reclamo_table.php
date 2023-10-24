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
        Schema::create('reclamo', function (Blueprint $table) {
            $table->id();                                   //PK
            $table->integer('cliente_id');                  //FK
            $table->integer('admin_id')->nullable();        //FK
            $table->integer('pedido_id');                   //FK
            $table->unsignedBigInteger('categoria_id');                //FK
            $table->unsignedBigInteger('prioridad_id');                //FK
            $table->string('descripcion');
            $table->string('evidencia')->nullable();
            $table->string('estado');

            $table->timestamps();

            //Foreign Key constraints
            $table->foreign('categoria_id')->references('id')->on('categoria');
            $table->foreign('prioridad_id')->references('id')->on('prioridad');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamo');
    }
};
