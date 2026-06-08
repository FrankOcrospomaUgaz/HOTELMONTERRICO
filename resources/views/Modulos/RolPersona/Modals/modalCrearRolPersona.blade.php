<!-- Modal CREAR-->

<div class="modal fade" id="modalRolPersona" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR ROL DE PERSONA</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroRolPersona">
                    @csrf
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="descripcion" class="form-label labelFormato">DESCRIPCION:</label>
                            <input type="text" class="form-control ajuste" name="descripcion" id="descripcion">
                            <div class="error-messageGrupo"></div>
                        </div>
                    </div>
                    <input type="hidden" id="idRolPersona">
                    <div class="botonesModal">
                        <a id="cerrarModal" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>