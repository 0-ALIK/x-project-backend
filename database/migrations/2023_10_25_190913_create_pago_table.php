<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('forma_pago_id');
            $table->double('monto');
            $table->dateTime('fecha');

            $table->timestamps();

            $table->foreign('pedido_id')->references('id_pedido')->on('pedido');
            $table->foreign('forma_pago_id')->references('id_forma_pago')->on('forma_pago');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
