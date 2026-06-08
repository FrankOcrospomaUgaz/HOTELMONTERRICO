<!-- Modal CREAR-->

<div class="modal fade" id="modalCambiarHabitacion" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CAMBIAR HABITACION</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroCambiarHabitacion">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="numHabActual" class="form-label labelFormato">ACTUAL HABITACIÓN :</label>
                                    <input type="text" class="form-control ajuste" name="numHabActual" id="numHabActual" readonly>
                                    <div class="error-message"></div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div Class="mb-3 selectCambiarHab">
                                    <label for="numHabNueva" class="form-label labelFormato">NUEVA HABITACIÓN:</label>

                                    <select name="numHabNueva" class="form-control ajuste" id="numHabNueva">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="botonesModal">
                        <a id="cerrarModalCambiarHabitacion" class="btn btn-dark m-2 btnCrear " tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>