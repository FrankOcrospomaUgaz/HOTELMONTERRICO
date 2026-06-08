$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="perfil">HOTEL | PERFIL</a>`
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

$.ajax({
    type: "get",
    url: "perfil",
    success: function (response) {
        console.log(response);
        switch (response.tipoUsuario) {
            case "Administrador":
                $("#imagenConfig").attr("src", "imagesComunes/perfil.png");
                break;
            case "Trabajador":
                $("#imagenConfig").attr("src", "imagesComunes/programador.png");
                break;
            case "Cliente":
                $("#imagenConfig").attr("src", "imagesComunes/client2.png");
                break;
            default:
                $("#imagenConfig").attr("src", "imagesComunes/worker.png");
                break;
        }
        $("#idInfo").val(response.id);
        $("#idPass").val(response.idUser);
        $("#empresaInfo").text(response.razonsocial);
        actualizarValores(response);
    },
});

function actualizarValores(response) {
    $("#nombreInfo").text(response.nombres + " " + response.apellidos);
    $("#emailInfo").text(response.email);
    $("#nombreUsuarioInfo").text(response.username);
    $("#telefonoInfo").text(response.telefono);
    $("#rolInfo").text(response.tipoUsuario);
    $("#nombresE").val(response.nombres);
    $("#apPaternoE").val(response.apellidopaterno);
    $("#apMaternoE").val(response.apellidomaterno);
    $("#nombreUsuarioE").val(response.username);
    $("#dniE").val(response.dni);
    $("#correoE").val(response.email);
    $("#telefonoE").val(response.telefono);
}
