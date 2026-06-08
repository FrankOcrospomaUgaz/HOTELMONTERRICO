$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="ventaHabitacion?id=${$(
            "#numHabitacion"
        ).val()}">HOTEL | AGREGAR VENTA</a>`
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
    $(".selectTwo").each(function () {
        var $p = $(this).parent();
        $(this).select2({
            dropdownParent: $p,
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
                                "Porcentaje Invalido. Porfavor Ingrese un valor entre 0."
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

    $.get(
        "vistaPrincipal/situacionNumeroHab/" + $("#numHabitacion").val(),
        function (data) {
            console.log("holu");
            console.log(data);
            var tipoHab = data.tipo;
            var currentDate = new Date();
            var formattedDate =
                currentDate.getDate() +
                "/" +
                (currentDate.getMonth() + 1) +
                "/" +
                currentDate.getFullYear();
            $("#fechaingresoI").text(formattedDate);
            $("#estadoI").text(data.situacion);
            $("#tipoI").text(data.tipo);

            if (data.situacion == "Ocupada") {
                //ESTADO - HABITACION == OCUPADA
                $("#estadoI").text(data.situacion);
                $(".cajaAccionesVenta").removeClass("d-none");
                $(".cajaProductos").removeClass("d-none");
                $("#irDetalleHabitacion").removeClass("d-none");
                $("#agregarServicio").removeClass("d-none");
                $("#cajaAgregar").removeClass("d-none");
                $.get(
                    "movimiento/show/" + $("#numHabitacion").val(),
                    function (data) {
                        console.log(data);
                        console.log("xdss");
                        $("#fechaingresoI").text(data.fechaingreso);
                        $("#fechasalidaI").text(data.fechaSalida);
                        $("#idMovimiento").val(data.id);
                        var nombreCompleto =
                            data.nombreCliente +
                            " " +
                            (data.apellidopaterno || "") +
                            " " +
                            (data.apellidomaterno || "");
                        $("#nombreClienteI").text(nombreCompleto);

                        $.get(
                            "detalleMovimiento/showDetalleProductos/" + data.id,
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
                                            <td style="width:300px">${
                                                item.comentario == null
                                                    ? "-"
                                                    : item.comentario
                                            }</td>
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
                            "detalleMovimiento/showDetalleServicios/" + data.id,
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
                            "detalleMovimiento/cantTotalMovCompra/" + data.id,
                            function (data) {
                                if (data.total != null) {
                                    $("#totalMasIgv").text("S/" + data.total);
                                }
                            }
                        );
                    }
                );
                //CAJA PRODUCTOS
            } else {
                //CAJA CLIENTE ACTIVA
                $("#agregarCliente").click(function (e) {
                    $("#registroUsuario")[0].reset();
                    $(".error-message").attr(
                        "class",
                        "error-message ajuste d-none"
                    ); //Desabilita Mensaje error
                    $(".CajaRUC").addClass("d-none");
                    $(".cajaUsuario").addClass("d-none");
                    $(".CajaDNI").removeClass("d-none");
                    $("#razonsocial").val("");
                    $("#direccion").val("");
                    // LLENAR TIPOS DE USUARIO
                    $.get("usuarios/create", function (data) {
                        $("#tipoUsuario").html("");
                        $.each(data.roles, function (index, item) {
                            $("#tipoUsuario").html(
                                $("#tipoUsuario").html() +
                                    `<option value="${item.id}"> ${item.name}</option>`
                            );
                        });
                    });

                    $.get("rolPersona/show", function (data) {
                        $(".cajaCheckBoxRoles").html("");
                        $.each(data, function (index, item) {
                            if (index == 3) {
                                $(".cajaCheckBoxRoles").append(
                                    `<label>Cliente</label> <div class="form-check form-check-inline d-none">
                                        <input class="form-check-input" checked="true" type="checkbox" id="` +
                                        item.descripcion +
                                        `"  name="roles[]" value="` +
                                        item.id +
                                        `">
                                        <label class="form-check-label" for="` +
                                        item.descripcion +
                                        `">` +
                                        item.descripcion +
                                        `</label>
                    </div>`
                                );
                            }
                        });
                        $("#Usuario").change(function () {
                            if ($("#Usuario").is(":checked")) {
                                $(".cajaUsuario").removeClass("d-none");
                            } else {
                                $(".cajaUsuario").addClass("d-none");
                            }
                        });
                        $("#modalNuevoUsuario").modal("show");
                    });
                });

                $(".cajaCliente").removeClass("d-none");

                $.get("ventaHabitacion/show", function (data) {
                    //OBTENER CLIENTES
                    console.log(data);

                    $("#clientes").html(``);

                    $.each(data, function (index, item) {
                        if (item.dni != null) {
                            $("#clientes").html(
                                $("#clientes").html() +
                                    `<option value="${item.id}"> ${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                            );
                        } else if (item.ruc != null) {
                            $("#clientes").html(
                                $("#clientes").html() +
                                    `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                            );
                        } else {
                            $("#clientes").html(
                                $("#clientes").html() +
                                    `<option value="${item.id}" selected>${item.nombres}</option>`
                            );
                        }
                    });
                });

                var idServicio = tipoHab == "VIP" ? 4 : (tipoHab == "Normal" ? 1 : 21);
                
                $.get("catServicios/showId/" + idServicio, function (data) {
                    $(".listaServicios").html(``);
                    var servicio = data;
                    $(".listaServicios").html(
                        $(".listaServicios").html() +
                            ` <tr id="${servicio.id}" >
                                    <td>-</td>
                                    <td>${servicio.nombre}</td>
                                    <td>1</td>
                                    <td>${servicio.precioventa}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>S/ ${servicio.precioventa * 1}</td>

                                </tr>`
                    );

                    $("#totalMasIgv").text(
                        `S/${parseFloat(servicio.precioventa).toFixed(1) * 1}`
                    );
                });

                //SE REGISTRA EL MOVIMIENTO DE MOVIMIENTO

                //SE RELLENAN LOS CAMPOS
                $("#registroNuevoMovimientoAtencion").submit(function (e) {
                    e.preventDefault();
                    $("#enviaCheckInBtn").prop("disabled", true);
                    var formData = new FormData(this);

                    $.get(
                        "movimiento/show/" + $("#numHabitacion").val(),
                        function (data) {
                            console.log(data);
                            if (data == "null") {
                                $.ajax({
                                    type: "POST",
                                    url: "ventaHabitacion/guardar",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function (response) {
                                        if (response) {
                                            console.log(response.fechaingreso);
                                            $(
                                                "#registroNuevoMovimientoAtencion"
                                            )[0].reset(); //limpiar campos

                                            Swal.fire({
                                                position: "center",
                                                icon: "success",
                                                title: "Registro Guardado con Éxito",
                                                showConfirmButton: false,
                                                timer: 1500,
                                            }).then(function () {
                                                window.location.href =
                                                    "ventaHabitacion?id=" +
                                                    $("#numHabitacion").val();
                                            });
                                        } else {
                                            alert("nO");
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        if (xhr.status === 422) {
                                            var errors =
                                                xhr.responseJSON.errors;

                                            $(".error-message").removeClass(
                                                "d-none"
                                            ); //HaBilitar mensaje error
                                            $.each(
                                                errors,
                                                function (field, messages) {
                                                    var element = $(
                                                        '[name="' + field + '"]'
                                                    );
                                                    var container = element
                                                        .closest(".form-group")
                                                        .find(".error-message");
                                                    container.text(messages[0]);
                                                }
                                            );
                                        } else {
                                            // maneja otros errores aquí
                                        }
                                    },
                                });
                            } else {
                                Swal.fire({
                                    position: "center",
                                    icon: "error",
                                    title: "Esta Habitación ya tiene un Checking",
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
            }
        }
    );
});
