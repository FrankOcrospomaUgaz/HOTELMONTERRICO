<!-- Modal CREAR APERTURA-->
<div class="modal fade" id="modalCarrito" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="nuevoModalLabel">DETALLE DE VENTA</h5>
            </div>
            <div class="modal-body modales">
                <form id="registroApertura">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="tipoDoc" class="form-label labelFormato">TIPO:</label>
                                    <input type="text" class="form-control ajuste text-center" name="tipoDoc" value="Ingreso" id="tipoDoc" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="personasC" class="form-label labelFormato">PERSONA:</label>
                                    <input type="text" class=" form-control ajuste text-center" name="personasC" id="personasC" readonly>


                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="fechaIngreso" class="form-label labelFormato">FECHA INGRESO:</label>
                                    <input type="text" class="form-control ajuste text-center" name="fechaIngreso" id="fechaIngreso" value="" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                          
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="numMovCajaTipoDoc" class="form-label labelFormato">N° Venta:</label>
                                        <input type="text" class="form-control ajuste text-center" name="numMovCajaTipoDoc" value="" id="numMovCajaTipoDoc" readonly>
                                    </div>
                                </div>
                         
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="fechaSalida" class="form-label labelFormato ">FECHA SALIDA:</label>
                                    <input type="text" class="form-control ajuste text-center" name="fechaSalida" id="fechaSalida" value="" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">

                                    <label for="responsableC" class="form-label labelFormato ">RESPONSABLE:</label>

                                    <input type="text" class=" form-control ajuste text-center" name="responsableC" id="responsableC" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="mb-4">
                                        <label for="comentarioC" class="form-label labelFormato">NOTAS:</label>
                                        <input type="text" class="form-control ajuste" name="comentarioC" value="" placeholder="Escribe una nota" id="comentarioC">
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="numHabC" class="form-label labelFormato">N° HABITACIÓN:</label>
                                        <input type="text" class="form-control ajuste text-center" name="numHabC" value="" id="numHabC" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mx-auto text-center mt-3">
                                <div class="form-group">
                                    <a href="#" data-tooltip="Imprimir Documento" class="btnCierre btnPdf btn btn-primary btn-sm d-none btn-profesional"><i class="fa-solid fa-file-invoice-dollar" style="color: #fff; font-size:27px;"></i></a>
                                </div>
                            </div>
                        </div>
                        <section class=" mt-2 mb-2 ">
                            <div class="row">
                               
                                    @include('Modulos.CajaChica.Tables.tablaDetalleVenta')
                                
                            </div>

                        </section>
<br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-floating">
                                    <div class="mb-3">
                                        <label for="totalPagar" id="lbltotalPagar" class="form-label labelFormato ">TOTAL APAGAR:</label>
                                        <input type="number" min="1" value="0" step="0.01" class="form-control ajuste cantidadPago" name="totalPagar" id="totalPagar" readonly>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                <div class="form-group form-floating">
                                    <div class="mb-3">
                                        <label for="totalPagado" id="lbltotalPagado" class="form-label labelFormato ">EFECTIVO:</label>
                                        <input type="number" min="1" value="0" step="0.01" class="form-control ajuste cantidadPago" name="totalPagado" id="totalPagado" readonly>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-floating">
                                    <div class="mb-3">
                                        <label for="vueltoPago" id="lblvueltoPago" class="form-label labelFormato ">VUELTO:</label>
                                        <input type="number" min="1" value="0" step="0.01" class="form-control ajuste cantidadPago" name="vueltoPago" id="vueltoPago" readonly>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="botonesModal">
                            <a id="cerrarModalCarrito" class="btn btn-dark m-2 btnCrear" tabindex="3">CANCELAR</a>
                        </div>

                </form>
                <input type="hidden" name="idMovCierre" id="idMovCierre">
            </div>
        </div>
    </div>
</div>