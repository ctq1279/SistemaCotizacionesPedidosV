<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCotizacionRequest;
use App\Http\Requests\UpdateCotizacionRequest;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Cotizacione;
use App\Models\Materiale;
use App\Models\Pedido;
use App\Models\Producto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class cotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:ver-cotizacion|crear-cotizacion|editar-cotizacion|eliminar-cotizacion', ['only' => ['index']]);
        $this->middleware('permission:crear-cotizacion', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-cotizacion', ['only' => ['show']]);
        $this->middleware('permission:eliminar-cotizacion', ['only' => ['destroy']]);
    }

    public function index()
    {
        $cotizaciones = Cotizacione::with('cliente.persona')->latest()->get();
        //dd($cotizaciones);
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd('Llegamos a la función create');
        $clientes = Cliente::all();
        $productos = Producto::all();

        return view('cotizaciones.create', compact('clientes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



    public function store(StoreCotizacionRequest $request)
    {
        //dd($request);
        //dd($request->validated());
        try {
            DB::beginTransaction();

            // Agregar 'tiempo_entrega' y 'lugar_entrega' a los datos validados
            $validatedData = $request->validated();
            $validatedData['tiempo_entrega'] = $request->input('tiempo_entrega');
            $validatedData['lugar_entrega'] = $request->input('lugar_entrega');

            // Llenar tabla cotizaciones
            $cotizacion = Cotizacione::create($validatedData);

            //Llenar tabla cotizaciones
            //$cotizacion = Cotizacione::create($request->validated());

            //Llenar tabla compra_producto
            //1.Recuperar los arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecio = $request->get('arrayprecio');
            $arrayCostoManoObra = $request->get('arraycostomanoobra');
            $arrayCostotTotalMateriales = $request->get('arraycostototalmateriales');
            $arrayMargen = $request->get('arraymargen');

            //2.Realizar el llenado
            $siseArray = count($arrayProducto_id);
            $cont = 0;
            while ($cont < $siseArray) {
                $cotizacion->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_cotizacion' => $arrayPrecio[$cont],
                        'costo_produccion' => $arrayCostoManoObra[$cont],
                        'costo_materiales' => $arrayCostotTotalMateriales[$cont],
                        'costo_margen' => $arrayMargen[$cont]
                    ]
                ]);
                $cont++;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('cotizaciones.index')->with('success', 'cotizacion exitosa exitosa');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return view('cotizaciones.show',compact('cotizacion'));
        // Cargar la cotización junto con la relación 'cliente.persona'
        $cotizacion = Cotizacione::with('cliente.persona')->find($id);

        // Verificar si la cotización existe
        if (!$cotizacion) {
            return redirect()->route('cotizaciones.index')->with('error', 'Cotización no encontrada.');
        }

        // Pasar la cotización a la vista
        return view('cotizaciones.show', compact('cotizacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cotizacione $cotizacione)
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        $materiales = Materiale::all();

        
        //dd($cotizacione);
        //return view('cotizaciones.edit', compact('cotizacione', 'clientes', 'productos'));

        $cotizacione->load(['cliente', 'productos']); // Carga las relaciones
        //dd($cotizacione); // Verifica que las relaciones estén presentes
        return view('cotizaciones.edit', compact('cotizacione', 'clientes', 'productos','materiales'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCotizacionRequest $request, Cotizacione $cotizacione)
    {
        try {
            DB::beginTransaction();

            // Actualizar datos generales de la cotización
            $cotizacione->update([
                'estado' => $request->estado,
                'cliente_id' => $request->cliente_id,
                'impuestos' => $request->impuestos,
                'costo_mano_obra' => $request->costo_mano_obra,
                'fecha_hora' => $request->fecha_hora,
                'total' => $request->total,
                'tiempo_entrega' => $request->input('tiempo_entrega'), // Nuevo campo
                'lugar_entrega' => $request->input('lugar_entrega'),   // Nuevo campo
            ]);

            // Sincronizar productos asociados a la cotización
            $productosData = [];
            if ($request->has('cantidad')) {
                foreach ($request->cantidad as $productoId => $cantidad) {
                    $productosData[$productoId] = [
                        'cantidad' => $cantidad,
                        'costo_materiales' => $request->input("costo_materiales.$productoId", 0),
                        'precio_cotizacion' => $request->input("precio_cotizacion.$productoId", 0),
                        'descuento_porcentaje' => $request->input("descuento_porcentaje.$productoId", 0),
                        'descuento_monto' => $request->input("descuento_monto.$productoId", 0),
                    ];
                }
            }

            // Sincronizar productos con la cotización
            if (!empty($productosData)) {
                $cotizacione->productos()->sync($productosData);
            }

            // Si la cotización es aprobada, crear un pedido
            if ($request->estado === 'aprobada') {
                $pedido = Pedido::create([
                    'comprobante_id' => $cotizacione->comprobante_id,
                    'cliente_id' => $cotizacione->cliente_id,
                    'fecha_pedido' => now(),
                    'tiempo_entrega' => $cotizacione->tiempo_entrega ?? 'No especificado',
                    'lugar_entrega' => $cotizacione->lugar_entrega ?? 'No especificado',   // Usar lugar de entrega de la cotización
                    'tipo_envio' => $cotizacione->tipo_envio ?? 'No especificado',
                    'user_id' => auth()->id(),
                    'total' => $cotizacione->total,
                    'estado' => 'pendiente', // Estado inicial del pedido
                ]);

                // Adjuntar los productos al pedido
                foreach ($cotizacione->productos as $producto) {
                    $pedido->productos()->attach($producto->id, [
                        'cantidad' => $producto->pivot->cantidad,
                        'costo_materiales' => $producto->pivot->costo_materiales,
                        'precio_cotizacion' => $producto->pivot->precio_cotizacion,
                    ]);
                }

                session()->flash('success', 'Cotizacion registrada correctamente.');
            }

            DB::commit();

            return redirect()->route('cotizaciones.index')->with('success', 'Cotización actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al actualizar la cotización: ' . $e->getMessage()]);
        }
    }



    /**
     * Función para calcular el total de la cotización.
     */
    private function calcularTotal($cantidad, $costo_materiales, $costo_mano_obra, $impuestos)
    {
        $subtotal = ($cantidad * ($costo_materiales + $costo_mano_obra));
        $total = $subtotal * (1 + ($impuestos / 100)); // Calcular el total con impuestos
        return $total;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cotizacion = Cotizacione::find($id);
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada');
    }

    public function generarPDF($id, $descargar = false)
    {
        // Recupera la cotización y los productos relacionados
        $cotizacion = Cotizacione::with('productos', 'cliente')->findOrFail($id);

        // Datos de la empresa
        $empresa = [
            'nombre' => 'Mod In',
            'nit' => '3970143016',
            'direccion' => 'Calle 6, Edif. 76, Depto. 302 Zona Los Pinos, La Paz',
            'telefonos' => '2455927 - 64171364 - 63170340',
        ];

        // Generar el PDF con la vista
        $pdf = Pdf::loadView('cotizaciones.pdf', compact('cotizacion', 'empresa'));

        // Si es para descarga, descarga el archivo
        if ($descargar) {
            return $pdf->download('cotizacion-' . $id . '.pdf');
        }

        // Si es para visualizar el PDF en el navegador
        return $pdf->stream('cotizacion-' . $id . '.pdf');
    }
}
