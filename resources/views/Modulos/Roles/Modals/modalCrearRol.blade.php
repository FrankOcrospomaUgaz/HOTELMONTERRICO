<!-- Modal CREAR-->
<div class="modal fade" id="modalNuevoRol" tabindex="-1" role="dialog" aria-labelledby="nuevoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR NUEVO TIPO DE USUARIO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroRol">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="name" class="form-label labelFormato">NOMBRE:</label>
                                    <input type="text" class="form-control ajuste" name="name" id="name">
                                    <div class="error-messageGrupo"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div Class="mb-2">
                                <label class="ajuste labelFormato">ACCESOS:</label><br>
                            </div>
                            <div Class="mt-3">
                                <label class="ml-4" style="float:left; position:center">Marcar Todos: <input type="checkbox" name="select" id="select"></label>
                            </div>
                        </div>
                    </div>


                    <div Class="mb-4">
                        <div class="form-group">
                            <div Class="centerTabla">

                                <table class="table table-bordered table-sm " style="text-align: left;" id="tablaPermisos">
                                    <thead id="opcionesHead" class="sticky-top">
                                        <tr>
                                            <th>VISTA</th>
                                            <th colspan="3">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody id="opciones">
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="botonesModal">
                        <a id="cerrarModal" class="btn btn-dark m-2 ancho btnCrear" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 ancho btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



