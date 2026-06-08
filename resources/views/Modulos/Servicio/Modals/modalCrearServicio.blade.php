<!-- Modal CREAR-->

<div class="modal fade" id="modalServicio" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR SERVICIO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroServicio">
                    @csrf
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="name" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="name" id="name">
                            <div class="error-messageGrupo"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="precioventa" class="form-label labelFormato">PRECIO VENTA:</label>
                            <input type="number" min="0"  step="0.01" class="form-control ajuste" name="precioventa" id="precioventa">
                            <div class="error-messageGrupo"></div>
                        </div>
                    </div>
                    <input type="hidden" id="idOpcionCrud">
                    <div class="botonesModal">
                        <a id="cerrarModal" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>