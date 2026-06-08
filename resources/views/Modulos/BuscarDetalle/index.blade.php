@extends('adminlte::page')

@section('title', 'DETALLE HABITACION')

@section('content_header')
<br><br>
<!-- <h1 id="titulo" class="text-center">BUSQUEDA DE HABITACION DETALLE</h1> -->
@stop

@section('content')

<section class="cajaAlrededor">
    <style>
        .negativo {
            color: red;
        }
    </style>
    
    <section>
        <div class="row justify-content-center bloqueCuota mb-2">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-5">
                        <p><b>Numero Habitación:</b></p>
                        <p><b>Tipo:</b></p>
                        <p><b>Nombre Cliente:</b></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted" id="numeroI">N° {{$numFil}}</p>
                        <p class="text-muted" id="tipoI">-</p>
                        <p class="text-muted" id="nombreClienteI">-</p>
                    </div>
                    

                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-5">
                        <p><b>F. de Ingreso:</b></p>
                        <p><b>F. de Salida</b></p>
                        <p><b>Estado:</b></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted" id="fechaingresoI">-</p>
                        <p class="text-muted" id="fechasalidaI">-</p>
                        <p class="text-muted" id="estadoI">-</p>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="mb-3">
                    <label for="comentario" class="form-label labelFormato">NOTAS:</label>
                    <input type="text" class="form-control ajuste" name="comentario" value="" id="comentario" placeholder="Escribe una nota">
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-8">
            <section class="w-100 mb-3 text-center border border-1 border-dark rounded-3 px-4 py-3 mt-2">
                <div class="container detalle text-center">
                    <br>

                    <h4 class="text-decoration-underline fw-normal text-left">Productos</h4>
                    <div class="formatoTabla mb-4">
                        <table class="table ">
                            <thead id="theadProductos">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Comentario</th>
                                    <th>Descuento (%)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody class="listaProductos">
                            </tbody>
                        </table>
                    </div>
<br>
                    <h4 class="text-decoration-underline fw-normal text-left ">Servicios</h4>
                    <div class="formatoTabla mb-4">
                        <table class="table">
                            <thead id="theadServicios">
                                <tr>
                                    <th>#</th>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Comentario</th>
                                    <th>Descuento (%)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody class="listaServicios">

                            </tbody>
                        </table>

                    </div>
                    <table class="table text-right">
                        <tbody>
                            <tr>
                                <td><b>Total a pagar:</b></td>
                                <td class="totalPagar" id="totalMasIgv"></td>
                            </tr>
                            <tr>
                                <td><b>Efectivo:</b></td>
                                <td class="totalPagar" id="totaPagado"></td>
                            </tr>
                            <tr>
                                <td><b>Vuelto:</b></td>
                                <td class="totalPagar" id="vueltoPago"></td>
                            </tr>
                        </tbody>


                    </table>

                </div>


            </section>
        </div>
        <div class="col-md-4">
            <section class="w-100  text-center border border-1 border-dark rounded-3 px-4 py-3 mt-2">
                <div class="container pago">
                    <h4 class="text-decoration-underline fw-normal text-center">Pago</h4>

                    <div class="opcion_pago detalleSelect">
                        <label for="tipoDocumentos">Tipo Documento:</label>
                        <select class="form-select text-center" name="tipoDocumentos" id="tipoDocumentos">

                        </select><br>

                        <label for="tipoDocumentos">Número:</label>
                        <input type="text" value="B003-00000001" id="numTipoDocumento" class="form-control text-center" readonly>
                            
                        <br>
                        <label id="clickAgregarCliente">Cliente <i class="fa-solid fa-plus" id="agregarCliente" style="color: #1f64db;"></i>
                        </label>

                        <select name="clientes" class="form-control selectTwo" id="clientes">
                        </select>
                        <br><br>
                        <form id="registroPagoMovimiento" class="detalle text-center">
                            @method('PUT')
                            @csrf
                            <div class="opcion_pago">
                                <label for="pagoEfectivo">Efectivo:</label>
                                <input type="number" min="0" step="0.01" value="0.00" id="pagoEfectivo" class="form-control pagoDealle">
                            </div>
                            <div class="opcion_pago">
                                <label for="pagoTarjeta">Tarjeta:</label>
                                <input type="number" min="0" step="0.01" value="0.00" id="pagoTarjeta" class="form-control pagoDealle">
                            </div>
                            <div class="opcion_pago">
                                <label for="pagoYape">Yape:</label>
                                <input type="number" min="0" step="0.01" value="0.00" id="pagoYape" class="form-control pagoDealle">
                            </div>

                            <div class="opcion_pago">
                                <label for="pagoDeposito">Depósito:</label>
                                <input type="number" min="0" step="0.01" value="0.00" id="pagoDeposito" class="form-control pagoDealle">
                            </div>

                            <div class="opcion_pago">
                                <label for="pagoPlin">Plin:</label>
                                <input type="number" min="0" step="0.01" value="0.00" id="pagoPlin" class="form-control pagoDealle">
                            </div>


                            <input type="hidden" name="numHabitacion" id="numHabitacion" value="{{$numFil}}">
                            <input type="hidden" name="nombreCliente" id="nombreCliente" value="VARIOS">
                            <div class="botonPagar">
                                <button type="submit" class="btn btn-primary">
                                    <i class="far fa-credit-card"></i> Pagar
                                </button>
                                
                            </div>


                        </form>
                     
                    </div>
                </div>


        </div>

        <input type="hidden" name="montoTotal" id="montoTotal">
        <input type="hidden" name="idMovimiento" id="idMovimiento">
    </div>


</section>

</div>
</div>

<br>
<div class="text-center mx-auto">
    <a name="" id="irVistaPrincipal" class="btn btn-primary ml-2 mr-2" href="vistaPrincipal" role="button">Vista Principal</a>
    <a name="" id="irAgregarVenta" class="btn btn-danger ml-2 mr-2" href="detalleHabitacion?id={{$numFil}}" role="button">Agregar Venta</a>
</div>

</section>

@section('footer')
<footer>
    <div class="footer-container">
        <div class="footer-content">
            <div class="row">
                <div class="col-md-6">
                    <p class="texto-Footer"><b>Copyright&copy; 2023. </b><a class="garzasoftFooter" href="http://www.garzasoft.com/">Garzasoft</a>. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6">
                    <div class="footer-bottom">
                        <div class="footer-social">
                            <ul>
                                <li><a target="_blank" href="https://www.facebook.com/Garzasoft"><i class="fa-brands fa-facebook"></i></a></li>
                                <li><a target="_blank" href="https://api.whatsapp.com/send?phone=+51%20979293176&text=%C2%A1Hola!%20Quisiera%20informaci%C3%B3n%20sobre%20los%20servicios%20de%20Garzasoft."><i class="fab fa-whatsapp"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@stop
@include('Modulos.AgregarVenta.Modals.modalCrearUsuario')
@stop

@section('css')
<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css">





<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">

<!-- CSS DEL BOOTSTRAP -->
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- AWESOME -->

<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />

<!-- //CALENDARIOo -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">

<!-- SEELCT 2 -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />

<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css"><!-- CSS LOCAL -->
<link rel="stylesheet" href="{{ asset('css/app2.css')}}">

<link rel="stylesheet" href="{{ asset('css/detalles.css')}}">
<link rel="stylesheet" href="{{ asset('css/crearCuota.css')}}">
<link rel="stylesheet" href="{{ asset('css/footer.css')}}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css')}}">

@stop

@section('js')

<!-- JS DE JQUERY-->
<script src="/proyectoHotel/Cdn-Locales/pkgJquery/dist/jquery.js"></script>


<!-- CDN para BOOTSTRAP -->

<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js">
</script>

<!-- JS AWESOME -->
<script src="/proyectoHotel/Cdn-Locales/pkgAwsome/js/all.js"></script>

<!-- //CALENDARIO -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>

<!--  SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>


<!-- JS SELECT 2 -->
<script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>

<!-- JS DEL JQUERY EN PUBLIC/JS -->


<script src="{{ asset('js/JqueryDetalleVenta/JqueryIndexDetalleVenta.js') }}"></script>




@stop