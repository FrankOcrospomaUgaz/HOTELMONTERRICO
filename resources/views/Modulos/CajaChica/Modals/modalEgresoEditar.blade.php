<!-- Modal CREAR Egresos-->
<div class="modal fade" id="modalEgresosE" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="nuevoModalLabelEgrE">EDITAR MOVIMIENTO</h5>
            </div>
            <div class="modal-body ">
                <form id="registroEgresos">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="tipoMovimientoCajaEgrE" class="form-label labelFormato">TIPO:</label>
                                    <input type="text" class="form-control ajuste" name="tipoMovimientoCajaEgrE"
                                        value="Ingreso" id="tipoMovimientoCajaEgrE" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="mb-3 cajaSelect">
                                    <label for="personasEgrE" class="form-label labelFormato">PERSONA:</label>
                                    <br>
                                    <input type="text" name="personasEgrE" class="form-control" id="personasEgrE" readonly>
                                    
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4" style="max-width: 100%;">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="fechaEgrE" class="form-label labelFormato">FECHA:</label>
                                    <input type="date" class="form-control ajuste" name="fechaEgrE" id="fechaEgrE"
                                        value="" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group form-floating">
                                <div class="mb-3">
                                    <label for="totalEgrE" id="lblTotal" class="form-label labelFormato">TOTAL:</label>
                                    <input type="number" min="1" value="0" step="0.01"
                                        class="form-control ajuste cantidadPago" name="totalEgrE" id="totalEgrE"
                                        readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="conceptoPagoEgrE" class="form-label labelFormato">CONCEPTO:</label>
                                    <input name="conceptoPagoEgrE" class="form-control ajuste" id="conceptoPagoEgrE" readonly>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">

                                    <label for="responsableEgrE" class="form-label labelFormato">RESPONSABLE:</label>

                                    <input type="text" class=" form-control ajuste" name="responsableEgrE"
                                        id="responsableEgrE" readonly>

                                </div>
                            </div>
                        </div>

                        <section class="cajaAlrededor mt-2">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="montoEgrE" class="form-label labelFormato">MONTO:</label>
                                            <input type="number" value="100" step="0.01"
                                                class="cantidadPago form-control w-75text-center mx-auto"
                                                name="montoEgrE" id="montoEgrE">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="notaEgrE" class="form-label labelFormato">NOTA:</label>
                                            <input type="text" placeholder="Escribe Aquí..."
                                                class="form-control text-center mx-auto" name="notaEgrE" id="notaEgrE">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="mediosPagoEgrE" class="form-label labelFormato">MEDIO
                                                PAGO:</label>
                                            <select name="mediosPagoEgrE" class="form-control text-center"
                                                id="mediosPagoEgrE">
                                                <option value="Efectivo">Efectivo</option>
                                                <option value="Tarjeta">Tarjeta</option>
                                                <option value="Yape">Yape</option>
                                                <option value="Deposito">Deposito</option>
                                                <option value="Plin">Plin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="mb-3 mx-auto text-center">
                                            <label for=".añadirMedioPago"
                                                class="form-label labelFormato ">AGREGAR:</label>
                                            <br>

                                            <a class="Btn añadirMedioPagoEgrE">

                                                <div class="sign">+</div>

                                                <div class="text">Agregar</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div>
                                @include('Modulos.CajaChica.Tables.tablaMediosPagoEgrE')
                            </div>
                        </section>

               
                        <input type="hidden" name="idMovimiento" id="idMovimientoEgrE">
                </form>
            </div>
        </div>
    </div>
</div>
