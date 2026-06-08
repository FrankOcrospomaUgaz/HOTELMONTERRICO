@extends('adminlte::page')

@section('title', 'REPORTE CHECK OUT')

@section('content_header')
    <br><br>
    <!-- <h1 id="titulo" class="text-center">BUSQUEDA DE HABITACION DETALLE</h1> -->
@stop

@section('content')

    <section class="cajaAlrededor">

        <section class="cajaAlrededor cajaVerBusqueda mx-auto text-center">
            <form id="filtro" method="POST" action="">
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-row align-items-center">
                            <div class="col-md-4">
                                <label for="calendarioInicio">Desde:</label>
                                <input type="date" id="calendarioInicio" class="form-control mb-2" style="text-align: center;background-color: white; color:black" placeholder="Fecha Inicio">
                            </div>
                            <div class="col-md-4">
                                <label for="calendarioFin">Hasta:</label>
                                <input type="date" id="calendarioFin" class="form-control mb-2" style="text-align: center;background-color: white; color:black" placeholder="Fecha Fin">
                            </div>
                            <div class="col-md-4">
                                <label for="numero">Habitación:</label>
                                <input type="number" id="numeroHabit" class="form-control mb-2" placeholder="TODOS" style="text-align: center;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center mt-4">
                            <button type="submit" id="botonEnviar" class="btn" style="background-color: #058ba0; color: white;">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        
        
        
        


        <div style="float:right" class=" btn-sm">
            <button id="verBusqueda" type="" class="selectActivos btn btn-sm" style="width:60px">
                <i class="fa-solid fa-search"></i>
            </button>
        </div>
     
        <div class="tablaContainer">
            @include('Modulos.CajaChica.Tables.tablaCaja')

        </div>


    </section>



@section('footer')
    <footer>
        <div class="footer-container mt-3">
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

    <div>
        @include('Modulos.CajaChica.Modals.modalEditar')
    </div>
    <div>
        @include('Modulos.CajaChica.Modals.modalCarrito')
    </div>

    <div>
        @include('Modulos.CajaChica.Modals.modalCierre')
    </div>
@stop
@stop

@section('css')
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css">



<script src="/proyectoHotel/Cdn-Locales/pkgAwsome/js/all.js"></script>
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />


<!-- //calendaario -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">
<!-- select2 -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />

<!-- CSS LOCAL -->
<link rel="stylesheet" href="{{ asset('css/app2.css') }}">

<link rel="stylesheet" href="{{ asset('css/caja.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/select.css') }}">

@stop

@section('js')
<!-- JS DE DATATABLE -->
<script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>





<!-- //calendaario -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>



<!-- select2 -->
<script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>

<!-- PARA MODAL DE ALERTAS SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

<!-- CDN para BOOTSTRAP -->
<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js"></script>

<!-- //calendaario -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>

<!-- JS DEL JQUERY EN PUBLIC/JS -->



<script src="{{ asset('js/JqueryCajaChica/JqueryIndexCajaChicaReporte.JS') }}"></script>
<script src="{{ asset('js/JqueryCajaChica/JqueryModalEditarCaja.JS') }}"></script>

@stop
