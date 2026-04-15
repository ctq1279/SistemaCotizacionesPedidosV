<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePedidoRequest;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Cotizacione;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class pedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:ver-pedido|crear-pedido|editar-pedido|eliminar-pedido', ['only' => ['index']]);
        $this->middleware('permission:crear-pedido', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-pedido', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-pedido', ['only' => ['destroy']]);
    }

    public function index()
    {

        $pedidos = Pedido::with('cliente.persona', 'user', 'comprobante')->latest()->get();
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productos = Producto::all();
        $clientes = Cliente::all();
        $comprobantes = Comprobante::all();
        $users = User::all();
        return view('pedidos.create', compact('productos', 'clientes', 'comprobantes', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePedidoRequest $request)
    {
        try {
            DB::beginTransaction();
            dd($request);
            // Crear el pedido
            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id,
                'comprobante_id' => $request->comprobante_id,
                'numero_comprobante' => $request->numero_comprobante,
                'fecha_hora' => now(),
                'user_id' => auth()->id(),
                'total' => $request->total,
            ]);

            // Guardar los detalles del pedido
            $productos = $request->arrayidproducto;
            $cantidades = $request->arraycantidad;

            foreach ($productos as $index => $productoId) {
                $producto = Producto::findOrFail($productoId);


                // Crear el detalle
                $pedido->detalles()->create([
                    'producto_id' => $productoId,
                    'cantidad' => $cantidades[$index],
                    'tipo_envio' => $request->tipo_envio,
                    'tiempo_entrega' => $request->tiempo_entrega,
                    'precio_unitario' => $producto->precio_venta,
                    'subtotal' => $cantidades[$index] * $producto->precio_venta,
                ]);

                $producto->save();
            }

            DB::commit();

            return redirect()->route('pedidos.index')->with('success', 'Pedido creado exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id) {}

    public function cambiarEstado(Request $request, $id)
    {
        try {
            // Registrar log inicial
            Log::info('Inicio de cambiarEstado para Pedido', ['id' => $id, 'estado' => $request->estado]);

            // Obtener el pedido por ID
            $pedido = Pedido::findOrFail($id);
            Log::info('Pedido encontrado', ['pedido' => $pedido]);

            // Validar que el estado proporcionado es válido
            $nuevoEstado = $request->get('estado');
            $estadosValidos = ['pendiente', 'en_proceso', 'entregada', 'cancelada'];

            if (!in_array($nuevoEstado, $estadosValidos)) {
                Log::warning('Estado no válido', ['estado_proporcionado' => $nuevoEstado]);
                return response()->json(['error' => 'Estado no válido'], 400);
            }

            // Cambiar el estado del pedido
            $pedido->estado = $nuevoEstado;
            $pedido->save();
            Log::info('Estado actualizado en pedido', ['pedido' => $pedido]);

            // Retornar respuesta exitosa
            Log::info('Estado actualizado correctamente');
            return response()->json(['success' => 'Estado actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Pedido no encontrado', ['id' => $id, 'exception' => $e->getMessage()]);
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        } catch (Exception $e) {
            Log::error('Error al actualizar el estado del pedido', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Error al actualizar el estado: ' . $e->getMessage()], 500);
        }
    }

    // Método para actualizar el comprobante
    public function updateComprobante(Request $request, $id)
    {
        // Encuentra el pedido por su ID
        $pedido = Pedido::find($id);

        // Asegúrate de que el pedido exista
        if (!$pedido) {
            return redirect()->route('pedidos.index')->with('error', 'Pedido no encontrado');
        }

        // Actualiza los campos tipo_comprobante y numero_comprobante en la tabla pedidos
        $pedido->tipo_comprobante = $request->input('tipo_comprobante');
        $pedido->numero_comprobante = $request->input('numero_comprobante');
        $pedido->tipo_envio = $request->input('tipo_envio');
        // Guarda los cambios en la tabla pedidos
        $pedido->save();

        return redirect()->route('pedidos.index')->with('success', 'Comprobante actualizado correctamente');
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
