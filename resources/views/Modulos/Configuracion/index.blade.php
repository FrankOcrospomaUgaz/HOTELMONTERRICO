@extends('adminlte::page')

@section('title', 'CONFIGURACION')

@section('content_header')
<br><br>
{{-- <h1 id="titulo" class="text-center">CONFIGURACIÓN</h1> --}}
@stop

@section('content')
<div class="container-fluid m-2">

    <div class="row d-flex justify-content-around mt-4">
        <div class="col-lg-3 border-top border-4 border-dark rounded-5 bg-body mb-4">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6 d-flex justify-content-center">
                    <img id="imagenConfig" src="" alt="alt" width="105" height="110" class="rounded-circle m-2" />
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <h4 class="text-center" id="nombreInfo"></h4>
            </div>
            <div class="row border-top border-3 border-dark rounded-3 mt-4 infoPerfil">

                <label class="mt-2">
                    <strong><i class="fas fa-user"></i> Nombre Usuario:
                    </strong>
                </label>
                <p id="nombreUsuarioInfo"></p>
                <label>
                    <strong><i class="fa-solid fa-users"></i> Tipo Usuario:</strong>
                </label>
                <p id="rolInfo"></p>
                <label>
                    <strong><i class="fa-solid fa-envelope"></i> Email</strong>
                </label>
                <p id="emailInfo"></p>
                <label><strong><i class="fa-solid fa-phone"></i> Telefono</strong>
                </label>
                <p id="telefonoInfo"></p>
            </div>
        </div>
        <div class="col-lg-8 ">
            <div class="row bg-body pb-4 cajaPerfil">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-dark" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                            <strong>Cambiar Contraseña</strong>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                            <strong>Actualizar Perfil</strong>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active mt-4" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form method="POST" id="contraseñaNueva">
                            @method('PUT')
                            @csrf
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="anteriorContraseña" class="col-sm-4 col-form-label"><strong>Contraseña Anterior</strong></label>
                                <div class="col-sm-5">
                                    <div class="input-group ajuste">
                                        <input type="password" class="form-control" id="anteriorContraseña" name="anteriorContraseña">
                                        <div class="input-group-append">
                                            <span id="mostrar-contrasenaAnterior" class="input-group-text"><i class="fa-solid fa-eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="nuevaContraseña" class="col-sm-4 col-form-label"><strong>Nueva Contraseña</strong></label>
                                <div class="col-sm-5">
                                    <div class="input-group ajuste">
                                        <input type="password" class="form-control" id="nuevaContraseña" name="nuevaContraseña">
                                        <div class="input-group-append">
                                            <span id="mostrar-contrasena" class="input-group-text"><i class="fa-solid fa-eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="password-confirm" class="col-sm-4 col-form-label"><strong>Confirmar Contraseña</strong></label>
                                <div class="col-sm-5">
                                    <div class="input-group ajuste">
                                        <input type="password" class="form-control" id="password-confirm" name="password-confirm">
                                        <div class="input-group-append">
                                            <span id="mostrar-contrasenaConfirm" class="input-group-text"><i class="fa-solid fa-eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <input type="hidden" id="idPass">
                            <div class="row d-flex justify-content-center mt-4">
                                <div class="col-md-4 d-flex justify-content-center">
                                    <button class="btn btnPerfil" type="submit">Guardar Cambios</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade show  mt-4" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form method="POST" id="updatePerfil">
                            @method('PUT')
                            @csrf
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="nombresE" class="col-sm-4 col-form-label"><strong>Nombres</strong></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="nombresE" name="nombresE" value="">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="apPaternoE" class="col-sm-4 col-form-label"><strong>Apellido Paterno</strong></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="apPaternoE" name="apPaternoE" value="">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="apMaternoE" class="col-sm-4 col-form-label"><strong>Apellido Materno</strong></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="apMaternoE" name="apMaternoE" value="">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="dniE" class="col-sm-4 col-form-label"><strong>DNI</strong></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="dniE" name="dniE" value="">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="nombreUsuarioE" class="col-sm-4 col-form-label"><strong>Nombre Usuario</strong></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="nombreUsuarioE" name="nombreUsuarioE" value="">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="correoE" class="col-sm-4 col-form-label"><strong>Email</strong></label>
                                <div class="col-sm-5">
                                    <input type="email" class="form-control" id="correoE" name="correoE" value="">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <div class="mb-3 row d-flex justify-content-center formGrupo">
                                <label for="telefonoE" class="col-sm-4 col-form-label"><strong>Telefono</strong></label>
                                <div class="col-sm-5">
                                    <input type="tel" class="form-control" id="telefonoE" name="telefonoE" value="">
                                    <div class="error-messageE"></div>
                                </div>
                            </div>
                            <input type="hidden" id="idInfo">
                            <div class="row d-flex justify-content-center mt-4">
                                <div class="col-md-4 d-flex justify-content-center">
                                    <button class="btn btnPerfil" type="submit">Guardar Cambios</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
@section('footer')
<footer>
    <div class="footer-container">
        <div class="footer-content">
            <div class="row">
            <div class="col-md-6">
                    <p class="texto-Footer"><b>Copyright&copy; 2023. </b><a class="garzasoftFooter" href="http://www.garzasoft.com/">Garzasoft</a>. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6">
                    <div class="footer-bottom">
                        <div class="footer-social">
                            <ul>
                                <li><a target="_blank" href="https://www.facebook.com/Garzasoft"><i class="fa-brands fa-facebook"></i></a></li>
                                <li><a target="_blank" href="https://api.whatsapp.com/send?phone=+51%20979293176&text=%C2%A1Hola!%20Quisiera%20informaci%C3%B3n%20sobre%20los%20servicios%20de%20Garzasoft."><i class="fab fa-whatsapp"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@stop
@stop

@section('css')
<!-- CSS DEL BOOTSTRAP -->
<link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- CSS DEL DATATABLE -->
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.css"><!-- CSS LOCAL -->

<script src="/proyectoHotel/Cdn-Locales/pkgAwsome/js/all.js"></script>
<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />

<link rel="stylesheet" href="{{ asset('css/app2.css')}}">
<link rel="stylesheet" href="{{ asset('css/perfil.css')}}">
<link rel="stylesheet" href="{{ asset('css/footer.css')}}">
<link rel="stylesheet" href="{{ asset('css/tooltips.css')}}">
@stop

@section('js')
<!-- JS DE JQUERY-->
<script src="/proyectoHotel/Cdn-Locales/pkgJquery/dist/jquery.js"></script>
<!-- JS DE DATATABLE -->
<script src="/proyectoHotel/Cdn-Locales/pkgDatatables/datatables.js"></script>



<!-- PARA MODAL DE ALERTAS SWEETALERT2-->
<script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

<!-- CDN para BOOTSTRAP -->
<!-- CDN para BOOTSTRAP -->

<script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js">
</script>
<!-- JS DEL JQUERY EN PUBLIC/JS -->
<script src="{{ asset('js/JqueryConfiguracion/JqueryIndexConfig.js') }}"></script>

@can($permisosEditar->name)
<script src="{{ asset('js/JqueryConfiguracion/JqueryEditConfig.js') }}"></script>
<script src="{{ asset('js/JqueryConfiguracion/JqueryUpdateConfig.js') }}"></script>
@endcan

@stop