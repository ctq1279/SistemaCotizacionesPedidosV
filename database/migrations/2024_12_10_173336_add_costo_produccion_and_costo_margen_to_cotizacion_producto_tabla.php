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
        Schema::table('cotizacion_producto_tabla', function (Blueprint $table) {
            $table->decimal('costo_produccion', 10, 2)->nullable(); // Reemplaza "column_name" por la columna existente después de la cual deseas agregar este campo
            $table->decimal('costo_margen', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cotizacion_producto_tabla', function (Blueprint $table) {
            $table->dropColumn(['costo_produccion', 'costo_margen']);
        });
    }
};
