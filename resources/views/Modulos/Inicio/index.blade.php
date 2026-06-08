@extends('adminlte::page')

@section('title', 'INICIO')

@section('content_header')
@stop

@section('content')
<div id="imageF">
    
    <div class="brindamos">
        <div class="row justify-content-center cajaServicios">
            <div class="col-md-11">
                <div class="row introduccion">
                    <div class="col-sm-12 col-md-6">
                        <h2 class="negrita">Qué brindamos</h2>
                        <div class="bg-primario mb-3" style="width: 10%; height: 3px"></div>
                        <p class="text-justify">
                            Garzasoft brinda a su empresa herramientas informáticas basadas
                            en software con la finalidad de lograr eficiencia en sus
                            procesos principalmente para la optimización de uso de recursos
                        </p>

                    <p><a href="https://garzasoft.com/" class="lead" target="_blank">Garzasoft</a></p>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <img src="../public/imagesComunes/servicios-1.png" class="img-fluid" alt="Responsive image" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section>
        <div class="overlay">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-11">
                        <div class="row introduccion">
                            <div class="col-sm-12">

                                <h2 class="nTservicio">Nuestros Servicios</h2>

                                <div class="row py-2 my-5" id="servicio1">

                                    <div class="col-sm-12 col-md-6">
                                        <img src="../public/imagesComunes/iconosap.png" class="img-fluid" alt="servicio">
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label class="textB">
                                            <h2>Desarrollo de software a medida</h2>
                                            Garzasoft se caracteriza por personalizar nuestras aplicaciones de software de acuerdo a las necesidades y expectativas del cliente. Contamos con amplia experiencia en el desarrollo de aplicaciones para el sector comercial, educativo, salud, gastronómico, hotelería, de servicios, productivo, entre otros. Nuestro personal analizará la situación actual de su empresa para lograr un diagnóstico situacional, luego de lo cual formulará propuestas para mejorar las cuales serán validadas por usted, y finalmente adecuar nuestra solución de software a su nuevo modelo de negocio, logrando su empresa el máximo rendimiento.
                                        </label>
                                    </div>
                                </div>

                                <div class="row py-2 my-5" id="servicio1">

                                    <div class="col-sm-12 col-md-6">
                                        <img src="../public/imagesComunes/iconofacturacion.png" class="img-fluid" alt="servicio">
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label class="textB">
                                            <h2>Facturación Electrónica</h2>
                                            La Factura Electrónica es el comprobante de pago: Factura / Boleta / Nota de Crédito y Débito emitido desde los sistemas del contribuyente (cliente empresa) hacia el sistema de emisión electrónica de SUNAT. La Facturación Electrónica es obligada por SUNAT desde inicios de 2018 en un plan de implementación que incluirá a todas las empresas en 2020. Garzasoft pone a disposición de su empresa el desarrollo personalizado de un software de facturación electrónica desde el cual usted puede cumplir con la obligación de SUNAT a un precio mensual de 100.00 soles
                                        </label>
                                    </div>

                                </div>

                                <div class="row py-2 my-5" id="servicio1">

                                    <div class="col-sm-12 col-md-6">
                                        <img src="../public/imagesComunes/iconoti.png" class="img-fluid" alt="servicio">
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label class="textB">
                                            <h2>Asesoría y consultoría en usos de TI</h2>
                                            Garzasoft realiza el diagnóstico de la actualidad de su empresa, luego basado en nuestra experiencia en el sector y en el uso de las tecnologías de información, proponemos rediseños a los procesos, adquisiciones de tencología y desarrollos de software que brinden a su negocio las herramientas tecnológicas que aseguren el logro de sus metas
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>
    <h1 class="title">Nuestros Servicios</h1>

    <h1 class="footer text-center"><i class="fas fa-laptop"></i> <br> Nuestra experiencia nos respalda</h1>
</div>
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


<link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />


<link rel="stylesheet" href="{{ asset('css/app2.css')}}">
<link rel="stylesheet" href="{{ asset('css/inicio.css')}}">
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
<script src="{{ asset('js/JqueryInicio/JqueryInicio.js') }}"></script>
@stop