function verCarrito(id) {
    $("#idMovCierre").val(id); //Id movimiento cierre en el modal

    $.get("detalleHabitacion/recuperar/" + id, function (dataMov) {
        console.log(dataMov);

        $("#tipoDoc").val(dataMov.tipoDocumento);
        $("#fechaIngreso").val(
            new Date(dataMov.MovimientoPadre.fechaingreso).toLocaleString()
        );
        $("#fechaSalida").val(
            new Date(dataMov.MovimientoPadre.fechasalida).toLocaleString()
        );

        $("#personasC").val(dataMov.persona);
        $("#responsableC").val(dataMov.responsable);

        $("#numMovCajaTipoDoc").val(dataMov.movCajaPagoCliente.numero);

        $("#comentarioC").val(
            dataMov.movCajaPagoCliente.comentario !== null
                ? dataMov.movCajaPagoCliente.comentario
                : "-"
        );

        $(".registrosDetalleVenta").html("");

        $.each(dataMov.detalleVentas, function (index, item) {
            var total = (
                item.cantidad * item.precioventa -
                (item.cantidad * item.precioventa * item.descuento) / 100
            ).toFixed(2);

            $(".registrosDetalleVenta").append(
                `<tr style="color:black";>
                    <td>${item.producto_servicio_nombre}</td>
                    <td>${
                        item.producto_id != null ? "Producto" : "Servicio"
                    }</td>
                    <td>${item.cantidad}</td>
                    <td>${item.precioventa}</td>
                    <td>${item.descuento}</td>
                    <td>${item.comentario}</td>
                    <td class="text-right">${total}</td>
                </tr>`
            );
        });

        console.log("dataMov");

        var total = parseFloat(dataMov.movCajaPagoCliente.total);
        var vuelto = parseFloat(dataMov.movCajaPagoCliente.vuelto);
        $("#totalPagar").val(dataMov.movCajaPagoCliente.total);

        $("#totalPagado").val((total + vuelto).toFixed(2));

        $("#vueltoPago").val(dataMov.movCajaPagoCliente.vuelto);

        $("#numHabC").val(dataMov.numHab);
        $("#modalCarrito").modal("show");
    });
}
function actualizarcamposTotales() {
    $.get("cajaChica/apertura/recuperar/Cierre", function (data) {
        $("#montoAperturaV").val(data.montoApertura.toFixed(2));

        $("#montoCajaV").val(data.TotalCaja);
        $("#OtrosIngresosV").val(
            (data.totalIngresos - data.TotalPagosCliente).toFixed(2)
        );

        $("#montoVentasV").val(data.TotalPagosCliente.toFixed(2));

        $("#montoComprasV").val(
            data.TotaCompras == 0 ? "0" : "-" + data.TotaCompras
        );

        var totalEgresos = parseFloat(data.TotalEgresos);
        var totalCompras = parseFloat(data.TotaCompras);

        $("#otrosEgresosV").val(
            totalEgresos - totalCompras === 0
                ? "0.00"
                : "-" + (totalEgresos - totalCompras)
        );

        $("#montoYapeV").val(data.yapeCaja);
        $("#montoEfectivoV").val(data.efectivoCaja);
        $("#montoTarjetaV").val(data.tarjetaCaja);
        $("#montoPlinV").val(data.plinCaja);
        $("#montoDepositoV").val(data.depositoCaja);
    });
}

function editarMovCaja(id) {
    $("#registroAperturaE")[0].reset(); //limpiar campos

    $(document).on("click", "#cerrarModal", function () {
        $("#modalEditarMovCaja").modal("hide");
    });

    $.get("cajaChica/recuperarMmovimiento/" + id, function (dataMov) {
        console.log(dataMov);
        $("#idMovimiento").val(id);
        $("#totalE").val(dataMov.total);

        if (dataMov.tipodocumento_id == 6) {
            $("#tipoMovimientoCajaE").val("Ingreso");
        } else {
            $("#tipoMovimientoCajaE").val("Egreso");
        }

        $("#responsableE").val(dataMov.nombreResponsable);

        $("#fechaE").val(new Date(dataMov.fecha).toLocaleString());

        $("#conceptoPagoE").val(dataMov.conceptoPago);

        $("#personaE").val(dataMov.nombreUsuario);

        $(".registrosMontoTipoE").html("");

        var yape =
            dataMov.yape == 0
                ? ``
                : `<tr style="color:black"; >
        <td class="montoPagE">${dataMov.yape}</td>
        <td>Yape</td>
        <td><button class="delete-button eliminarMontoE">
        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                          <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                        </svg>
      </button></td>
          </tr>`;
        var efectivo =
            dataMov.efectivo == 0
                ? ``
                : `<tr style="color:black"; >
        <td class="montoPagE">${dataMov.efectivo}</td>
        <td>Efectivo</td>
        <td><button class="delete-button eliminarMontoE">
        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                          <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                        </svg>
      </button></td>
          </tr>`;
        var tarjeta =
            dataMov.tarjeta == 0
                ? ``
                : `<tr style="color:black"; >
        <td class="montoPagE">${dataMov.tarjeta}</td>
        <td>Tarjeta</td>
        <td><button class="delete-button eliminarMontoE">
        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                          <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                        </svg>
      </button></td>
          </tr>`;
        var deposito =
            dataMov.deposito == 0
                ? ``
                : `<tr style="color:black"; >
        <td class="montoPagE">${dataMov.deposito}</td>
        <td>Depósito</td>
        <td><button class="delete-button eliminarMontoE">
        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                          <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                        </svg>
      </button></td>
          </tr>`;
        var plin =
            dataMov.plin == 0
                ? ``
                : `<tr style="color:black"; >
        <td class="montoPagE">${dataMov.plin}</td>
        <td>Plin</td>
        <td><button class="delete-button eliminarMontoE">
        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                          <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                        </svg>
      </button></td>
          </tr>`;

        $(".registrosMontoTipoE").html(
            efectivo + yape + tarjeta + deposito + plin
        );
        setTimeout(function () {
            $("#modalEditarMovCaja").modal("show");
        }, 1500);
    });
}

function editarMovCajaIngresoEgreso(id) {
    $(document).on("click", "#cerrarModal", function () {
        $("#modalEgresosE").modal("hide");
    });

    $.get("cajaChica/recuperarMmovimiento/" + id, function (dataMov) {
        console.log(dataMov);
        $("#idMovimientoEgrE").val(id);
        $("#totalEgrE").val(dataMov.total);

        if (dataMov.tipodocumento_id == 6) {
            $("#tipoMovimientoCajaEgrE").val("Ingreso");
        } else {
            $("#tipoMovimientoCajaEgrE").val("Egreso");
        }

        $("#responsableEgrE").val(dataMov.nombreResponsable);

        $("#fechaEgrE").val(new Date(dataMov.fecha).toLocaleString());

        $("#conceptoPagoEgrE").val(dataMov.conceptoPago);

        $("#personasEgrE").val(dataMov.nombreUsuario);

        $(".registrosMontoTipoEgrE").html("");

        $.get("cajaChica/obtenerDetalleEgresos/" + id, function (data) {
            data.forEach((element) => {
                $(".registrosMontoTipoEgrE").append(
                    `<tr style="color:black" >
                    <td class="idPagEgrE d-none">${element.id}</td>
    <td class="montoPagEgrE">${element.monto}</td>
    <td class="notaPagoEgrE">${element.nota || "-".trim()}</td>
    <td class="megioPagoEgrE">${element.tipo}</td>
    <td><button class="delete-button eliminarMontoEgrE">
    <svg class="delete-svgIcon" viewBox="0 0 448 512">
                  <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                </svg>
    </button></td>
    </tr>`
                );
            });

            setTimeout(function () {
                $("#modalEgresosE").modal("show");
            }, 1000);
        });
    });
}

$(document).ready(function () {
    $("#modalCarrito .btnPdf").on("click", function (e) {
        e.preventDefault();

        if ($("#tipoDoc").val() != "Ticket Venta") {
            var url = "/clifacturacion/controlador/obtenerParaTicket.php";
            var parametros = {
                numDocumento: $("#numMovCajaTipoDoc").val(),
            };
            $.ajax({
                type: "GET",
                url: url,
                data: parametros,

                success: function (result) {
                    // $.get("ticket-pdf/" + result, function (dataSituacion) {
                    //     console.log(dataSituacion);
                    // });
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
                            "ticket-pdf-ticketera/" + result + "/ImpresionReimprimirBoleta",
                            function (data) {
                                console.log(data);
                            }
                        ).fail(function (xhr, status, error) {
                            console.log("Hubo un error: " + error);
                        });

                    window.open("ticket-pdf/" + result+"/VerPDFReimprimirBoleta", "_blank");
                },
                error: function (e) {
                    console.log("error:".e);
                },
            });
        } else {
            console.log("TICKET DE VENTA");
            $.get(
                "ticket-pdf-sinFact-ticketera/" +
                    +$("#idMovCierre").val() +
                    "/ImpresionReimprimirTicket",
                function (data) {
                    console.log(data);
                }
            ).fail(function (xhr, status, error) {
                console.log("Hubo un error: " + error);
            });

            window.open(
                "ticket-pdf-sinFact/" + $("#idMovCierre").val()+"/ReimprimirTicket",
                "_blank"
            );

        }
    });

    $(document).on("click", "#cerrarModalCarrito", function () {
        $("#modalCarrito").modal("hide");
    });

    $(document).on("click", ".añadirMedioPagoE", function () {
        if ($("#montoE").val() > 0) {
            $(".registrosMontoTipoE").append(
                `<tr style="color:black" >
        <td class="montoPagE">${$("#montoE").val()}</td>
        <td>${$("#mediosPagoE").val()}</td>
        <td><button class="delete-button eliminarMontoE">
        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                          <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                        </svg>
      </button></td>
          </tr>`
            );
            $("#totalE").val(
                parseFloat($("#totalE").val()) + parseFloat($("#montoE").val())
            );
            $("#monto").val("0");
        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Corrija el Monto",
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });

    $(document).on("click", ".eliminarMontoE", function () {
        $(this).closest("tr").remove();
        var monto = $(this).closest("tr").find(".montoPagE").text();
        $("#totalE").val($("#totalE").val() - monto);
    });

    $(document).on("click", ".eliminarMontoEgrE", function (event) {
        event.preventDefault(); // Detener el comportamiento predeterminado
    
        var thisTR= $(this);
        var idMovimientoDetalleSalida = $(this)
            .closest("tr")
            .find(".idPagEgrE")
            .text();
    
        // Mostrar un Sweet Alert de confirmación
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción eliminará el elemento. ¿Deseas continuar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                // El usuario confirmó la eliminación, realizar la acción
                $.get(
                    "eliminarDetalleMovimientoSalida",
                    {
                        idMovimientoEgrE: idMovimientoDetalleSalida,
                    },
                    function (data) {
                        console.log(data);
    
                       
                        $("#totalEgrE").val(data.monto);
                        $("#tbCaja").DataTable().ajax.reload(); //recargar datatable
                        $("#tbListadoEgresos").DataTable().ajax.reload(); //recargar datatable
                        thisTR.closest("tr").remove();
                        actualizarcamposTotales()
                    }
                );
            }
        });
    });
    
    //
    $("#registroAperturaE").submit(function (e) {
        e.preventDefault();

        Swal.fire({
            title: "CONFIRMACIÓN DE ENVIO",
            text: "Con un monto de: S/" + $("#totalE").val(),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Confirmo!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData(this);

                var registros = [];

                $(".registrosMontoTipoE")
                    .find("tr")
                    .each(function () {
                        var monto = $(this).find(".montoPagE").text();
                        var tipo = $(this).find("td:nth-child(2)").text();

                        var registro = {
                            monto: monto,
                            tipo: tipo,
                        };

                        registros.push(registro);
                    });

                var registrosJSON = JSON.stringify(registros);
                formData.append("registroPagos", registrosJSON);

                $.ajax({
                    type: "POST",
                    url:
                        "cajaChica/apertura/editar/" + $("#idMovimiento").val(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response) {
                            $("#registroAperturaE")[0].reset(); //limpiar campos
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Registro Guardado" + response,
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            $("#tbCaja").DataTable().ajax.reload(); //recargar datatable
                            $("#tbListadoEgresos").DataTable().ajax.reload(); //recargar datatable
                            setTimeout(function () {
                                $("#modalEditarMovCaja").modal("hide");

                                actualizarcamposTotales();
                            }, 1200);
                        } else {
                            alert("nO");
                        }
                    },
                });
            }
        });
    });
});
function revertirMovPagoCliente(id) {
    $.get("cajaChica/situacionHabXreversion/" + id, function (dataSituacion) {
        console.log(dataSituacion);
        if (dataSituacion.estadoMovPadre == 0) {
            if (
                dataSituacion.situacionHab == "Disponible" ||
                dataSituacion.situacionHab == "Limpieza"
            ) {
                Swal.fire({
                    title:
                        "Confirmación de reversión" +
                        " en la Habitación N°" +
                        dataSituacion.numHab,
                    text: "Este monto volvera a ser contabilizado",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, Confirmo!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.get(
                            "cajaChica/revertirAnulacionMovAtencion/" + id,
                            function (data) {
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Registro Revertido con Exito",
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                window.location.href =
                                    "detalleHabitacion?id=" + data;
                            }
                        );
                    }
                });
            } else {
                $.get(
                    "catHabitaciones/habitacionesXsituacion/" + "Disponible",
                    function (data) {
                        var opcionesDisponibles = {};
                        $.each(data, function (index, item) {
                            $.each(data, function (index, item) {
                                opcionesDisponibles[item.id] =
                                    "Habitación N° " + item.numero;
                            });
                        });

                        Swal.fire({
                            title:
                                "La Habitación N°" +
                                dataSituacion.numHab +
                                " se encuentra Ocupada",
                            input: "select",
                            inputOptions: opcionesDisponibles,
                            text: "Seleccione una habitación disponible para la Reversión",
                            icon: "warning",
                            inputValidator: (value) => {},
                            showCancelButton: true,
                            confirmButtonText: "Guardar",
                            showLoaderOnConfirm: true,
                            cancelButtonText: "Cancelar",
                        }).then((result) => {
                            if (result.isConfirmed && result.value) {
                                $.get(
                                    "cajaChica/revertirAnulacionMovAtencionOtraHab/" +
                                        id +
                                        "/" +
                                        result.value,
                                    function (data) {
                                        Swal.fire({
                                            position: "center",
                                            icon: "success",
                                            title: "Registro Revertido con Exito",
                                            showConfirmButton: false,
                                            timer: 1500,
                                        });

                                        window.location.href =
                                            "detalleHabitacion?id=" + data;
                                    }
                                );
                            }
                        });
                    }
                );
            }
        } else {
            Swal.fire({
                position: "center",
                icon: "warning",
                title:
                    "El movimiento de Atención esta Activo en la Habitación N°" +
                    dataSituacion.numHab,
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });
}
function actualizarcamposTotales() {
    $.get("cajaChica/apertura/recuperar/Cierre", function (data) {
        console.log(data);

        $("#montoAperturaV").val(data.montoApertura.toFixed(2));
        console.log(data.totalIngresos - data.TotalPagosCliente);

        $("#montoCajaV").val(data.TotalCaja);

        $("#OtrosIngresosV").val(
            (data.totalIngresos - data.TotalPagosCliente).toFixed(2)
        );
        $("#montoVentasV").val(data.TotalPagosCliente.toFixed(2));

        $("#montoComprasV").val(
            data.TotaCompras == 0 ? "0.00" : "-" + data.TotaCompras
        );
        $("#montoVentasEfectivoV").val(data.TotaefectivoVenta.toFixed(2));
        $("#montoAperturaEfecitvo").val(data.TotaefectivoApertura.toFixed(2));
        $("#otrosEgresosV").val(
            data.TotalEgresos - data.TotaCompras === 0
                ? "0"
                : "-" + (data.TotalEgresos - data.TotaCompras)
        );

        $("#montoYapeV").val(data.yapeCaja);
        $("#montoEfectivoV").val(data.efectivoCaja);
        $("#montoTarjetaV").val(data.tarjetaCaja);
        $("#montoPlinV").val(data.plinCaja);
        $("#montoDepositoV").val(data.depositoCaja);
    });
}

function revertirMovCaja(id) {
    Swal.fire({
        title: "Confirmación de reversión",
        text: "Este monto volvera a ser contabilizado",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Confirmo!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.get("cajaChica/revertirAnulacion/" + id, function (data) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Registro Revertido " + data,
                    showConfirmButton: false,
                    timer: 1500,
                });

                $("#tbCaja").DataTable().ajax.reload(); //recargar datatable
                $("#tbListadoEgresos").DataTable().ajax.reload(); //recargar datatable
                actualizarcamposTotales();
            });
        }
    });
}

$(document).on("click", ".añadirMedioPagoEgrE", function () {
    if ($("#montoEgrE").val() > 0) {
        $.get(
            "agregarNuevaEntrada",
            {
                idMovimientoEgrE: $("#idMovimientoEgrE").val(),
                montoEgrE: $("#montoEgrE").val(),
                notaEgrE: $("#notaEgrE").val(),
                mediosPagoEgrE: $("#mediosPagoEgrE").val(),
            },
            function (data) {
                console.log(data);

                $(".registrosMontoTipoEgrE").append(
                    `<tr style="color:black" >
        <td class="montoPagEgrE">${parseFloat($("#montoEgrE").val()).toFixed(
            2
        )}</td>
        <td class="idPagEgrE d-none">${data.idNuevoMovimiento}</td>
        <td class="notaPagoEgrE">${$("#notaEgrE").val() || "-".trim()}</td>
        <td class="megioPagoEgrE">${$("#mediosPagoEgrE").val()}</td>
        <td><button class="delete-button eliminarMontoEgrE">
        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                          <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                        </svg>
      </button></td>
          </tr>`
                );

                $("#totalEgrE").val(data.monto);
                $("#montoEgrE").val("0");
                $("#notaEgrE").val("");
                $("#tbCaja").DataTable().ajax.reload(); //recargar datatable
                $("#tbListadoEgresos").DataTable().ajax.reload(); //recargar datatable
                actualizarcamposTotales()
            }
        );
    } else {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Corrija el Monto",
            showConfirmButton: false,
            timer: 1500,
        });
    }
});
