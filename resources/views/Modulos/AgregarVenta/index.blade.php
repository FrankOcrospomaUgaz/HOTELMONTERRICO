@extends('adminlte::page')

@section('title', 'AGREGAR VENTA')

@section('content_header')
    <br><br>
    <!-- <h1 id="titulo" class="text-center mt-4">AGREGAR UNA VENTA </h1> -->
@stop

@section('content')
    <section class="cajaAlrededor">

        <section class="cajaAccionesVenta d-none">
            <div class="mb-1">
                <div style="float:left" class="mx-2">
                    <a href="javascript:void(0)" data-tooltip="Cambiar Situación" id="btonCambiarHabitacion"
                        onclick="cambiarHabitacion(' . $habitacion->id . ')"
                        style="background:BLUE;margin:3px; color:white;" class="btn btn-sm btn-profesional">CAMBIAR
                        HABITACIÓN</a>

                </div>
                <div style="float:left" class="mx-2">
                    <a href="#" id="btonEliminarMovimientoAtencion" style="background-color:#ca0000"
                        class="btn btonNuevo px-4 btn-sm"><i class="fa-solid fa-trash " style="color: #fff;"></i></a>
                </div>
                <input type="hidden" name="idMovimiento" id="idMovimiento">
            </div>
            <br><br>
        </section>

        <section>
            <div class="row justify-content-center bloqueCuota mb-4">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-5">
                            <p><b>Numero Habitación:</b></p>
                            <p><b>Tipo:</b></p>
                            <p><b>Nombre Cliente:</b></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted" id="numeroI">N° {{ $numFil }}</p>
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

        <section class="w-100 border border-1 border-dark rounded-3 cajaCliente d-none px-4">
            <form id="registroNuevoMovimientoAtencion" class="mt-4 container">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>
                            <h4 class="text-decoration-underline fw-normal ">Cliente <i class="fa-solid fa-plus agregar"
                                    id="agregarCliente" style="color: #1f64db;"></i></h4>
                        </label>
                        <select name="clientes" class="form-control selectTwo " id="clientes">

                        </select>

                    </div>
                    <input type="hidden" name="numHabitacion" id="numHabitacion" value="{{ $numFil }}">
                    <div class="col-md-6">
                        <div class="text-center">
                            <button type="submit" id="enviaCheckInBtn" class="btn btn-primary btn-sm ">REGISTRAR
                                CHECK-IN</button>
                        </div>

                    </div>

                </div>
            </form>


        </section>


        <section id="cajaAgregar"
            class="w-100 mb-3 text-center mx-auto border border-1 border-dark rounded-3 px-4 py-3 mt-4 ">
            <div class="container Ventas text-left">
                <br>


                <div class="cajaProductos d-none">
                    <h4 class="text-decoration-underline fw-normal">Productos <i class="fa-solid fa-plus agregar"
                            id="agregarProducto" style="color: #ff1414;"></i></h4>
                    <div class="formatoTabla mb-4">
                        <table class="table">
                            <thead id="theadProductos">
                                <tr>
                                    <th>Acciones</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Comentario</th>
                                    <th>Descuento (%)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody class="listaProductos">
                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>


                <div class="cajaServicios">
                    <h4 class="text-decoration-underline fw-normal">Servicios <i class="fa-solid fa-plus agregar d-none"
                            id="agregarServicio" style="color: #1f64db;"></i></h4>
                    <div class="formatoTabla mb-4">
                        <table class="table">
                            <thead id="theadServicios">
                                <tr>
                                    <th>Acciones</th>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Comentario</th>
                                    <th>Descuento (%)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody class="listaServicios">
                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="cajaTotal">
                    <ul>
                        <li>
                            <span><b>Total a pagar:</b></span>
                            <span class="totalPagar" id="totalMasIgv"></span>
                        </li>
                    </ul>
                </div>


            </div>
            <div class="text-center mx-auto">
                <a name="" id="irVistaPrincipal" class="btn btn-primary ml-2 mr-2" href="vistaPrincipal"
                    role="button">Vista Principal</a>
                <a name="" id="irDetalleHabitacion" class="btn btn-danger ml-2 mr-2 d-none"
                    href="detalleHabitacion?id={{ $numFil }}" role="button">Pagar Habitación</a>
            </div>


            </div>

        </section>
    </section>

@section('footer')
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="row">
                    <div class="col-md-6">
                        <p class="texto-Footer"><b>Copyright&copy; 2023. </b><a class="garzasoftFooter"
                                href="http://www.garzasoft.com/">Garzasoft</a>. Todos los derechos reservados.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-bottom">
                            <div class="footer-social">
                                <ul>
                                    <li><a target="_blank" href="https://www.facebook.com/Garzasoft"><i
                                                class="fa-brands fa-facebook"></i></a></li>
                                    <li><a target="_blank"
                                            href="https://api.whatsapp.com/send?phone=+51%20979293176&text=%C2%A1Hola!%20Quisiera%20informaci%C3%B3n%20sobre%20los%20servicios%20de%20Garzasoft."><i
                                                class="fab fa-whatsapp"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@stop

<div>
    @include('Modulos.AgregarVenta.Modals.modalCambiarHabitacion')
    @include('Modulos.AgregarVenta.Modals.modalCrearUsuario')
    @include('Modulos.AgregarVenta.Modals.Producto.modalVentaProducto')
    @include('Modulos.AgregarVenta.Modals.Producto.modalEditCantProducto')

</div>

<div>
    @include('Modulos.AgregarVenta.Modals.Servicio.modalVentaServicio')
</div>

@stop

@section('css')


<!-- CSS DEL BOOTSTRAP -->
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">


<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css">




<!-- AWESOME -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />

<!-- //calendaario -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">

<!-- select2 -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />
<!-- CSS LOCAL -->
<link rel="stylesheet" href="{{ asset('css/app2.css') }}">
<link rel="stylesheet" href="{{ asset('css/select.css') }}">
<link rel="stylesheet" href="{{ asset('css/venta.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/selectCambiarHabitacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/crearCuota.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">

@stop

@section('js')
<!-- JS DE DATATABLE -->
<script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>

<!-- //calendaario -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>




<!-- PARA MODAL DE ALERTAS SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

<!-- JS SELECT 2 -->
<script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>


<!-- CDN para BOOTSTRAP -->
<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js">
    <!-- //calendaario 
    -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>

<!-- JS DEL JQUERY EN PUBLIC/JS -->



<script src="{{ asset('js/JqueryVentaHabitacion/JqueryIndexVenta.js') }}"></script>
<script src="{{ asset('js/JqueryVentaHabitacion/JqueryCantProducto.js') }}"></script>
<script src="{{ asset('js/JqueryVentaHabitacion/JqueryCambiarHabitacion.js') }}"></script>
<script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyMovimiento.js') }}"></script>
<script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyProducto.js') }}"></script>
<script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyServicio.js') }}"></script>
<script src="{{ asset('js/JqueryVentaHabitacion/JqueryAgregarProducto.js') }}"></script>
<script src="{{ asset('js/JqueryVentaHabitacion/JqueryAgregarServicio.js') }}"></script>


<script src="{{ asset('js/JqueryUsuario/JqueryCreateUsuario.js') }}"></script>

@stop
