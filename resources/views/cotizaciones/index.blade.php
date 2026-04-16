@extends('template')

@section('title', 'Cotizaciones')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .row-not-space {
            width: 110px;
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
        <h1 class="mt-4 text-center">Cotizaciones</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Cotizaciones</li>
        </ol>
        <div class="mb-4">
            <a href="{{ route('cotizaciones.create') }}">
                <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Cotizaciones
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Fecha y hora</th>
                            <th>Tiempo de entrega</th>
                            <th>Lugar de entrega</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cotizaciones as $item)
                            <tr>
                                <td>
                                    <p class="fw-semibold mb-1">{{ ucfirst($item->cliente->persona->tipo_persona) }}</p>
                                    <p class="text-muted mb-0">{{ $item->cliente->persona->razon_social }}</p>
                                </td>
                                <td>
                                    <div class="row-not-space">
                                        <p class="fw-semibold mb-1"><span class="m-1"><i
                                                    class="fa-solid fa-calendar-days"></i></span>{{ \Carbon\Carbon::parse($item->fecha_hora)->format('d-m-Y') }}
                                        </p>
                                        <p class="fw-semibold mb-0"><span class="m-1"><i
                                                    class="fa-solid fa-clock"></i></span>{{ \Carbon\Carbon::parse($item->fecha_hora)->format('H:i') }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    {{ $item->tiempo_entrega ?? 'No especificado' }}
                                </td>
                                <td>
                                    {{ $item->lugar_entrega ?? 'No especificado' }}
                                </td>

                                <td>
                                    {{ $item->total }}
                                </td>
                                <td>
                                    @foreach (['pendiente', 'aprobada', 'rechazada', 'vencida'] as $estado)
                                        <button
                                            class="btn btn-sm {{ $item->estado == $estado ? 'btn-primary' : 'btn-secondary' }}"
                                            onclick="cambiarEstadoManual('{{ $estado }}', {{ $item->id }})">
                                            {{ ucfirst($estado) }}
                                        </button>
                                    @endforeach
                                </td>
                                <td>
                                    <!-- Botones para ver y descargar el PDF -->
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <!-- Botón para ver el PDF -->
                                        <a href="{{ route('cotizaciones.ver-pdf', ['id' => $item->id]) }}"
                                            class="btn btn-info" target="_blank">
                                            Ver PDF
                                        </a>

                                        <!-- Botón para descargar el PDF -->
                                        <a href="{{ route('cotizaciones.descargar-pdf', ['id' => $item->id]) }}"
                                            class="btn btn-primary">
                                            Descargar PDF
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <form action="{{ route('cotizaciones.show', ['cotizacione' => $item]) }}"
                                            method="get">
                                            <button type="submit" class="btn btn-success">
                                                Ver
                                            </button>
                                        </form>
                                        <form action="{{ route('cotizaciones.edit', ['cotizacione' => $item]) }}"
                                            method="get">
                                            <button type="submit" class="btn btn-warning">
                                                Editar
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#confirmModal-{{ $item->id }}">Eliminar</button>

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
                                            <form
                                                action="{{ route('cotizaciones.destroy', ['cotizacione' => $item->id]) }}"
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
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
<script>
    function cambiarEstadoManual(estado, cotizacionId) {
        if (confirm("¿Estás seguro de cambiar el estado de esta cotización a " + estado + "?")) {
            fetch('/cotizaciones/' + cotizacionId + '/estado', {
                    method: 'POST', // Asegúrate de que el método sea POST
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
