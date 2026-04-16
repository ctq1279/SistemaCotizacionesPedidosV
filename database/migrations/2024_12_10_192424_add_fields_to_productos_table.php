<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('logos_insignias')->nullable(); // Añade logos o insignias (opcional)
            $table->string('forro')->nullable();          // Añade tipo de forro
            $table->string('material_tela')->nullable();           // Añade material de la tela
       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['logos_insignias', 'forro', 'material_tela']);
        });
        
    }
};
