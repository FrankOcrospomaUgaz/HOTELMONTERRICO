@extends('adminlte::page')

@section('title', 'HABITACIONES')

@section('content_header')
    <br><br>
    <!-- <h1 id="titulo" class="text-center">SERVICIOS</h1> -->
@stop

@section('content')
    <section class="cajaAlrededor">

        <div>
            <a href="javascript:void(0)" data-tooltip="Reporte de Habitaciones" onclick="reporteEmergenciaPdf()"
                style="margin:2px; color:white;" class="btn btn-danger btn-profesional">REPORTE DE EMERGENCIA <i
                    class="fa-solid fa-file-pdf" style="color: #fff; font-size:15px;"></i></a>
        </div>


        <div class="tablaContainer">

            @include('Modulos.VistaPrincipal.Tables.tablaHabitaciones')

            <br>
        </div>


    </section>
    @can($permisosEditar->name)
    @endcan


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
@include('Modulos.VistaPrincipal.Modal.modalCambiarSituacion')
@include('Modulos.AgregarVenta.Modals.modalCambiarHabitacion')
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
<!-- CSS LOCAL -->

<link rel="stylesheet" href="{{ asset('css/appOpciones.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/habitaciones.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">
@stop

@section('js')

<!-- JS DE JQUERY-->
<script src="/proyectoHotel/Cdn-Locales/pkgJquery/dist/jquery.js"></script>

<!-- JS DE DATATABLE -->
<script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>

<!-- JS AWESOME -->
<script src="/proyectoHotel/Cdn-Locales/pkgAwsome/js/all.js"></script>



<!-- //calendaario -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>

<!-- PARA MODAL DE ALERTAS SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

<!-- CDN para BOOTSTRAP -->
<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js">
</script>
<!-- JS DEL JQUERY EN PUBLIC/JS -->


<script src="{{ asset('js/JqueryVistaHabitaciones/tablaVista.js') }}"></script>
<script src="{{ asset('js/JqueryVistaHabitaciones/botones.js') }}"></script>




@stop
