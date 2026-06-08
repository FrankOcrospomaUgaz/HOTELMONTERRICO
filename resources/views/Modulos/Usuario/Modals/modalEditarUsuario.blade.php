<!-- Modal EDITAR-->
<div class="modal fade" id="modalNuevoUsuarioE" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255, 171, 0); color:white;">
                <h5 class="modal-title"><strong id="nuevoModalLabel">EDITAR USUARIO</strong></h5>
            </div>
            <div class="modal-body">
                <form id="registroUsuarioE" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">

                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="selectDNI-RUC-E" id="selectDNI-RUC-E" class="form-control ml-1">
                                            <option value="DNI"><strong>DNI</strong></option>
                                            <option value="RUC"><strong>RUC</strong></option>
                                        </select>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="input-group ajuste">

                                            <input type="number" class="form-control text-left" name="dniE" id="dniE" min="0">
                                            <div class="input-group-append">
                                                <span id="dniBuscarE" class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                            </div>

                                        </div>
                                        <div class="error-messageE"></div>
                                    </div>
                                </div>

                            </div>
                            <!-- NOMBRE -->
                            <div class="CajaDNI-E">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="nombreE" class="form-label labelFormato"><strong>NOMBRE:</strong></label>
                                        <input type="text" name="nombreE" id="nombreE" class="form-control ajuste">
                                        <div class="error-messageE"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="mb-3">
                                                <label for="apellPaternoE" class="form-label labelFormato"><strong>APELLIDOS:</strong></label>
                                                <input type="text" name="apellPaternoE" id="apellPaternoE" class="ajuste form-control">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mt-2">
                                            <div class="mb-3">
                                                <label for="apellMaternoE" class="form-label labelFormato"><strong></strong></label>
                                                <input type="text" name="apellMaternoE" id="apellMaternoE" class="ajuste form-control">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="CajaRUC-E d-none">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="razonsocialE" class="form-label labelFormato"><strong>RAZÓN SOCIAL:</strong></label>
                                        <input type="text" name="razonsocialE" id="razonsocialE" class="ajuste form-control">
                                        <div class="error-messageE"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="direccionE" class="form-label labelFormato"><strong>DIRECCIÓN:</strong></label>
                                        <input type="text" name="direccionE" id="direccionE" class="ajuste form-control">
                                        <div class="error-messageE"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <br><br>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="emailE" class="form-label labelFormato"><strong>EMAIL:</strong></label>
                                    <input type="text" class="form-control ajuste" name="emailE" id="emailE" placeholder="example@gmail.com">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="telefonoE" class="form-label labelFormato"><strong>TELEFONO:</strong></label>
                                    <input type="text" class="form-control ajuste" name="telefonoE" id="telefonoE" placeholder="Escribe tu numero">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="" class="form-label labelFormato"><strong>ROLES:</strong></label>
                            <br>
                            <div class="mb-3 text-center mx-auto">
                                <div class="d-inline-block cajaCheckBoxRolesE">

                                </div>
                            </div>
                        </div>
                        <div class="cajaUsuarioE d-none row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="nombreUsuarioE" class="form-label labelFormato"><strong>NOMBRE USUARIO:</strong></label>
                                        <input type="text" name="nombreUsuarioE" id="nombreUsuarioE" class="form-control ajuste">
                                        <div class="error-messageE"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <div Class="mb-3">
                                        <label for="tipoUsuarioE" class="form-label labelFormato"><strong>TIPO USUARIO:</strong></label>

                                        <select name="tipoUsuarioE" class="form-control ajuste" id="tipoUsuarioE">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row cajaPasswordEdit">
                        <label class="PasswordEdit">OPCIONAL</label>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="form-group">
                                    <label for="password" class="form-label labelFormato">CONTRASEÑA NUEVA:</label>
                                    <div class="input-group ajuste">
                                        <input type="password" class="form-control text-left" name="password" id="password" tabindex="1">
                                        <div class="input-group-append">
                                            <span id="mostrar-contrasena" class="input-group-text"><i class="fa-solid fa-eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="error-messageEE"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="form-group">
                                    <label for="password-confirm" class="form-label labelFormato">CONFIRMAR CONTRASEÑA:</label>
                                    <div class="input-group ajuste">
                                        <input type="password" class="form-control text-left" name="password-confirm" id="password-confirm">
                                        <div class="input-group-append">
                                            <span id="mostrar-contrasenaConfirm" class="input-group-text"><i class="fa-solid fa-eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="error-messageEE"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                        <input type="hidden" id="idE">
                    </div>

                    
                    <div class="botonesModalE">
                        <a id="cerrarModalUsuarioE" class="btn btn-dark m-2 btnEditar">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnEditar">GUARDAR</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>