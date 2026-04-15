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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade'); // Se crea la relación con la tabla clientes
       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
             // Eliminar la columna cliente_id si se revierte la migración
             $table->dropForeign(['cliente_id']);
             $table->dropColumn('cliente_id');
        });
    }
};
