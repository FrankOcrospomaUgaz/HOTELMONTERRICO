<!-- Modal CREAR APERTURA-->
<div class="modal fade" id="modalEditarMovCaja" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0);; color:white;">
                <h5 class="modal-title"><strong id="nuevoModalLabel">EDITAR MOVIMIENTO</h5>
            </div>
            <div class="modal-body modales">
                <form id="registroAperturaE">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="tipoMovimientoCajaE" class="form-label labelFormato">TIPO:</label>
                                    <input type="text" class="form-control ajuste text-center" name="tipoMovimientoCajaE" value="Ingreso" id="tipoMovimientoCajaE" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="personaE" class="form-label labelFormato">PERSONA:</label>
                                    <input type="text" class="form-control ajuste text-center" name="personaE" id="personaE" readonly>

                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="fechaE" class="form-label labelFormato">FECHA:</label>
                                    <input type="text" class="form-control ajuste text-center" name="fechaE" id="fechaE" value="" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group form-floating">
                                <div class="mb-3">
                                    <label for="totalE" id="lblTotalE" class="form-label labelFormato">TOTAL:</label>
                                    <input type="number" min="1" value="0" step="0.01" class="form-control  ajuste cantidadPago" name="totalE" id="totalE" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="conceptoPagoE" class="form-label labelFormato">CONCEPTO:</label>
                                    <input type="text" class="form-control ajuste text-center" name="conceptoPagoE" id="conceptoPagoE" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">

                                    <label for="responsableE" class="form-label labelFormato">RESPONSABLE:</label>

                                    <input type="text" class=" form-control ajuste text-center" name="responsableE" id="responsableE" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="comentarioE" class="form-label labelFormato">NOTAS:</label>
                                    <input type="text" class="form-control ajuste" name="comentarioE" value="" placeholder="Escribe una nota" id="comentarioE">
                                </div>
                            </div>
                        </div>
                        <section class="cajaAlrededor mt-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="montoE" class="form-label labelFormato">MONTO:</label>
                                            <input type="number" value="100" step="0.01" class="cantidadPago  form-control w-50 text-center mx-auto" name="montoE" id="montoE">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="mediosPagoE" class="form-label labelFormato">MEDIO PAGO:</label>
                                            <select name="mediosPagoE" class="form-control text-center" id="mediosPagoE">
                                                <option value="Efectivo">Efectivo</option>
                                                <option value="Tarjeta">Tarjeta</option>
                                                <option value="Yape">Yape</option>
                                                <option value="Deposito">Deposito</option>
                                                <option value="Plin">Plin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="mb-3 mx-auto text-center">
                                            <label for=".añadirMedioPagoE" class="form-label labelFormato ">AGREGAR:</label>
                                            <br>

                                            <a class="Btn añadirMedioPagoE">

                                                <div class="sign">+</div>

                                                <div class="text">Agregar</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div>
                                @include('Modulos.CajaChica.Tables.tablaMediosPagoE')
                            </div>
                        </section>

                        <div class="botonesModal">
                            <a id="cerrarModal" class="btn btn-dark m-2 btnCrear" tabindex="3">CANCELAR</a>
                            <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                        </div>

                </form>
                <input type="hidden" name="idMovimiento" id="idMovimiento">
            </div>
        </div>
    </div>
</div>