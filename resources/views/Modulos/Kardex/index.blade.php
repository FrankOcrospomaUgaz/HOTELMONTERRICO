@extends('adminlte::page')

@section('title', 'KARDEX')

@section('content_header')
@stop

@section('content')
    <br><br><br>
    <section class="cajaAlrededor mt-2">

        <div class="row">
            <div class="col-md-6 offset-md-3 kardex">

                <h2 class="text-center">REPORTE KARDEX</h2>
                <br>


                <form id="envioDescargarKardex" action="procesar_formulario.php" method="POST">

                    <div class="mb-3">
                        <label for="fechaInicio" class="form-label"><b>Fecha de inicio:</b></label>
                        <br>
                        <select name="productos" class="form-control selectTwo " id="productos">
                        </select>
                    </div> <br>
                    <div class="mb-3">
                        <label for="fechaInicio" class="form-label"><b>Fecha de inicio:</b></label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                    </div> <br>
                    <div class="mb-3">
                        <label for="fechaFin" class="form-label"><b>Fecha de fin:</b></label>
                        <input type="date" class="form-control" id="fechaFin" name="fechaFin" required>
                    </div> <br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><b>Descargar</b></button>
                    </div>
                </form>

            </div>
        </div>

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
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css"><!-- CSS LOCAL -->

<!-- SEELCT 2 -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />
<link rel="stylesheet" href="{{ asset('css/selectCambiarHabitacion.css') }}">
<script src="/proyectoHotel/Cdn-Locales/pkgAwsome/js/all.js"></script>
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />
<link rel="stylesheet" href="{{ asset('css/app2.css') }}">
<link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">
<link rel="stylesheet" href="{{ asset('css/select.css') }}">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<!-- JS DE DATATABLE -->
<script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>



<!-- PARA MODAL DE ALERTAS SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>
<!-- select2 -->
<!-- JS SELECT 2 -->
<script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>

<!-- CDN para BOOTSTRAP -->
<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js">
</script>
<!-- JS DEL JQUERY EN PUBLIC/JS -->
<script src="{{ asset('js/JqueryKardex/JqueryKardex.js') }}"></script>
@stop
