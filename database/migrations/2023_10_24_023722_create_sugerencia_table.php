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
        Schema::create('sugerencia', function (Blueprint $table) {
            $table->id(); //PK
            $table->integer('cliente_id');  //FK
            $table->string('contenido');  
            $table->timestamp('fecha');  
            $table->integer('valoracion');  
            $table->timestamps();

            //Foreign Key constraints
            //$table->foreign('cliente_id')->references('id')->on('clientes');
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sugerencia');
    }
};
