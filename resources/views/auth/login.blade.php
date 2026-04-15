<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Inicio de sesión del sistema" />
    <meta name="author" content="MOD IN" />
    <title>Acceso al sistema - MOD IN</title>

    <!-- CSS personalizado -->
    <link href="{{ asset('css/template.css') }}" rel="stylesheet" />
    <style>
        /* Estilos generales */
        body {
            background: linear-gradient(135deg, #8EB1BF, #8EB1BF); /* Fondo general degradado */
            font-family: 'Roboto', sans-serif;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        /* Encabezado innovador */
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(90deg, #88AABE, #88AABE);
            color: #ffffff;
            padding: 20px 30px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .card-header img {
            width: 150px; /* Logo más grande */
            height: auto;
            border-radius: 8px;
            margin-right: 20px;
        }

        .card-header h3 {
            font-size: 1.5rem;
            margin: 0;
            font-weight: bold;
        }

        .card-body {
            padding: 30px;
            background-color: #ffffff;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            box-shadow: 0 0 6px #0056b3;
            border-color: #0056b3;
        }

        .btn-primary {
            background-color: #8C8C8C;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #88AABE;
            color: #ffffff;
        }

        .footer {
            text-align: center;
            color: #ffffff;
            margin-top: 20px;
            font-size: 14px;
        }

        .footer a {
            color: #f2f2f2;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div id="layoutAuthentication">
        <div class="container vh-100 d-flex align-items-center justify-content-center">
            <div class="row w-100 justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-lg border-0">
                        <!-- Encabezado con logo y título -->
                        <div class="card-header">
                            <img src="{{ asset('images/logoMODIN.PNG') }}" alt="Logo MOD IN" />
                            <h3>Inicio de sesión</h3>
                        </div>

                        <!-- Cuerpo del formulario -->
                        <div class="card-body">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ $error }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endforeach
                            @endif

                            <form action="/login" method="POST">
                                @csrf
                                <!-- Campo de correo -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="email" id="inputEmail" type="email" placeholder="name@example.com" required />
                                    <label for="inputEmail">Correo electrónico</label>
                                </div>

                                <!-- Campo de contraseña -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required />
                                    <label for="inputPassword">Contraseña</label>
                                </div>

                                <!-- Botón de inicio de sesión -->
                                <div class="d-grid mt-4">
                                    <button class="btn btn-primary" type="submit">Iniciar sesión</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Pie de página -->
                    <div class="footer mt-4">
                        <p>&copy; 2023 MOD IN. Todos los derechos reservados.</p>
                        <p>
                            <a href="#">Política de Privacidad</a> ·
                            <a href="#">Términos y Condiciones</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
