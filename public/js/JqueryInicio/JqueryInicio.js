$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="inicio">HOTEL | INICIO</a>`
    );
    $("#stockProductos").html(
        `<a class="nav-link active btn-profesional" href="stockProductos" data-tooltip="Reporte Stock" ><i class="fa-solid fa-box"></i></a>`
    );
   $("#consumoHab").html(
        `<a class="nav-link active btn-profesional" href="consumoHab" data-tooltip="Reporte Check-Out" ><i class="fa-regular fa-calendar-check"></i></a>`
    );



    $("#vistaPrincipal").html(
        `<a class="nav-link active btn-profesional" href="vistaPrincipal" data-tooltip="Vista Principal" ><i class="fa-solid fa-star"></i></a>`
    );
    $("#vistaTabla").html(
        `<a class="nav-link active btn-profesional" href="listaHab" data-tooltip="Vista Tabla" ><i class="fa-solid fa-table-list"></i></a>`
    );
    $("#vistaCaja").html(
        `<a class="nav-link active btn-profesional" href="cajaChica" data-tooltip="Caja" ><i class="fa-solid fa-cash-register"></i></a>`
    );
    $("#vistaCompras").html(
        `<a class="nav-link active btn-profesional" href="movCompras" data-tooltip="Compras" ><i class="fa-solid fa-cart-shopping"></i></a>`
    );

    $("#vistaAlmacen").html(

        `<a class="nav-link active btn-profesional" href="movAlmacen" data-tooltip="Documento Almacén" ><i class="fa-solid fa-store"></i></a>`
    );
});

$(document).ready(function () {
    var url = "/clifacturacion/controlador/contComprobante.php";
    var funcion_Boleta = "enviarPendienteBoleta";

    var parametros = {
        funcion: funcion_Boleta,
    };

    $.ajax({
        type: "GET",
        url: url,
        data: parametros,

        success: function (result) {
            console.log(result);
        },
        error: function (e) {
            console.log("error:".e);
        },
    });
});

$(document).ready(function () {
    var url = "/clifacturacion/controlador/contComprobante.php";
    var funcion_Facturas = "enviarPendienteFactura";

    var parametros = {
        funcion: funcion_Facturas,
    };

    $.ajax({
        type: "GET",
        url: url,
        data: parametros,

        success: function (result) {
            console.log(result);
        },
        error: function (e) {
            console.log("error:".e);
        },
    });
});
