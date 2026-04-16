@extends('template')

@section('title', 'Crear material')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    #box-razon-social {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Material</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('materiales.index') }}">Materiales</a></li>
        <li class="breadcrumb-item active">Crear Material</li>
    </ol>

    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{ route('materiales.store') }}" method="post">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
                    @error('nombre')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion')}}</textarea>
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
                    <input type="text" name="unidad_medida" id="unidad_medida" class="form-control" value="{{ old('unidad_medida') }}"> @error('unidad_medida')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{ old('precio') }}">@error('precio')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>
            </div>    

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
@endpush