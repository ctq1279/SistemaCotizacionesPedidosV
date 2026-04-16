<?php
namespace Tests\Unit;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function store_producto()
    {   // Crear una categoría
        $categoria = Categoria::create(['nombre' => 'Ropa']);
        // Datos válidos para el producto
        $data = [
            'nombre' => 'Camiseta',
            'descripcion' => 'Camiseta de algodón',
            'precio_unitario' => 20.00,
            'talla' => 'M',
            'color' => 'Azul',
            'genero' => 'Unisex',
            'costo_mano_obra' => 5.00,
            'categorias' => [$categoria->id], // Asociar la categoría
        ];
        // Enviar la solicitud POST
        $response = $this->post(route('productos.store'), $data);
        // Verificar que el producto se haya creado
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Camiseta',
            'descripcion' => 'Camiseta de algodón',
        ]);
        // Verificar que el producto esté asociado con la categoría correcta
        $producto = Producto::first();
        $this->assertTrue($producto->categorias->contains($categoria));
        // Verificar la redirección y el mensaje de éxito
        $response->assertRedirect(route('productos.index'));
        $response->assertSessionHas('success', 'Producto registrado');
    }

    /** @test */
    public function update_producto()
    {   // Crear una categoría y un producto
        $categoria = Categoria::create(['nombre' => 'Ropa']);
        $producto = Producto::create([
            'nombre' => 'Camiseta',
            'descripcion' => 'Camiseta de algodón',
            'precio_unitario' => 20.00,
            'talla' => 'M',
            'color' => 'Azul',
            'genero' => 'Unisex',
            'costo_mano_obra' => 5.00,
        ]);
        $producto->categorias()->attach($categoria);
        // Nuevos datos para la actualización
        $newData = [
            'nombre' => 'Camiseta Actualizada',
            'descripcion' => 'Camiseta de algodón actualizada',
            'precio_unitario' => 25.00,
            'talla' => 'L',
            'color' => 'Rojo',
            'genero' => 'Hombre',
            'costo_mano_obra' => 6.00,
            'categorias' => [$categoria->id],
        ];
        // Enviar la solicitud PUT para actualizar el producto
        $response = $this->put(route('productos.update', $producto->id), $newData);
        // Verificar que los cambios se hayan guardado en la base de datos
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Camiseta Actualizada',
            'descripcion' => 'Camiseta de algodón actualizada',
        ]);
        // Verificar que las categorías asociadas se actualicen
        $producto->refresh();
        $this->assertTrue($producto->categorias->contains($categoria));
        // Verificar la redirección y el mensaje de éxito
        $response->assertRedirect(route('productos.index'));
        $response->assertSessionHas('success', 'Producto editado');
    }

    /** @test */
    public function destroy_producto()
    {
        // Crear una categoría y un producto
        $categoria = Categoria::create(['nombre' => 'Ropa']);
        $producto = Producto::create([
            'nombre' => 'Camiseta',
            'descripcion' => 'Camiseta de algodón',
            'precio_unitario' => 20.00,
            'talla' => 'M',
            'color' => 'Azul',
            'genero' => 'Unisex',
            'costo_mano_obra' => 5.00,
        ]);
        $producto->categorias()->attach($categoria);

        // Verificar que el producto existe en la base de datos antes de eliminarlo
        $this->assertDatabaseHas('productos', ['nombre' => 'Camiseta']);

        // Enviar la solicitud DELETE para eliminar el producto
        $response = $this->delete(route('productos.destroy', $producto->id));

        // Verificar que el producto se haya eliminado de la base de datos
        $this->assertDatabaseMissing('productos', ['nombre' => 'Camiseta']);

        // Verificar la redirección y el mensaje de éxito
        $response->assertRedirect(route('productos.index'));
        $response->assertSessionHas('success', 'Producto elimindado');
    }
}
