<!-- MODAL EDITA -->
<div class="modal fade" id="modalConceptoPagosE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR CONCEPTO PAGO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroConceptoPagosE">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="nombreConceptoPagoE" class="form-label labelFormato">NOMBRE:</label>
                                    <input type="text" class="form-control ajuste" name="nombreConceptoPagoE" id="nombreConceptoPagoE">
                                    <div class="error-messageGrupoE"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="tipoE" class="form-label labelFormato">NOMBRE:</label>
                                    <select name="tipoE" class="form-control ajuste" id="tipoE">
                                        <option value="Ingreso">Ingreso</option>
                                        <option value="Egreso">Egreso</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                    </div>
                    <input type="hidden" id="idE">
                    <div class="botonesModalE">
                        <a id="cerrarModalE" class="btn btn-dark m-2 btnEditar" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>