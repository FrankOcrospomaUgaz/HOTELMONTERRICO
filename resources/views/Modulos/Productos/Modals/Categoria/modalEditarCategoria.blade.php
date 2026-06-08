<!-- MODAL EDITA -->
<div class="modal fade" id="modalCategoriaE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR CATEGORIA</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroCategoriaE">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="nameECategoria" class="form-label labelFormato">NOMBRE:</label>
                            <input type="text" class="form-control ajuste" name="nameECategoria" id="nameECategoria">
                            <div class="error-messageGrupoE"></div>
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="botonesModalE">
                        <a id="cerrarModalEcat" class="btn btn-dark m-2 btnEditar" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>