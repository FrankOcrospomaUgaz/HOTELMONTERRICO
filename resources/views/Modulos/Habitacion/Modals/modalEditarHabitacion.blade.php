<!-- MODAL EDITA -->
<div class="modal fade" id="modalHabitacionE" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title "><strong id="nuevoModalLabel">EDITAR HABITACION</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroHabitacionE">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <div Class="mb-4">
                            <label for="tipoE" class="form-label labelFormato">TIPO:</label>
                            <select name="tipoE" class="form-control ajuste" id="tipoE">
                                <option value="Normal">Normal</option>
                                <option value="VIP">VIP</option>
                                <option value="Estandar">Estándar</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="nameE" class="form-label labelFormato">NÚMERO:</label>
                            <input type="text" class="form-control ajuste" name="nameE" id="nameE">
                            <div class="error-messageGrupoE"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div Class="mb-4 mt-1">
                            <label for="situacionCambio" class="form-label labelFormato">SITUACION:</label>
                            <select name="situacionCambio" class="form-control ajuste mt-2" id="situacionCambio">
                                <option value="Disponible">Disponible</option>
                                <option value="Mantenimiento">Mantemimiento</option>
                                <option value="Limpieza">Limpieza</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="idE">
                    <div class="botonesModalE">
                        <a id="cerrarModalE" class="btn btn-dark m-2 btnEditar" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar" tabindex="4">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>