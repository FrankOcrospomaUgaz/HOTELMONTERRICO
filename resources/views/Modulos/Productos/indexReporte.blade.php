@extends('adminlte::page')

@section('title', 'REPORTE STOCK PRODUCTOS')

@section('content_header')
    <br><br>
    <!-- <h1 id="titulo" class="text-center">PRODUCTOS</h1> -->
@stop

@section('content')

    <style>
        #tbProductoStock td {
            font-size: 15px;
            
        }

        .pdf-export-button {
            background-color: #000144;
            /* Fondo rojo */
            color: #fff;
            /* Texto blanco */
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            /* Espaciado interior */
            font-size: 16px;
            /* Tamaño de fuente */
            cursor: pointer;
            border-radius: 10px;
        }


        .container {
            display: flex;
            align-items: center;
            /* Centra verticalmente */
            justify-content: space-between;
            /* Separa el botón de la izquierda y el título al centro */
        }

        .text-center {
            text-align: center;
            /* Centra el texto horizontalmente */
        }
        .pdf-export-button:hover {
    background-color: rgb(6, 147, 228); /* Cambio de color al pasar el cursor */
}
    </style>

    <section class="cajaAlrededor">


        <div style="overflow: hidden;">
  

            <button id="imprimirStockProd" class="pdf-export-button" type="" style=" float: right;">
                PRINT <i class="fa-solid fa-print"></i>
            </button>
        </div>


        <div class="mt-2">
 
            @include('Modulos.Productos.Tables.tablaProductoStock')
        </div>


    @section('footer')
        <footer>
            <div class="footer-container">
                <div class="footer-content">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="texto-Footer"><b>Copyright&copy; 2023 </b><a class="garzasoftFooter"
                                    href="http://www.garzasoft.com/">Garzasoft</a>. Todos los derechos reservados.
                            </p>
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
@stop

@section('css')




<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css">

<!-- CSS DEL BOOTSTRAP -->
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- AWESOME -->

<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />

<!-- //CALENDARIOo -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">

<!-- SEELCT 2 -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />

<!-- CSS LOCAL -->
<link rel="stylesheet" href="{{ asset('css/appOpciones.css') }}">
<link rel="stylesheet" href="{{ asset('css/select.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">
@stop

@section('js')

<!-- JS DE JQUERY-->
<script src="/proyectoHotel/Cdn-Locales/pkgJquery/dist/jquery.js"></script>


<!-- CDN para BOOTSTRAP -->

<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js"></script>

<!-- JS AWESOME -->
<script src="/proyectoHotel/Cdn-Locales/pkgAwsome/js/all.js"></script>

<!-- //CALENDARIO -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>

<!--  SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

<!-- JS DE DATATABLE -->
<script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>

<!-- JS SELECT 2 -->
<script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>
<!-- JS DEL JQUERY EN PUBLIC/JS -->



<!-- PARA PRODUCTO -->
<script src="{{ asset('js/JqueryProducto/JqueryIndexProductoStock.js') }}"></script>


@stop
