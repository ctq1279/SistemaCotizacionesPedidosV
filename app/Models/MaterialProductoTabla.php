<?php

namespace App\Models;

use App\Models\Producto; // Asegúrate de importar el modelo Producto
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class MaterialProductoTabla extends Model
{
    protected $table = 'material_producto_tabla'; // El nombre de tu tabla pivot

    public $timestamps = false;

    protected $fillable = ['producto_id', 'materiale_id'];

    // Definimos los eventos para actualizar el costo total de materiales
    protected static function boot()
    {
        parent::boot();

        // Evento para cuando se crea un nuevo vínculo entre producto y material
        static::created(function ($materialProducto) {
            Log::debug("Evento creado disparado. Producto ID: {$materialProducto->producto_id}");
    
            // Verificar que el evento se está ejecutando
            Log::info('Se ha creado un vínculo entre producto y material');
            // Actualizar el costo total de materiales del producto
            self::actualizarCostoTotal($materialProducto->producto_id);
        });

        // Evento para cuando se elimina un vínculo entre producto y material
        static::deleted(function ($materialProducto) {
            Log::info('Se ha eliminado un vínculo entre producto y material');
            dd("Evento creado disparado. Producto ID: {$materialProducto->producto_id}");

            // Actualizar el costo total de materiales del producto
            self::actualizarCostoTotal($materialProducto->producto_id);
        });
    }

    // Función para actualizar el costo total de materiales del producto
    protected static function actualizarCostoTotal($productoId)
    {
        Log::debug("Actualizando costo total de materiales para el producto con ID: {$productoId}");

        // Obtener el producto con los materiales asociados
        $producto = Producto::find($productoId);
         // Obtener el producto con los materiales asociados
        if (!$producto) {
            Log::debug("Producto no encontrado: {$productoId}");
            return;
        }

        // Obtener los materiales del producto a través de la relación
        $materiales = $producto->materiale;

        // Si no hay materiales asociados al producto
        if ($materiales->isEmpty()) {
            Log::warning("El producto con ID {$productoId} no tiene materiales asociados.");
        }
        // Calcular el costo total sumando los precios de los materiales
        $costoTotal = $materiales->sum(function ($materiale) {
            return $materiale->precio; // Asegúrate de que cada material tenga el campo precio_unitario
        });
         // Registra en el log el costo total calculado
        Log::debug("Costo total calculado para el producto con ID {$productoId}: {$costoTotal}");

        // Actualizar el campo costo_total_materiales en la tabla productos
        $producto->update(['costo_total_materiales' => $costoTotal]);
    
        // Registra que el campo ha sido actualizado correctamente
        Log::debug("Costo total de materiales actualizado para el producto con ID {$productoId}");

    }
}
