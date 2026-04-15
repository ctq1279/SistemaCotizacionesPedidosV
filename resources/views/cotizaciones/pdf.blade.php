<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            margin-left: 20px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
            color: #0066CC;
            flex-grow: 1;
            text-align: center;
        }

        .client-details, .terms-container {
            margin-bottom: 20px;
        }

        .client-details table, .terms table {
            width: 100%;
            border-collapse: collapse;
        }

        .client-details td, .terms td {
            padding: 5px;
        }

        .table-title {
            background-color: #0066CC;
            color: white;
            padding: 5px;
            font-weight: bold;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .product-table th, .product-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .product-table th {
            background-color: #0066CC;
            color: white;
        }

        .product-table td:first-child {
            width: 50%; /* Descripción más amplia */
        }

        .terms-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .terms {
            width: 70%;
        }

        .total-facturado {
            width: 25%;
            border: 1px solid #000;
            padding: 10px;
            font-weight: bold;
            background-color: #f0f0f0;
            text-align: center;
            line-height: 20px;
        }

        .footer {
            text-align: right;
            margin-top: 40px;
        }

        .footer p {
            margin: 0;
            font-size: 10px;
        }

        .footer .thank-you {
            font-family: "Brush Script MT", cursive, sans-serif;
            font-size: 24px; /* Más grande */
            color: #0066CC;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>COTIZACIÓN</h1>
        <img src="{{ asset('images/logoMODIN.PNG') }}" alt="Logo">
    </div>

    <div class="client-details">
        <table>
            <tr>
                <td><strong>DATOS DEL CLIENTE</strong></td>
                <td align="right"><strong>No.</strong> {{ $cotizacion->id }}</td>
            </tr>
            <tr>
                <td>
                    <p><strong>{{ $cotizacion->cliente->persona->razon_social }}</strong></p>
                    <p>{{ $cotizacion->cliente->persona->nombre }}</p>
                    <p>{{ $cotizacion->cliente->persona->telefono }}</p>
                    <p>{{ $cotizacion->cliente->persona->email }}</p>
                </td>
                <td align="right">
                    <p><strong>Fecha:</strong> {{ $cotizacion->fecha_hora }}</p>
                    <p><strong>Válida por:</strong> 15 días calendario</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>DESCRIPCIÓN</th>
                <th>CANTIDAD</th>
                <th>UNIDAD</th>
                <th>PRECIO UNITARIO</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizacion->productos as $producto)
                <tr>
                    <td>
                        <p><strong>{{ $producto->nombre }}</strong></p>
                        <p>{{ $producto->descripcion }}</p>
                        <p>Material: {{ $producto->material }}</p>
                        <p>Forro: {{ $producto->forro }}</p>
                        <p>Color: {{ $producto->color }}</p>
                        <p>Logos & Insignias: {{ $producto->logos_insignias }}</p>
                    </td>
                    <td>{{ $producto->pivot->cantidad }}</td>
                    <td>Unid.</td>
                    <td>{{ number_format($producto->precio_unitario, 2) }}</td>
                    <td>{{ number_format($producto->pivot->cantidad * $producto->precio_unitario, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="terms-container">
        <div class="terms">
            <table>
                <tr>
                    <td><strong>Términos y Condiciones</strong></td>
                </tr>
                <tr>
                    <td>Tiempo de entrega: <strong>{{ $cotizacion->tiempo_entrega }}</strong></td>
                </tr>
                <tr>
                    <td>Lugar de entrega: <strong>{{ $cotizacion->lugar_entrega }}</strong></td>
                </tr>
                <tr>
                    <td>Forma de pago: <strong>Transferencia a la Cuenta No.860046-401-0 del Banco Bisa S. A./ M.N.</strong></td>
                </tr>
                <tr>
                    <td>Plazo de pago: <strong>15 días posteriores a la entrega</strong></td>
                </tr>
            </table>
        </div>
        <div class="total-facturado">
            <p>TOTAL FACTURADO:</p>
            <p>{{ number_format($cotizacion->productos->sum(fn($producto) => $producto->pivot->cantidad * $producto->precio_unitario), 2) }}</p>
        </div>
    </div>

    <div class="footer">
        <p class="thank-you">¡Gracias por su preferencia!</p>
        <p>Karla Loayza Chalar (Mod In) - NIT</p>
        <p>Calle 6, Edif. 76 Depto. 302 Zona Los Pinos, La Paz</p>
        <p>Teléfonos: 2455927</p>
    </div>
</body>
</html>
