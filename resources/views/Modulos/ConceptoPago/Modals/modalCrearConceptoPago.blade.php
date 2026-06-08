<!-- Modal CREAR-->

<div class="modal fade" id="modalConceptoPagos" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR CONCEPTO DE PAGO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroConceptoPagos">
                    @csrf
                    <div class="form-group">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="nombreConceptoPago" class="form-label labelFormato">NOMBRE:</label>
                                    <input type="text" class="form-control ajuste" name="nombreConceptoPago" id="nombreConceptoPago">
                                    <div class="error-messageGrupo"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="tipo" class="form-label labelFormato">NOMBRE:</label>
                                    <select name="tipo" class="form-control ajuste" id="tipo">
                                        <option value="Ingreso">Ingreso</option>
                                        <option value="Egreso">Egreso</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="idConceptoPago">
                    <div class="botonesModal">
                        <a id="cerrarModal" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>