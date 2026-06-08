$("#agregarProducto").click(function (e) {
    $.get("catProductos/show", function (data) {
        $("#productos").html(``);
        $.each(data, function (index, item) {
            $("#productos").html(
                $("#productos").html() +
                    `<option value="${item.id}"> ${item.nombre}  -> Stock(${item.stock}) </option>`
            );
        });
        $("#modalAgregarProdAlmacen").modal("show");
    });
    $.get("catMotivos/show/" + $("#tipoI").text(), function (data) {
        $("#motivos").html(``);
        $.each(data, function (index, item) {
            $("#motivos").html(
                $("#motivos").html() +
                    `<option value="${item.id}"> ${item.nombre}</option>`
            );
        });
    });
});

$(document).on("click", "#cerrarModalAddProducto", function () {
    $("#modalAgregarProdAlmacen").modal("hide");
});

$("#registroAddProductoDocAlmacen").submit(function (e) {
    e.preventDefault();
    //ENVIAR A GUARDAR REGISTROS HIJOS

    var formData = new FormData(this);
    formData.append("idMovimientoPadre", $("#idMovCompra").val());
    formData.append("tipo", $("#tipoI").text());
    $.ajax({
        type: "POST",
        url: "docAlmacen/guardarDetalleCuadre",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response) {
                console.log(response);
                window.location.href =
                    "docAlmacen?operacion=Doc%20Almacen&tipo=" +
                    $("#tipoI").text() +
                    "&id=" +
                    response;
            } else {
                alert("nO");
            }
        },
    });
});

var cantidadInput = $("#cantidadProductoEd").val();


// Agregar el evento click al botón de más
$("#btnMas").on("click", function () {
    // Obtener el valor actual del input de cantidad
    var cantidadActual = parseInt($("#cantidadProductoEd").val());

    // Incrementar la cantidad en 1
    $("#cantidadProductoEd").val(cantidadActual + 1);
});

// Agregar el evento click al botón de menos
$("#btnMenos").on("click", function () {
    // Obtener el valor actual del input de cantidad
    var cantidadActual = parseInt($("#cantidadProductoEd").val());

    // Validar que la cantidad sea mayor a 1 para evitar números negativos
    if (cantidadActual > 1) {
        // Disminuir la cantidad en 1
        $("#cantidadProductoEd").val(cantidadActual - 1);
    } else {
        // Si la cantidad es 1, no hacer nada (no permitir restar más)
        $("#cantidadProductoEd").val(1);
    }
});
