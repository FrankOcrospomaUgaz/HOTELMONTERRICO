
<!-- Modal EDITAR OPCION-->

<div class="modal fade" id="modalEditarOpcionE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR OPCION</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroOpcionE">
                    @method('PUT')
                    @csrf
                    <div Class="mb-4">
                        <label for="categoriaE" class="form-label labelFormato">CATEGORIA:</label>
                        <select name="categoriaE" class="form-control ajuste" id="categoriaE" required tabindex="2">
                        </select>
                    </div>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="nameE" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="nameE" id="nameE" tabindex="1">
                            <div class="error-messageE"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="rutaE" class="form-label labelFormato">RUTA:</label>
                            <input type="text" class="form-control ajuste" name="rutaE" id="rutaE" tabindex="1">
                            <div class="error-messageE"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="iconoE" class="form-label labelFormato">ICONO:</label>
                            <div class="input-group ajuste">
                                <input type="text" class="form-control text-left" name="iconoE" id="iconoE" tabindex="1">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i id="iconMuestraE" class=""></i></span>
                                </div>
                            </div>
                            <div class="error-messageE"></div>
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="botonesModalE">
                        <a id="cerrarModalE" class="btn btn-dark m-2 btnEditar " >CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar" >GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>