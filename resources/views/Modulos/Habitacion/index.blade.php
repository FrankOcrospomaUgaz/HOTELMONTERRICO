@extends('adminlte::page')

@section('title', 'HABITACIONES')

@section('content_header')
<br><br>
<!-- <h1 id="titulo" class=<br>"text-center">HABITACIONES</h1> -->
@stop

@section('content')
<section class="cajaAlrededor">

    <div class="mb-1">
        <div style="float:left">
            @can($permisosCrear->name)
            <a href="#" id="btonNuevo" class="btn btn-primary btonNuevo btn-sm">NUEVA HABITACION</a>
            @include('Modulos.Habitacion.Modals.modalCrearHabitacion')
            @endcan
        </div>
        <form id="filtro">
            <div class="enviaFiltro" style="float:right">
               <a id="resetFiltro" class="selectActivos btn btn-sm btn-dark"
                        style="font-size:12px;width:62px; background-color:black">
                        <i class="fa-solid fa-rotate-left btn-sm"></i>
                    </a>
                <button type="submit" class="selectActivos btn btn-sm" style="width:60px">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>
            <div style="float:right" class=" btn-sm">
                <select id="activos" class="form-control activos selectActivos">
                    <option value="todos">Todos</option>
                    <option value="activos">Activos</option>
                  <option value="inactivos">Inactivos</option>
                </select>
            </div>
            <br><br><br>
        </form>
    </div>
    @include('Modulos.Habitacion.Tables.tablaHabitacion')
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
@can($permisosEditar->name)
@include('Modulos.Habitacion.Modals.modalEditarHabitacion')
@endcan

@can($permisosEditar->name)
<input type="text" class="d-none" id="permisoEdit" value="editar">
@else
<input type="text" class="d-none" id="permisoEdit" value="noeditar">
@endcan

@can($permisosEliminar->name)
<input type="text" class="d-none" id="permisoElim" value="eliminar">
@else
<input type="text" class="d-none" id="permisoElim" value="noeliminar">
@endcan


@stop

@section('css')
<!-- CSS DEL BOOTSTRAP -->
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css"><!-- CSS LOCAL -->


<script src="/proyectoHotel/Cdn-Locales/pkgAwsome/js/all.js"></script>
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />

<!-- //calendaario -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">


<link rel="stylesheet" href="{{ asset('css/appOpciones.css')}}">
<link rel="stylesheet" href="{{ asset('css/footer.css')}}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css')}}">
@stop

@section('js')
<!-- JS DE DATATABLE -->
<!-- JS DE JQUERY-->
<script src="/proyectoHotel/Cdn-Locales/pkgJquery/dist/jquery.js"></script>
<!-- JS DE DATATABLE -->
<script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>



<!-- //calendaario -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>
<!-- PARA MODAL DE ALERTAS SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

<!-- CDN para BOOTSTRAP -->
<!-- CDN para BOOTSTRAP -->

<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js">
</script>
<!-- JS DEL JQUERY EN PUBLIC/JS -->


<script src="{{ asset('js/JqueryHabitacion/JqueryIndexHabitacion.js') }}"></script>

@can($permisosEliminar->name)
<script src="{{ asset('js/JqueryHabitacion/JqueryDestroyHabitacion.js') }}"></script>
@endcan


@can($permisosCrear->name)
<script src="{{ asset('js/JqueryHabitacion/JqueryCreateHabitacion.js') }}"></script>
@endcan

@can($permisosEditar->name)
<script src="{{ asset('js/JqueryHabitacion/JqueryEditHabitacion.js') }}"></script>
<script src="{{ asset('js/JqueryHabitacion/JqueryUpdateHabitacion.js') }}"></script>
@endcan



@stop