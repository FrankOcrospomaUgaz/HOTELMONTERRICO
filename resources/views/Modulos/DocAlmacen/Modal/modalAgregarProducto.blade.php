<!-- Modal CREAR-->

<div class="modal fade" id="modalAgregarProdAlmacen" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">AGREGAR PRODUCTO</strong></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="stock" id="stock">
                <form id="registroAddProductoDocAlmacen">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <div Class="mb-4 compraSelect w-75">
                                    <label for="productos" class="form-label labelFormato">PRODUCTO:</label>
                                    <select name="productos" class="form-control selectTwo " id="productos">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="cantidadProductoEd" class="form-label labelFormato">CANTIDAD:</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btnAgregarDisminuir" id="btnMenos"><i class="fa-solid fa-minus"></i></button>
                                        </span>
                                        <input type="number" class="form-control ajuste text-center w-75" value="1" min="1" name="cantidadProductoEd" id="cantidadProductoEd">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btnAgregarDisminuir" id="btnMas"><i class="fa-solid fa-plus"></i></button>
                                        </span>
                                    </div>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group ">
                                <div Class="mb-4 compraSelect w-75">
                                    <label for="motivos" class="form-label labelFormato">Motivos:</label>
                                    <select name="motivos" class="form-control selectTwo " id="motivos">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="comentarioProducto" class="form-label labelFormato">NOTAS:</label>
                                    <br>
                                    <textarea name="comentarioProducto" id="comentarioProducto" class="form-control" rows="2">-</textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <input type="hidden" name="idProducto" id="idProducto">
                    <div class="botonesModal">
                        <a id="cerrarModalAddProducto" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>