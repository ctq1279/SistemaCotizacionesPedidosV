<?php

use App\Http\Controllers\categoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\compraController;
use App\Http\Controllers\cotizacionController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\proveedorController;
use App\Http\Controllers\materialController;
use App\Http\Controllers\pedidoController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [homeController::class, 'index'])->name('panel');

//Route::view('/panel','panel.index')->name('panel');

Route::resources([
    'categorias' => categoriaController::class,
    'productos' => ProductoController::class,
    'clientes' => clienteController::class,
    'proveedores' => proveedorController::class,
    'compras' => compraController::class,
    'materiales' => materialController::class,
    'cotizaciones' => cotizacionController::class,
    'pedidos' => pedidoController::class,
    'users' => userController::class,
    'roles' => roleController::class,
    'profile' => profileController::class
]);

Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');

Route::post('/cotizaciones/{id}/estado', [CotizacionController::class, 'cambiarEstado'])->name('cotizaciones.cambiarEstado');
Route::post('pedidos/{pedidoId}/comprobante', [PedidoController::class, 'updateComprobante'])->name('pedidos.updateComprobante');
// En web.php o api.php
Route::post('/pedidos/{id}/estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');

Route::post('/productos/costo-total-materiales', [ProductoController::class, 'calcularYGuardarCostoTotalMateriales']);
//ruta para generar el pdf
// Ruta para visualizar el PDF en el navegador
Route::get('/cotizaciones/{id}/ver-pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.ver-pdf');

// Ruta para descargar el PDF
Route::get('/cotizaciones/{id}/descargar-pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.descargar-pdf')->defaults('descargar', true);

Route::get('/401', function () {
    return view('pages.401');
});
Route::get('/404', function () {
    return view('pages.404');
});
Route::get('/500', function () {
    return view('pages.500');
});
