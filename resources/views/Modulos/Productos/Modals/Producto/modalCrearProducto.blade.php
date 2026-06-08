<!-- Modal CREAR-->

<div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CREAR PRODUCTO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroProducto">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="name" class="form-label labelFormato">*NOMBRE:</label>
                                    <input type="text" class="form-control ajuste" name="name" id="name" placeholder="Escribe un nombre">
                                    <div class="error-messageGrupo"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div Class="mb-3">
                                    <label for="categorias" class="form-label labelFormato">*CATEGORIA:</label>

                                    <select name="categorias" class="form-control ajuste" id="categorias">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div Class="mb-3">
                                    <label for="unidades" class="form-label labelFormato">*UNIDAD:</label>

                                    <select name="unidades" class="form-control ajuste" id="unidades">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="codigo" class="form-label labelFormato">*CODIGO:</label>
                                    <input type="text" class="form-control ajuste" name="codigo" id="codigo" placeholder="Escribe su código">
                                    <div class="error-messageGrupo"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div Class="mb-3">
                                    <label for="precioventa" class="form-label labelFormato">*PRECIO VENTA:</label>

                                    <input type="number" min="0" step="0.01" class="form-control ajuste" placeholder="0.00" value="1.00" name="precioventa" id="precioventa">
                                    <div class="error-messageGrupo"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div Class="mb-3">
                                    <label for="preciocompra" class="form-label labelFormato">*PRECIO COMPRA:</label>

                                    <input type="number" min="0" step="0.01" VALUE="1.00" class="form-control ajuste"  placeholder="0.00" name="preciocompra" id="preciocompra">
                                    <div class="error-messageGrupo"></div>
                                </div>
                            </div>
                     
                        </div>
                    </div>

                    <input type="hidden" id="idOpcionCrud">
                    <div class="botonesModal">
                        <a id="cerrarModalProd" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>