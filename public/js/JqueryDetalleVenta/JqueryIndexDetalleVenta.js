$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="detalleHabitacion?id=${$(
            "#numHabitacion"
        ).val()}">HOTEL | PAGAR HABITACION</a>`
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
    $.get("movimiento/show/" + $("#numHabitacion").val(), function (data) {
        console.log(data);
        if (data != "null") {
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
    $(document).on("click", "#cerrarModalUsuario", function () {
        $("#modalNuevoUsuario").modal("hide");
    });
});
$(".selectTwo").each(function () {
    var $p = $(this).parent();
    $(this).select2({
        dropdownParent: $p,
    });
});

$(document).ready(function () {
    $("#irAgregarVenta").click(function (e) {
        e.preventDefault();
        window.location.href =
            "ventaHabitacion?id=" + $("#numHabitacion").val();
    });

    $.get("detalleMovimiento/obtenerDocumentosVenta", function (data) {
        //OBTENER tipodocumentos de venta
        console.log(data);

        $("#tipoDocumentos").html(``);

        $.each(data, function (index, item) {
            $("#tipoDocumentos").html(
                $("#tipoDocumentos").html() +
                    `<option value="${item.id}"> ${item.nombre}</option>`
            );
        });

        $("#tipoDocumentos").val(5);
        $("#tipoDocumentos").change();
    });

    $.get("movimiento/show/" + $("#numHabitacion").val(), function (data) {
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

        $("#estadoI").text(data.estadoHabitacion);
        $("#tipoI").text(data.tipo);

        $.get(
            "detalleMovimiento/showDetalleProductos/" + data.id,
            function (data) {
                console.log(data);
                $(".listaProductos").html(``);

                $.each(data, function (index, item) {
                    $(".listaProductos").html(
                        $(".listaProductos").html() +
                            ` <tr id="${item.id}" >
                            <td>${index + 1}</td>
                                            <td>${item.nombre}</td>
                                            <td>${item.cantidad}</td>
                                            <td>${item.precioventa}</td>
                                            <td>${item.comentario}</td>
                                            <td>${item.descuento}
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
            }
        );
        $.get(
            "detalleMovimiento/showDetalleServicios/" + data.id,
            function (data) {
                console.log(data);
                $(".listaServicios").html(``);

                $.each(data, function (index, item) {
                    $(".listaServicios").html(
                        $(".listaServicios").html() +
                            ` <tr id="${item.id}" >
                            <td>${index + 1}</td>
                                            <td>${item.nombre}</td>
                                            <td>${item.cantidad}</td>
                                            <td>${item.precioventa}</td>
                                            <td>${item.comentario}</td>
                                            <td>${item.descuento}
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
            }
        );

        $.get(
            "detalleMovimiento/cantTotalMovCompra/" + data.id,
            function (data) {
                if (data.total != null) {
                    $("#totalMasIgv").text(data.total);
                    $("#totaPagado").text(data.total);

                    $("#pagoEfectivo").val(data.total);
                    $("#montoTotal").val(data.total);
                }
            }
        );
    });

    $(document).ready(function () {
        // Agregar un evento de doble clic a todos los campos de entrada
        $(".pagoDealle").dblclick(function () {
            // Establecer todos los valores en 0
            $(".pagoDealle").val("0.00");
            // Establecer el valor en 100 solo para el campo en el que se hizo doble clic

            console.log($("#totalMasIgv").text());
            $(this).val($("#montoTotal").val());
            calcularVuelto();
            verificarMontoTarjeta();
            switch ($("#tipoDocumentos").val()) {
                case "1":
                    $.get(
                        "detalleMovimiento/getNumTipoDocumento/1",
                        function (data) {
                            $("#numTipoDocumento").val(
                                "B003-" + data.toString().padStart(8, "0")
                            );
                        }
                    );
                    break;
                case "2":
                    $.get(
                        "detalleMovimiento/getNumTipoDocumento/2",
                        function (data) {
                            $("#numTipoDocumento").val(
                                "F003-" + data.toString().padStart(8, "0")
                            );
                        }
                    );

                    break;
                case "5":
                    $.get(
                        "detalleMovimiento/getNumTipoDocumento/5",
                        function (data) {
                            $("#numTipoDocumento").val(
                                "T003-" + data.toString().padStart(8, "0")
                            );
                        }
                    );
                    break;
            }
        });

        $(".pagoDealle").blur(function () {
            var valor = $(this).val();
            if (valor === "" || parseFloat(valor) === 0) {
                $(this).val("0.00");
            }
        });

        var totalAPagarCampo = document.getElementById("totalMasIgv");
        var efectivoCampo = document.getElementById("totaPagado");
        var vueltoCampo = document.getElementById("vueltoPago");

        var entradas = document.querySelectorAll(".pagoDealle");

        // Función para calcular la suma
        function calcularSuma() {
            var suma = 0;

            entradas.forEach(function (entrada) {
                var valor = parseFloat(entrada.value);
                if (!isNaN(valor)) {
                    suma += valor;
                }
            });

            return suma;
        }

        // Función para calcular y mostrar el vuelto
        function calcularVuelto() {
            var totalAPagar = parseFloat(totalAPagarCampo.textContent);
            var totalPagado = calcularSuma();

            var vuelto = totalPagado - totalAPagar;

            if (isNaN(vuelto)) {
                vuelto = 0.0;
            }

            // Muestra el total pagado y el vuelto
            efectivoCampo.textContent = totalPagado.toFixed(2);
            vueltoCampo.textContent = vuelto.toFixed(2);

            if (vuelto < 0) {
                vueltoCampo.classList.add("negativo");
            } else {
                vueltoCampo.classList.remove("negativo");
            }
        }

        // Agrega un controlador de eventos "input" a cada entrada
        entradas.forEach(function (entrada) {
            entrada.addEventListener("input", function () {
                calcularVuelto();
            });
        });

        // Calcula y muestra el vuelto inicial
        calcularVuelto();

        function verificarMontoTarjeta() {
            var montoTarjeta = parseFloat($("#pagoTarjeta").val());
            var montoYape = parseFloat($("#pagoYape").val());

            if ($("#tipoDocumentos").val() != 2) {
                if (montoTarjeta !== 0 || montoYape !== 0) {
                    $("#tipoDocumentos option[value='5']").prop(
                        "disabled",
                        true
                    );
                    $("#tipoDocumentos").val("1");
                    switch ($("#tipoDocumentos").val()) {
                        case "1":
                            $.get(
                                "detalleMovimiento/getNumTipoDocumento/1",
                                function (data) {
                                    $("#numTipoDocumento").val(
                                        "B003-" +
                                            data.toString().padStart(8, "0")
                                    );
                                }
                            );
                            break;
                        case "2":
                            $.get(
                                "detalleMovimiento/getNumTipoDocumento/2",
                                function (data) {
                                    $("#numTipoDocumento").val(
                                        "F003-" +
                                            data.toString().padStart(8, "0")
                                    );
                                }
                            );
                            break;
                        case "5":
                            $.get(
                                "detalleMovimiento/getNumTipoDocumento/5",
                                function (data) {
                                    $("#numTipoDocumento").val(
                                        "T003-" +
                                            data.toString().padStart(8, "0")
                                    );
                                }
                            );
                            break;
                    }
                } else {
                    $("#tipoDocumentos option[value='5']").prop(
                        "disabled",
                        false
                    );
                }
            }
        }

        verificarMontoTarjeta();
        $("#pagoTarjeta").on("input", verificarMontoTarjeta);
        $("#pagoYape").on("input", verificarMontoTarjeta);
    });

    //CAJA CLIENTE ACTIVA
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
    $("#clientes").change(function () {
        // $("#nombreCliente").val('VARIOS');

        $.get("usuarios/showId/" + $("#clientes").val(), function (data) {
            console.log(data.persona);
            $("#nombreCliente").val(data.persona);
        });
    });

    $("#tipoDocumentos").change(function () {
        switch ($("#tipoDocumentos").val()) {
            case "1":
                $.get(
                    "detalleMovimiento/getNumTipoDocumento/1",
                    function (data) {
                        $("#numTipoDocumento").val(
                            "B003-" + data.toString().padStart(8, "0")
                        );
                    }
                );
                break;
            case "2":
                $.get(
                    "detalleMovimiento/getNumTipoDocumento/2",
                    function (data) {
                        $("#numTipoDocumento").val(
                            "F003-" + data.toString().padStart(8, "0")
                        );
                    }
                );

                break;
            case "5":
                $.get(
                    "detalleMovimiento/getNumTipoDocumento/5",
                    function (data) {
                        $("#numTipoDocumento").val(
                            "T003-" + data.toString().padStart(8, "0")
                        );
                    }
                );
                break;
        }

        $.get("ventaHabitacion/show", function (data) {
            //OBTENER CLIENTES
            console.log(data);
            $("#clientes").html(``);

            if ($("#tipoDocumentos").val() == 2) {
                $.each(data, function (index, item) {
                    if (item.ruc != null) {
                        $("#clientes").html(
                            $("#clientes").html() +
                                `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                        );
                    }

                    if (item.ruc == null && item.dni == null) {
                        $("#clientes").html(
                            $("#clientes").html() +
                                `<option value="${item.id}" selected>${item.nombres}</option>`
                        );
                    }
                });
            } else {
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
            }
        });
    });

    $("#registroPagoMovimiento").submit(function (e) {
        e.preventDefault();
        console.log($("#clientes").val());
        console.log($("#tipoDocumentos").val());

        if ($("#clientes").val() != 17 || $("#tipoDocumentos").val() != "2") {
            var form = this;

            $.get("cajaChica/apertura/buscarApertura", function (data) {
                if (data == 1) {
                    $.get(
                        "movimiento/show/" + $("#numHabitacion").val(),
                        function (data) {
                            console.log(data);
                            if (data != "null") {
                                var tipoDoc = "";
                                switch ($("#tipoDocumentos").val()) {
                                    case "5":
                                        tipoDoc = "TICKET";
                                        break;
                                    case "2":
                                        tipoDoc = "FACTURA";
                                        break;
                                    default:
                                        tipoDoc = "BOLETA";
                                        break;
                                }

                                Swal.fire({
                                    title: "CONFIRMACIÓN DE ENVIO",
                                    icon: "warning",
                                    html:
                                        `<p>Cliente: <strong>${$(
                                            "#nombreCliente"
                                        ).val()}</strong></p>` +
                                        `<p>Monto: <strong>${$(
                                            "#totalMasIgv"
                                        ).text()}</strong></p>` +
                                        `<p>Tipo de documento: <strong>${tipoDoc}</strong></p>`,
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Sí, Confirmo!",
                                    cancelButtonText: "Cancelar",
                                }).then((resultPago) => {
                                    if (resultPago.isConfirmed) {
                                        var formData = new FormData(form);
                                        formData.append(
                                            "pagoEfectivo",
                                            $("#pagoEfectivo").val()
                                        );
                                        formData.append(
                                            "numTipoDocumento",
                                            $("#numTipoDocumento").val()
                                        );
                                        formData.append(
                                            "pagoTarjeta",
                                            $("#pagoTarjeta").val()
                                        );
                                        formData.append(
                                            "pagoYape",
                                            $("#pagoYape").val()
                                        );
                                        formData.append(
                                            "pagoDeposito",
                                            $("#pagoDeposito").val()
                                        );
                                        formData.append(
                                            "pagoPlin",
                                            $("#pagoPlin").val()
                                        );
                                        formData.append(
                                            "tipoDocumentos",
                                            $("#tipoDocumentos").val()
                                        );
                                        formData.append(
                                            "comentario",
                                            $("#comentario").val()
                                        );
                                        formData.append(
                                            "clientes",
                                            $("#clientes").val()
                                        );
                                        $.ajax({
                                            type: "POST",
                                            url:
                                                "movimiento/pagarMovimientoAtencion/" +
                                                $("#idMovimiento").val(),
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function (response) {
                                                console.log("xd-xd");
                                                console.log(response);
                                                if (response != "error") {
                                                    Swal.fire({
                                                        position: "center",
                                                        icon: "success",
                                                        title: "Registro Guardado con Exito",
                                                        showConfirmButton: false,
                                                        timer: 1500,
                                                    });

                                                    if (
                                                        response.tipoDocumento !=
                                                        5
                                                    ) {
                                                        var funcion_ =
                                                            response.tipoDocumento ==
                                                            1
                                                                ? "enviarBoleta"
                                                                : "enviarFactura";

                                                        var url =
                                                            "/clifacturacion/controlador/contComprobante.php?funcion=" +
                                                            funcion_;

                                                        var clienteBF =
                                                            response.nombresRazonSocial;
                                                        var parametros = {
                                                            funcion: funcion_,
                                                            declarar: "",
                                                            serie: response.movimientoCaja.numero.match(
                                                                /(\d+)/
                                                            )[1],
                                                            numero: response
                                                                .movimientoCaja
                                                                .numero,
                                                            fecha: response.movimientoCaja.fecha.split(
                                                                " "
                                                            )[0],
                                                            total: response
                                                                .movimientoCaja
                                                                .total,
                                                            ruc:
                                                                response.ruc !=
                                                                null
                                                                    ? response.ruc
                                                                    : "-",
                                                            dni: response.dniRuc,
                                                            cliente: clienteBF,
                                                            direccion:
                                                                response.direccion,
                                                            detalle:
                                                                JSON.stringify(
                                                                    response.detalleMovimientos
                                                                ),
                                                        };

                                                        // $.get(url, parametros, function (data) {
                                                        //     // Aquí puedes manejar la respuesta de la solicitud
                                                        //     // Por ejemplo, puedes imprimir la data en la consola
                                                        //     console.log(data);
                                                        // });

                                                        $.ajax({
                                                            type: "POST",
                                                            url: url,
                                                            data: parametros,

                                                            success: function (
                                                                result
                                                            ) {
                                                                console.log(
                                                                    result
                                                                );
                                                                console.log(
                                                                    $(
                                                                        "#numTipoDocumento"
                                                                    ).val()
                                                                );
                                                                $(
                                                                    "#estadoI"
                                                                ).text(
                                                                    "Pagada"
                                                                );
                                                                generarPdf(
                                                                    $(
                                                                        "#numTipoDocumento"
                                                                    ).val()
                                                                );
                                                                // setTimeout(function () {
                                                                //     window.location.href = "listaHab";
                                                                // }, 2000);
                                                            },
                                                            error: function (
                                                                e
                                                            ) {
                                                                generarPdf(
                                                                    $(
                                                                        "#numTipoDocumento"
                                                                    ).val()
                                                                );
                                                                console.log(
                                                                    "error:".e
                                                                );
                                                            },
                                                        });
                                                    } else {
                                                        mostraTicket(
                                                            response
                                                                .movimientoCaja
                                                                .id
                                                        );
                                                    }
                                                } else {
                                                    Swal.fire({
                                                        position: "center",
                                                        icon: "error",
                                                        title: "Los datos no coinciden",
                                                        showConfirmButton: false,
                                                        timer: 1500,
                                                    }).then(() => {
                                                        window.location.href =
                                                            "detalleHabitacion?id=" +
                                                            $(
                                                                "#numHabitacion"
                                                            ).val();
                                                    });
                                                }
                                            },
                                            error: function (
                                                xhr,
                                                status,
                                                error
                                            ) {},
                                        });
                                    }
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
                        }
                    );
                } else {
                    Swal.fire({
                        title: "Debe de Aperturar la Caja",
                        text: "Es necesario para realizar el pago de la habitación",
                        icon: "warning",
                        confirmButtonText: "Ir a Aperturar Caja",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirige a la dirección deseada
                            window.location.href = "cajaChica";
                        } else {
                            // Aquí puedes agregar código adicional si el usuario decide cancelar
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: "Debe seleccionar un cliente",
                text: "Es necesario para realizar la FACTURA",
                icon: "warning",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            });
        }
    });
});

function generarPdf(numTipoDocumento) {
    var url = "/clifacturacion/controlador/obtenerParaTicket.php";
    var parametros = {
        numDocumento: numTipoDocumento,
    };
    $.ajax({
        type: "GET",
        url: url,
        data: parametros,

        success: function (result) {
            // $.get("ticket-pdf/" + result, function (dataSituacion) {
            //     console.log(dataSituacion);
            // });

            console.log(result);

            result = result
            .replace(/\\u00d1/g, "Ñ")
            .replace(/\\u00f1/g, "ñ")
            .replace(/\\u00c1/g, "Á")
            .replace(/\\u00e1/g, "á")
            .replace(/\\u00c9/g, "É")
            .replace(/\\u00e9/g, "é")
            .replace(/\\u00cd/g, "Í")
            .replace(/\\u00ed/g, "í")
            .replace(/\\u00d3/g, "Ó")
            .replace(/\\u00f3/g, "ó")
            .replace(/\\u00da/g, "Ú")
            .replace(/\\u00fa/g, "ú")
            .replace(/\\u00dc/g, "Ü")
            .replace(/\\u00fc/g, "ü")
            .replace(/\\u0027/g, "'")
            .replace(/\\u00a9/g, "©")
            .replace(/\\u00ae/g, "®")
            .replace(/\\u20ac/g, "€")
            .replace(/\\u00b0/g, "°")
            .replace(/\\u00b3/g, "³")
            .replace(/\\u00b2/g, "²")
            .replace(/\\u00b5/g, "µ")
            .replace(/\\u00bc/g, "¼")
            .replace(/\\u00bd/g, "½")
            .replace(/\\u00be/g, "¾")
            .replace(/\\u03c0/g, "π")
            .replace(/\//g, "");

            $.get(
                "ticket-pdf-ticketera/" + result + "/ImpresionNormalBoleta",
                function (data) {
                    console.log(data);
                    window.location.href = "vistaPrincipal";
                }
            ).fail(function (xhr, status, error) {
                console.log("Hubo un error: " + error);
                
            });

            window.open(
                "ticket-pdf/" + result + "/VerPDFNormalBoleta",
                "_blank"
            );
            console.log(result);
            
        },
        error: function (e) {
            console.log("error:".e);
        },
    });
}

function mostraTicket(idMovCierre) {
    console.log("TICKET DE VENTA");

    $.get(
        "ticket-pdf-sinFact-ticketera/" +
            +idMovCierre +
            "/ImpresionNormalTicket",
        function (data) {
            console.log(data);
            window.location.href = "vistaPrincipal";
        }
    ).fail(function (xhr, status, error) {
        console.log("Hubo un error: " + error);
    });

    // Abre el PDF del ticket en una nueva ventana
    window.open(
        "ticket-pdf-sinFact/" + idMovCierre + "/VerPDFImpresionNormalTicket",
        "_blank"
    );

    // // Cambia la URL de la ventana actual a la vistaPrincipal
    
}

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
                $("#registroUsuario")[0].reset(); //limpiar campos

                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Registro Guardado con Exito" ,
                    showConfirmButton: false,
                    timer: 1500,
                });

                $("#modalNuevoUsuario").modal("hide"); //ocultar modal

                if ($("#clientes").length > 0) {
                    $.get("ventaHabitacion/show", function (data) {
                        //OBTENER CLIENTES
                        console.log(data);

                        $("#clientes").html(``);

                        $.each(data, function (index, item) {
                            if (item.dni != null) {
                                $("#clientes").html(
                                    $("#clientes").html() +
                                        `<option value="${item.id}">${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                                );
                            } else if (item.ruc != null) {
                                $("#clientes").html(
                                    $("#clientes").html() +
                                        `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                                );
                            } else {
                                $("#clientes").html(
                                    $("#clientes").html() +
                                        `<option value="${item.id}">${item.nombres}</option>`
                                );
                            }
                            if (index === data.length - 1) {
                                console.log("Último índice:", index);
                            }
                        });
                        $("#clientes").val(response.id);
                        $("#clientes").change();
                    });
                }
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

//quitar mensaje de alidacion AL ESCRIBIR
$(document).ready(function () {
    $(".error-message")
        .closest(".form-group")
        .find("input")
        .on("input", function () {
            $(this).closest(".form-group").find(".error-message").empty();
        });
});
$(document).ready(function () {
    $(".error-messageE")
        .closest(".form-group")
        .find("input")
        .on("input", function () {
            $(this).closest(".form-group").find(".error-messageE").empty();
        });
});

$("#clickAgregarCliente").click(function (e) {
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
        // $("#Usuario").change(function () {
        //     if ($("#Usuario").is(":checked")) {
        //         $(".cajaUsuario").removeClass("d-none");
        //     } else {
        //         $(".cajaUsuario").addClass("d-none");
        //     }
        // });
        $("#modalNuevoUsuario").modal("show");
    });
});
