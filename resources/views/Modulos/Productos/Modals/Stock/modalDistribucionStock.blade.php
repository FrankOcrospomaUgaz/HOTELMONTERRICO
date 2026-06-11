<div class="modal fade" id="modalDistribucionStock" tabindex="-1" role="dialog" aria-labelledby="modalDistribucionStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#0b5ed7;color:#fff;">
                <h5 class="modal-title"><strong id="modalDistribucionStockLabel">DISTRIBUCIÓN DE STOCK</strong></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="productoDistribucionId">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <div class="fw-bold">Producto</div>
                            <div id="productoDistribucionNombre">-</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 border rounded bg-light">
                            <div class="fw-bold">General</div>
                            <div id="productoDistribucionGeneral">0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="fw-bold">Habitaciones</div>
                            <div id="productoDistribucionHabitaciones">0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="fw-bold">Total</div>
                            <div id="productoDistribucionTotal">0</div>
                        </div>
                    </div>
                </div>

                <form id="formTransferirStock" class="mb-3">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Habitación destino</label>
                            <select id="habitacionDestinoStock" class="form-control"></select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" min="1" step="1" value="1" class="form-control" id="cantidadTransferirStock">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100">Mover desde general</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Habitación</th>
                                <th>Situación</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Acción rápida</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDistribucionStock">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
