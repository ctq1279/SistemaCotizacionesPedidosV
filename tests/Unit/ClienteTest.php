<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cliente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ClienteTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test para almacenar un nuevo cliente
     *
     * @return void
     */
    public function test_store_cliente()
    {
        $user = User::factory()->create();
        // Datos válidos para crear una persona/cliente
        $data = [
            'razon_social' => 'Roma Textiles',
            'ciudad' => 'La Paz',
            'calle' => 'Calle Los Pinos',
            'nro_vivienda' => '123',
            'telefono' => '76543210', // Número único
            'email' => 'roma@example.com', // Correo único
            'tipo_persona' => 'juridica', // Puede ser físico o jurídica según reglas
        ];
        // Enviar solicitud
        $response = $this->actingAs($user)->post(route('clientes.store'), $data);
        // Verificar redirección y base de datos
        $response->assertRedirect(route('clientes.index'));
        $this->assertDatabaseHas('personas', [
            'razon_social' => $data['razon_social'],
            'ciudad' => $data['ciudad'],
            'calle' => $data['calle'],
            'nro_vivienda' => $data['nro_vivienda'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'tipo_persona' => $data['tipo_persona'],
        ]);
    }


    /**
     * Test para actualizar un cliente
     *
     * @return void
     */
    public function test_update_cliente(){
        $user = User::factory()->create();
        // Crear persona y cliente asociado
        $persona = Persona::factory()->create([
            'razon_social' => 'Carmen Textiles',
            'ciudad' => 'La Paz',
            'calle' => 'Calle Los Pinos',
            'nro_vivienda' => '123',
            'telefono' => '76543210',
            'email' => 'carmen@example.com',
            'tipo_persona' => 'juridica',
        ]);
        $cliente = Cliente::factory()->create([
            'persona_id' => $persona->id,
        ]);
        // Datos actualizados
        $data = [
            'razon_social' => 'Textiles Modificados',
            'ciudad' => 'El Alto',
            'calle' => 'Calle de los Andes',
            'nro_vivienda' => '456',
            'telefono' => '76543210', // Misma persona, no debe fallar
            'email' => 'carmen@example.com', // Misma persona, no debe fallar
            'tipo_persona' => 'fisica',
        ];
        // Enviar solicitud
        $response = $this->actingAs($user)->put(route('clientes.update', $cliente->id), $data);
        // Verificar redirección y base de datos
        $response->assertRedirect(route('clientes.index'));
        $this->assertDatabaseHas('personas', [
            'id' => $persona->id,
            'razon_social' => $data['razon_social'],
            'ciudad' => $data['ciudad'],
            'calle' => $data['calle'],
            'nro_vivienda' => $data['nro_vivienda'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'tipo_persona' => $data['tipo_persona'],
        ]);}


    /**
     * Test para eliminar un cliente
     *
     * @return void
     */
    public function test_destroy_cliente()
    {
        // Crear un cliente para eliminar
        $cliente = Cliente::factory()->create();

        // Verificar que el cliente existe en la base de datos antes de eliminar
        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id
        ]);

        // Llamada al método destroy mediante delete
        $response = $this->delete(route('clientes.destroy', $cliente->id));

        // Verificar que el cliente ha sido eliminado de la base de datos
        $this->assertDatabaseMissing('clientes', [
            'id' => $cliente->id
        ]);

        // Verificar que la redirección sea correcta
        $response->assertRedirect(route('clientes.index'));
        $response->assertSessionHas('success', 'Cliente eliminado');
    }
}
