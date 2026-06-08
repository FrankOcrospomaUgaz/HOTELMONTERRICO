<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockHabitacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_habitacions', function (Blueprint $table) {
            $table->id();

            $table->integer('cantidad');
            $table->foreignId('producto_id')->unsigned()->constrained('productos');
            $table->foreignId('habitacion_id')->unsigned()->constrained('habitacions');
            $table->boolean('estado')->default(1); //Estado
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
        Schema::dropIfExists('stock_habitacions');
    }
}
