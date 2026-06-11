$(document).on("click", "#cerrarModalVentaCantProducto", function () {
    $("#modalEditCantProducto").modal("hide");
});

$(document).ready(function () {
    $(document).on("click", ".listaProductos tr", function (event) {
        // Obtenemos el elemento en el que se hizo clic
        var clickedElement = event.target;

        if ($(clickedElement).is(".fa-trash")) {
            return;
        }

        if ($(clickedElement).is(".descuentoInput")) {
            return;
        }

        // Si no es un input de tipo "number", continuamos con la lógica original
        var id = $(this).attr("id");
        console.log(id);
        $("#idProducto").val(id);
        $.get("detalleMovimiento/showId/" + id, function (data) {
            console.log(data);
            $("#nombreProducto").val(data.producto.nombre);
            $("#notaProductoE").val(data.movimiento.comentario);
            $("#cantidadProductoEd").val(data.movimiento.cantidad);
            var stockDisponibleHabitacion = parseFloat(
                data.stockHabitacionDisponible || 0
            );
            var cantidadTotal =
                stockDisponibleHabitacion + data.movimiento.cantidad;

            // $("#cantidadProductoEd").attr("max", cantidadTotal);
            $("#cantidadProductoEd").prop(
                "title",
                "La cantidad debe ser menor o igual a: " + cantidadTotal
            );

            $("#stockCantProd").val(stockDisponibleHabitacion);
            $("#modalEditCantProducto").modal("show");
        });
    });
    // Obtener el elemento del input de cantidad
    var cantidadInput = $("#cantidadProductoEd");

    // Agregar el evento click al botón de más
    $("#btnMas").on("click", function () {
        // Obtener el valor actual del input de cantidad
        var cantidadActual = parseInt(cantidadInput.val());

        // Incrementar la cantidad en 1
        cantidadInput.val(cantidadActual + 1);
    });

    // Agregar el evento click al botón de menos
    $("#btnMenos").on("click", function () {
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
    $("#registroEditCantProducto").submit(function (e) {
        e.preventDefault();
        console.log("xd");
        // if (
        //     parseFloat($("#cantidadProductoEd").val()) <
        //     parseFloat($("#stockCantProd").val())
        // ) {
        //     var formData = new FormData(this);

        //     $.ajax({
        //         type: "POST",
        //         url:
        //             "detalleMovimiento/updateCantIdProd/" +
        //             $("#idProducto").val(),
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function (response) {
        //             if (response) {
        //                 $("#registroEditCantProducto")[0].reset(); //limpiar campos
        //                 $("#modalEditCantProducto").modal("hide"); //ocultar modaL
        //                 window.location.href =
        //                     "ventaHabitacion?id=" + $("#numHabitacion").val();
        //             } else {
        //                 alert("nO");
        //             }
        //         },
        //     });
        // } else {
        //     Swal.fire({
        //         title: "El stock de este producto es de: ",
        //         text: "Debe realizar la compra de este producto",
        //         icon: "warning",
        //         confirmButtonColor: "#3085d6",
        //     }).then((result) => {
        //         // if (result.isConfirmed) {
        //         //     $("#modalEditCantProducto").modal("hide"); //ocultar modaL
        //         // }
        //     });
        // }
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "detalleMovimiento/updateCantIdProd/" + $("#idProducto").val(),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroEditCantProducto")[0].reset(); //limpiar campos
                    $("#modalEditCantProducto").modal("hide"); //ocultar modaL
                    window.location.href =
                        "ventaHabitacion?id=" + $("#numHabitacion").val();
                } else {
                    alert("nO");
                }
            },
        });
    });
});
