@extends('template')

@section('title', 'Ver cotizacion')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Ver Cotización</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Ver Cotización</li>
        </ol>
    </div>

    <div class="container-fluid">

        <div class="card mb-4">
            <div class="card-header">
                Datos generales de la cotización
            </div>

            <div class="card-body">

                <!---Cliente--->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="input-group" id="hide-group">
                            <span class="input-group-text"><i class="fa-solid fa-user-tie"></i></span>
                            <input disabled type="text" class="form-control" value="Cliente: ">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span title="Cliente" id="icon-form" class="input-group-text"><i
                                    class="fa-solid fa-user-tie"></i></span>
                            <input disabled type="text" class="form-control"
                                value="{{ $cotizacion->cliente->persona->razon_social }}">
                        </div>
                    </div>
                </div>

                <!---Fecha--->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="input-group" id="hide-group">
                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                            <input disabled type="text" class="form-control" value="Fecha: ">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span title="Fecha" id="icon-form" class="input-group-text"><i
                                    class="fa-solid fa-calendar-days"></i></span>
                            <input disabled type="text" class="form-control"
                                value="{{ \Carbon\Carbon::parse($cotizacion->fecha_hora)->format('d-m-Y') }}">
                        </div>
                    </div>
                </div>

                <!---Hora-->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="input-group" id="hide-group">
                            <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                            <input disabled type="text" class="form-control" value="Hora: ">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span title="Hora" id="icon-form" class="input-group-text"><i
                                    class="fa-solid fa-clock"></i></span>
                            <input disabled type="text" class="form-control"
                                value="{{ \Carbon\Carbon::parse($cotizacion->fecha_hora)->format('H:i') }}">
                        </div>
                    </div>
                </div>

                <!---Impuesto-
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group" id="hide-group">
                            <span class="input-group-text"><i class="fa-solid fa-percent"></i></span>
                            <input  disabled type="text" class="form-control" value="Impuesto: ">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span title="Impuestos" id="icon-form" class="input-group-text"><i
                                    class="fa-solid fa-percent"></i></span>
                            <input disabled type="text" id="input-impuestos" class="form-control"
                                value="{{ $cotizacion->impuestos }}">
                        </div>
                    </div>
                </div>-->

            </div>
        </div>


        <!---Tabla--->
        <div class="card mb-2">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla de detalle de la cotización
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped">
                    <thead class="bg-primary">
                        <tr class="align-top">
                            <th class="text-white">Producto</th>
                            <th class="text-white">Cantidad</th>
                            <th class="text-white">Costo mano de obra</th>
                            <th class="text-white">Costo materiales</th>
                            <th class="text-white">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cotizacion->productos as $item)
                            <tr>
                                <td>
                                    {{ $item->nombre }}
                                </td>
                                <td>
                                    {{ $item->pivot->cantidad }}
                                </td>
                                <td>
                                    {{ $item->costo_mano_obra }}
                                </td>
                                <td>
                                    {{ $item->pivot->costo_materiales }}
                                </td>
                                <td class="td-subtotal">
                                    {{
                                        ($item->pivot->costo_materiales + $item->costo_mano_obra) 
            
                                    }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5"></th>
                        </tr>
                        <tr>
                            <th colspan="4">Margen ganancia:</th>
                            <th id="th-suma"></th>
                        </tr>
    
                        <tr>
                            <th colspan="4">Total:</th>
                            <th id="th-total"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        //Variables
        let filasSubtotal = document.getElementsByClassName('td-subtotal');
        let cont = 0;
        let impuestos = $('#input-impuestos').val();

        $(document).ready(function() {
            calcularValores();
        });

        function calcularValores() {
            for (let i = 0; i < filasSubtotal.length; i++) {
                cont += parseFloat(filasSubtotal[i].innerHTML);
            }

            $('#th-suma').html(cont);
            $('#th-igv').html(impuestos);
            $('#th-total').html(round(cont + parseFloat(impuestos)));
        }

        function round(num, decimales = 2) {
            var signo = (num >= 0 ? 1 : -1);
            num = num * signo;
            if (decimales === 0) //con 0 decimales
                return signo * Math.round(num);
            // round(x * 10 ^ decimales)
            num = num.toString().split('e');
            num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
            // x * 10 ^ (-decimales)
            num = num.toString().split('e');
            return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
        }
        //Fuente: https://es.stackoverflow.com/questions/48958/redondear-a-dos-decimales-cuando-sea-necesario
    </script>
@endpush
