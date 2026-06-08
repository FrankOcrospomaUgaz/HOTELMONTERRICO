<!-- Modal CREAR APERTURA-->
<div class="modal fade" id="modalApertura" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="nuevoModalLabel">APERTURAR CAJA</h5>
            </div>
            <div class="modal-body ">
                <form id="registroApertura">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="tipoMovimientoCaja" class="form-label labelFormato">TIPO:</label>
                                    <input type="text" class="form-control ajuste" name="tipoMovimientoCaja" value="Ingreso" id="tipoMovimientoCaja" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="mb-3 cajaSelect">
                                    <label for="tipoMovimientoCaja" class="form-label labelFormato">PERSONA:</label>
                                    </br>
                                    <select name="personas" class="form-control selectTwo" id="personas">
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4" style="max-width: 100%;">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="fecha" class="form-label labelFormato">FECHA:</label>
                                    <input type="date" class="form-control ajuste" name="fecha" id="fecha" value="" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group form-floating">
                                <div class="mb-3">
                                    <label for="total" id="lblTotal" class="form-label labelFormato">TOTAL:</label>
                                    <input type="number" min="1" value="0" step="0.01" class="form-control ajuste cantidadPago" name="total" id="total" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="conceptoPago" class="form-label labelFormato">CONCEPTO:</label>
                                    <select name="conceptoPago" class="form-control ajuste" id="conceptoPago">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">

                                    <label for="responsable" class="form-label labelFormato">RESPONSABLE:</label>

                                    <input type="text" class=" form-control ajuste" name="responsable" id="responsable" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="comentario" class="form-label labelFormato">NOTAS:</label>
                                    <input type="text" class="form-control ajuste" name="comentario" value="" placeholder="Escribe una nota" id="comentario">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 turnoDiv d-none">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="turno" class="form-label labelFormato">TURNO:</label>
                                    <select name="turno" class="form-control ajuste " id="turno">
                                        <option value="MAÑANA">MAÑANA</option>
                                        <option value="TARDE">TARDE</option>
                                        <option value="NOCHE">NOCHE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <section class="cajaAlrededor mt-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="monto" class="form-label labelFormato">MONTO:</label>
                                            <input type="number" value="100" step="0.01" class="cantidadPago form-control w-50 text-center mx-auto" name="monto" id="monto">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label for="mediosPago" class="form-label labelFormato">MEDIO PAGO:</label>
                                            <select name="mediosPago" class="form-control text-center" id="mediosPago">
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
                                            <label for=".añadirMedioPago" class="form-label labelFormato ">AGREGAR:</label>
                                            <br>

                                            <a class="Btn añadirMedioPago">

                                                <div class="sign">+</div>

                                                <div class="text">Agregar</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div>
                                @include('Modulos.CajaChica.Tables.tablaMediosPago')
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