<!-- Modal CREAR Egresos-->
<div class="modal fade" id="modalEgresos" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="nuevoModalLabelEgr">NUEVO INGRESO</h5>
            </div>
            <div class="modal-body ">
                <form id="registroEgresos">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="tipoMovimientoCajaEgr" class="form-label labelFormato">TIPO:</label>
                                    <input type="text" class="form-control ajuste" name="tipoMovimientoCajaEgr" value="Ingreso" id="tipoMovimientoCajaEgr" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="mb-3 cajaSelect">
                                    <label for="personasEgr" class="form-label labelFormato">PERSONA:</label>
                             <br>
                                    <select name="personasEgr" class="form-control selectTwo" id="personasEgr">
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4" style="max-width: 100%;">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="fechaEgr" class="form-label labelFormato">FECHA:</label>
                                    <input type="date" class="form-control ajuste" name="fechaEgr" id="fechaEgr" value="" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group form-floating">
                                <div class="mb-3">
                                    <label for="totalEgr" id="lblTotal" class="form-label labelFormato">TOTAL:</label>
                                    <input type="number" min="1" value="0" step="0.01" class="form-control ajuste cantidadPago" name="totalEgr" id="totalEgr" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="conceptoPagoEgr" class="form-label labelFormato">CONCEPTO:</label>
                                    <select name="conceptoPagoEgr" class="form-control ajuste" id="conceptoPagoEgr">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">

                                    <label for="responsableEgr" class="form-label labelFormato">RESPONSABLE:</label>

                                    <input type="text" class=" form-control ajuste" name="responsableEgr" id="responsableEgr" readonly>

                                </div>
                            </div>
                        </div>
                     
                        <section class="cajaAlrededor mt-2">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="montoEgr" class="form-label labelFormato">MONTO:</label>
                                            <input type="number" value="100" step="0.01" class="cantidadPago form-control w-75 text-center mx-auto" name="montoEgr" id="montoEgr">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="notaEgr" class="form-label labelFormato">NOTA:</label>
                                            <input type="text" placeholder="Escribe Aquí..." class="form-control text-center mx-auto" name="notaEgr" id="notaEgr">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="mediosPagoEgr" class="form-label labelFormato">MEDIO PAGO:</label>
                                            <select name="mediosPagoEgr" class="form-control text-center" id="mediosPagoEgr">
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
                                            <label for=".añadirMedioPago" class="form-label labelFormato ">AGREGAR:</label>
                                            <br>

                                            <a class="Btn añadirMedioPagoEgr">

                                                <div class="sign">+</div>

                                                <div class="text">Agregar</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div>
                                @include('Modulos.CajaChica.Tables.tablaMediosPagoEgr')
                            </div>
                        </section>

                        <div class="botonesModal">
                            <a id="cerrarModal" class="btn btn-dark m-2 btnCrear" tabindex="3">CANCELAR</a>
                            <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>