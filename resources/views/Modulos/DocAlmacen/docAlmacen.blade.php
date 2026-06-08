@extends('adminlte::page')

@section('title', 'AGREGAR COMPRA')

@section('content_header')
<br><br>
<!-- <h1 id="titulo" class="text-center mt-4">AGREGAR UNA VENTA </h1> -->
@stop

@section('content')
<section class="cajaAlrededor">
    <section class="btnEliminarMovCompra d-none">
        <div class="mb-1">

            <input type="hidden" name="idMovimiento" id="idMovimiento">
        </div>
        <br><br>
    </section>
    <section>
        <div class="row justify-content-center bloqueCuota mb-4">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-5">
                        <p><b>Operacion:</b></p>
                        <p><b>Tipo:</b></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted" id="operacionI">{{$operacion}}</p>
                        <p class="text-muted" id="tipoI">{{$tipo}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-5">
                        <p><b>Responsable:</b></p>
                        <p><b>Fecha:</b></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted" id="responsableI">-</p>
                        <p class="text-muted" id="fechaI">-</p>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <input type="hidden" name="idMovCompra" id="idMovCompra" value="{{$idMovCompra}}">
    <input type="hidden" name="agregarDocALmacen" id="agregarDocALmacen" value="{{$agregarDocALmacen}}">

    <section id="cajaAgregar" class="w-100 mb-3 text-center mx-auto  border border-1 border-dark rounded-3 px-4 py-3 mt-4 ">
        <div class="container Compras text-left">
            <br>


            <div class="cajaProductos">
                
                <?php
                if ($agregarDocALmacen=='true') {
                    echo '<h4 class="text-decoration-underline fw-normal">Productos <i class="fa-solid fa-plus agregar" id="agregarProducto" style="color: #ff1414;"></i></h4>';
                }
                ?>
                
                
                <div class="formatoTabla mb-4">
                    <table class="table">
                        <thead id="theadProductos">
                            <tr>
                            
                              <th id='idAccionesDocA' class='d-none'>Acciones</th>
                             
                                
                                <th>Producto</th>
                                <th>Motivo</th>
                                <th>Cantidad</th>
                                <th>Precio</th>

                                <th>Total</th>



                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody class="listaProductos">
                        </tbody>
                    </table>

                </div>
            </div>




            <div class="cajaTotal">
                <ul>
                    <li>
                        <span><b>Monto Total:</b></span>
                        <span class="totalPagar" id="totalMasIgv"></span>
                    </li>
                </ul>
            </div>





        </div>



    </section>

    <div>
        @include('Modulos.DocAlmacen.Modal.modalEditarCantProducto')
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



<div>
    <div>
        @include('Modulos.DocAlmacen.Modal.modalAgregarProducto')
    </div>
</div>
@stop

@section('css')
<!-- CSS DEL BOOTSTRAP -->
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css"><!-- CSS LOCAL -->


<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />

<!-- //calendaario -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">
<!-- SEELCT 2 -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />


<link rel="stylesheet" href="{{ asset('css/app2.css')}}">
<link rel="stylesheet" href="{{ asset('css/select.css')}}">
<link rel="stylesheet" href="{{ asset('css/compras.css')}}">
<link rel="stylesheet" href="{{ asset('css/footer.css')}}">
<link rel="stylesheet" href="{{ asset('css/selectCambiarHabitacion.css')}}">
<link rel="stylesheet" href="{{ asset('css/crearCuota.css')}}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css')}}">
@stop

@section('js')
<!-- JS DE DATATABLE -->
<!-- JS DE JQUERY-->
<script src="/proyectoHotel/Cdn-Locales/pkgJquery/dist/jquery.js"></script>

<!-- //calendaario -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>



<!-- JS SELECT 2 -->
<script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>

<!-- PARA MODAL DE ALERTAS SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

<!-- CDN para BOOTSTRAP -->
<!-- CDN para BOOTSTRAP -->

<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js">
</script>

<!-- //calendaario -->
<script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>

<!-- JS DEL JQUERY EN PUBLIC/JS -->

<script src="{{ asset('js/JqueryDocAlmacen/JqueryCreateAlmacen.js') }}"></script>

<script src="{{ asset('js/JqueryDocAlmacen/JqueryAgregarProductoAlmacen.js') }}"></script>
<script src="{{ asset('js/JqueryDocAlmacen/JqueryDestroyAlmacen.js') }}"></script>
<script src="{{ asset('js/JqueryDocAlmacen/JueryCantidProdAlmacen.js') }}"></script>

@stop