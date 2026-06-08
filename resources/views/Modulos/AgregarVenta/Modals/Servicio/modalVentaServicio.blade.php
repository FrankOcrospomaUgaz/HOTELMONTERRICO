<!-- Modal CREAR-->

<div class="modal fade" id="modalVentaServicio" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">AGREGAR SERVICIO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroVentaServicio">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                    <div class="form-group">
                        <div Class="mb-3  ventaSelect">
                            <label for="servicios" class="form-label labelFormato">SERVICIO:</label>
                            <select name="servicios" class="form-control  selectTwo" id="servicios">
                            </select>
                        </div>
                    </div></div>
                </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="precioServicio" class="form-label labelFormato">PRECIO:</label>
                                    <input type="number" placeholder="S/" step="0.01" class="form-control ajuste" name="precioServicio" id="precioServicio">
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="cantidadServicio" class="form-label labelFormato">CANTIDAD:</label>
                                    <input type="number" class="form-control ajuste" min="1" value="1" name="cantidadServicio" id="cantidadServicio">
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="form-group">
                        <div class="mb-4">
                            <label for="comentarioServicio" class="form-label labelFormato">COMENTARIO:</label>
                            <br>
                            <textarea name="comentarioServicio" id="comentarioServicio"  class="form-control" rows="2">-</textarea>

                            <div class="error-message"></div>
                        </div>
                    </div>

                    <div class="botonesModal">
                        <a id="cerrarModalVentaServicio" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>