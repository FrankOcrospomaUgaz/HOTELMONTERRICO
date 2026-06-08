<!-- Modal CREAR-->
<div class="modal fade" id="modalNuevoUsuario" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong id="nuevoModalLabel">AGREGAR NUEVA PERSONA</strong></h5>
            </div>
            <div class="modal-body">
                
                <form id="registroUsuario" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">

                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="selectDNI-RUC" id="selectDNI-RUC" class="form-control ml-1">
                                            <option value="DNI"><strong>DNI</strong></option>
                                            <option value="RUC"><strong>RUC</strong></option>
                                        </select>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="input-group ajuste">

                                            <input type="number" class="form-control text-left" name="dni" id="dni" min="0">
                                            <div class="input-group-append">
                                                <span id="dniBuscar" class="input-group-text btn tooltipped"><i class="fa-solid fa-magnifying-glass"></i></span>
                                            </div>

                                        </div>
                                        <div class="error-message"></div>
                                    </div>
                                </div>

                            </div>
                            <!-- NOMBRE -->

                            <div class="CajaDNI">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label labelFormato"><strong>NOMBRE:</strong></label>
                                        <input type="text" name="nombre" id="nombre" class="form-control ajuste">
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="mb-3">
                                                <label for="apellPaterno" class="form-label labelFormato"><strong>APELLIDOS:</strong></label>
                                                <input type="text" name="apellPaterno" id="apellPaterno" class="ajuste form-control">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mt-2">
                                            <div class="mb-3 ">
                                                <label for="apellMaterno" class="form-label labelFormato"><strong></strong></label>
                                                <input type="text" name="apellMaterno" id="apellMaterno" class="ajuste form-control">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="CajaRUC d-none">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="razonsocial" class="form-label labelFormato"><strong>RAZÓN SOCIAL:</strong></label>
                                        <input type="text" name="razonsocial" id="razonsocial" class="ajuste form-control">
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label labelFormato"><strong>DIRECCIÓN:</strong></label>
                                        <input type="text" name="direccion" id="direccion" class="ajuste form-control">
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <br><br>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="email" class="form-label labelFormato"><strong>EMAIL:</strong></label>
                                    <input type="text" class="form-control ajuste" name="email" id="email" placeholder="example@gmail.com">
                                    <div class="error-message"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label labelFormato"><strong>TELEFONO:</strong></label>
                                    <input type="text" class="form-control ajuste" name="telefono" id="telefono" placeholder="Escribe tu numero">
                                    <div class="error-message"></div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="" class="form-label labelFormato"><strong>ROLES:</strong></label>
                            <br>
                            <div class="mb-3 text-center mx-auto">

                                <div class="d-inline-block cajaCheckBoxRoles">

                                </div>



                            </div>
                        </div>
                        <div class="cajaUsuario d-none row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="nombreUsuario" class="form-label labelFormato"><strong>NOMBRE USUARIO:</strong></label>
                                        <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control ajuste">
                                        <div class="error-message"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <div Class="mb-3">
                                        <label for="tipoUsuario" class="form-label labelFormato"><strong>TIPO USUARIO:</strong></label>

                                        <select name="tipoUsuario" class="form-control ajuste" id="tipoUsuario">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="botonesModal">
                        <a id="cerrarModalUsuario" class="btn btn-dark m-2 btnCrear" tabindex="3">CANCELAR</a>
                        <button type="submit" class="btn btn-secondary m-2 btnCrear" tabindex="4">GUARDAR</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>