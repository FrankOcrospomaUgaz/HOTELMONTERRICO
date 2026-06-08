<!-- Modal CREAR-->

<div class="modal fade" id="modalCambiarSituacion" tabindex="-1" role="dialog" aria-labelledby="nuevoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "><strong id="nuevoModalLabel">CAMBIAR SITUACION</strong></h5>
            </div>
            <div class="modal-body">

                <form id="registroCambiarSituacion">
                    @method('PUT')
                    @csrf
                    <div class="mb-4 mt-1">
                        <div class="form-group">
                            <label for="numero" class="form-label labelFormato">NUMERO HABITACION:</label>
                            <input type="text" class="form-control ajuste" name="numero" id="numero" tabindex="1" readonly>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div Class="mb-4 mt-1">
                        <label for="situacionCambio" class="form-label labelFormato">SITUACION:</label>
                        <select name="situacionCambio" class="form-control ajuste mt-2" id="situacionCambio">
                            <option value="Disponible">Disponible</option>
                            <option value="Mantenimiento">Mantemimiento</option>
                            <option value="Limpieza">Limpieza</option>
                        </select>
                    </div>
                    <input type="hidden" name="idHabitacion" id="idHabitacion">
                    <div class="botonesModal mt-2">
                        <a id="cerrarModal" class="btn btn-dark m-2 btnCrear" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>