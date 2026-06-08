<!-- Modal CREAR Cierre-->
<div class="modal fade" id="modalCierre" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="nuevoModalLabel">CIERRE DE CAJA</h5>
            </div>
            <div class="modal-body modales">
                <form id="registroCierre">
                    @csrf
                    <input type="hidden" name="idMovApertura" id="idMovApertura">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="tipoMovimientoCajaCierr" class="form-label labelFormato">TIPO:</label>
                                    <input type="text" class="form-control ajuste text-center" name="tipoMovimientoCajaCierr" value="" id="tipoMovimientoCajaCierr" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="personasCierr" class="form-label labelFormato">PERSONA:</label>
                                    </br>
                                    <select name="personasCierr" class="form-control ajuste selectTwo" id="personasCierr">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="fechaCier" class="form-label labelFormato">FECHA:</label>
                                    <input type="date" class="form-control ajuste text-center" name="fechaCier" id="fechaCier" value="" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group form-floating">
                                <div class="mb-3">
                                    <label for="totalCierr" id="lablTotal" class="form-label labelFormato">MONTO DE APERTURA:</label>
                                    <input type="number" min="1" value="0" step="0.01" class="form-control ajuste cantidadPago" name="totalCierr" id="totalCierr" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="conceptoPagoCierr" class="form-label labelFormato">CONCEPTO:</label>
                                    <select name="conceptoPagoCierr" class="form-control ajuste text-center" id="conceptoPagoCierr">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">

                                    <label for="responsableCierr" class="form-label labelFormato">RESPONSABLE:</label>

                                    <input type="text" class=" form-control ajuste text-center" name="responsableCierr" id="responsableCierr" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="comentario" class="form-label labelFormato">NOTAS:</label>
                                        <input type="text" class="form-control ajuste" name="comentario" value="" placeholder="Escribe una nota" id="comentario">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 mx-auto text-center mt-3">
                                <div class="form-group">
                                    <a href="#"  data-tooltip="Generar PDF" class="btnCierre btnPdf btn btn-danger btn-sm d-none btn-profesional"><i class="fa-solid fa-file-pdf" style="color: #fff; font-size:27px;"></i></a>
                                </div>
                            </div>
                            <div class="col-md-2 mx-auto text-center mt-3">
                                <div class="form-group">
                                    <a href="#"  data-tooltip="Generar Ticket" class="btnCierre btnTicket btn btn-success btn-sm d-none btn-profesional"><i class="fa-solid fa-receipt" style="color: #fff; font-size:27px;"></i></a>
                                </div>
                            </div>
                            <div class="col-md-2 mx-auto text-center mt-3">
                                <div class="form-group">
                                    <a href="#"  data-tooltip="Detalle Cuadre Caja" class="btnCierre btnCuadreCaja btn btn-primary btn-sm d-none btn-profesional"><i class="fa-solid fa-book-open" style="color: #fff; font-size:27px;"></i></a>
                                </div>
                            </div>
                        </div>


                        <section class="cajaAlrededor mt-2 ">
                            <label for="responsableCierr" class="form-label labelFormato">DETALLES DEL CIERRE:</label>
                            <div class="mb-3 mt-3">

                                @include('Modulos.CajaChica.Tables.tablaDetalle')

                            </div>



                        </section>
                        <div class="cajaAlrededor">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="montoVentas" class="form-label labelFormato">MONTO VENTAS:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="montoVentas" value="" id="montoVentas" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="OtrosIngresos" class="form-label labelFormato">OTROS INGRESOS:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="OtrosIngresos" value="" id="OtrosIngresos" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="montoCompras" class="form-label labelFormato">MONTO COMPRAS:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="montoCompras" value="" id="montoCompras" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="otrosEgresos" class="form-label labelFormato">OTROS EGRESOS:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="otrosEgresos" value="" id="otrosEgresos" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="montoCaja" class="form-label labelFormato">MONTO TOTAL:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="montoCaja" value="" id="montoCaja" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </br>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="montoEfectivo" class="form-label labelFormato">MONTO EFECTIVO:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="montoEfectivo" value="" id="montoEfectivo" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="montoTarjeta" class="form-label labelFormato">MONTO TARJETA:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="montoTarjeta" value="" id="montoTarjeta" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="montoYape" class="form-label labelFormato">MONTO YAPE:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="montoYape" value="" id="montoYape" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="montoPlin" class="form-label labelFormato">MONTO PLIN:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="montoPlin" value="" id="montoPlin" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group px-4">
                                        <div class="mb-3">
                                            <label for="montoDeposito" class="form-label labelFormato">MONTO DEPÓSITO:</label>
                                            <input type="number" class="cantidadPago form-control ajuste" name="montoDeposito" value="" id="montoDeposito" readonly>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>
                        <div class="botonesModal">
                            <a id="cerrarModalC" class="btn btn-dark m-2 btnCrear" tabindex="3">CANCELAR</a>
                            <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">CERRAR</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>