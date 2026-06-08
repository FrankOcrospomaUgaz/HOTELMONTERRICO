<!-- Modal CREAR-->

<div class="modal fade" id="modalCrearGrupoMenu" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR GRUPO DE MENU</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroEditarGrupoMenu">
                    @csrf
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="nameGrupoOpcion" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="nameGrupoOpcion" id="nameGrupoOpcion">
                            <div class="error-messageGrupo"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="iconoGrupoOpcion" class="form-label labelFormato">ICONO:</label>
                            <div class="input-group ajuste">
                                <input type="text" class="form-control text-left" name="iconoGrupoOpcion" id="iconoGrupoOpcion">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i id="iconMuestraCRUD_E" class=""></i></span>
                                </div>
                            </div>
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