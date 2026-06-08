<!-- Modal EDITAR-->
<div class="modal fade" id="modalEditarRolE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0);; color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR TIPO DE USUARIO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroRolE">
                    @method('PUT')
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="nameE" class="form-label labelFormato">NOMBRE:</label>
                                    <input type="text" class="form-control ajuste" name="nameE" id="nameE" tabindex="1">
                                    <div class="error-messageGrupoE"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div Class="mb-2">
                                <label class="ajuste labelFormato">ACCESOS:</label><br>
                            </div>
                            <div Class="mt-3">
                                <label class="ml-4" style="float:left; position:center">Marcar Todos: <input type="checkbox" name="selectE" id="selectE"></label>
                            </div>
                        </div>
                    </div>

                    <div Class="mb-4">

                        <div Class="centerTabla ">
                            <table class="table table-bordered table-sm " style="text-align: left;" id="tablaPermisosE">
                                <thead id="opcionesHeadE" class="sticky-top">
                                    <tr>
                                        <th>VISTA</th>
                                        <th colspan="3">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="opcionesE">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="botonesModalE">
                        <a id="cerrarModalE" class="btn btn-dark m-2 btnEditar " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>