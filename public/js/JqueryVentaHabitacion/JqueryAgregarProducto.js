$(document).ready(function () {
    var carrito = [];
    var total = 0;
    var cantidad = 1;

    function actualizarTabla() {
        // Vaciar el cuerpo de la tabla.
        $("#carrito-body").html("");

        // Iterar a través de los productos en el carrito y agregarlos a la tabla.
        carrito.forEach(function (producto) {
            $("#carrito-body").append(
                "<tr ><td>" +
                    producto.nombre +
                    "</td><td>S/ " +
                    producto.precio.toFixed(2) +
                    "</td><td>" +
                    producto.cantidad +
                    "</td><td>S/ " +
                    producto.subtotal.toFixed(2) +
                    "</td><td><i class='fa-solid fa-trash'></i></td></tr>"
            );
        });

        // Actualizar el total.
        $("#total").text("S/ " + total.toFixed(2));
    }
    // Evento para agregar un registro al carrito.
    $("#carritoIcon").click(function (e) {
        e.preventDefault();

        producto = {
            nombre: $("#productos option:selected").text(),
            precio: parseFloat($("#precioProducto").val()),
            cantidad: parseInt($("#cantidadProducto").val()),
            subtotal:
                parseFloat($("#precioProducto").val()) *
                parseInt($("#cantidadProducto").val()),
        };

        console.log(producto);
        console.log($("#cantidadProducto").val());

        // Agregar el producto al carrito.
        carrito.push(producto);

        // Actualizar la tabla de carrito.
        actualizarTabla();
        $("#cantidadProducto").val(1)
    });

    $(document).ready(function () {
        $("#agregarProducto").click(function (e) {
            e.preventDefault();
            $.get(
                "movimiento/show/" + $("#numHabitacion").val(),
                function (data) {
                    console.log(data);
                    if (data != "null") {
                        // Reinicia el arreglo carrito a un arreglo vacío
                        carrito = [];
                        $("#carrito-body").html("");
                        var producto = "";

                        $.get("catProductos/show", function (data) {
                            console.log(data);
                            $("#productos").html(``);
                            $.each(data, function (index, item) {
                                $("#productos").html(
                                    $("#productos").html() +
                                        `<option value="${item.id}"> ${item.nombre} </option>`
                                );
                            });
                            $.get(
                                "catProductos/showId/" + $("#productos").val(),
                                function (data) {
                                    $("#precioProducto").val(data.precioventa);
                                    $("#carrito-body").html("");

                                    $("#cantidadProducto").val(1);
                                    $("#modalVentaProducto").modal("show");
                                }
                            );
                        });

                        $("#carrito").on("click", ".fa-trash", function () {
                            var index = $(this).closest("tr").index();
                            eliminarFila(index);
                        });
                        function eliminarFila(index) {
                            carrito.splice(index, 1);
                            actualizarTabla();
                        }
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Esta Habitación no tiene un Checking",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        setTimeout(function () {
                            window.location.href = "vistaPrincipal";
                        }, 1600);
                    }
                }
            );
        });
    });

    $("#productos").change(function () {
        // Obtener el valor seleccionado
        var selectedValue = $(this).val();
        console.log(selectedValue);
        $.get("catProductos/showId/" + $("#productos").val(), function (data) {
            $("#precioProducto").val(data.precioventa);
        });
    });
});

$(document).ready(function () {
    function modalDescuento(tipo) {
        $(".descuentoInput").click(function (e) {
            e.preventDefault();

            const id = $(this).attr("id").split("-")[1];
            console.log("ID: " + id);

            Swal.fire({
                title: "Ingresa el Porcentaje de Descuento",
                input: "number",
                inputAttributes: {
                    min: 0,
                    max: 100,
                },
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (
                            parseFloat(value) >= 0 &&
                            parseFloat(value) <= 100
                        ) {
                            resolve();
                        } else {
                            resolve(
                                "Invalid percentage. Please enter a value between 0 and 100."
                            );
                        }
                    });
                },

                showCancelButton: true,
                confirmButtonText: "Guardar",
                showLoaderOnConfirm: true,
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $("#descuento-" + id).val(result.value);
                    $.get(
                        "detalleMovimiento/actualizarDescuento/" + id,
                        { descuento: result.value },
                        function (data) {
                            console.log(data);
                            if (data.total != null) {
                                $("#totalMasIgv").text("S/" + data.total);
                                $("#pagoEfectivo").val(data.total);

                                $("#descuento-" + id)
                                    .closest("tr")
                                    .find(".totalFila")
                                    .text(
                                        "S/" +
                                            (
                                                data.detalleMovimiento
                                                    .cantidad *
                                                    data.detalleMovimiento
                                                        .precioventa -
                                                (data.detalleMovimiento
                                                    .descuento /
                                                    100) *
                                                    data.detalleMovimiento
                                                        .cantidad *
                                                    data.detalleMovimiento
                                                        .precioventa
                                            ).toFixed(1)
                                    );
                            }
                        }
                    );
                }
            });
        });
    }

    $(document).on("click", "#cerrarModalVentaProducto", function () {
        $("#modalVentaProducto").modal("hide");
    });

    // <!-- CREAR NUEVA REGISTRO-->
    $("#registroVentaProducto")[0].reset(); //limpiar campos

    function obtenerCarritoDesdeTabla() {
        var carrito = [];

        // Itera a través de las filas del cuerpo de la tabla
        $("#carrito-body tr").each(function () {
            var fila = $(this);
            var producto = {
                nombre: fila.find("td:eq(0)").text(), // Obtiene el contenido de la primera columna (Producto)
                precio: parseFloat(
                    fila.find("td:eq(1)").text().replace("S/ ", "")
                ), // Obtiene el contenido de la segunda columna (Precio)
                cantidad: parseInt(fila.find("td:eq(2)").text()), // Obtiene el contenido de la tercera columna (Cantidad)
                subtotal: parseFloat(
                    fila.find("td:eq(3)").text().replace("S/ ", "")
                ), // Obtiene el contenido de la cuarta columna (Subtotal)
            };
            carrito.push(producto);
        });
        return carrito;
    }

    $("#registroVentaProducto").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        console.log(obtenerCarritoDesdeTabla());
        console.log("hola");

        $.get("movimiento/show/" + $("#numHabitacion").val(), function (data) {
            console.log(data);
            if (data != "null") {
                if (obtenerCarritoDesdeTabla().length != 0) {
                    var tableData = obtenerCarritoDesdeTabla();

                    // Convierte a JSON
                    var tableDataJSON = JSON.stringify(tableData);

                    formData.append("idMovimiento", $("#idMovimiento").val());
                    formData.append("tableData", tableDataJSON); //

                    console.log(tableDataJSON);
                    formData.append("idMovimiento", $("#idMovimiento").val());
                    $.ajax({
                        type: "POST",
                        url: "detalleMovimiento/guardar",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response) {
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Registro Guardado con Exito",
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                modalDescuento();
                                $.get(
                                    "detalleMovimiento/show/" +
                                        $("#idMovimiento").val(),
                                    function (data) {
                                        $("#totalMasIgv").text(
                                            "S/" + data.total
                                        );
                                    }
                                );

                                $("#carrito-body").html("");

                                $.get(
                                    "detalleMovimiento/showDetalleProductos/" +
                                        response.detalleMovimiento
                                            .movimiento_id,
                                    function (data) {
                                        console.log(data);
                                        $(".listaProductos").html(``);

                                        $.each(data, function (index, item) {
                                            $(".listaProductos").html(
                                                $(".listaProductos").html() +
                                                    ` <tr id="${item.id}" >
                                                    <td>
                                                <a href="javascript:void(0)" onclick="eliminarProductoAgregado(${
                                                    item.id
                                                })">
                                                    <i class="fa-solid fa-trash " style="color: #0047c2;"></i>
                                                </a>
                                                
                                                    </td>
                                                    <td>${item.nombre}</td>
                                                    <td>${item.cantidad}</td>
                                                    <td>${item.precioventa}</td>
                                                    <td>${item.comentario}</td>
                                                    <td><input type="number" class="form-control mx-auto w-50 text-center descuentoInput" id="descuento-${
                                                        item.id
                                                    }" min="0" max="100" step="0.01" value="${
                                                        item.descuento
                                                    }" readonly>
                                                    </td>
                                                    <td class="totalFila">S/ ${
                                                        item.cantidad *
                                                            item.precioventa -
                                                        (item.descuento / 100) *
                                                            item.precioventa *
                                                            item.cantidad
                                                    }</td>
                                                </tr>`
                                            );
                                        });
                                        modalDescuento();
                                    }
                                );

                                $("#modalVentaProducto").modal("hide"); //ocultar modal
                            } else {
                                alert("nO");
                            }
                        },
                        error: function (xhr, status, error) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;

                                $(".error-message").removeClass("d-none"); //HaBilitar mensaje error
                                $.each(errors, function (field, messages) {
                                    var element = $('[name="' + field + '"]');
                                    var container = element
                                        .closest(".form-group")
                                        .find(".error-message");
                                    container.text(messages[0]);
                                });
                            } else {
                                // maneja otros errores aquí
                            }
                        },
                    });
                } else {
                    Swal.fire({
                        title: "Debe Ingresar Productos",
                        text: "Si desea ingresar productos a la habitación",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok",
                        cancelButtonText: "Cancelar",
                    });
                }
            } else {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Esta Habitación no tiene un Checking",
                    showConfirmButton: false,
                    timer: 1500,
                });
                setTimeout(function () {
                    window.location.href = "vistaPrincipal";
                }, 1600);
            }
        });
    });
});
