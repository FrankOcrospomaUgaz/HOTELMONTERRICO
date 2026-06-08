<!-- MODAL EDITA -->
<div class="modal fade" id="modalProductoE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR PRODUCTO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroProductoE">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="nameE" class="form-label labelFormato">*NOMBRE:</label>
                                    <input type="text" class="form-control ajuste" name="nameE" id="nameE" placeholder="Escribe un nombre">
                                    <div class="error-messageGrupoE"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div Class="mb-3">
                                    <label for="categoriasE" class="form-label labelFormato">*CATEGORIA:</label>

                                    <select name="categoriasE" class="form-control ajuste" id="categoriasE">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div Class="mb-3">
                                    <label for="unidadesE" class="form-label labelFormato">*UNIDAD:</label>

                                    <select name="unidadesE" class="form-control ajuste" id="unidadesE">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="codigoE" class="form-label labelFormato">*CODIGO:</label>
                                    <input type="text" class="form-control ajuste" name="codigoE" id="codigoE" placeholder="Escribe su código">
                                    <div class="error-messageGrupoE"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div Class="mb-3">
                                    <label for="precioventaE" class="form-label labelFormato">*PRECIO VENTA:</label>

                                    <input type="number" min="0" step="0.01" class="form-control ajuste" placeholder="00.00" value="00.00" name="precioventaE" id="precioventaE">
                                    <div class="error-messageGrupoE"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div Class="mb-3">
                                    <label for="preciocompraE" class="form-label labelFormato">*PRECIO COMPRA:</label>

                                    <input type="number" min="0" step="0.01" class="form-control ajuste"  placeholder="00.00" value="00.00" name="preciocompraE" id="preciocompraE">
                                    <div class="error-messageGrupoE"></div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="botonesModalE">
                        <a id="cerrarModalEProd" class="btn btn-dark m-2 btnEditar" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>