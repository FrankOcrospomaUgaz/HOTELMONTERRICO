@extends('adminlte::page')

@section('title', 'GRUPO MENU')

@section('content_header')
<br><br>
<!-- <h1 id="titulo" class="text-center">GRUPO MENU</h1> -->
@stop

@section('content')


<section class="cajaAlrededor">
    <div class="mb-2">
        <div style="float:left">
            @can($permisosCrear->name)
            <a href="#" id="btonNuevo" class="btn btn-primary btonNuevo btn-sm">NUEVO GRUPO</a>
            @include('Modulos.GrupoMenu.Modals.modalCrearGrupoMenu')
            @endcan
        </div>
        <form id="filtro">
            <div class="enviaFiltro" style="float:right">
                <span id="resetFiltro" class="selectActivos btn btn-dark" style="font-size:12px;width:62px; background-color:black">
                    <i class="fa-solid fa-rotate-left btn-sm"></i>
                </span>
                <button type="submit" class="selectActivos btn btn-sm" style="width:60px">
                    <i class="fa-solid fa-search btn-sm"></i>
                </button>
            </div>
            <div style="float:right" class="mt-1 btn-sm">
                <select id="activos" class="form-control activos selectActivos btn-sm">
                    <option value="todos">Todos</option>
                    <option value="activos">Activos</option>
                  <option value="inactivos">Inactivos</option>
                </select>
            </div>
            <div class="centrarFechas">
                <input type="text" id="calendarioInicio" class="selectActivos btn-sm" placeholder="Fecha Inicio">
                <input type="text" id="calendarioFin" class="selectActivos btn-sm" placeholder="Fecha Fin">
            </div>
        </form>
    </div>
@include('Modulos.GrupoMenu.Tables.tablaGrupoMenu')
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
@include('Modulos.GrupoMenu.Modals.modalEditarGrupoMenu')
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



<link rel="stylesheet" href="{{ asset('css/appOpciones.css')}}">
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

<!-- JS DE DATATABLE -->
<script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>

<!-- JS SELECT 2 -->
<script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>

<!-- JS DEL JQUERY EN PUBLIC/JS -->
<script src="{{ asset('js/JqueryGrupoMenu/JqueryIndexGrupo.js') }}"></script>




@can($permisosEliminar->name)
<script src="{{ asset('js/JqueryGrupoMenu/JqueryDestroyGrupo.js') }}"></script>
@endcan


@can($permisosCrear->name)
<script src="{{ asset('js/JqueryGrupoMenu/JqueryCreateGrupo.js') }}"></script>
@endcan

@can($permisosEditar->name)
<script src="{{ asset('js/JqueryGrupoMenu/JqueryEditGrupo.js') }}"></script>
<script src="{{ asset('js/JqueryGrupoMenu/JqueryUpdateGrupo.js') }}"></script>
@endcan



@stop