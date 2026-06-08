@extends('adminlte::page')

@section('title', 'TIPOS DE USUARIO')

@section('content_header')
    <br><br>
    <!-- <h1 id="titulo" class="text-center">TIPOS DE USUARIOS</h1> -->
@stop

@section('content')
    <section class="cajaAlrededor">

        <div class="mb-1">
            <div style="float:left">
                @can($permisosCrear->name)
                    <a href="#" id="btonNuevo" class="btn btn-primary mt-2 btn-sm">NUEVO TIPO DE USUARIO</a>
                    @include('Modulos.Roles.Modals.modalCrearRol')
                @endcan
            </div>
            <form id="filtro">
                <div class="enviaFiltro" style="float:right">
                    <a id="resetFiltro" class="selectActivos btn btn-sm btn-dark"
                        style="font-size:12px;width:62px; background-color:black">
                        <i class="fa-solid fa-rotate-left btn-sm"></i>
                    </a>
                    <button type="submit" class="selectActivos  btn-sm btn" style="width:60px">
                        <i class="fa-solid fa-search btn-sm"></i>
                    </button>
                </div>
                <div style="float:right" class="mt-1 btn-sm">
                    <select id="activos" class="form-control activos selectActivos">
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

        @include('Modulos.Roles.Tables.tablaRol')
    </section>


    @can($permisosEditar->name)
        @include('Modulos.Roles.Modals.modalEditarRol')
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
<!-- CSS DEL BOOTSTRAP -->
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css"><!-- CSS DEL DATATABLE -->

<script src="/proyectoHotel/Cdn-Locales/pkgAwsome/js/all.js"></script>
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />

<!-- //calendaario -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">

<link rel="stylesheet" href="{{ asset('css/app2.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">
@stop

@section('js')

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

<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js"></script>


<!-- JS DEL JQUERY EN PUBLIC/JS -->

<script src="{{ asset('js/JqueryRol/JqueryIndexRol.js') }}"></script>

@can($permisosEliminar->name)
    <script src="{{ asset('js/JqueryRol/JqueryDestroyRol.js') }}"></script>
@endcan


@can($permisosCrear->name)
    <script src="{{ asset('js/JqueryRol/JqueryCreateRol.js') }}"></script>
@endcan

@can($permisosEditar->name)
    <script src="{{ asset('js/JqueryRol/JqueryEditRol.js') }}"></script>
    <script src="{{ asset('js/JqueryRol/JqueryUpdateRol.js') }}"></script>
@endcan


@stop
