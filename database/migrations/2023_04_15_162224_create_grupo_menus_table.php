<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_menu', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('icono',100);
            $table->integer('orden');
            $table->boolean('estado')->default(1);//Estado
            $table->unique(['nombre']);//Campo Unico

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
        Schema::dropIfExists('grupo_menu');
    }
}
