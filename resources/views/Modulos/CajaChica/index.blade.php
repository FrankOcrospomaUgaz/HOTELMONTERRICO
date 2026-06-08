@extends('adminlte::page')

@section('title', 'CAJA CHICA')

@section('content_header')
    <br><br>
    <!-- <h1 id="titulo" class="text-center">BUSQUEDA DE HABITACION DETALLE</h1> -->
@stop

@section('content')


    <section class="cajaAlrededor">

        <div class="btnesCaja" style="display: flex; justify-content: flex-end;">
            <button href="javascript:void(0)" onclick="Apertura()" data-tooltip="Aperturar Caja"
                class="btnApertura btn btn-primary btn-sm d-none btn-profesional">Apertura</button>
            <button href="javascript:void(0)" onclick="Ingreso()" data-tooltip="Nuevo Ingreso"
                class="btnIngreso btn btn-success btn-sm d-none btn-profesional">Ingreso</button>
            <button href="javascript:void(0)" onclick="Egreso()" data-tooltip="Nuevo Egreso"
                class="btnEgreso btn btn-danger btn-sm d-none btn-profesional">Egreso</button>
            <button href="javascript:void(0)" onclick="Cierre()" data-tooltip="Cerrar Caja"
                class="btnCierre btn btn-warning btn-sm d-none btn-profesional">Cierre</button>
        </div>
        <br>

        <div class="cajaAlrededor">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoAperturaV" class="form-label labelFormato">M. APERTURA TOTAL:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoAperturaV"
                                value="" id="montoAperturaV" readonly>
                        </div>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoVentasV" class="form-label labelFormato">MONTO TOTAL DE VENTAS:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoVentasV"
                                value="" id="montoVentasV" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="OtrosIngresosV" class="form-label labelFormato">OTROS INGRESOS:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="OtrosIngresosV"
                                value="" id="OtrosIngresosV" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoComprasV" class="form-label labelFormato">MONTO COMPRAS:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoComprasV"
                                value="" id="montoComprasV" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="otrosEgresosV" class="form-label labelFormato">OTROS EGRESOS:

                            </label>

                            <button id="btnDetalleOtrosEgresos" class="btn btn-sm" style="font-size: 15px;">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <input type="number" class="cantidadPago form-control ajuste" name="otrosEgresosV"
                                value="" id="otrosEgresosV" readonly>
                        </div>
                    </div>
                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoEfectivoV" class="form-label labelFormato">EFECTIVO TOTAL EN CAJA:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoEfectivoV"
                                value="" id="montoEfectivoV" readonly>
                        </div>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoTarjetaV" class="form-label labelFormato">MONTO TARJETA:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoTarjetaV"
                                value="" id="montoTarjetaV" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoYapeV" class="form-label labelFormato">MONTO YAPE:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoYapeV"
                                value="" id="montoYapeV" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoPlinV" class="form-label labelFormato">MONTO PLIN:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoPlinV"
                                value="" id="montoPlinV" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoDepositoV" class="form-label labelFormato">MONTO DEPÓSITO:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoDepositoV"
                                value="" id="montoDepositoV" readonly>
                        </div>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoCajaV" class="form-label labelFormato">MONTO TOTAL:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoCajaV"
                                value="" id="montoCajaV" readonly>
                        </div>
                    </div>
                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoVentasEfectivoV" class="form-label labelFormato">MONTO DE VENTAS EN
                                EFECTIVO:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoVentasEfectivoV"
                                value="" id="montoVentasEfectivoV" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group px-4">
                        <div class="mb-3 text-center">
                            <label for="montoAperturaEfecitvo" class="form-label labelFormato">M. APERTURA EN
                                EFECTIVO:</label>
                            <input type="number" class="cantidadPago form-control ajuste" name="montoAperturaEfecitvo"
                                value="" id="montoAperturaEfecitvo" readonly>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br>
  
        <div class="cajaListadoEgresos cajaAlrededor text-center d-none">




            <div class="container">
                <div class="text-left">
                    <h5 for="tbCaja" id="lblTituloEgresos" class="form-label text-center">
                    <b>LISTADO DE OTROS EGRESOS</b>
                </h5>
                </div>
                
                <button id="imprimirEgresos" class="pdf-export-button">
                        PRINT <i class="fa-solid fa-print"></i> 
                    </button>
            </div>
            
            
            
            <br>
            <table id="tbListadoEgresos" class="table table-striped shadow-lg mt-4 table-sm text-center"
                style="width:100%;">
                <thead>
                    <tr class="custom-header-bg">
                        <th scope=" col">#</th>

                        <th scope="col">N° Movimiento</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Concepto</th>
                        <th scope="col">Responsable</th>
                        {{-- <th scope="col">Situacion</th> --}}
                        <th scope="col">Detalle</th>
                        <th scope="col">Monto Pagado</th>

                    </tr>
                </thead>

            </table>

        </div>
        <br>

        <div class="tablaContainer">
            @include('Modulos.CajaChica.Tables.tablaCaja')

        </div>
        @include('Modulos.CajaChica.Modals.modalAperturar')
    
    </section>
  <div>
        @include('Modulos.CajaChica.Modals.modalEgreso')
      </div>

      <div>
        @include('Modulos.CajaChica.Modals.modalEgresoEditar')
      </div>


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



<script src="{{ asset('js/JqueryCajaChica/JqueryIndexCajaChica.JS') }}"></script>
<script src="{{ asset('js/JqueryCajaChica/JqueryModalesCajaChica.JS') }}"></script>
<script src="{{ asset('js/JqueryCajaChica/JqueryCreateCajaChica.JS') }}"></script>
<script src="{{ asset('js/JqueryCajaChica/JqueryDestroyCajaChica.JS') }}"></script>
<script src="{{ asset('js/JqueryCajaChica/JqueryCerrarCaja.JS') }}"></script>
<script src="{{ asset('js/JqueryCajaChica/JqueryModalEditarCaja.JS') }}"></script>

@stop
