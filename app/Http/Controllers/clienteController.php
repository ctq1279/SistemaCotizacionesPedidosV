<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateClienteRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Persona;

class clienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:ver-cliente|crear-cliente|editar-cliente|eliminar-cliente', ['only' => ['index']]);
        $this->middleware('permission:crear-cliente', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-cliente', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-cliente', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $clientes = Cliente::with('persona')->get();
        //dd($clientes);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.create');
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
            $persona->cliente()->create([
                'persona_id' => $persona->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado');
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
    public function edit(Cliente $cliente)
    {
        $cliente->load('persona');
        //dd($cliente);
        return view('clientes.edit',compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        //dd($request->validated());
        //dd($cliente);

        try {
            DB::beginTransaction();

            Persona::where('id', $cliente->persona->id)
                ->update($request->validated());

            $cliente->save();
            //dd($cliente);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            //dd($e->getMessage()); 
            //return redirect()->back()->withErrors(['error' => $e->getMessage()]);

        }

        return redirect()->route('clientes.index')->with('success', 'Cliente editado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado');
    }
}
