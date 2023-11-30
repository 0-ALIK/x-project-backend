<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_productos', function (Blueprint $table) {
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedInteger('cantidad');
            
            $table->primary(['pedido_id', 'producto_id']); //PK

            $table->timestamps();
            
            //FK
            $table->foreign('pedido_id')->references('id_pedido')->on('pedido');
            $table->foreign('producto_id')->references('id_producto')->on('producto');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_productos');
    }
};
