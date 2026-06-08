<!-- Modal CREAR-->

<div class="modal fade" id="modalNuevoOpcion" tabindex="-1" role="dialog" aria-labelledby="nuevoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR NUEVA OPCION</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroOpcion">
                    @csrf
                    <div Class="mb-4">
                        <label for="categoria" class="form-label labelFormato">CATEGORIA:</label>
                        <select name="categoria" class="form-control ajuste" id="categoria" tabindex="2">
                        </select>
                    </div>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="name" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="name" id="name" tabindex="1">
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="ruta" class="form-label labelFormato">RUTA:</label>
                            <input type="text" class="form-control ajuste" name="ruta" id="ruta" tabindex="1">
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="icono" class="form-label labelFormato">ICONO:</label>
                            <div class="input-group ajuste">
                                <input type="text" class="form-control text-left" name="icono" id="icono" tabindex="1">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i id="iconMuestra" class=""></i></span>
                                </div>
                            </div>
                            <div class="error-message"></div>
                        </div>
                        <div class="botonesModal">
                            <a id="cerrarModal" class="btn btn-dark m-2 anchoOpcion btnCrear" tabindex="3">CANCELAR</a>
                            <button type="submit" class="btn btn-secondary m-2 anchoOpcion btnCrear" tabindex="4">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
