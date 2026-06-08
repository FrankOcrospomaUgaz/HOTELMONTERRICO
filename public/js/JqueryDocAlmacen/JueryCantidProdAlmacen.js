$(document).ready(function () {
    if ($("#agregarDocALmacen").val() == "true") {
        $(document).on("click", "#cerrarModalVentaCantProductoE", function () {
            $("#modalEditarCantProducto").modal("hide");
        });

        $(document).ready(function () {
            $(document).on("click", ".listaProductos tr", function (event) {
                // Obtenemos el elemento en el que se hizo clic
                var clickedElement = event.target;

                if ($(clickedElement).is("input[type='number']")) {
                    return;
                }

                if ($(clickedElement).is(".fa-trash")) {
                    return;
                }

                // Si no es un input de tipo "number", continuamos con la lógica original
                var id = $(this).attr("id");
                console.log(id);
                $("#idProducto").val(id);
                $.get("detalleMovimiento/showId/" + id, function (data) {
                    console.log(data);
                    $("#nombreProductoE").val(
                        data.producto.nombre +
                            "-> Stock(" +
                            data.producto.stock +
                            ")"
                    );
                    $("#notaProductoE").val(data.movimiento.comentario);
                    $("#cantidadProductoE").val(data.movimiento.cantidad);
                    $("#modalEditarCantProducto").modal("show");
                });
            });
            // Obtener el elemento del input de cantidad
            var cantidadInput = $("#cantidadProductoE");

            // Agregar el evento click al botón de más
            $("#btnMasE").on("click", function () {
                // Obtener el valor actual del input de cantidad
                var cantidadActual = parseInt(cantidadInput.val());

                // Incrementar la cantidad en 1
                cantidadInput.val(cantidadActual + 1);
            });

            // Agregar el evento click al botón de menos
            $("#btnMenosE").on("click", function () {
                // Obtener el valor actual del input de cantidad
                var cantidadActual = parseInt(cantidadInput.val());

                // Validar que la cantidad sea mayor a 1 para evitar números negativos
                if (cantidadActual > 1) {
                    // Disminuir la cantidad en 1
                    cantidadInput.val(cantidadActual - 1);
                } else {
                    // Si la cantidad es 1, no hacer nada (no permitir restar más)
                    cantidadInput.val(1);
                }
            });
        });

        $(document).ready(function () {
            if ($("#operacionI").text() == "compras") {
                $("#registroEditCantProducto").submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);

                    $.ajax({
                        type: "POST",
                        url:
                            "detalleMovimiento/updateCantIdProdCompra/" +
                            $("#idProducto").val(),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response) {
                                $("#modalEditarCantProducto").modal("hide"); //ocultar modaL
                                window.location.href =
                                    "docAlmacen?operacion=compras&id=" +
                                    $("#idMovCompra").val();
                            } else {
                                alert("nO");
                            }
                        },
                    });
                });
            } else {
                $("#registroEditCantProducto").submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);

                    $.ajax({
                        type: "POST",
                        url:
                            "detalleMovimiento/updateCantIdProdCompra/" +
                            $("#idProducto").val(),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response) {
                                $("#modalEditarCantProducto").modal("hide"); //ocultar modaL
                                window.location.href =
                                    "docAlmacen?Cuadre%20Caja=compras&id=" +
                                    $("#idMovCompra").val() +
                                    "&tipo=" +
                                    $("#tipoI").text();
                            } else {
                                alert("nO");
                            }
                        },
                    });
                });
            }
        });
    } else {
        console.log($("#agregarDocALmacen").val());
    }
});
