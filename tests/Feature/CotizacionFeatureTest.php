<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CotizacionFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_cotizacion_valida()
    {
        // Crear un usuario autenticado
        $user = User::factory()->create();

        // Crear datos de prueba
        $cliente = Cliente::factory()->create();
        $producto = Producto::factory()->create(['precio_unitario' => 100]);

        // Datos para enviar: incluye arrays de productos, cantidades, costos, etc.
        $data = [
            'cliente_id' => $cliente->id,
            'arrayidproducto' => [$producto->id],
            'arraycantidad' => [2],  // Cantidad de productos
            'arraycostomateriales' => [50],  // Costo del material por producto
            'arraycostomanoobra' => [20],    // Costo de mano de obra por producto
            'arraypreciocotizacion' => [100],  // Precio de cotización por producto
            'estado' => 'pendiente',
            'fecha_hora' => now(),  // Fecha actual para la cotización
            'total' => 240,         // El total calculado para la cotización
        ];

        // Enviar solicitud POST y capturar respuesta
        $response = $this->actingAs($user)->postJson('/cotizaciones', $data);

        // Verifica qué URL está devolviendo el sistema
        dump($response->headers->get('Location'));
        dd($response);

        // Verificar que la solicitud fue exitosa (esperamos 201 como código de estado)
        $response->assertRedirect(route('cotizaciones.index'));

        // Verificar que la cotización fue guardada en la base de datos
        $this->assertDatabaseHas('cotizaciones', [
            'cliente_id' => $cliente->id,
            'estado' => 'pendiente',
            'fecha_hora' => $data['fecha_hora'],
            'total' => $data['total'],
        ]);

        // Verificar que los productos fueron correctamente asociados a la cotización
        $this->assertDatabaseHas('cotizacion_producto', [
            'cotizacion_id' => $response->json('id'),  // Asumiendo que la cotización tiene un ID
            'producto_id' => $producto->id,
            'cantidad' => 2,
            'costo_materiales' => 50,
            'costo_mano_obra' => 20,
            'precio_cotizacion' => 100,
        ]);
    }
}
