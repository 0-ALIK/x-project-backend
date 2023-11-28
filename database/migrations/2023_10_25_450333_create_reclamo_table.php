<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamo', function (Blueprint $table) {
            $table->id('id_reclamo');                               //PK
            $table->unsignedBigInteger('cliente_id');               //FK
            $table->unsignedBigInteger('admin_id')->nullable();     //FK
            $table->unsignedBigInteger('pedido_id');                //FK
            $table->unsignedBigInteger('categoria_id');             //FK
            $table->unsignedBigInteger('prioridad_id');             //FK
            $table->unsignedBigInteger('estado_id');                //FK
            $table->string('descripcion');
            $table->string('evidencia')->nullable();

            $table->timestamps();

            //Foreign Key constraints
            $table->foreign('categoria_id')->references('id_r_categoria')->on('reclamo_categoria');
            $table->foreign('prioridad_id')->references('id_r_prioridad')->on('reclamo_prioridad');
            $table->foreign('estado_id')->references('id_r_estado')->on('reclamo_estado');
            
            $table->foreign('cliente_id')->references('id_cliente')->on('cliente');
            $table->foreign('admin_id')->references('id_admin')->on('admin');
            $table->foreign('pedido_id')->references('id_pedido')->on('pedido');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamo');
    }
};

