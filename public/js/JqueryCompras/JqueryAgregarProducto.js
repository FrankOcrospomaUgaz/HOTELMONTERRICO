$("#tituloPagina").html(
    `<a class="nav-link active" href="">HOTEL | COMPRAS</a>`
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
$(document).on("click", "#cerrarModalUsuario", function () {
    $("#modalNuevoUsuario").modal("hide");
});

$("#agregarProducto").click(function (e) {
    if ($("#operacionI").text() == "Doc Almacen") {
        $(".tipoIngresoSalida").removeClass("d-none");
    }

    $.get("catProductos/show", function (data) {
        $("#productos").html(``);
        $.each(data, function (index, item) {
            $("#productos").html(
                $("#productos").html() +
                    `<option value="${item.id}"> ${item.nombre}  -> Stock(${item.stock}) </option>`
            );
        });
        $.get("catProductos/showId/" + $("#productos").val(), function (data) {
            $("#precioUnitario").val(data.precioventa);
            $("#modalAgregarProdAlmacen").modal("show");
        });
    });
});

$(document).ready(function () {
    $("#productos").change(function () {
        // Obtener el valor seleccionado
        var selectedValue = $(this).val();
        console.log(selectedValue);
        $.get("catProductos/showId/" + $("#productos").val(), function (data) {
            $("#precioUnitario").val(data.preciocompra);
        });
    });
});

$(document).on("click", "#cerrarModalAddProducto", function () {
    $("#modalAgregarProdAlmacen").modal("hide");
});

$("#registroAddProductoCompra").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    //ENVIAR A GUARDAR REGISTROS HIJOS

    var valid = true;

    if (
        $("#cantidadProductoEd").val() <= 0 ||
        $("#cantidadProductoEd").val() === ""
    ) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "La Cantidad Producto debe ser mayor a 0 y no puede estar vacía",
            showConfirmButton: false,
            timer: 1500,
        });
        valid = false;
    }
    if ($("#precioUnitario").val() <= 0 || $("#precioUnitario").val() === "") {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "El P. Unitario debe ser mayor a 0 y no puede estar vacía",
            showConfirmButton: false,
            timer: 1500,
        });
        valid = false;
    }

    if (valid) {
        $.ajax({
            type: "POST",
            url: "movCompras/guardarDetalle/" + $("#idMovCompra").val(),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#modalAgregarProdAlmacen").modal("hide");
                    $.get(
                        "movCompra/show/" + $("#idMovCompra").val(),
                        function (data) {
                            // Aquí va tu código existente para obtener los datos

                            // Inicializar DataTable
                            var tablaProductosComprados = $(
                                "#tablaProductosComprados"
                            ).DataTable({
                                destroy: true,
                                language: {
                                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json",
                                },
                            });

                            // Limpiar tabla antes de agregar nuevos datos
                            tablaProductosComprados.clear().draw();

                            // Agregar filas a la tabla con los datos obtenidos
                            $.each(
                                data.detalleMovimientos,
                                function (index, item) {
                                    var total = (
                                        item.preciocompra * item.cantidad
                                    ).toFixed(2);

                                    tablaProductosComprados.row
                                        .add([
                                            `<a href="javascript:void(0)" onclick="eliminarProductoAgregado(${item.id})"><i class="fa-solid fa-trash" style="color: #0047c2;"></i></a>`,
                                            item.nombre,
                                            item.cantidad,
                                            item.preciocompra,
                                            total,
                                            item.comentario,
                                        ])
                                        .nodes() // Obtener el nodo HTML de la fila agregada
                                        .to$() // Convertirlo a objeto jQuery
                                        .attr("id", item.id); // Establecer el atributo id con el valor de item.id
                                }
                            );

                            $.get(
                                "detalleMovimiento/cantTotalMovComprado/" +
                                    $("#idMovCompra").val(),
                                function (data) {
                                    var totalC =
                                        data.total == null
                                            ? "0.00"
                                            : data.total;
                                    $("#totalMasIgv").text("S/" + totalC);
                                }
                            );

                            // Resto de tu código aquí
                        }
                    );
                } else {
                    alert("nO");
                }
            },
        });
    }
});

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

function verDetalleCompra() {
    $(".cajaProveedor").toggleClass("d-none");
}

$(document).ready(function () {
    $.get(
        "detalleMovimiento/cantTotalMovComprado/" + $("#idMovCompra").val(),
        function (data) {
            var totalC = data.total == null ? "0.00" : data.total;
            $("#totalMasIgv").text("S/" + totalC);
        }
    );
});

function agregarProveedor() {
    $("#registroUsuario")[0].reset();
    $(".error-message").attr("class", "error-message ajuste d-none"); //Desabilita Mensaje error
    if ($("#tipoDocumentos").val() == 2) {
        console.log("cambiooo FACTURA");
        $(".CajaDNI").addClass("d-none");
        $(".CajaRUC").removeClass("d-none");
        $("#nombre").val("");
        $("#apellPaterno").val("");
        $("#apellMaterno").val("");
        $("#selectDNI-RUC").val("RUC");
        $("#razonsocial").prop("required", true);
        $("#nombre").prop("required", false);
        $("#apellPaterno").prop("required", false);
        $("#apellMaterno").prop("required", false);
    } else {
        $("#selectDNI-RUC").val("DNI");
        $(".CajaRUC").addClass("d-none");
        $(".CajaDNI").removeClass("d-none");
        $("#razonsocial").val("");
        $("#direccion").val("");
        $("#nombre").prop("required", true);
        $("#apellPaterno").prop("required", true);
        $("#apellMaterno").prop("required", true);
        $("#razonsocial").prop("required", false);
    }

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
            if (index == 2) {
                $(".cajaCheckBoxRoles").append(
                    `<label>` +
                        item.descripcion +
                        `</label> <div class="form-check form-check-inline d-none">
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
        // $("#Usuario").change(function () {
        //     if ($("#Usuario").is(":checked")) {
        //         $(".cajaUsuario").removeClass("d-none");
        //     } else {
        //         $(".cajaUsuario").addClass("d-none");
        //     }
        // });
        $("#selectDNI-RUC").val("RUC").change();

        $("#modalNuevoUsuario").modal("show");
    });
}

// -------------------------------------
// ------------------------------------

$(document).ready(function () {
    $(document).on("click", "#dniBuscar", function () {
        $("#email").val("");
        $("#telefono").val("");

        if ($("#selectDNI-RUC").val() == "DNI") {
            $("#nombre").val("");
            $("#apellPaterno").val("");
            $("#apellMaterno").val("");

            var dni = $("#dni").val();
            var dniRegex = /^\d{8}$/; // Expresión regular que valida 8 dígitos

            if (dniRegex.test(dni)) {
                console.log("Válido: " + dni);
                $.get("usuarios/buscarDNI/" + $("#dni").val(), function (data) {
                    if (data.mensaje) {
                        $(".error-message").removeClass("d-none"); //HaBilitar mensaje error
                        $("#nombre").val("");
                        $("#apellPaterno").val("");
                        $("#apellMaterno").val("");
                        var element = $("[name=dni]");
                        var container = element
                            .closest(".form-group")
                            .find(".error-message");
                        container.text(data.mensaje);
                    } else {
                        $("#nombre").val(data.nombres);
                        $("#apellPaterno").val(data.apepat);
                        $("#apellMaterno").val(data.apemat);
                    }
                }).fail(function (xhr, status, error) {
                    console.log("Hubo un error: " + error);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "DNI inválido",
                    text: "El DNI debe tener 8 dígitos.",
                });
            }
        } else {
            $("#razonsocial").val("");
            $("#direccion").val("");

            var ruc = $("#dni").val();
            var rucRegex = /^\d{11}$/; // Expresión regular que valida 11 dígitos
            if (rucRegex.test(ruc)) {
                console.log("RUC válido: " + ruc);
                $.get("usuarios/buscarRUC/" + $("#dni").val(), function (data) {
                    if (data.mensaje) {
                        $(".error-message").removeClass("d-none"); //HaBilitar mensaje error
                        $("#razonsocial").val("");
                        $("#direccion").val("");
                        var element = $("[name=dni]");
                        var container = element
                            .closest(".form-group")
                            .find(".error-message");
                        container.text(data.mensaje);
                    } else {
                        $("#razonsocial").val(data.RazonSocial);
                        $("#direccion").val(data.Direccion);
                    }
                }).fail(function (xhr, status, error) {
                    console.log("Hubo un error: " + error);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "RUC inválido",
                    text: "El RUC debe tener 11 dígitos.",
                });
            }
        }
    });

    $("#selectDNI-RUC").change(function () {
        $(".error-message").attr("class", "error-message ajuste d-none"); //Desabilita Mensaje error
        $("#dni").val("");
        if ($("#selectDNI-RUC").val() == "DNI") {
            $(".CajaRUC").addClass("d-none");
            $(".CajaDNI").removeClass("d-none");
            $("#razonsocial").val("");
            $("#direccion").val("");
            $("#nombre").prop("required", true);
            $("#apellPaterno").prop("required", true);
            $("#apellMaterno").prop("required", true);
            $("#razonsocial").prop("required", false);
        } else {
            $(".CajaDNI").addClass("d-none");
            $(".CajaRUC").removeClass("d-none");
            $("#nombre").val("");
            $("#apellPaterno").val("");
            $("#apellMaterno").val("");
            $("#razonsocial").prop("required", true);
            $("#nombre").prop("required", false);
            $("#apellPaterno").prop("required", false);
            $("#apellMaterno").prop("required", false);
        }
    });
});

$(document).ready(function () {
    // <!-- CREAR NUEVA REGISTRO-->
    $("#registroUsuario")[0].reset(); //limpiar campos

    $("#registroUsuario").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        if ($("#selectDNI-RUC").val() == "DNI") {
            var dni = $("#dni").val();
            var dniRegex = /^\d{8}$/; // Expresión regular que valida 8 dígitos

            if (dniRegex.test(dni)) {
                Swal.fire({
                    title: "CONFIRMACIÓN DE DATOS",
                    icon: "warning",
                    html:
                        `<p>Nombres: <strong>${$(
                            "#nombre"
                        ).val()}</strong></p>` +
                        `<p>Apellido: <strong>${$("#apellPaterno").val()} ${$(
                            "#apellMaterno"
                        ).val()}</strong></p>`,

                    showCancelButton: true,
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        agregarUsuario(formData);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "DNI inválido",
                    text: "El DNI debe tener 8 dígitos.",
                });
            }
        } else if ($("#selectDNI-RUC").val() == "RUC") {
            var ruc = $("#dni").val();
            var rucRegex = /^\d{11}$/; // Expresión regular que valida 11 dígitos

            if (rucRegex.test(ruc)) {
                Swal.fire({
                    title: "CONFIRMACIÓN DE DATOS",
                    icon: "warning",
                    html:
                        `<p>Razón Social: <strong>${$(
                            "#razonsocial"
                        ).val()}</strong></p>` +
                        `<p>Dirección: <strong>${$(
                            "#direccion"
                        ).val()}</strong></p>`,

                    showCancelButton: true,
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        agregarUsuario(formData);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "RUC inválido",
                    text: "El RUC debe tener 11 dígitos.",
                });
            }
        }
        $("#razonsocial").prop("required", false);
        $("#nombre").prop("required", false);
        $("#apellPaterno").prop("required", false);
        $("#apellMaterno").prop("required", false);
    });
});

function agregarUsuario(formData) {
    $.ajax({
        type: "POST",
        url: "usuarios/guardar",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response) {
                console.log('respuesta');
                console.log(response);
                $("#registroUsuario")[0].reset(); //limpiar campos

                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Registro Guardado con Exito",
                    showConfirmButton: false,
                    timer: 1500,
                });

                $("#modalNuevoUsuario").modal("hide"); //ocultar modal

                $.get("showProveedores", function (data) {
                    //OBTENER CLIENTES
                    ////console.log(data);
                    console.log(data);
                    $("#proveedores").html(``);

                    $.each(data, function (index, item) {
                        if (item.dni != null) {
                            $("#proveedores").html(
                                $("#proveedores").html() +
                                    `<option value="${item.id}">${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                            );
                        } else if (item.ruc != null) {
                            $("#proveedores").html(
                                $("#proveedores").html() +
                                    `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                            );
                        } else {
                            $("#proveedores").html(
                                $("#proveedores").html() +
                                    `<option value="${item.id}">${item.nombres}</option>`
                            );
                        }
                        if (index === data.length - 1) {
                            console.log("Último índice:", index);
                        }
                    });
                    console.log( $("#proveedores").html());
                    $("#proveedores").val(response.id);
                    $("#proveedores").change();
                });
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
}
