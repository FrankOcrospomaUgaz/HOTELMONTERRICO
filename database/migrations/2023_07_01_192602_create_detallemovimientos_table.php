<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallemovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallemovimientos', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad');
            $table->decimal('preciocompra');
            $table->decimal('precioventa');
            $table->decimal('descuento');
            $table->foreignId('servicio_id')->unsigned()->constrained('servicios');
            $table->foreignId('producto_id')->unsigned()->constrained('productos');
            $table->foreignId('movimiento_id')->unsigned()->constrained('movimientos');
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
        Schema::dropIfExists('detallemovimientos');
    }
}
