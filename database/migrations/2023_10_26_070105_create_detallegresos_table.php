<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallegresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallegresos', function (Blueprint $table) {
            $table->id();
            $table->string('nota');
            $table->string('tipo');
            $table->decimal('monto');

            $table->foreignId('movimiento_id')->unsigned()->constrained('movimientos');
            $table->boolean('estado')->default(1); //Estado

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detallegresos');
    }
}
