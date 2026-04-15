@extends('template')

@section('title','Editar material')
    
@push('css')
    
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar materiales</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('materiales.index') }}">Materiales</a></li>
        <li class="breadcrumb-item active">Editar material</li>
    </ol>

    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{ route('materiales.update',['materiale'=>$materiale->id]) }}" method="post">
            @method('PATCH')
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre',$materiale->nombre)}}">
                    @error('nombre')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion', $materiale->descripcion)}}</textarea>
                    @error('descripcion')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>
                <!-----Cantidad---->
                <div class="col-sm-4 mb-2">
                    <label for="cantidad" class="form-label">Cantidad:</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control">
                </div>
                
                <div class="col-md-12">
                    <label for="unidad_medida" class="form-label">Unidad de medida:</label>
                    <textarea name="unidad_medida" id="unidad_medida" rows="3" class="form-control">{{old('unidad_medida', $materiale->unidad_medida)}}</textarea>
                    @error('unidad_medida')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="precio" class="form-label">Precio:</label>
                    <textarea name="precio" id="precio" rows="3" class="form-control">{{old('precio', $materiale->precio)}}</textarea>
                    @error('precio')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>
            </div>    
            
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <button type="reset" class="btn btn-secondary">Reiniciar</button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    
@endpush