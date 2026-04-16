<?php

namespace App\Listeners;

use App\Events\EstadoCotizacionActualizado;
use App\Models\Pedido;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrearPedidoDesdeCotizacion
{

    public function __construct()
    {
        //
    }

    public function handle(EstadoCotizacionActualizado $event)
    {
        $cotizacion = $event->cotizacion;

        // Solo crear el pedido si el estado es "aprobada"
        if ($cotizacion->estado === 'aprobada') {
            try {
                DB::beginTransaction();

                // Crear el pedido
                $pedido = Pedido::create([
                    'cotizacion_id' => $cotizacion->id,
                    'cliente_id' => $cotizacion->cliente_id,
                    'total' => $cotizacion->total,
                    'estado' => 'pendiente', // Estado inicial del pedido
                    'user_id' => auth()->id(), // Usuario actual
                    'fecha_pedido' => now(),
                ]);

                // Asociar los productos de la cotización al pedido
                foreach ($cotizacion->productos as $producto) {
                    $pedido->productos()->attach($producto->id, [
                        'cantidad' => $producto->pivot->cantidad,
                        'precio_pedido' => $producto->pivot->precio_cotizacion,
                        'total_pedido' => $producto->pivot->cantidad * $producto->pivot->precio_cotizacion,
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error("Error al crear el pedido desde la cotización: " . $e->getMessage());
            }
        }
    }
}
