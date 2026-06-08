<!-- Modal CREAR-->

<div class="modal fade" id="modalEditCantProducto" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR PRODUCTO</strong></h5>
            </div>
            <div class="modal-body">
            <input type="hidden" name="stockCantProd" id="stockCantProd">
                <form id="registroEditCantProducto">
                    @csrf
                    @method('PUT')

                    <div class="row mx-auto">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div Class="mb-4">
                                    <label for="productos" class="form-label labelFormato">PRODUCTO:</label>
                                    <input type="text" class="form-control ajuste" name="nombreProducto" id="nombreProducto" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="cantidadProductoEd" class="form-label labelFormato">CANTIDAD:</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btnAgregarDisminuir"  id="btnMenos"><i class="fa-solid fa-minus"></i></button>
                                        </span>
                                        <input type="number" class="form-control ajuste text-center w-75" value="1" min="1" name="cantidadProductoEd" id="cantidadProductoEd">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btnAgregarDisminuir"  id="btnMas"><i class="fa-solid fa-plus"></i></button>
                                        </span>
                                    </div>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="mb-4">
                                <label for="comentarioProducto" class="form-label labelFormato">NOTA:</label>
                                <input type="text" class="form-control ajuste" name="notaProductoE" id="notaProductoE">

                            </div>
                        </div>

                        <input type="hidden" name="idProducto" id="idProducto">
                        <div class="botonesModal">
                            <a id="cerrarModalVentaCantProducto" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                            <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>