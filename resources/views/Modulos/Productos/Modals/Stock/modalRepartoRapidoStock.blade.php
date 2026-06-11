<div class="modal fade" id="modalRepartoRapidoStock" tabindex="-1" role="dialog" aria-labelledby="modalRepartoRapidoStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#198754;color:#fff;">
                <h5 class="modal-title"><strong id="modalRepartoRapidoStockLabel">REPARTO RÁPIDO DE STOCK</strong></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="repartoRapidoProductoId">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <div class="fw-bold">Producto</div>
                            <div id="repartoRapidoProductoNombre">-</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 border rounded bg-light">
                            <div class="fw-bold">General</div>
                            <div id="repartoRapidoGeneral">0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="fw-bold">Habitaciones</div>
                            <div id="repartoRapidoHabitaciones">0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="fw-bold">Total</div>
                            <div id="repartoRapidoTotal">0</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Cantidad por habitacion</label>
                        <input type="number" min="1" step="1" value="1" class="form-control" id="cantidadPorHabitacionRapido">
                    </div>
                    <div class="col-md-8 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-outline-primary" id="btnMarcarTodasHabitaciones">Marcar todas</button>
                        <button type="button" class="btn btn-outline-secondary" id="btnDesmarcarTodasHabitaciones">Desmarcar todas</button>
                    </div>
                </div>

                <div class="row mb-3 d-none" id="repartoRapidoModoContainer">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="repartoRapidoExacto">
                            <label class="form-check-label" for="repartoRapidoExacto">
                                Reparto exacto por habitacion
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1" id="repartoRapidoModoTexto">
                            Marcado: intenta entregar la cantidad completa a cada habitacion en orden. Desmarcado: entrega 1 a todas las seleccionadas y reparte los adicionales al azar entre ellas.
                        </small>
                    </div>
                </div>

                <div class="alert alert-light border mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Habitaciones seleccionadas:</strong> <span id="repartoRapidoSeleccionadas">0</span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <strong>Cantidad por habitacion:</strong> <span id="repartoRapidoCantidadPreview">1</span>
                        </div>
                    </div>
                </div>

                <form id="formRepartoRapidoStock">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Sel.</th>
                                    <th>Habitación</th>
                                    <th>Situación</th>
                                    <th>Stock actual</th>
                                </tr>
                            </thead>
                            <tbody id="tablaRepartoRapidoStock"></tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">Confirmar reparto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

