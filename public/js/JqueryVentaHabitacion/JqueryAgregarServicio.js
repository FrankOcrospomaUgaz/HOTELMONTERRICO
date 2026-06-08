$("#agregarServicio").click(function (e) {
    e.preventDefault();

    $.get("movimiento/show/" + $("#numHabitacion").val(), function (data) {
        console.log(data);
        if (data != "null") {
            $.get("catServicios/show", function (data) {
                console.log(data);
                $("#servicios").html(``);
                $.each(data, function (index, item) {
                    if (item.tipo != "Tiempo") {
                        $("#servicios").html(
                            $("#servicios").html() +
                                `<option value="${item.id}"> ${item.nombre}</option>`
                        );
                    }
                });
                $.get(
                    "catServicios/showId/" + $("#servicios").val(),
                    function (data) {
                        $("#precioServicio").val(data.precioventa);
                        $("#modalVentaServicio").modal("show");
                    }
                );
            });
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

$(document).ready(function () {
    $("#servicios").change(function () {
        $.get("catServicios/showId/" + $("#servicios").val(), function (data) {
            $("#precioServicio").val(data.precioventa);
        });
    });
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
                    $("#descuento-" + id).val(result.value + ".00");
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

    $(document).on("click", "#cerrarModalVentaServicio", function () {
        $("#modalVentaServicio").modal("hide");
    });

    // <!-- CREAR NUEVA REGISTRO-->
    $("#registroVentaServicio")[0].reset(); //limpiar campos

    $("#registroVentaServicio").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("idMovimiento", $("#idMovimiento").val());

        $.get("movimiento/show/" + $("#numHabitacion").val(), function (data) {
            console.log(data);
            if (data != "null") {
                $.ajax({
                    type: "POST",
                    url: "detalleMovimiento/guardarServicio",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response) {
                            $("#registroVentaServicio")[0].reset(); //limpiar campos
                            console.log(response);
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Registro Guardado con Exito",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            $("#theadServicios").removeClass("d-none");
                            $.get(
                                "detalleMovimiento/showDetalleServicios/" +
                                    response.detalleMovimiento.movimiento_id,
                                function (data) {
                                    console.log(data);
                                    $(".listaServicios").html(``);
                                    var acciones = "<td>-</td>";
                                    $.each(data, function (index, item) {
                                        if (index == 0) {
                                            acciones = "<td>-</td>";
                                        } else {
                                            acciones = `<td><a href="javascript:void(0)" onclick="eliminarProductoAgregado(${item.id})">
            <i class="fa-solid fa-trash " style="color: #0047c2;"></i> </a></td>`;
                                        }
                                        $(".listaServicios").html(
                                            $(".listaServicios").html() +
                                                ` <tr id="${item.id}" >
                                                ` +
                                                acciones +
                                                `
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

                            $.get(
                                "detalleMovimiento/cantTotalMovCompra/" +
                                    $("#idMovimiento").val(),
                                function (data) {
                                    if (data.total != null) {
                                        $("#totalMasIgv").text(
                                            "S/" + data.total
                                        );
                                    }
                                }
                            );

                            $("#modalVentaServicio").modal("hide"); //ocultar modal
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
