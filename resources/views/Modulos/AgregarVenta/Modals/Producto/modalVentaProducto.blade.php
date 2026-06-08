<!-- Modal CREAR-->

<div class="modal fade" id="modalVentaProducto" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">AGREGAR PRODUCTO</strong></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="stock" id="stock">
                <form id="registroVentaProducto">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group ">
                                <div Class="mb-3 ventaSelect">
                                    <label for="productos" class="form-label labelFormato">PRODUCTO:</label>
                                    <select name="productos" class="form-control selectTwo" id="productos">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group text-right mr-1">
                                <label class="form-label labelFormato">AÑADIR:</label>
                                <div class="mb-3 ventaSelect btn">
                                    <i class="fas fa-shopping-cart fa-2x text-danger" id="carritoIcon"></i>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="precioProducto" class="form-label labelFormato">PRECIO:</label>
                                    <input type="number" placeholder="S/" step="0.01" class="form-control ajuste"
                                        name="precioProducto" id="precioProducto" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="cantidadProducto" class="form-label labelFormato">CANTIDAD:</label>
                                <input type="number" class="form-control ajuste" value="1" min="1"
                                    name="cantidadProducto" id="cantidadProducto">
                                <div class="error-message"></div>

                            </div>
                        </div>

                    </div>


                    {{-- <div class="form-group">
                        <div class="mb-4">
                            <label for="comentarioProducto" class="form-label labelFormato">NOTAS:</label>
                            <br>
                            <textarea name="comentarioProducto" id="comentarioProducto" class="form-control" rows="1">-</textarea>

                            <div class="error-message"></div>
                        </div>
                    </div> --}}



                    <div class="Ventas">
                        <div class="formatoTabla">
                            <!-- Tabla de productos seleccionados -->
                            <table id="carrito" class="table tabla-carrito">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody id="carrito-body">
                                    <!-- Aquí se agregarán los productos seleccionados dinámicamente -->
                                </tbody>

                            </table>
                        </div>

                    </div>


                    <div class="botonesModal">
                        <a id="cerrarModalVentaProducto" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>







                </form>
            </div>
        </div>
    </div>
</div>
