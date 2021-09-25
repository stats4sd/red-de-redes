<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DeleteAllAgronometricTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('comunidad');
        Schema::dropIfExists('departamento');
        Schema::dropIfExists('cultivos');
        Schema::dropIfExists('fenologia');
        Schema::dropIfExists('manejo_parcela');
        Schema::dropIfExists('municipio');
        Schema::dropIfExists('pachagrama');
        Schema::dropIfExists('parcela');
        Schema::dropIfExists('plagas_y_enfermedades');
        Schema::dropIfExists('produccion');
        Schema::dropIfExists('suelo');
        Schema::dropIfExists('variedades');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
