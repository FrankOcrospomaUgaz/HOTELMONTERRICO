<!-- Modal CREAR-->

<div class="modal fade" id="modalVentaProducto" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 1040px;">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%); color:#fff; padding: .75rem 1.25rem;">
                <h5 class="modal-title w-100 text-center">
                    <strong id="nuevoModalLabel" style="letter-spacing:1px;">AGREGAR PRODUCTO</strong>
                </h5>
            </div>

            <div class="modal-body" style="padding: 1rem 1.1rem 1.1rem;">
                <input type="hidden" name="stock" id="stock">
                <input type="hidden" name="stockHabitacionProducto" id="stockHabitacionProducto">

                <div class="p-2 mb-3" style="border:1px solid #e6e9ef; border-radius:16px; background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg-4 col-md-5">
                            <label for="cantidadReponerGeneral" class="form-label fw-semibold mb-1" style="color:#495057; font-size: 14px;">Reposicion desde general</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white" style="border-right:0;">
                                    <i class="fa-solid fa-boxes-stacked text-success"></i>
                                </span>
                                <input type="number" id="cantidadReponerGeneral" class="form-control text-center form-control-sm" value="1" min="1" step="1" style="font-weight:600; max-width: 95px;">
                                <button type="button" id="btnReponerDesdeGeneral" class="btn btn-success btn-sm" style="min-width: 165px; font-weight:700; border-radius: 0 10px 10px 0;">
                                    <i class="fa-solid fa-truck-ramp-box me-1"></i> Reponer
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 12px;">Mueve stock desde general a la habitacion actual.</small>
                        </div>

                        <div class="col-lg-8 col-md-7">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <div class="p-2 h-100" style="border:1px solid #e6e9ef; border-radius:14px; background:#fff;">
                                        <div class="text-uppercase small text-muted fw-semibold" style="font-size: 12px;">Stock en habitacion</div>
                                        <div class="fs-5 fw-bold text-primary" id="stockHabitacionProductoDisplay">0</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="p-2 h-100" style="border:1px solid #e6e9ef; border-radius:14px; background:#fff;">
                                        <div class="text-uppercase small text-muted fw-semibold" style="font-size: 12px;">Stock general</div>
                                        <div class="fs-5 fw-bold text-success" id="stockGeneralProductoDisplay">0</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="p-2 h-100" style="border:1px solid #e6e9ef; border-radius:14px; background:#fff;">
                                        <div class="text-uppercase small text-muted fw-semibold" style="font-size: 12px;">Precio</div>
                                        <div class="fs-5 fw-bold text-dark" id="precioProductoDisplay">0.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="registroVentaProducto">
                    @csrf

                    <div class="row g-2 align-items-end">
                        <div class="col-lg-6 col-md-5">
                            <div class="form-group mb-0">
                                <label for="productos" class="form-label labelFormato mb-1" style="font-size: 13px;">PRODUCTO:</label>
                                <select name="productos" class="form-control selectTwo form-control-sm" id="productos"></select>
                                <div class="error-message"></div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2">
                            <div class="form-group mb-0">
                                <label for="cantidadProducto" class="form-label labelFormato mb-1" style="font-size: 13px;">CANTIDAD:</label>
                                <input type="number" class="form-control ajuste form-control-sm text-center" value="1" min="1"
                                    name="cantidadProducto" id="cantidadProducto">
                                <div class="error-message"></div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2">
                            <div class="form-group mb-0">
                                <label class="form-label labelFormato mb-1" style="font-size: 13px;">AÑADIR:</label>
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-2" id="carritoIcon" style="border-radius:12px; min-height: 38px;">
                                    <i class="fas fa-shopping-cart"></i>
                                    Agregar
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <div class="px-2 py-2 rounded-3 h-100 d-flex flex-column justify-content-center" style="background:#f8f9fa;border:1px solid #e6e9ef;">
                                <div id="stockHabitacionTexto" class="fw-semibold text-secondary" style="font-size: 13px; line-height:1.2;">Stock disponible en la habitacion: 0</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-2">
                                    <label for="precioProducto" class="form-label labelFormato" style="font-size: 13px;">PRECIO:</label>
                                    <input type="number" placeholder="S/" step="0.01" class="form-control ajuste form-control-sm"
                                        name="precioProducto" id="precioProducto" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3" style="border:1px solid #e6e9ef; border-radius:16px; overflow:hidden; background:#fff;">
                        <div class="px-3 py-2" style="background:#f4f9f7; border-bottom:1px solid #e6e9ef;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="fw-bold text-uppercase" style="letter-spacing:1px; font-size: 14px;">Carrito de productos</div>
                                <small class="text-muted" style="font-size: 12px;">Revisa y ajusta antes de guardar</small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="carrito" class="table tabla-carrito table-sm mb-0">
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
                                    <!-- Aqui se agregaran los productos seleccionados dinamicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a id="cerrarModalVentaProducto" class="btn btn-outline-dark btn-sm px-4" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-primary btn-sm px-4" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
