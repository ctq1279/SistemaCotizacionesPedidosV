<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Materiale;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:ver-producto|crear-producto|editar-producto|eliminar-producto', ['only' => ['index']]);
        $this->middleware('permission:crear-producto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
    }
    public function index()
    {
        $productos = Producto::with('categorias', 'materiale')->latest()->get();
        //return view('producto.index',compact('productos'));
        //$productos = Producto::with(['categorias'])->get();
        //dd($productos);


        return view('producto.index', compact('productos'));
        //$productos = Producto::with(['categorias'])->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();
        $materiales = Materiale::all();
        //dd($categorias);
        return view('producto.create', compact('categorias', 'materiales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function calcularYGuardarCostoTotalMateriales(Request $request)
    {
        $productoId = $request->input('producto_id');

        if (!$productoId) {
            return response()->json([
                'message' => 'El ID del producto es obligatorio.',
            ], 400);
        }

        // Obtener los materiales relacionados al producto
        $materiales = DB::table('materiales')
            ->where('producto_id', $productoId) // Relación directa si `producto_id` está en `materiales`
            ->select('precio', 'cantidad') // Asumiendo que la tabla `materiales` tiene `precio` y `cantidad`
            ->get();

        // Calcular el costo total de materiales
        $costoTotalMateriales = $materiales->sum(function ($material) {
            return $material->precio * $material->cantidad;
        });

        // Actualizar el campo `costo_total_materiales` en la tabla `productos`
        DB::table('productos')
            ->where('id', $productoId)
            ->update(['costo_total_materiales' => $costoTotalMateriales]);

        return response()->json([
            'message' => 'El costo total de materiales se ha calculado y almacenado correctamente.',
            'costo_total_materiales' => $costoTotalMateriales,
        ]);
    }

    public function store(StoreProductoRequest $request)
    {

        //dd($request);
        //dd($request->all());
        try {
            DB::beginTransaction();
            //Tabla producto
            $producto = new Producto();
            if ($request->hasFile('img_path')) {
                $name = $producto->handleUploadImage($request->file('img_path'));
            } else {
                $name = null;
            }

            $producto->fill([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio_unitario' => $request->precio_unitario,
                'talla' => $request->talla,
                'color' => $request->color,
                'genero' => $request->genero,
                'costo_mano_obra' => $request->costo_mano_obra,
                'logos_insignias' => $request->logos_insignias,
                'forro' => $request->forro,
                'material_tela' => $request->material_tela,
                'img_path' => $name
            ]);

            $producto->save();
            Log::info('Producto guardado con éxito', ['producto_id' => $producto->id]);

            //dd($producto);
            //Tabla materiale producto
            $materiales = $request->get('materiales', []);
            //dd($materiales);
            if ($materiales) {
                // Guardar materiales en la tabla pivot
                $producto->materiale()->sync($materiales);
                Log::info('Materiales sincronizados con producto', [
                    'producto_id' => $producto->id,
                    'materiales' => $materiales
                ]);
            } else {
                Log::warning('No se seleccionaron materiales para el producto', ['producto_id' => $producto->id]);
            }

            // Calcular el costo total de materiales
            $this->actualizarCostoTotalMateriales($producto);

            //Tabla categoría producto
            $categorias = $request->get('categorias');
            //dd($categorias);
            $producto->categorias()->attach($categorias);


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar el producto', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            dd($e);
        }

        return redirect()->route('productos.index')->with('success', 'Producto registrado');
    }

    private function actualizarCostoTotalMateriales(Producto $producto)
    {
        // Obtener los materiales asociados al producto
        $materiales = $producto->materiale; // Esto obtiene todos los materiales relacionados al producto

        // Log para verificar si hay materiales asociados
        Log::info('Materiales asociados al producto', [
            'producto_id' => $producto->id,
            'materiales' => $materiales->pluck('nombre')->toArray() // Mostrar los nombres de los materiales asociados
        ]);
        // Calcular el costo total de materiales sumando el precio de cada material
        $totalMateriales = $materiales->sum(function ($material) {
            Log::info('Calculando precio de material', [
                'material_id' => $material->id,
                'material_nombre' => $material->nombre,
                'precio' => $material->precio
            ]);
            return $material->precio; // Precio * Cantidad
        });

        // Log para ver el resultado del cálculo
        Log::info('Costo total de materiales calculado', [
            'producto_id' => $producto->id,
            'costo_total_materiales' => $totalMateriales
        ]);
        // Actualizar el campo costo_total_materiales en el producto
        $producto->update(['costo_total_materiales' => $totalMateriales]);

        Log::info('Costo total de materiales actualizado', [
            'producto_id' => $producto->id,
            'costo_total_materiales' => $totalMateriales
        ]);
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
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        $materiales = Materiale::all();
        //dd($categorias);
        return view('producto.edit', compact('producto', 'categorias', 'materiales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        //dd('Hola');
        try {
            DB::beginTransaction();

            if ($request->hasFile('img_path')) {
                $name = $producto->handleUploadImage($request->file('img_path'));

                //Eliminar si existiese una imagen
                if (Storage::disk('public')->exists('productos/' . $producto->img_path)) {
                    Storage::disk('public')->delete('productos/' . $producto->img_path);
                }
            } else {
                $name = $producto->img_path;
            }

            $producto->fill([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio_unitario' => $request->precio_unitario,
                'talla' => $request->talla,
                'color' => $request->color,
                'genero' => $request->genero,
                'costo_mano_obra' => $request->costo_mano_obra,
                'logos_insignias' => $request->logos_insignias,
                'forro' => $request->forro,
                'material_tela' => $request->material_tela,
                'img_path' => $name
            ]);

            $producto->save();
            //dd($producto);
            //Tabla categoría producto
            $materiales = $request->get('materiales');
            //dd($categorias);
            $producto->materiale()->sync($materiales);
            //Tabla categoría producto
            $categorias = $request->get('categorias');
            //dd($categorias);
            $producto->categorias()->sync($categorias);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('productos.index')->with('success', 'Producto editado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto elimindado');
    }
}
