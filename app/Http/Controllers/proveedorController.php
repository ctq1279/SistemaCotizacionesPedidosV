<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateProveedorRequest;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Proveedore;
use Exception;
use Illuminate\Support\Facades\DB;

class proveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:ver-proveedore|crear-proveedore|editar-proveedore|eliminar-proveedore', ['only' => ['index']]);
        $this->middleware('permission:crear-proveedore', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-proveedore', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-proveedore', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $proveedores = Proveedore::with('persona')->get();
        return view('proveedores.index',compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonaRequest $request)
    {
        try {
            DB::beginTransaction();
            $persona = Persona::create($request->validated());
            $persona->proveedore()->create([
                'persona_id' => $persona->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado');
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
    public function edit(Proveedore $proveedore)
    {
        $proveedore->load('persona');
        //dd($cliente);
        return view('proveedores.edit',compact('proveedore'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProveedorRequest $request, Proveedore $proveedore)
    {
        try {
            DB::beginTransaction();

            Persona::where('id', $proveedore->persona->id)
                ->update($request->validated());

            $proveedore->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

        }

        return redirect()->route('proveedores.index')->with('success', 'Proveedor editado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proveedore = Proveedore::find($id);
        $proveedore->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado');
    }
}
