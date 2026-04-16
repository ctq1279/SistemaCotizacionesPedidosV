@extends('template')

@section('title', 'Editar Cotización')

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Cotización</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cotizaciones.index') }}">Cotizaciones</a></li>
            <li class="breadcrumb-item active">Editar Cotización</li>
        </ol>

        <!-- Formulario de edición de cotización -->
        <form action="{{ route('cotizaciones.update', $cotizacione->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="cliente">Cliente</label>
                <select name="cliente_id" id="cliente" class="form-control" required>
                    <option value="" disabled>Seleccione un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                            {{ $cotizacione->cliente_id == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" class="form-control" required>
                    <option value="pendiente" {{ $cotizacione->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobada" {{ $cotizacione->estado == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                    <option value="rechazada" {{ $cotizacione->estado == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    <option value="vencida" {{ $cotizacione->estado == 'vencida' ? 'selected' : '' }}>Vencida</option>
                </select>
            </div>



            <div class="col-md-6 mb-2">
                <label for="materiales" class="form-label">Materiales:</label>
                <select data-size="4" multiple title="Seleccione los materiales" data-live-search="true"
                    name="materiales[]" id="materiales" class="form-control selectpicker show-tick">
                    @foreach ($materiales as $item)
                        <option value="{{ $item->id }}"
                            {{ in_array($item->id, $cotizacione->productos->materiale->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $item->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('materiales')
                    <small class="text-danger">{{ '*' . $message }}</small>
                @enderror
            </div>



            <div class="form-group">
                <label for="costo_mano_obra">Costo de Mano de Obra</label>
                <input type="number" step="0.01" name="costo_mano_obra" id="costo_mano_obra" class="form-control"
                    value="{{ $cotizacion->costo_mano_obra }}" required>
            </div>

            <div class="form-group">
                <label for="costo_material">Costo de Material</label>
                <input type="number" step="0.01" name="costo_material" id="costo_material" class="form-control"
                    value="{{ $cotizacion->costo_material }}" readonly>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="4">{{ $cotizacion->descripcion }}</textarea>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Actualizar Cotización</button>
                <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>

    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#producto_id').change(function() {
                actualizarDatosProducto();
            });

            $('#tiempo_entrega').change(function() {
                actualizarCostoUrgencia();
            });

            $('#btn_agregar').click(function() {
                agregarProducto();
            });

            $('#btnCancelarCotizacion').click(function() {
                cancelarCotizacion();
            });

            disableButtons();

            $('#impuestos').val(impuestos + '%');
        });

        // Variables
        let cont = 0;
        let subtotal = [];
        let sumas = 0;
        let igv = 0;
        let total = 0;

        // Constantes
        const impuestos = 18;


        function cancelarCotizacion() {
            // Eliminar el tbody de la tabla
            $('#tabla_detalle tbody').empty();

            //Añadir una nueva fila a la tabla
            let fila = '<tr>' +
                '<th></th>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '</tr>';
            $('#tabla_detalle').append(fila);
            // Reiniciar valores de las variables
            cont = 0;
            subtotal = [];
            sumas = 0;
            igv = 0;
            total = 0;

            // Mostrar los campos calculados
            $('#sumas').html(sumas);
            $('#igv').html(igv);
            $('#total').html(total);
            $('#impuestos').val(impuestos + '%');
            $('#inputTotal').val(total);

            limpiarCampos();
            disableButtons();
        }

        function disableButtons() {
            if (total == 0) {
                $('#guardar').hide();
                $('#cancelar').hide();
            } else {
                $('#guardar').show();
                $('#cancelar').show();
            }
        }

        function actualizarDatosProducto() {
            let producto = $('#producto_id option:selected');
            let precio = producto.data('precio-unitario');
            let costoManoObra = producto.data('costo-mano-obra');

            $('#precio_unitario').val(precio);
            $('#costo_mano_obra').val(costoManoObra);
        }

        function actualizarCostoUrgencia() {
            let tiempoEntrega = $('#tiempo_entrega').val();
            let costoUrgencia = 0;

            if (tiempoEntrega <= 3 && tiempoEntrega > 0) {
                costoUrgencia = 50;
            } else if (tiempoEntrega > 3 && tiempoEntrega <= 7) {
                costoUrgencia = 30;
            }

            $('#costo_urgencia').val(costoUrgencia);
        }

        function agregarProducto() {
            // Obtener valores de los campos
            const productoId = $('#producto_id').val();
            let nombreProducto = ($('#producto_id option:selected').text()).split('')[1];
            let cantidad = $('#cantidad').val();
            let precio = $('#precio_unitario').val();
            let costoMateriales = $('#costo_materiales').val();
            let costoManoObra = $('#costo_mano_obra').val();
            let tiempoEntrega = $('#tiempo_entrega').val();
            let costoUrgencia = $('#costo_urgencia').val();
            // Validaciones 
            // 1. Para que los campos no estén vacíos
            if (nombreProducto != '' && nombreProducto != undefined && cantidad != '' && costoMateriales != '' &&
                costoManoObra != '') {
                // 2. Para que los valores ingresados sean los correctos
                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(costoMateriales) >= 0 && parseFloat(
                        costoManoObra) >= 0) {
                    // Calcular valores
                    let subtotalProducto = round((cantidad * precio) + (cantidad * costoManoObra) + (cantidad *
                        costoMateriales) + parseFloat(costoUrgencia));
                    subtotal[cont] = subtotalProducto;
                    sumas += subtotal[cont];
                    igv = round(sumas / 100 * impuestos);
                    total = round(sumas + igv);

                    // Crear la fila
                    let fila = '<tr id="fila' + cont + '">' +
                        '<th>' + (cont + 1) + '</th>' +
                        '<td><input type="hidden" name="arrayidproducto[]" value="' + productoId + '">' +
                        nombreProducto + '</td>' +
                        '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td>' + precio + '</td>' +
                        '<td><input type="hidden" name="arraycostomanoobra[]" value="' + costoManoObra + '">' +
                        costoManoObra + '</td>' +
                        '<td><input type="hidden" name="arraycostomateriales[]" value="' + costoMateriales + '">' +
                        costoMateriales + '</td>' +
                        '<td>' + costoUrgencia + '</td>' +
                        '<td>' + subtotalProducto + '</td>' +
                        '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + cont +
                        ')"><i class="fa-solid fa-trash"></i></button></td>' +
                        '</tr>';

                    // Acciones después de añadir la fila
                    $('#tabla_detalle').append(fila);
                    limpiarCampos();
                    cont++;
                    disableButtons();

                    // Mostrar los campos calculados
                    $('#sumas').html(sumas);
                    $('#igv').html(igv);
                    $('#total').html(total);
                    $('#impuestos').val(igv);
                    $('#inputTotal').val(total);
                } else {
                    showModal('Valores incorrectos');
                }
            } else {
                showModal('Le faltan campos por llenar');
            }
        }

        function eliminarProducto(indice) {
            // Calcular valores
            sumas -= round(subtotal[indice]);
            igv = round(sumas / 100 * impuestos);
            total = round(sumas + igv);

            // Mostrar los campos calculados
            $('#sumas').html(sumas);
            $('#igv').html(igv);
            $('#total').html(total);
            $('#impuestos').val(igv);
            $('#inputTotal').val(total);

            // Eliminar la fila de la tabla
            $('#fila' + indice).remove();
            disableButtons();
        }

        function limpiarCampos() {
            let select = $('#producto_id');
            $('#producto_id').val('');
            $('#cantidad').val('');
            $('#precio_unitario').val('');
            $('#costo_materiales').val('');
            $('#costo_mano_obra').val('');
            $('#tiempo_entrega').val('');
            $('#costo_urgencia').val('');
        }

        function round(num, decimales = 2) {
            var signo = (num >= 0 ? 1 : -1);
            num = num * signo;
            if (decimales === 0) // con 0 decimales
                return signo * Math.round(num);
            num = num.toString().split('e');
            num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
            num = num.toString().split('e');
            return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
        }

        function showModal(message, icon = 'error') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: icon,
                title: message
            })
        }
    </script>
@endpush
