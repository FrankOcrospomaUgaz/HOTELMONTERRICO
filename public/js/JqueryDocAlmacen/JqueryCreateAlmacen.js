$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="categoriaMenu">HOTEL | D. ALMACÉN</a>`
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

$(".selectTwo").each(function () {
    var $p = $(this).parent();
    $(this).select2({
        dropdownParent: $p,
    });
});

var fechaCompro = flatpickr("#fechaCompro", {
    dateFormat: "Y-m-d",
    defaultDate: "today",
    maxDate: new Date(),
    allowInput: true,
});

if ($("#idMovCompra").val() != "") {
    $("#Actualizar").removeClass("d-none");

    $.get("docCompras/show/" + $("#idMovCompra").val(), function (data) {
        //3 son proveedores
        //OBTENER proveedores
        console.log(data);

        $("#fechaI").text(data.fecha);
        $("#responsableI").text(data.responsable.nombres);

        $(".listaProductos").html(``);

        $.each(data.detalleMovimientos, function (index, item) {
            var tipoIngresoEgreso = "";

            var total = `
            <td class="totalFila">S/ ${item.preciocompra * item.cantidad}</td>`;
            var accion = ``;
            if ($("#agregarDocALmacen").val() == "true") {
                var accion = `<td>
    <a href="javascript:void(0)" onclick="eliminarProductoAgregado(${item.id})">
        <i class="fa-solid fa-trash " style="color: #0047c2;"></i>
    </a>
    
        </td>`;
                $("#idAccionesDocA").removeClass("d-none");
            }

            $(".listaProductos").html(
                $(".listaProductos").html() +
                    ` <tr id="${item.id}" >` +
                    accion +
                    `
                    
                    <td>${item.nombre}</td>
                    <td>${item.motivo}</td>
                    <td>${item.cantidad}</td>
                    <td>${item.preciocompra}</td>
                     ` +
                    tipoIngresoEgreso +
                    total +
                    ` <td>${item.comentario}</td>
                    
                </tr>`
            );
        });

        $.get(
            "detalleMovimiento/showDocAlmacen/" + $("#idMovCompra").val(),
            function (data) {
                var totalC = data.total == null ? "0.00" : data.total;
                $("#totalMasIgv").text("S/" + totalC);
            }
        );
    });
} else {
    $.get("usuarios/show/3", function (data) {
        //3 son proveedores
        //OBTENER proveedores
        console.log("xd");
        console.log(data);

        $("#responsableI").text(data.responsable.nombres);
        $("#fechaI").text(data.fecha);
    });
}
