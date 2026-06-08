@extends('adminlte::page')
<link type="image/png" href="/proyectoHotel/public/imagesComunes/Monterrico.jpg" rel="icon">

@section('title', 'AGREGAR COMPRA')

@section('content_header')
    <br><br>
    <!-- <h1 id="titulo" class="text-center mt-4">AGREGAR UNA VENTA </h1> -->
@stop

@section('content')
    <section class="cajaAlrededor">
        <section class="btnEliminarMovCompra d-none">
            <div class="mb-1">
                <div style="float:left" class="mx-2">
                    <a href="#" id="btonEliminarMovimientoCompra" style="background-color:#ca0000; color : white"
                        class="btn btonNuevo px-4 btn-sm"><b>Eliminar Compra</b> <i class="fa-solid fa-trash "
                            style="color: #fff;"></i></a>

                    <a href="javascript:void(0)" onclick="verDetalleCompra()" style="background:#3b5255; color:white;"
                        class="btn btn-info btn-sm">Ver Más <i class="fa-solid fa-eye"></i></a>

                </div>
                <input type="hidden" name="idMovimiento" id="idMovimiento">
            </div>
            <br><br>
        </section>
        <section>
            {{-- <div class="row justify-content-center bloqueCuota mb-4">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-5">
                        <p><b>Operacion:</b></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted" id="operacionI">{{$operacion}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-5">
                        <p><b>Responsable:</b></p>

                    </div>
                    <div class="col-md-6">
                        <p class="text-muted" id="responsableI">-</p>

                    </div>
                </div>
            </div>
        </div> --}}

        </section>
        <input type="hidden" name="idMovCompra" id="idMovCompra" value="{{ $idMovCompra }}">

        <form id="registroNuevoMovimientoCompra" class="mt-4 container ">
            @csrf
            <section class="w-100 border border-1 border-dark rounded-3 cajaProveedor px-4">

                <div class="form-group row m-1 ">
                    <div class="col-md-6 ">
                        <label>

                            <a id="agregarProveedor" class="btn btn-sm" onclick="agregarProveedor()">
                                <h4 class="text-decoration-underline fw-normal">Proveedor
                                    <i class="fa-solid fa-plus agregar" style="color: #1f64db;"></i>
                                </h4>
                            </a>
                            


                        </label>

                        <br>
                        <select name="proveedores" class="text-center selectTwo form-control " id="proveedores">

                        </select>

                    </div>
                    <div class="col-md-4">
                        <label>
                            <h4 class="text-decoration-underline fw-normal ">Tipo</h4>
                        </label>
                        <br>
                        <select name="tipo" class="text-center w-75" id="tipo">
                            <option value="Boleta">Boleta Compra</option>
                            <option value="Factura">Factura Compra</option>
                            <option value="Ticket">Ticket Compra</option>
                        </select>

                    </div>




                </div>

                <div class="form-group row m-3 p-2 ">
                    <div class="col-md-6 ">
                        <label>
                            <h4 class="text-decoration-underline fw-normal ">Número Compra</h4>
                        </label>
                        <br>
                        <input type="text" id="numCompra" class="text-center form-control w-75"
                            placeholder="Escribe el Numero de Compras">

                    </div>
                    <div class="col-md-4">
                        <label>
                            <h4 class="text-decoration-underline fw-normal ">Fecha</h4>
                        </label>
                        <br>
                        <input type="date" id="fechaCompro" class="text-center btn-sm mt-2 form-control w-75"
                            placeholder="yyyy/mm/dd">


                    </div>

                    <div class="col-md-2">
                        <br>
                        <div class="text-center mx-auto">
                            <a name="" id="Registrar" class="btn btn-primary  d-none ml-2 mr-2" href="#"
                                role="button">CONTINUAR</a>
                            <a name="" id="Actualizar" class="btn btn-warning d-none ml-2 mr-2" href="#"
                                role="button">GRABAR</a>
                        </div>
                    </div>

                </div>

                <div class="form-group row m-3 p-2 ">
                    <div class="col-md-6 ">
                        <label>
                            <h4 class="text-decoration-underline fw-normal ">Forma de Pago</h4>
                        </label>
                        <br>
                        <select name="formaPagoSelect" class="text-center w-75" id="formaPagoSelect">
                            <option value="Contado">Contado</option>
                            <option value="Credito">Credito</option>
                        </select>

                    </div>
                    <div class="col-md-4 d-none numCuotasLbl">
                        <label>
                            <h4 class="text-decoration-underline fw-normal ">N° Cuotas</h4>
                        </label>
                        <br>
                        <input type="number" class="text-center form-control w-75" name="cantCuotas" id="cantCuotas"
                            placeholder="Escribe el N° Cuotas">

                    </div>

                </div>


            </section>
        </form>

        <section id="cajaAgregar"
            class="w-100 mb-3 text-center mx-auto  border border-1 border-dark rounded-3 px-4 py-3 mt-4 ">
            <div class="container Compras text-left">
                <br>


                <div class="cajaProductos">
                    <h4 class="text-decoration-underline fw-normal">Productos <i class="fa-solid fa-plus agregar"
                            id="agregarProducto" style="color: #ff1414;"></i></h4>

                    <table class="table" id="tablaProductosComprados">
                        <thead id="theadProductos">
                            <tr>
                                <th>Acciones</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <?php
                                if ($operacion != 'compras') {
                                    echo '<th>Tipo</th>';
                                }
                                
                                if ($operacion == 'compras') {
                                    echo ' <th>Total</th>';
                                }
                                
                                ?>
                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody class="listaProductos">
                        </tbody>
                    </table>


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
            @include('Modulos.Compras.Modal.modalEditarCantProducto')
        </div>



    </section>




    {{-- @section('footer')
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
@stop --}}



    <div>
        <div>
            @include('Modulos.AgregarVenta.Modals.modalCrearUsuario')
            @include('Modulos.Compras.Modal.modalAgregarProducto')
        </div>
    </div>
@stop

@section('css')
    <!-- CSS DEL BOOTSTRAP -->
    <link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS DEL DATATABLE -->
    <link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css"><!-- CSS LOCAL -->


    <!-- AWESOME -->

    <link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />
    <!-- //calendaario -->
    <link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.css">
    <!-- select2 -->
    <!-- SEELCT 2 -->
    <link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />


    <link rel="stylesheet" href="{{ asset('css/app2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selectCambiarHabitacion.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crearCuota.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">
@stop

@section('js')
    <!-- JS DE DATATABLE -->
    <!-- JS DE JQUERY-->
    <script src="/proyectoHotel/Cdn-Locales/pkgJquery/dist/jquery.js"></script>


    <!-- //CALENDARIO -->
    <script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>



    <!-- JS SELECT 2 -->
    <script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>

    <!--  SWEETALERT2-->
    <script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

    <!-- CDN para BOOTSTRAP -->

    <script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js"></script>


    <!-- //CALENDARIO -->
    <script src="/proyectoHotel/Cdn-Locales/pkgCalendarFlatpick/dist/flatpickr.js"></script>


    <!-- JS DE DATATABLE -->
    <script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>

    <!-- JS DEL JQUERY EN PUBLIC/JS -->

    <script src="{{ asset('js/JqueryCompras/JqueryCreateCompras.js') }}"></script>

    <script src="{{ asset('js/JqueryCompras/JqueryAgregarProducto.js') }}"></script>
    <script src="{{ asset('js/JqueryCompras/JqueryDestroyCompras.js') }}"></script>
    <script src="{{ asset('js/JqueryCompras/JueryCantidProdCompra.js') }}"></script>
    {{-- <script src="{{ asset('js/JqueryCompras/JqueryCreateCuadre.js') }}"></script> --}}
@stop
