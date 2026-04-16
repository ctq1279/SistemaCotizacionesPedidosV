@extends('template')

@section('title', 'Crear Cotización')

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Cotización</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Crear Cotización</li>
        </ol>
    </div>

    <form action="{{ route('cotizaciones.store') }}" method="POST">
        @csrf

        <div class="container-lg mt-4">
            <div class="row gy-4">
                <!------Detalles de la cotización---->
                <div class="col-xl-8">
                    <div class="text-white bg-primary p-1 text-center">
                        Detalles de la Cotización
                    </div>
                    <div class="p-3 border border-3 border-primary">
                        <div class="row">
                            <!-----Producto---->
                            <div class="col-12 mb-4">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker"
                                    data-live-search="true" data-size="1" title="Busque un producto aquí">
                                    <!--<option value="" data-costo-mano-obra="0">Seleccionar Producto</option>--->
                                    @foreach ($productos as $item)
                                        <option value="{{ $item->id }}"
                                            data-precio-unitario="{{ $item->precio_unitario }}"
                                            data-costo-mano-obra="{{ $item->costo_mano_obra }}"
                                            data-costo-total-materiales="{{ $item->costo_total_materiales }}">
                                            {{ $item->codigo . ' ' . $item->nombre }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <!-----Cantidad---->
                            <div class="col-sm-4 mb-2">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>
                            <!-----Precio base---->
                            <div class="col-sm-4 mb-2">
                                <label for="precio_unitario" class="form-label">Precio de venta:</label>
                                <input type="number" name="precio_unitario" id="precio_unitario" class="form-control"
                                    step="0.1" readonly>
                            </div>



                            <!-- Costo de mano de obra -->
                            <div class="col-sm-4 mb-2">
                                <label for="costo_mano_obra" class="form-label">Costo mano de obra:</label>
                                <input type="number" name="costo_mano_obra" id="costo_mano_obra" class="form-control"
                                    step="0.1" readonly>
                            </div>
                            <!-----Costo de materiales---->
                            <div class="col-sm-4 mb-2">
                                <label for="costo_total_materiales" class="form-label">Costo de Materiales:</label>
                                <input type="number" name="costo_total_materiales" id="costo_total_materiales"
                                    class="form-control" step="0.1" readonly>
                            </div>

                            <div class="form-group">
                                <label for="tiempo_entrega">Tiempo de Entrega</label>
                                <input type="text" name="tiempo_entrega" id="tiempo_entrega" class="form-control" value="{{ old('tiempo_entrega') }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="lugar_entrega">Lugar de Entrega</label>
                                <input type="text" name="lugar_entrega" id="lugar_entrega" class="form-control" value="{{ old('lugar_entrega') }}">
                            </div>
                            

                            <!-----botón para agregar--->
                            <div class="col-12 mb-4 mt-2 text-end">
                                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                            </div>
                            @if ($errors->any())

                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-----Tabla para el detalle de la cotización--->
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-hover">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th class="text-white">#</th>
                                                <th class="text-white">Producto</th>
                                                <th class="text-white">Cantidad</th>
                                                <th class="text-white">Precio de venta</th>

                                                <th class="text-white">Costo Mano de Obra</th>
                                                <th class="text-white">Costo de Materiales</th>

                                                <th class="text-white">Margen de ganancia</th>
                                                <th class="text-white">Total costo</th>

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
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th colspan="4">Total</th>
                                                <th colspan="2"><input type="text" id="total" name="total"
                                                        readonly value="0"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!--Boton para cancelar cotización-->
                            <div class="col-12 mt-2">
                                <button id="cancelar" type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Cancelar Cotización
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-----Datos generales---->
                <div class="col-xl-4">
                    <div class="text-white bg-success p-1 text-center">
                        Datos Generales
                    </div>
                    <div class="p-3 border border-3 border-success">
                        <div class="row">
                            <!--Cliente-->
                            <div class="col-12 mb-2">
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



                            <!--Fecha--->
                            <div class="col-sm-6 mb-2">
                                <label for="fecha_hora" class="form-label">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha"
                                    class="form-control border-success" value="<?php echo date('Y-m-d'); ?>">
                                <?php
                                use Carbon\Carbon;
                                
                                $fecha_hora = Carbon::now()->toDateTimeString();
                                ?>
                                <input type="hidden" name="fecha_hora" value="{{ $fecha_hora }}">
                            </div>

                            <!--Botones--->
                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-success" id="guardar">Realizar
                                    Cotización</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para cancelar la cotización -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Seguro que quieres cancelar la cotización?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarCotizacion" type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            inicializarEventos();
        });

        let contadorFilas = 0;
        let totalGeneral = 0;

        function inicializarEventos() {
            $('#producto_id').change(cargarDatosProductoSeleccionado);
            $('#btn_agregar').click(agregarProductoATabla);
            $('#btnCancelarCotizacion').click(cancelarCotizacion);
            actualizarBotonesAccion();
        }

        function cargarDatosProductoSeleccionado() {
            const productoSeleccionado = $('#producto_id option:selected');
            console.log('Producto seleccionado:', productoSeleccionado.text());
            console.log('Costo mano de obra:', productoSeleccionado.data('costo-mano-obra'));

            $('#precio_unitario').val(productoSeleccionado.data('precio-unitario'));
            $('#costo_mano_obra').val(productoSeleccionado.data('costo-mano-obra'));
            $('#costo_total_materiales').val(productoSeleccionado.data('costo-total-materiales'));
        }

        function agregarProductoATabla() {
            const productoId = $('#producto_id').val();
            const productoNombre = $('#producto_id option:selected').text().trim();
            const cantidad = parseInt($('#cantidad').val(), 10);
            const precio = parseFloat($('#precio_unitario').val());
            //const costoMateriales = parseFloat($('#costo_materiales').val());
            const costoManoObra = parseFloat($('#costo_mano_obra').val());
            const costoTotalMateriales = parseFloat($('#costo_total_materiales').val());
            //console.log(productoNombre);

            if (validarDatosProducto(productoNombre, cantidad, precio, costoTotalMateriales, costoManoObra)) {
                const subtotal = parseFloat(calcularSubtotal(cantidad, precio));
                const margenGanancia = calcularMargen(precio, costoTotalMateriales + costoManoObra);

                totalGeneral += subtotal;
                agregarFilaATabla(productoId, productoNombre, cantidad, precio, costoTotalMateriales, costoManoObra, margenGanancia,
                    subtotal);
                actualizarTotales();

                limpiarCamposFormulario();
                actualizarBotonesAccion();
            }
        }

        function agregarFilaATabla(productoId, productoNombre, cantidad, precio, costoTotalMateriales, costoManoObra, margen, subtotal) {



            let fila = '<tr id="fila' + contadorFilas + '">' +
                '<th>' + (contadorFilas + 1) + '</th>' +
                '<td><input type="hidden" name="arrayidproducto[]" value="' + productoId + '">' + productoNombre + '</td>' +
                '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                '<td><input type="hidden" name="arrayprecio[]" value="' + precio + '">' + precio +
                '</td>' +
                '<td><input type="hidden" name="arraycostomanoobra[]" value="' + costoManoObra + '">' + costoManoObra +
                '</td>' +
                '<td><input type="hidden" name="arraycostototalmateriales[]" value="' + costoTotalMateriales + '">' +
                costoTotalMateriales + '</td>' +
                '<td><input type="hidden" name="arraymargen[]" value="' + margen + '">' +
                margen + '</td>' +
                '<td>' + subtotal[contadorFilas] + '</td>' +
                '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + contadorFilas +
                ')"><i class="fa-solid fa-trash"></i></button></td>' +
                '</tr>';



            $('#tabla_detalle').append(fila);
            contadorFilas++;
        }

        function eliminarProducto(indice, subtotal) {
            totalGeneral -= parseFloat(subtotal);
            $('#fila' + indice).remove();
            actualizarTotales();
            actualizarBotonesAccion();
        }

        //aqui falta validar el boton cancelar cotizacion ya que no eloimia las filas de cotizaciones y tampoco el total 
        function cancelarCotizacion() {
            $('#tabla_detalle tbody').empty();
            totalGeneral = 0;
            contadorFilas = 0;
            actualizarTotales();
            limpiarCamposFormulario();
            actualizarBotonesAccion();
        }

        function actualizarTotales() {
            totalGeneral = isNaN(totalGeneral) ? 0 : parseFloat(totalGeneral); // Forzar número si hay error
            $('#sumas').html(totalGeneral.toFixed(2));
            $('#total').val(totalGeneral.toFixed(2));
        }

        function actualizarBotonesAccion() {
            if (totalGeneral > 0) {
                $('#guardar').show();
                $('#cancelar').show();
            } else {
                $('#guardar').hide();
                $('#cancelar').hide();
            }
        }

        function limpiarCamposFormulario() {
            $('#producto_id').val('');
            $('#cantidad').val('');
            $('#precio_unitario').val('');
            $('#costo_total_materiales').val('');
            $('#costo_mano_obra').val('');
        }

        function validarDatosProducto(nombre, cantidad, precio, costoTotalMateriales, costoManoObra) {
            if (!nombre || cantidad <= 0 || isNaN(precio) || isNaN(costoTotalMateriales) || isNaN(costoManoObra)) {
                mostrarAlerta('Datos inválidos. Por favor, verifica los campos.', 'error');
                return false;
            }
            return true;
        }

        function calcularSubtotal(cantidad, precio) {
            return (cantidad * precio).toFixed(2);
        }

        function calcularMargen(precio, costoTotal) {
            return (precio - costoTotal).toFixed(2);
        }

        function mostrarAlerta(mensaje, tipo = 'info') {
            Swal.fire({
                title: mensaje,
                icon: tipo,
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        }

        function esFormularioVacio() {
            return totalGeneral === 0;
        }
    </script>
@endpush
