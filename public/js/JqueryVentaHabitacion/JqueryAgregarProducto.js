$(document).ready(function () {
    var carrito = [];
    var total = 0;
    var cantidad = 1;

    function obtenerCantidadEnCarrito(productoId) {
        return carrito.reduce(function (acumulado, item) {
            if (parseInt(item.id) === parseInt(productoId)) {
                return acumulado + parseInt(item.cantidad);
            }
            return acumulado;
        }, 0);
    }

    function actualizarTabla() {
        // Vaciar el cuerpo de la tabla.
        $("#carrito-body").html("");

        // Iterar a través de los productos en el carrito y agregarlos a la tabla.
        carrito.forEach(function (producto) {
            $("#carrito-body").append(
                "<tr data-id='" +
                    producto.id +
                    "'><td>" +
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

    function getProductosEnTablaHabitacion() {
        var productos = [];

        $(".listaProductos tr").each(function () {
            var fila = $(this);
            var productoId = fila.data("producto-id");
            var nombre = fila.data("producto-nombre") || fila.find("td:eq(1)").text();

            if (productoId) {
                productos.push({
                    id: productoId,
                    nombre: nombre,
                });
            }
        });

        return productos;
    }

    function actualizarResumenProductoModal(precio, stockGeneral, stockHabitacion) {
        var precioFormateado = parseFloat(precio || 0).toFixed(2);
        var stockGeneralFormateado = parseFloat(stockGeneral || 0);
        var stockHabitacionFormateado = parseFloat(stockHabitacion || 0);

        $("#precioProducto").val(precioFormateado);
        $("#precioProductoDisplay").text(precioFormateado);
        $("#stockGeneralProductoDisplay").text(stockGeneralFormateado);
        $("#stockHabitacionProducto").val(stockHabitacionFormateado);
        $("#stockHabitacionProductoDisplay").text(stockHabitacionFormateado);
        $("#stockHabitacionTexto").text("Stock disponible en la habitacion: " + stockHabitacionFormateado);
    }

    function refrescarResumenProductoSeleccionado() {
        var productoId = $("#productos").val();

        if (!productoId) {
            actualizarResumenProductoModal(0, 0, 0);
            return;
        }

        $.get("catProductos/showId/" + productoId, function (dataProducto) {
            var stockHabitacionActual = parseFloat(
                $("#productos option[value='" + productoId + "']").attr("data-stock")
            ) || 0;

            actualizarResumenProductoModal(
                dataProducto.precioventa,
                dataProducto.stock,
                stockHabitacionActual
            );
        });
    }

    function reponerDesdeGeneral(productoId, nombreProducto, cantidadSolicitada) {
        var habitacionId = $("#numHabitacion").val();
        var cantidad = parseInt(cantidadSolicitada, 10);

        if (!productoId) {
            Swal.fire({
                icon: "warning",
                title: "Producto no valido",
                text: "No se pudo identificar el producto para reponer.",
            });
            return;
        }

        if (isNaN(cantidad) || cantidad < 1) {
            Swal.fire({
                icon: "warning",
                title: "Cantidad invalida",
                text: "La cantidad debe ser mayor a cero.",
            });
            return;
        }

        $.get("catProductos/showId/" + productoId, function (dataProducto) {
            var stockGeneralActual = parseFloat(dataProducto.stock || 0);

            if (cantidad > stockGeneralActual) {
                Swal.fire({
                    icon: "warning",
                    title: "Stock insuficiente",
                    text: "No hay suficiente stock general para reponer esa cantidad.",
                });
                return;
            }

            Swal.fire({
                title: "Reponer desde general",
                text: "Se moveran " + cantidad + " unidades de " + nombreProducto + " a la habitacion actual.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Reponer",
                cancelButtonText: "Cancelar",
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "stockProductos/transferir/" + productoId,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        habitacion_id: habitacionId,
                        cantidad: cantidad,
                    },
                    success: function (data) {
                        var stockActualizado = parseFloat(data.stock_habitacion || 0);

                        var $opcion = $("#productos option[value='" + productoId + "']");
                        if ($opcion.length) {
                            $opcion.attr("data-stock", stockActualizado);
                            $opcion.data("stock", stockActualizado);
                            $opcion.text(data.producto + " -> Stock(" + stockActualizado + ")");
                        }

                        var productoSeleccionadoActual = $("#productos").val();
                        var $selectProductos = $("#productos");
                        if ($selectProductos.hasClass("select2-hidden-accessible")) {
                            $selectProductos.select2("destroy");
                        }
                        $selectProductos.select2({
                            dropdownParent: $selectProductos.parent(),
                        });
                        $selectProductos.val(productoSeleccionadoActual).trigger("change");

                        Swal.fire({
                            icon: "success",
                            title: "Stock repuesto",
                            html:
                                "<strong>Producto:</strong> " + data.producto +
                                "<br><strong>Habitacion:</strong> " + data.habitacion +
                                "<br><strong>Almacen general:</strong> " + data.stock_general +
                                "<br><strong>Stock habitacion:</strong> " + data.stock_habitacion +
                                "<br><strong>Total:</strong> " + data.stock_total,
                        });

                        if ($("#productos").val() == productoId) {
                            actualizarResumenProductoModal(
                                dataProducto.precioventa,
                                data.stock_general,
                                stockActualizado
                            );
                        }
                        $("#cantidadReponerGeneral").val(1);
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "No se pudo reponer",
                            text: xhr.responseJSON && xhr.responseJSON.message
                                ? xhr.responseJSON.message
                                : "Ocurrio un error al mover el stock.",
                        });
                    },
                });
            });
        });
    }

    // Evento para agregar un registro al carrito.
    $("#carritoIcon").click(function (e) {
        e.preventDefault();

        var productoId = $("#productos").val();
        var stockDisponible = parseInt($("#productos option:selected").attr("data-stock")) || 0;
        var cantidadSolicitada = parseInt($("#cantidadProducto").val());
        var cantidadEnCarrito = obtenerCantidadEnCarrito(productoId);

        if (!productoId || stockDisponible <= 0) {
            Swal.fire({
                icon: "warning",
                title: "Sin stock en habitación",
                text: "Este producto no tiene stock disponible en la habitación.",
            });
            return;
        }

        if (cantidadEnCarrito + cantidadSolicitada > stockDisponible) {
            Swal.fire({
                icon: "warning",
                title: "Stock insuficiente",
                text: "La cantidad supera el stock disponible en la habitación.",
            });
            return;
        }

        producto = {
            id: productoId,
            nombre: $("#productos option:selected").data("nombre") || $("#productos option:selected").text(),
            precio: parseFloat($("#precioProducto").val()),
            cantidad: cantidadSolicitada,
            subtotal:
                parseFloat($("#precioProducto").val()) *
                cantidadSolicitada,
            stockHabitacion: stockDisponible,
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

                        $.get("catProductos/showHabitacion/" + $("#numHabitacion").val(), function (data) {
                            console.log(data);
                            $("#productos").html(``);
                            if (data.length === 0) {
                                Swal.fire({
                                    icon: "warning",
                                    title: "Sin stock",
                                    text: "No hay productos disponibles en el almacén de esta habitación.",
                                });
                                return;
                            }

                            $.each(data, function (index, item) {
                                $("#productos").html(
                                    $("#productos").html() +
                                `<option value="${item.id}" data-nombre="${item.nombre}" data-stock="${item.stock_habitacion}"> ${item.nombre} -> Stock(${item.stock_habitacion}) </option>`
                                );
                            });

                            var primerProductoId = $("#productos option:first").val();
                            $("#productos").val(primerProductoId);
                            refrescarResumenProductoSeleccionado();
                            $("#carrito-body").html("");
                            $("#cantidadProducto").val(1);
                            $("#cantidadReponerGeneral").val(1);
                            $("#modalVentaProducto").modal("show");
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

    $(document).on("change", "#productos", function () {
        refrescarResumenProductoSeleccionado();
    });

    $(document).on("click", ".btn-reponer-rapido-producto", function (e) {
        e.preventDefault();
        reponerDesdeGeneral($(this).data("producto-id"), $(this).data("producto-nombre") || "el producto");
    });

    $(document).on("click", "#btnReponerDesdeGeneralLista", function (e) {
        e.preventDefault();

        var productos = getProductosEnTablaHabitacion();
        if (!productos.length) {
            Swal.fire({
                icon: "warning",
                title: "Sin productos",
                text: "No hay productos agregados en esta habitacion para reponer.",
            });
            return;
        }

        var opciones = "";
        productos.forEach(function (producto) {
            opciones += '<option value="' + producto.id + '">' + producto.nombre + '</option>';
        });

        Swal.fire({
            title: "Reponer desde general",
            html:
                '<div class="text-start mb-2">Selecciona el producto a reponer:</div>' +
                '<select id="swalProductoReponer" class="swal2-select">' + opciones + "</select>",
            showCancelButton: true,
            confirmButtonText: "Reponer 1",
            cancelButtonText: "Cancelar",
            focusConfirm: false,
            preConfirm: function () {
                return $("#swalProductoReponer").val();
            },
        }).then(function (result) {
            if (!result.isConfirmed || !result.value) {
                return;
            }

            var productoSeleccionado = productos.find(function (item) {
                return String(item.id) === String(result.value);
            });

            reponerDesdeGeneral(result.value, productoSeleccionado ? productoSeleccionado.nombre : "el producto");
        });
    });

    $("#btnReponerDesdeGeneral").on("click", function (e) {
        e.preventDefault();

        var productoId = $("#productos").val();
        var nombreProducto = $("#productos option:selected").data("nombre") || $("#productos option:selected").text();
        var cantidad = $("#cantidadReponerGeneral").val();
        reponerDesdeGeneral(productoId, nombreProducto, cantidad);
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
                id: fila.data("id"),
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
                                    ` <tr id="${item.id}" data-producto-id="${item.producto_id || ""}" data-producto-nombre="${item.nombre}">
                                    <td>
                                <a href="javascript:void(0)" onclick="eliminarProductoAgregado(${
                                    item.id
                                })" title="Eliminar producto">
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

