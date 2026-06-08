<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nombre');
            $table->decimal('preciocompra');
            $table->decimal('precioventa');
        

            $table->foreignId('unidad_id')->unsigned()->constrained('unidads');
            $table->foreignId('categoria_id')->unsigned()->constrained('categorias');

            $table->boolean('estado')->default(1);//Estado
            $table->timestamps();
            $table->unique(['codigo']);//Campo Unico
            $table->unique(['nombre']);//Campo Unico
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
