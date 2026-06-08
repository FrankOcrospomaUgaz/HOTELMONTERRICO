<!-- MODAL EDITA -->
<div class="modal fade" id="modalServicioE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR SERVICIO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroServicioE">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="nameE" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="nameE" id="nameE">
                            <div class="error-messageGrupoE"></div>
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="precioVentaE" class="form-label labelFormato">PRECIO VENTA:</label>
                            <input type="number" placeholder="S/" step="0.01" min="0" class="form-control ajuste" name="precioVentaE" id="precioVentaE">
                            <div class="error-messageGrupo"></div>
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