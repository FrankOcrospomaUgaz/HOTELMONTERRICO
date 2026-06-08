<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->nullable();
            $table->dateTime('fecha')->nullable();
            $table->dateTime('fechaningreso')->nullable();
            $table->dateTime('fechasalida')->nullable();
            $table->foreignId('persona_id')->unsigned()->constrained('personas')->nullable();
            $table->foreignId('tipodocumento_id')->unsigned()->constrained('tipo_documentos')->nullable();
            $table->foreignId('conceptopago_id')->unsigned()->constrained('concepto_pagos')->nullable();
            $table->decimal('total')->default(0.00)->nullable();
            $table->decimal('efectivo')->default(0.00)->nullable();
            $table->decimal('tarjeta')->default(0.00)->nullable();
            $table->decimal('yape')->default(0.00)->nullable();
            $table->decimal('deposito')->default(0.00)->nullable();
            $table->decimal('plin')->default(0.00)->nullable();
            $table->string('comentario')->default('');
            $table->string('situacion')->default('Disponible');
            $table->foreignId('movimiento_id')->unsigned()->constrained('movimientos')->nullable();
            $table->foreignId('habitacion_id')->unsigned()->constrained('habitacions')->nullable();
            $table->foreignId('usuario_id')->unsigned()->constrained('users')->nullable();
            $table->boolean('estado')->default(1);//Estado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
}
