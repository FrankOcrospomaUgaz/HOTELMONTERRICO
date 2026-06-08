<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol_personas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->unsigned()->constrained('personas');
            $table->foreignId('rol_id')->unsigned()->constrained('rols');
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
        Schema::dropIfExists('rol_personas');
    }
}
