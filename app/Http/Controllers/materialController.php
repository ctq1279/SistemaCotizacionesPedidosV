<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Materiale;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class materialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:ver-material|crear-material|editar-material|eliminar-material', ['only' => ['index']]);
        $this->middleware('permission:crear-material', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-material', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-material', ['only' => ['destroy']]);
    }

    public function index()
    {
        $materiales = Materiale::latest()->get();
        //return view('categorias.index', compact('categorias'));
        //dd($categorias);
        return view('materiales.index',['materiales' => $materiales]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('materiales.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMaterialRequest $request)
    {
        try{
            DB::beginTransaction();
            $material = Materiale::create($request->validated());

            DB::commit();
        }catch(Exception  $e){
            DB::rollBack();
        }

        return redirect()->route('materiales.index')->with('success','Material registrado');
   
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
    public function edit(Materiale $materiale)
    {
        return view('materiales.edit',['materiale'=>$materiale]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaterialRequest $request, Materiale $materiale)
    {
        $materiale->update($request->validated());
        return redirect()->route('materiales.index')->with('success','Material editado');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $material= Materiale::find($id);
        $material->delete();
        return redirect()->route('materiales.index')->with('success','Material elimindado');
    
    }
}
