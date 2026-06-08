<!-- Modal CREAR-->

<div class="modal fade" id="modalUnidad" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR UNIDAD</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroUnidad">
                    @csrf
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="nameUnidad" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="nameUnidad" id="nameUnidad">
                            <div class="error-messageGrupo"></div>
                        </div>
                    </div>
                    <input type="hidden" id="idOpcionCrud">
                    <div class="botonesModal">
                        <a id="cerrarModalUni" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>