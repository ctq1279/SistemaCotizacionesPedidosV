@extends('template')

@section('title', 'pedidos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .row-not-space {
            width: 110px;
        }

        .btn-group {
            display: flex;
            justify-content: space-around;
        }
    </style>
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
                title: "Operacion exitosa"
            });
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Pedidos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Pedidos</li>
        </ol>

        <div class="mb-4">
            <a href="{{ route('pedidos.create') }}">
                <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Pedidos
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Comprobante</th>
                            <th>Cliente</th>
                            <th>Fecha y hora</th>
                            <th>Tipo de Envío</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $item)
                            <tr>
                                <td>
                                    <p class="text-muted mb-0">{{ $item->numero_comprobante }} -
                                        {{ ucfirst($item->tipo_comprobante) }}</p>
                                </td>
                                <td>
                                    <p class="fw-semibold mb-1">{{ ucfirst($item->cliente->persona->tipo_persona) }}</p>
                                    <p class="text-muted mb-0">{{ $item->cliente->persona->razon_social }}</p>
                                </td>
                                <td>
                                    <div class="row-not-space">
                                        <p class="fw-semibold mb-1"><span class="m-1"><i
                                                    class="fa-solid fa-calendar-days"></i></span>{{ \Carbon\Carbon::parse($item->fecha_pedido)->format('d-m-Y') }}
                                        </p>
                                        <p class="fw-semibold mb-0"><span class="m-1"><i
                                                    class="fa-solid fa-clock"></i></span>{{ \Carbon\Carbon::parse($item->fecha_pedido)->format('H:i') }}
                                        </p>
                                    </div>
                                </td>

                                <td>
                                    <p>{{ ucfirst($item->tipo_envio) }}</p>
                                </td>
                                <td>
                                    {{ $item->total }}
                                </td>
                                <td>
                                    @foreach (['pendiente', 'en_proceso', 'entregada', 'cancelada'] as $estado)
                                        <button
                                            class="btn btn-sm {{ $item->estado == $estado ? 'btn-primary' : 'btn-secondary' }}"
                                            onclick="cambiarEstadoManual('{{ $estado }}', {{ $item->id }})">
                                            {{ ucfirst($estado) }}
                                        </button>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">


                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#confirmModal-{{ $item->id }}">Eliminar</button>

                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#comprobanteModal" data-pedido-id="{{ $item->id }}">
                                            Completar registro
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de confirmación-->
                            <div class="modal fade" id="confirmModal-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Seguro que quieres eliminar el registro?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('pedidos.destroy', ['pedido' => $item->id]) }}"
                                                method="post">
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

    </div>

    <!-- Modal de Comprobante -->
    <div class="modal fade" id="comprobanteModal" tabindex="-1" aria-labelledby="comprobanteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="comprobanteModalLabel">Agregar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="comprobanteForm" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="pedido_id" name="pedido_id">
                        <div class="mb-3">
                            <label for="tipo_comprobante" class="form-label">Tipo de Comprobante</label>
                            <select class="form-select" id="tipo_comprobante" name="tipo_comprobante" required>
                                <option value="factura">Factura</option>
                                <option value="boleta">Boleta</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="numero_comprobante" class="form-label">Número de Comprobante</label>
                            <input type="text" class="form-control" id="numero_comprobante" name="numero_comprobante"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_envio" class="form-label">Tipo de envio</label>
                            <select class="form-select" id="tipo_envio" name="tipo_envio" required>
                                <option value="presencial">Presencial</option>
                                <option value="bus">Por bus</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Simple-DataTables
        window.addEventListener('DOMContentLoaded', event => {
            const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {})
        });

        $(document).ready(function() {
            $('#comprobanteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var pedidoId = button.data('pedido-id');
                var form = $('#comprobanteForm');
                form.attr('action', '/pedidos/' + pedidoId + '/comprobante');
                $('#pedido_id').val(pedidoId);
            });
        });


        function cambiarEstadoManual(estado, pedidoId) {
            if (confirm("¿Estás seguro de cambiar el estado de este pedido a " + estado + "?")) {
                fetch('/pedidos/' + pedidoId + '/estado', {
                        method: 'POST', // Asegúrate de que el método sea POST
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Incluir el token CSRF
                        },
                        body: JSON.stringify({
                            estado: estado
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Recargar la página para mostrar el estado actualizado
                        } else {
                            alert('Error al cambiar el estado: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                        alert('Hubo un error al intentar cambiar el estado');
                    });
            }
        }

        
    </script>
@endpush
