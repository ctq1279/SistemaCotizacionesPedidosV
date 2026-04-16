@extends('template')

@section('title','Productos')


@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')

@if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: 'message'
            });
        </script>
    @endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>
    <div class=" mb-4">
        <a href="{{route('productos.create')}}">
            <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Tabla prendas
    </div>
    <div class="card-body">
        <table id="datatablesSimple" class="table table-striped fs-6">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Modelo</th>
                    <th>Precio unitario</th>
                    <th>Tallas</th>
                    <th>Colores</th>
                    <th>Género</th>
                    <th>Costo total de materiales</th>
                    <th>Costo producción</th>
                    <th>Logos e insignias</th>
                    <th>Forros</th>
                    <th>Material utilizado</th>
                    <th>Materiales</th>
                    <th>Categorias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $item)
                <tr>
                    <td>
                        {{$item->nombre}}
                    </td>
                    <td>
                        {{$item->descripcion}}
                    </td>
                    <td>
                        {{$item->precio_unitario}}
                    </td>
                    <td>
                        {{$item->talla}}
                    </td>
                    <td>
                        {{$item->color}}
                    </td>
                    <td>
                        {{$item->genero}}
                    </td>
                    <td>{{ number_format($item->costo_total_materiales, 2) }} Bs</td>
                    <td>
                        {{$item->costo_mano_obra}}
                    </td>
                    <td>
                        {{$item->logos_insignias}}
                    </td>
                    <td>
                        {{$item->forro}}
                    </td>
                    <td>
                        {{$item->material_tela}}
                    </td>

                    <td>
                        @foreach ($item->materiale as $material)
                        <div class="container" style="font-size: small;">
                            <div class="row">
                                <span class="m-1 rounded-pill p-1 bg-secondary text-white text-center">{{$material->nombre}}</span>
                            </div>
                        </div>
                        @endforeach
                       
                    </td>
                    
                    <td>
                        @foreach ($item->categorias as $category)
                        <div class="container" style="font-size: small;">
                            <div class="row">
                                <span class="m-1 rounded-pill p-1 bg-secondary text-white text-center">{{$category->nombre}}</span>
                            </div>
                        </div>
                        @endforeach
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                        <form action="{{route('productos.edit',['producto' => $item])}}" method="get">   
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>

                            <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verModal-{{$item->id}}">Ver</button>
                            <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Eliminar</button>
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="verModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles de la prenda</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Nombre: </span>{{$item->nombre}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Descripción: </span>{{$item->descripcion=='' ? 'No tiene' : $item->descripcion}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Precio: </span>{{$item->precio_unitario}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Talla: </span>{{$item->talla=='' ? 'No tiene' : $item->talla}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Color: </span>{{$item->color=='' ? 'No tiene' : $item->color}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Genero: </span>{{$item->genero=='' ? 'No tiene' : $item->genero}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Costo mano de obra: </span>{{$item->costo_mano_obra=='' ? 'No tiene' : $item->costo_mano_obra}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Logos e logos_insignias: </span>{{$item->logos_insignias=='' ? 'No tiene' : $item->logos_insignias}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Forro: </span>{{$item->forro=='' ? 'No tiene' : $item->forro}}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><span class="fw-bolder">Material utilizado: </span>{{$item->material_tela=='' ? 'No tiene' : $item->material_tela}}</p>
                                    </div>

                                   
                                    <div class="col-12">
                                        <p class="fw-bolder">Imagen:</p>
                                        <div>
                                            @if ($item->img_path != null)
                                            <img src="{{ Storage::url('public/productos/'.$item->img_path) }}" alt="{{$item->nombre}}" class="img-fluid img-thumbnail border border-4 rounded">
                                            @else
                                            <img src="" alt="{{$item->nombre}}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de confirmación-->
                <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ¿Seguro que quieres eliminar la prenda?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <form action="{{ route('productos.destroy',['producto'=>$item->id]) }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush

