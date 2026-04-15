@extends('template')

@section('title', 'Realizar Pedido')

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Realizar Pedido</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
            <li class="breadcrumb-item active">Realizar Pedido</li>
        </ol>
    </div>

    <form action="{{ route('pedidos.store') }}" method="post">
        @csrf
        <div class="container-lg mt-4">
            <div class="row gy-4">

                <!------pedido producto---->
                <div class="col-xl-8">
                    <div class="text-white bg-primary p-1 text-center">
                        Detalles de pedido
                    </div>
                    <div class="p-3 border border-3 border-primary">
                        <div class="row gy-4">

                            <!-----Prenda---->
                            <div class="col-12">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker"
                                    data-live-search="true" data-size="1" title="Busque un producto aquí">
                                    @foreach ($productos as $item)
                                        <option value="{{ $item->id }}-{{ $item->stock }}-{{ $item->precio_venta }}">
                                            {{ $item->codigo . ' ' . $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-----Cantidad---->
                            <div class="col-sm-4">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!-- Tipo de Envío -->
                            <div class="col-12">
                                <label for="tipo_envio" class="form-label">Tipo de Envío:</label>
                                <select name="tipo_envio" id="tipo_envio" class="form-control" required>
                                    <option value="entrega presencial">Entrega Presencial</option>
                                    <option value="envío por bus">Envío por Bus</option>
                                </select>
                            </div>

                            <!-- Tiempo de Entrega -->
                            <div class="col-12">
                                <label for="tiempo_entrega" class="form-label">Tiempo de Entrega:</label>
                                <input type="number" name="tiempo_entrega" id="tiempo_entrega" class="form-control"
                                    placeholder="Ingrese el tiempo de entrega" required>
                            </div>

                            <!-----botón para agregar--->
                            <div class="col-12 text-end">
                                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                            </div>

                            <!-----Tabla para el detalle de la venta--->
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-hover">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th class="text-white">#</th>
                                                <th class="text-white">Prenda</th>
                                                <th class="text-white">Cantidad</th>
                                                <th class="text-white">Tipo de Envío</th>
                                                <th class="text-white">Tiempo de Entrega</th>
                                                <th class="text-white">Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Sumas</th>
                                                <th colspan="2"><span id="sumas">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">IGV %</th>
                                                <th colspan="2"><span id="igv">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Total</th>
                                                <th colspan="2"> <input type="hidden" name="total" value="0"
                                                        id="inputTotal"> <span id="total">0</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!--Boton para cancelar venta--->
                            <div class="col-12">
                                <button id="cancelar" type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Cancelar pedido
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-----Pedido---->
                <div class="col-xl-4">
                    <div class="text-white bg-success p-1 text-center">
                        Datos generales
                    </div>
                    <div class="p-3 border border-3 border-success">
                        <div class="row gy-4">

                            <!--Cliente-->
                            <div class="col-12">
                                <label for="cliente_id" class="form-label">Cliente:</label>
                                <select name="cliente_id" id="cliente_id" class="form-control selectpicker show-tick"
                                    data-live-search="true" title="Selecciona" data-size='2'>
                                    @foreach ($clientes as $item)
                                        <option value="{{ $item->id }}">{{ $item->persona->razon_social }}</option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>
                            <!--Tipo de comprobante-->
                            <div class="col-12">
                                <label for="comprobante_id" class="form-label">Comprobante:</label>
                                <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker"
                                    title="Selecciona">
                                    @foreach ($comprobantes as $item)
                                        <option value="{{ $item->id }}">{{ $item->tipo_comprobante }}</option>
                                    @endforeach
                                </select>
                                @error('comprobante_id')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>
                            <!--Numero de comprobante-->
                            <div class="col-12">
                                <label for="numero_comprobante" class="form-label">Numero de comprobante:</label>
                                <input required type="text" name="numero_comprobante" id="numero_comprobante"
                                    class="form-control">
                                @error('numero_comprobante')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>
                            <!--Impuesto---->
                            <div class="col-sm-6">
                                <label for="impuestos" class="form-label">Impuesto(IGV):</label>
                                <input readonly type="text" name="impuestos" id="impuestos"
                                    class="form-control border-success">
                                @error('impuestos')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>
                            <!--Fecha--->
                            <div class="col-sm-6">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha"
                                    class="form-control border-success" value="<?php echo date('Y-m-d'); ?>">
                                <?php
                                
                                use Carbon\Carbon;
                                
                                $fecha_hora = Carbon::now()->toDateTimeString();
                                ?>
                                <input type="hidden" name="fecha_hora" value="{{ $fecha_hora }}">
                            </div>
                            <!----User--->
                            <input type="hidden" name="user_id" value="{{ auth()->user() }}">

                            <!--Botones--->
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success" id="guardar">Realizar pedido</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para cancelar la venta -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Seguro que quieres cancelar la venta?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarVenta" type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    
@endpush
