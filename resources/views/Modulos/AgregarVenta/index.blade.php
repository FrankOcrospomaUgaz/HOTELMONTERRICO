@extends('adminlte::page')

@section('title', 'AGREGAR VENTA')

@section('content_header')
    <br><br>
    <!-- <h1 id="titulo" class="text-center mt-4">AGREGAR UNA VENTA </h1> -->
@stop

@section('content')
    @include('Modulos.AgregarVenta.partials.contenido')
@stop

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

@section('css')
    <link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />
    <link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />
    <link rel="stylesheet" href="{{ asset('css/app2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/venta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selectCambiarHabitacion.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crearCuota.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">
@stop

@section('js')
    <script>
        window.__VENTA_RAPIDA_DATA__ = @json($initialData);
    </script>
    <script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>
    <script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>
    <script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js"></script>

    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryIndexVenta.js') }}"></script>
    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryCambiarHabitacion.js') }}"></script>
    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyMovimiento.js') }}"></script>
    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyProducto.js') }}"></script>
    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyServicio.js') }}"></script>
    <script src="{{ asset('js/JqueryUsuario/JqueryCreateUsuario.js') }}"></script>
@stop
