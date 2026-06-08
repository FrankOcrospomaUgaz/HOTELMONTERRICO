$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="kardex">HOTEL | KARDEX</a>`
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

    $("#fechaInicio, #fechaFin").val(new Date().toISOString().slice(0, 10));
});

$(document).ready(function () {
    $(".selectTwo").each(function () {
        var $p = $(this).parent();
        $(this).select2({
            dropdownParent: $p,
        });
    });

    $.get("catProductos/show", function (data) {
        console.log(data);
        $("#productos").html(`<option value="todos"> TODOS </option>`);
        $.each(data, function (index, item) {
            $("#productos").html(
                $("#productos").html() +
                    `<option value="${item.id}"> ${item.nombre} </option>`
            );
        });
    });

    $("#envioDescargarKardex").submit(function (e) {
        e.preventDefault();

        reporteKardex(
            $("#productos").val(),
            $("#fechaInicio").val(),
            $("#fechaFin").val()
        );
    });
});

function reporteKardex(id, fechaI, fechaT) {

    
    // Construye la URL de la solicitud AJAX con los parámetros
    var url = "export-kardex/" + id + "/" + fechaI + "/" + fechaT;

    // Realiza una petición AJAX para obtener el archivo Excel generado en el servidor
    // Asegúrate de ajustar la URL de la petición AJAX según tu aplicación
    fetch(url) // Utiliza la URL construida con los parámetros
        .then((response) => response.blob())
        .then((blob) => {
            // Utiliza FileSaver.js para guardar el archivo como descarga
            saveAs(blob, "kardex.xlsx"); // Reemplaza 'kardex.xlsx' con el nombre deseado para el archivo
        })
        .catch((error) => {
            console.error("Error al descargar el archivo Excel:", error);
        });
}
