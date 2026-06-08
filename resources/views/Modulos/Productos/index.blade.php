@extends('adminlte::page')

@section('title', 'PRODUCTOS')

@section('content_header')
<br><br>
<!-- <h1 id="titulo" class="text-center">PRODUCTOS</h1> -->
@stop

@section('content')
<section class="cajaAlrededor">

<div class="col-lg-12">
    <div class="row bg-body pb-4 cajaPerfil">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
            <a class="nav-link  text-dark" id="producto" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                    <strong>PRODUCTOS</strong>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active text-dark" id="categoria-tab" data-bs-toggle="tab" href="#categoria" role="tab" aria-controls="categoria" aria-selected="false">
                    <strong>CATEGORIA</strong>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" id="unidad-tab" data-bs-toggle="tab" href="#unidad" role="tab" aria-controls="unidad" aria-selected="false">
                    <strong>UNIDAD</strong>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade mt-4" style="width:100%" id="unidad" role="tabpanel" aria-labelledby="unidad-tab">
                @include('Modulos.Productos.Modals.Unidad.ventanaUnidad')
                @include('Modulos.Productos.Modals.Unidad.modalCrearUnidad')
                @include('Modulos.Productos.Modals.Unidad.modalEditarUnidad')
                @include('Modulos.Productos.Tables.tablaUnidad')
            </div>
            <div class="tab-pane show active fade mt-4" style="width:100%" id="categoria" role="tabpanel" aria-labelledby="categoria-tab">
            @include('Modulos.Productos.Modals.Categoria.ventanaCategoria')
            @include('Modulos.Productos.Modals.Categoria.modalCrearCategoria')
                @include('Modulos.Productos.Modals.Categoria.modalEditarCategoria')
                @include('Modulos.Productos.Tables.tablaCategoria')
            </div>
            <div class="tab-pane fade mt-4" style="width:100%" id="home" role="tabpanel" aria-labelledby="producto">
                @include('Modulos.Productos.Modals.Producto.ventanaProducto')
                @include('Modulos.Productos.Modals.Producto.modalCrearProducto')
                @include('Modulos.Productos.Modals.Producto.modalEditarProducto')
                @include('Modulos.Productos.Tables.tablaProducto')
            </div>
        </div>
    </div>
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
<link rel="stylesheet" href="{{ asset('css/appOpciones.css')}}">
<link rel="stylesheet" href="{{ asset('css/select.css')}}">
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

<!-- PARA CATEGORIA -->
<script src="{{ asset('js/JqueryCategoria/JqueryIndexCategoria.js') }}"></script>
@can($permisosCrear->name)
<script src="{{ asset('js/JqueryCategoria/JqueryCreateCategoria.js') }}"></script>
@endcan
@can($permisosEditar->name)
<script src="{{ asset('js/JqueryCategoria/JqueryEditCategoria.js') }}"></script>
<script src="{{ asset('js/JqueryCategoria/JqueryUpdateCategoria.js') }}"></script>
@endcan
@can($permisosEliminar->name)
<script src="{{ asset('js/JqueryCategoria/JqueryDestroyCategoria.js') }}"></script>
@endcan


<!-- PARA UNIDAD -->
<script src="{{ asset('js/JqueryUnidad/JqueryIndexUnidad.js') }}"></script>
@can($permisosCrear->name)
<script src="{{ asset('js/JqueryUnidad/JqueryCreateUnidad.js') }}"></script>
@endcan
@can($permisosEditar->name)
<script src="{{ asset('js/JqueryUnidad/JqueryEditUnidad.js') }}"></script>
<script src="{{ asset('js/JqueryUnidad/JqueryUpdateUnidad.js') }}"></script>
@endcan
@can($permisosEliminar->name)
<script src="{{ asset('js/JqueryUnidad/JqueryDestroyUnidad.js') }}"></script>
@endcan


<!-- PARA PRODUCTO -->
<script src="{{ asset('js/JqueryProducto/JqueryIndexProducto.js') }}"></script>
@can($permisosCrear->name)
<script src="{{ asset('js/JqueryProducto/JqueryCreateProducto.js') }}"></script>
@endcan
@can($permisosEditar->name)
<script src="{{ asset('js/JqueryProducto/JqueryEditProducto.js') }}"></script>
<script src="{{ asset('js/JqueryProducto/JqueryUpdateProducto.js') }}"></script>
@endcan
@can($permisosEliminar->name)
<script src="{{ asset('js/JqueryProducto/JqueryDestroyProducto.js') }}"></script>
@endcan

@stop