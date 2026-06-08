<!-- Modal EDITAR OPCION-CRUD-->

<div class="modal fade" id="modalEditarCRUD" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR OPCION CRUD</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroEditarCRUD">
                    @method('PUT')
                    @csrf
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="nameCRUD_E" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="nameCRUD_E" id="nameCRUD_E" tabindex="1">

                            <div class="error-messageCRUD"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="rutaCRUD_E" class="form-label labelFormato">RUTA:</label>
                            <input type="text" class="form-control ajuste" name="rutaCRUD_E" id="rutaCRUD_E" tabindex="1">

                            <div class="error-messageCRUD"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="iconoCRUD_E" class="form-label labelFormato">ICONO:</label>
                            <div class="input-group ajuste">
                                <input type="text" class="form-control text-left" name="iconoCRUD_E" id="iconoCRUD_E" tabindex="1">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i id="iconMuestraCRUD_E" class=""></i></span>
                                </div>
                            </div>
                            <div class="error-messageCRUD"></div>
                        </div>
                    </div>
                    <input type="hidden" id="idOpcionCrud">
                    <div class="botonesModalE">
                        <a id="cerrarModalCRUD_E" class="btn btn-dark m-2 btnEditar " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
