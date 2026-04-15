@extends('template')

@section('title', 'Panel')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <style>
        /* Ajustes generales */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F2F2F2; /* Fondo claro */
        }

        /* Estilos para los títulos */
        h1 {
            color: #8C8C8C; /* Gris suave */
            font-weight: bold;
        }

        .breadcrumb-item.active {
            color: #8C8C8C; /* Gris suave */
        }

        /* Tarjetas (Cards) */
        .card {
            border: none;
            border-radius: 8px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
        }

        /* Tarjetas con los colores proporcionados */
        .card.bg-primary {
            background-color: #8EB1BF; /* Azul grisáceo */
            color: white;
        }

        .card.bg-primary .card-footer {
            background-color: #96CCD9; /* Azul claro */
        }

        /* Tarjetas con el fondo claro para las otras secciones */
        .card.bg-warning {
            background-color: #96CCD9; /* Azul claro */
            color: white;
        }

        .card.bg-warning .card-footer {
            background-color: #8EB1BF; /* Azul grisáceo */
        }

        /* Tarjetas para productos */
        .card.bg-success {
            background-color: #8EB1BF; /* Azul grisáceo */
            color: white;
        }

        .card.bg-success .card-footer {
            background-color: #96CCD9; /* Azul claro */
        }

        /* Enlaces en las tarjetas */
        .card-footer a {
            color: white;
            font-weight: bold;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }

        /* Texto en el footer */
        .card-footer .small {
            color: #D9D9D9; /* Gris muy claro */
        }

        /* Íconos */
        .card-body i {
            font-size: 1.5rem;
            color: white;
        }

        /* Texto dentro de las tarjetas */
        .card-body span {
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* Contadores */
        .card-body p {
            font-size: 2rem;
            font-weight: bold;
            color: #D9D9D9; /* Gris muy claro */
            margin: 0;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                let message = "{{ session('success') }}";
                Swal.fire(message);

            });
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4">MOD IN</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">EMPRESA TEXTIL</li>
        </ol>
        <div class="row">
            <!----Clientes--->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4" style="background-color: #96CCD9;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-people-group"></i><span class="m-1">Clientes</span>
                            </div>
                            <div class="col-4">
                                <?php
                                
                                use App\Models\Cliente;
                                
                                $clientes = count(Cliente::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $clientes }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('clientes.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!----Categoria--->
            <div class="col-xl-3 col-md-6">
                <div class="card  text-white mb-4" style="background-color: #88AABE;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-tag"></i><span class="m-1">Categorías</span>
                            </div>
                            <div class="col-4">
                                <?php
                                
                                use App\Models\Categoria;
                                
                                $categorias = count(Categoria::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $categorias }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('categorias.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!----Cotizaciones--->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4" style="background-color: #96CCD9;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-store"></i><span class="m-1">Cotizaciones</span>
                            </div>
                            <div class="col-4">
                                <?php
                                
                                use App\Models\Cotizacione;
                                
                                $cotizaciones = count(Cotizacione::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $cotizaciones }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('cotizaciones.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!----Producto--->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4" style="background-color: #88AABE;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-brands fa-shopify"></i><span class="m-1">Productos</span>
                            </div>
                            <div class="col-4">
                                <?php
                                
                                use App\Models\Producto;
                                
                                $productos = count(Producto::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $productos }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('productos.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!----Proveedore--->
            <div class="col-xl-3 col-md-6">
                <div class="card text-white mb-4" style="background-color: #96CCD9;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-user-group"></i><span class="m-1">Proveedores</span>
                            </div>
                            <div class="col-4">
                                <?php
                                
                                use App\Models\Proveedore;
                                
                                $proveedores = count(Proveedore::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $proveedores }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('proveedores.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            

        </div>

    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <!---script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script--->
    <!---script src="{{ asset('assets/demo/chart-bar-demo.js') }}"></script--->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
