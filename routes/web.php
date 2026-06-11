<?php

use App\Http\Controllers\KardexController;
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

Route::get('', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::group(['middleware' => ['auth']], function () {

    Route::resource('inicio', 'App\Http\Controllers\inicioController');

    Route::resource('roles', 'App\Http\Controllers\RolController');
    Route::get('roles/eliminar/{id}', 'App\Http\Controllers\RolController@destroy');
    Route::get('roles/recuperar/{id}', 'App\Http\Controllers\RolController@edit');
    Route::put('roles/editar/{id}', 'App\Http\Controllers\RolController@update');
    Route::post('roles/guardar', 'App\Http\Controllers\RolController@store');
    Route::get('roles/opcionesXrol/{id}', 'App\Http\Controllers\RolController@opcionesXrol')->name('roles.opcionesXrol');

    Route::resource('opciones', 'App\Http\Controllers\OpcionMenuController');
    Route::get('opciones/CRUD/{id}', 'App\Http\Controllers\OpcionMenuController@obtenerCRUD');
    Route::get('opciones/editarCRUD/{id}', 'App\Http\Controllers\OpcionMenuController@editarCRUD');
    Route::put('opciones/updateCRUD/{id}', 'App\Http\Controllers\OpcionMenuController@updateCRUD');
    Route::get('opciones/create', 'App\Http\Controllers\OpcionMenuController@create')->name('opciones.create');
    Route::post('opciones/guardar', 'App\Http\Controllers\OpcionMenuController@store');

    Route::get('opciones/eliminar/{id}', 'App\Http\Controllers\OpcionMenuController@destroy');
    Route::get('opciones/recuperar/{id}', 'App\Http\Controllers\OpcionMenuController@edit');
    Route::put('opciones/editar/{id}', 'App\Http\Controllers\OpcionMenuController@update');

    Route::resource('usuarios', 'App\Http\Controllers\UsuarioController');
    Route::get('usuarios/eliminar/{id}', 'App\Http\Controllers\UsuarioController@destroy');
    Route::get('usuarios/recuperar/{id}', 'App\Http\Controllers\UsuarioController@edit');
    Route::put('usuarios/editar/{id}', 'App\Http\Controllers\UsuarioController@update');
    Route::post('usuarios/guardar', 'App\Http\Controllers\UsuarioController@store');
    Route::get('usuarios/show/{ROL}', 'App\Http\Controllers\UsuarioController@show');
    Route::get('usuarios/showId/{id}', 'App\Http\Controllers\UsuarioController@showId');
    Route::get('usuarios/create', 'App\Http\Controllers\UsuarioController@create')->name('usuarios.create');
    Route::get('usuarios/buscarDNI/{dni}', 'App\Http\Controllers\UsuarioController@buscarDNI')->name('usuarios.buscarDNI');
    Route::get('usuarios/buscarRUC/{ruc}', 'App\Http\Controllers\UsuarioController@buscarRUC')->name('usuarios.buscarRUC');

    Route::resource('categoriaMenu', 'App\Http\Controllers\GrupoMenuController');
    Route::get('categoriaMenu/eliminar/{id}', 'App\Http\Controllers\GrupoMenuController@destroy');
    Route::get('categoriaMenu/recuperar/{id}', 'App\Http\Controllers\GrupoMenuController@edit');
    Route::put('categoriaMenu/editar/{id}', 'App\Http\Controllers\GrupoMenuController@update');
    Route::post('categoriaMenu/guardar', 'App\Http\Controllers\GrupoMenuController@store');

    Route::resource('perfil', 'App\Http\Controllers\userConfigController');
    Route::put('perfil/editarPass/{id}', 'App\Http\Controllers\userConfigController@updatePassword')->name('perfil.updatePassword');;
    Route::put('perfil/editar/{id}', 'App\Http\Controllers\userConfigController@update');

    Route::resource('categoria', 'App\Http\Controllers\categoriaController');
    Route::get('categoria/eliminar/{id}', 'App\Http\Controllers\categoriaController@destroy');
    Route::get('categoria/recuperar/{id}', 'App\Http\Controllers\categoriaController@edit');
    Route::put('categoria/editar/{id}', 'App\Http\Controllers\categoriaController@update');
    Route::post('categoria/guardar', 'App\Http\Controllers\categoriaController@store');

    Route::resource('unidad', 'App\Http\Controllers\unidadController');
    Route::get('unidad/eliminar/{id}', 'App\Http\Controllers\unidadController@destroy');
    Route::get('unidad/recuperar/{id}', 'App\Http\Controllers\unidadController@edit');
    Route::put('unidad/editar/{id}', 'App\Http\Controllers\unidadController@update');
    Route::post('unidad/guardar', 'App\Http\Controllers\unidadController@store');

    Route::resource('categoria', 'App\Http\Controllers\categoriaController');
    Route::get('categoria/eliminar/{id}', 'App\Http\Controllers\categoriaController@destroy');
    Route::get('categoria/recuperar/{id}', 'App\Http\Controllers\categoriaController@edit');
    Route::put('categoria/editar/{id}', 'App\Http\Controllers\categoriaController@update');
    Route::post('categoria/guardar', 'App\Http\Controllers\categoriaController@store');

    Route::resource('catProductos', 'App\Http\Controllers\productoController');
    Route::get('catProductos/eliminar/{id}', 'App\Http\Controllers\productoController@destroy');
    Route::get('catProductos/recuperar/{id}', 'App\Http\Controllers\productoController@edit');
    Route::put('catProductos/editar/{id}', 'App\Http\Controllers\productoController@update');
    Route::post('catProductos/guardar', 'App\Http\Controllers\productoController@store');
    Route::get('catProductos/create', 'App\Http\Controllers\productoController@create')->name('catProductos.create');
    Route::get('catProductos/show', 'App\Http\Controllers\productoController@show');
    Route::get('catProductos/showId/{id}', 'App\Http\Controllers\productoController@showId')->name('catProductos.showId');;
    Route::get('catProductos/showHabitacion/{numHabitacion}', 'App\Http\Controllers\productoController@showHabitacion')->name('catProductos.showHabitacion');
    Route::get('catProductos/stockHabitacion/{productoId}/{numHabitacion}', 'App\Http\Controllers\productoController@stockHabitacion')->name('catProductos.stockHabitacion');
    Route::post('stockProductos/repartir/{id}', 'App\Http\Controllers\productoController@repartirStockHabitaciones')->name('stockProductos.repartir');
    Route::get('stockProductos/distribucion/{id}', 'App\Http\Controllers\productoController@distribucionProducto')->name('stockProductos.distribucion');
    Route::post('stockProductos/transferir/{id}', 'App\Http\Controllers\productoController@transferirStockHabitacion')->name('stockProductos.transferir');

    Route::resource('vistaPrincipal', 'App\Http\Controllers\vistaPrincipalController');
    Route::get('vistaPrincipal/show', 'App\Http\Controllers\vistaPrincipalController@show');
    Route::get('vistaPrincipal/sumarHorasHab/{num}/{cant}/{coment}/{modoHoraAdicional}', 'App\Http\Controllers\vistaPrincipalController@sumarHorasHab');
    Route::get('vistaPrincipal/situacion/{id}', 'App\Http\Controllers\vistaPrincipalController@situacion')->name('vistaPrincipal.situacion');
    Route::get('vistaPrincipal/situacionNumeroHab/{num}', 'App\Http\Controllers\vistaPrincipalController@situacionNumeroHab')->name('vistaPrincipal.situacionNumeroHab');
    Route::put('vistaPrincipal/editar/{id}', 'App\Http\Controllers\vistaPrincipalController@updateSituacion');
    Route::resource('listaHab', 'App\Http\Controllers\vistaHabTablaController');

    Route::resource('catServicios', 'App\Http\Controllers\servicioController');
    Route::get('catServicios/eliminar/{id}', 'App\Http\Controllers\servicioController@destroy');
    Route::get('catServicios/recuperar/{id}', 'App\Http\Controllers\servicioController@edit');
    Route::put('catServicios/editar/{id}', 'App\Http\Controllers\servicioController@update');
    Route::post('catServicios/guardar', 'App\Http\Controllers\servicioController@store');
    Route::get('catServicios/showId/{id}', 'App\Http\Controllers\servicioController@showId')->name('catServicios.showId');;
    Route::get('catServicios/show', 'App\Http\Controllers\servicioController@show');

    Route::resource('catHabitaciones', 'App\Http\Controllers\habitacionController');
    Route::get('catHabitaciones/eliminar/{id}', 'App\Http\Controllers\habitacionController@destroy');
    Route::get('catHabitaciones/recuperar/{id}', 'App\Http\Controllers\habitacionController@edit');
    Route::put('catHabitaciones/editar/{id}', 'App\Http\Controllers\habitacionController@update');
    Route::post('catHabitaciones/guardar', 'App\Http\Controllers\habitacionController@store');
    Route::get('catHabitaciones/habitacionesXsituacion/{situacion}', 'App\Http\Controllers\habitacionController@habitacionesXsituacion')->name('catHabitaciones.habitacionesXsituacion');;

    Route::resource('rolPersona', 'App\Http\Controllers\rolPersonaController');
    Route::get('rolPersona/eliminar/{id}', 'App\Http\Controllers\rolPersonaController@destroy');
    Route::get('rolPersona/recuperar/{id}', 'App\Http\Controllers\rolPersonaController@edit');
    Route::put('rolPersona/editar/{id}', 'App\Http\Controllers\rolPersonaController@update');
    Route::post('rolPersona/guardar', 'App\Http\Controllers\rolPersonaController@store');
    Route::get('rolPersona/show', 'App\Http\Controllers\rolPersonaController@show');

    Route::resource('ventaHabitacion', 'App\Http\Controllers\ventaHabitacionController');
    Route::get('movimiento/show/{id}', 'App\Http\Controllers\movimientoController@show');
    Route::get('movimiento/showId/{id}', 'App\Http\Controllers\movimientoController@showId');
    Route::get('movimiento/send/{to}/{subject}/{body}', 'App\Http\Controllers\movimientoController@send');
    Route::get('movimiento/eliminar/{id}', 'App\Http\Controllers\movimientoController@destroy')->name('movimiento.eliminar');
    Route::put('movimiento/pagarMovimientoAtencion/{id}', 'App\Http\Controllers\movimientoController@pagarMovimientoAtencion')->name('movimiento.pagarMovimientoAtencion');

    Route::post('ventaHabitacion/guardar', 'App\Http\Controllers\ventaHabitacionController@store');
    Route::put('ventaHabitacion/editar', 'App\Http\Controllers\ventaHabitacionController@update');

    Route::get('ventaHabitacion/show', 'App\Http\Controllers\ventaHabitacionController@show');
    Route::get('showProveedores', 'App\Http\Controllers\ventaHabitacionController@showProveedores');

    Route::resource('detalleHabitacion', 'App\Http\Controllers\detalleHabitacionController');
    Route::get('detalleHabitacion/recuperar/{id}', 'App\Http\Controllers\detalleHabitacionController@show');

    Route::post('detalleMovimiento/guardar', 'App\Http\Controllers\detallMovimientoController@store');
    Route::post('detalleMovimiento/guardarServicio', 'App\Http\Controllers\detallMovimientoController@storeServicio')->name('detalleMovimiento.storeServicio');

    Route::get('detalleMovimiento/eliminar/{id}', 'App\Http\Controllers\detallMovimientoController@destroy');
    Route::get('detalleMovimiento/eliminarCompra/{id}', 'App\Http\Controllers\detallMovimientoController@destroyCompra');

    Route::get('detalleMovimiento/showDetalleProductos/{id}', 'App\Http\Controllers\detallMovimientoController@showDetalleProductos')->name('detalleMovimiento.showDetalleProductos');
    Route::get('detalleMovimiento/showDetalleServicios/{id}', 'App\Http\Controllers\detallMovimientoController@showDetalleServicios')->name('detalleMovimiento.showDetalleServicios');
    Route::get('detalleMovimiento/show/{id}', 'App\Http\Controllers\detallMovimientoController@show');
    Route::get('detalleMovimiento/showDocAlmacen/{id}', 'App\Http\Controllers\detallMovimientoController@showDocAlmacen');

    Route::get('detalleMovimiento/cantTotalMovCompra/{id}', 'App\Http\Controllers\detallMovimientoController@cantTotalMovCompra');
    Route::get('detalleMovimiento/cantTotalMovComprado/{id}', 'App\Http\Controllers\detallMovimientoController@cantTotalMovComprado');

    Route::get('detalleMovimiento/obtenerDocumentosVenta', 'App\Http\Controllers\detallMovimientoController@obtenerDocumentosVenta')->name('detalleMovimiento.obtenerDocumentosVenta');
    Route::get('detalleMovimiento/actualizarDescuento/{id}', 'App\Http\Controllers\detallMovimientoController@actualizarDescuento')->name('detalleMovimiento.actualizarDescuento');
    Route::get('detalleMovimiento/getNumTipoDocumento/{tipoDoc}', 'App\Http\Controllers\detallMovimientoController@getNumTipoDocumento')->name('detalleMovimiento.getNumTipoDocumento');;
    Route::get('detalleMovimiento/showId/{id}', 'App\Http\Controllers\detallMovimientoController@showId');
    Route::put('detalleMovimiento/updateCantIdProd/{id}', 'App\Http\Controllers\detallMovimientoController@updateCantIdProd');
    Route::put('detalleMovimiento/updateCantIdProdCompra/{id}', 'App\Http\Controllers\detallMovimientoController@updateCantIdProdCompra');

    Route::resource('conceptoPagos', 'App\Http\Controllers\conceptoPagoController');
    Route::get('conceptoPagos/eliminar/{id}', 'App\Http\Controllers\conceptoPagoController@destroy');
    Route::get('conceptoPagos/recuperar/{id}', 'App\Http\Controllers\conceptoPagoController@edit');
    Route::put('conceptoPagos/editar/{id}', 'App\Http\Controllers\conceptoPagoController@update');
    Route::post('conceptoPagos/guardar', 'App\Http\Controllers\conceptoPagoController@store');
    Route::get('conceptoPagos/show', 'App\Http\Controllers\conceptoPagoController@show');

    Route::resource('cajaChica', 'App\Http\Controllers\movimientoCajaController');
    Route::get('cajaChica/apertura/recuperar/{tipo}', 'App\Http\Controllers\movimientoCajaController@editApertura');
    Route::post('cajaChica/apertura/guardar', 'App\Http\Controllers\movimientoCajaController@store');
    Route::put('cajaChica/apertura/editar/{id}', 'App\Http\Controllers\movimientoCajaController@update');

    Route::get('cajaChica/apertura/buscarApertura', 'App\Http\Controllers\movimientoCajaController@buscarApertura');
    Route::get('cajaChica/apertura/eliminar/{id}', 'App\Http\Controllers\movimientoCajaController@destroy');
    Route::get('cajaChica/detalleCierre', 'App\Http\Controllers\movimientoCajaController@show');
    Route::get('cajaChica/revertirAnulacion/{id}', 'App\Http\Controllers\movimientoCajaController@revertirAnulacion');
    Route::get('cajaChica/situacionHabXreversion/{id}', 'App\Http\Controllers\movimientoCajaController@situacionHabXreversion');
    Route::get('cajaChica/revertirAnulacionMovAtencion/{id}', 'App\Http\Controllers\movimientoCajaController@revertirAnulacionMovAtencion');
    Route::get('cajaChica/revertirAnulacionMovAtencionOtraHab/{id}/{idHab}', 'App\Http\Controllers\movimientoCajaController@revertirAnulacionMovAtencionOtraHab');
    Route::get('cajaChica/recuperarMmovimiento/{id}', 'App\Http\Controllers\movimientoCajaController@recuperarMmovimiento');

    Route::post('cajaChica/cierre/guardarCierre', 'App\Http\Controllers\movimientoCajaController@cierre');

    Route::get('reporteEgresos', 'App\Http\Controllers\movimientoController@showEgresos');

    Route::get('generar-pdf', 'App\Http\Controllers\PDFController@exportToPDF')->name('generar.pdf');
    Route::get('generarReportes-pdf/{idApertura}/{idCierre}', 'App\Http\Controllers\PDFController@exportToPDF_Reportes')->name('generarReporteCierresPdf.pdf');
    Route::get('generarReportes-ticket/{idApertura}/{idCierre}', 'App\Http\Controllers\PDFController@exportToTicketCaja_Reportes')->name('generarReporteCierresTicket.pdf');
    Route::get('generarReporte-emergencia', 'App\Http\Controllers\PDFController@exportReportesEmergencia')->name('exportReportesEmergencia.pdf');
    Route::get('generarReportes-cuadreCaja/{idApertura}/{idCierre}', 'App\Http\Controllers\PDFController@exportToCuadreCaja')->name('generarReporteCuadreCaja.pdf');
    Route::get('generarReportesEgresos', 'App\Http\Controllers\PDFController@exportToPDF_Egresos')->name('generarReporteEgresos.pdf');

    Route::get('export-ticketCaja', 'App\Http\Controllers\PDFController@exportToTicketCaja')->name('generarTciket.pdf');
    //VISTA PDF
    Route::get('ticket-pdf/{data}/{tipo}', 'App\Http\Controllers\PDFController@exportToPDF_ticket')->name('generarTicket.pdf');
    Route::get('ticket-pdf-sinFact/{idMov}/{tipo}', 'App\Http\Controllers\PDFController@exportToPDF_ticket_NoFacturacion')->name('generarTicketNfact.pdf');
    //IMPRESION TICKETERA
    Route::get('ticket-pdf-ticketera/{data}/{tipo}', 'App\Http\Controllers\PDFController@exportToPDF_ticket_ImpTicketera')->name('generarTicket.pdf');
    Route::get('ticket-pdf-sinFact-ticketera/{idMov}/{tipo}', 'App\Http\Controllers\PDFController@exportToPDF_ticket_NoFacturacion_ImpTicketera')->name('generarTicketNfact.pdf');

    Route::get('imprimirVenta', 'App\Http\Controllers\PDFController@imprimirVenta');

    Route::resource('movCompras', 'App\Http\Controllers\comprasController');
    Route::post('movCompras/guardar', 'App\Http\Controllers\comprasController@store');
    Route::get('movCompras/actualizar/{id}', 'App\Http\Controllers\comprasController@actualizar');

    Route::get('movCompra/show/{id}', 'App\Http\Controllers\comprasController@show');
    Route::get('movCompra/verificarNumeroMovCompra/{numero}/{id}', 'App\Http\Controllers\comprasController@verificarNumeroMovCompra');
    Route::get('movCompra/eliminar/{id}', 'App\Http\Controllers\comprasController@destroy');



    Route::post('movCompras/guardarDetalle/{id}', 'App\Http\Controllers\comprasController@guardarDetalle');

    Route::get('docCompras/show/{id}', 'App\Http\Controllers\docAlmacenController@show');
    Route::get('docCompras', 'App\Http\Controllers\comprasController@docCompras');
    Route::get('docAlmacen', 'App\Http\Controllers\docAlmacenController@docAlmacen');
    Route::post('docAlmacen/guardarDetalleCuadre', 'App\Http\Controllers\docAlmacenController@guardarDetalleCuadre');
    Route::get('docAlmacen/eliminarDocAlmacen/{id}/{tipo}', 'App\Http\Controllers\docAlmacenController@destroy');

    Route::resource('repCierreCaja', 'App\Http\Controllers\reporteCierreController');
    Route::resource('movAlmacen', 'App\Http\Controllers\docAlmacenController');

    Route::get('catMotivos/show/{tipo}', 'App\Http\Controllers\motivosDocAlmacenController@show');

    Route::get('export-kardex/{idProducto}/{fechaI}/{fechaT}', [KardexController::class, 'export'])->name('descargar.kardex');
    Route::resource('kardex', 'App\Http\Controllers\KardexController');

    Route::get('stockProductos', 'App\Http\Controllers\productoController@indexReporte');
    Route::resource('consumoHab', 'App\Http\Controllers\ChechOutListadoController');
    Route::get('generarReportesStock', 'App\Http\Controllers\PDFController@exportToPDF_Stock')->name('generarReporteStock.pdf');

    Route::get('cajaChica/obtenerDetalleEgresos/{id}', 'App\Http\Controllers\movimientoCajaController@showDetallesEgresos');

    Route::get('agregarNuevaEntrada', 'App\Http\Controllers\movimientoCajaController@agregarNuevaEntrada');
    Route::post('cajaChica/IngresoEgreso/guardar', 'App\Http\Controllers\movimientoCajaController@storeIngresoEgreso');
    Route::get('eliminarDetalleMovimientoSalida', 'App\Http\Controllers\movimientoCajaController@eliminarDetalleMovimientoSalida');
});
