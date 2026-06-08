<!-- MODAL EDITA -->
<div class="modal fade" id="modalEditarGrupoMenu" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR GRUPO DE MENU</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroEditarGrupoMenuE">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="nameGrupoOpcionE" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="nameGrupoOpcionE" id="nameGrupoOpcionE">
                            <div class="error-messageGrupoE"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="iconoGrupoOpcionE" class="form-label labelFormato">ICONO:</label>
                            <div class="input-group ajuste">
                                <input type="text" class="form-control text-left" name="iconoGrupoOpcionE" id="iconoGrupoOpcionE">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i id="iconMuestraE" class=""></i></span>
                                </div>
                            </div>
                            <div class="error-messageGrupoE"></div>
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="botonesModalE">
                        <a id="cerrarModalEGrupo" class="btn btn-dark m-2 btnEditar" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>