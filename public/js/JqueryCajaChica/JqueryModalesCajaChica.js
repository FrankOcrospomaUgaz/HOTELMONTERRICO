function Apertura() {
    var options = {
        timeZone: "America/Lima",
        hour12: false,
        hour: "2-digit",
        minute: "2-digit",
    };
    var currentTime = new Date().toLocaleTimeString("es-PE", options);
    console.log(currentTime);

    var currentHour = parseInt(currentTime.split(":")[0]);
    console.log(currentHour);
    // Asigna el turno según el rango horario
    if (currentHour >= 6 && currentHour < 14) {
        $("#turno").val("MAÑANA");
    } else if (currentHour >= 14 && currentHour < 22) {
        $("#turno").val("TARDE");
    } else {
        $("#turno").val("NOCHE");
    }

    $(".turnoDiv").removeClass("d-none");

    $(".registrosMontoTipo").html(``);
    $.get("cajaChica/apertura/recuperar/Apertura", function (data) {
        console.log(data);
        $("#total").val("0");
        $("#monto").val("100");
        $("#responsable").val(
            data.responsable.nombres + " " + data.responsable.apellidopaterno
        );
        $("#conceptoPago").html(``);
        $("#conceptoPago").append(
            `<option value="${data.conceptopago.id}"> ${data.conceptopago.nombre}</option>`
        );
        $("#personas").html(``);
        $.each(data.personas, function (index, item) {
            if (item.dni != null) {
                $("#personas").html(
                    $("#personas").html() +
                        `<option value="${item.id}"> ${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                );
            } else if (item.ruc != null) {
                $("#personas").html(
                    $("#personas").html() +
                        `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                );
            } else {
                $("#personas").html(
                    $("#personas").html() +
                        `<option value="${item.id}" selected>${item.nombres}</option>`
                );
            }
        });
        $("#fecha").val(data.fechaActual);
        $("#modalApertura").modal("show");
    });
}

function Ingreso() {
    $(".turnoDiv").addClass("d-none");
    $(".registrosMontoTipoEgr").html(``);
    $.get("cajaChica/apertura/recuperar/Ingreso", function (data) {
        console.log(data);
        $("#totalEgr").val("0");
        $("#montoEgr").val("100");
        $("#tipoMovimientoCajaEgr").val("Ingreso");
        $("#responsableEgr").val(
            data.responsable.nombres + " " + data.responsable.apellidopaterno
        );
        $("#conceptoPagoEgr").html(``);
        $.each(data.conceptopago, function (index, item) {
            $("#conceptoPagoEgr").append(
                `<option value="${item.id}"> ${item.nombre}</option>`
            );
        });
        $("#personasEgr").html(``);
        $.each(data.personas, function (index, item) {
            if (item.dni != null) {
                $("#personasEgr").html(
                    $("#personasEgr").html() +
                        `<option value="${item.id}"> ${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                );
            } else if (item.ruc != null) {
                $("#personasEgr").html(
                    $("#personasEgr").html() +
                        `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                );
            } else {
                $("#personasEgr").html(
                    $("#personasEgr").html() +
                        `<option value="${item.id}" selected>${item.nombres}</option>`
                );
            }
        });
        $("#fechaEgr").val(data.fechaActual);
        $("#modalEgresos").modal("show");

        $("#nuevoModalLabelEgr").text("NUEVO INGRESO");
    });
}

function Egreso() {
    $(".registrosMontoTipoEgr").html(``);
    $.get("cajaChica/apertura/recuperar/Egreso", function (data) {
        console.log(data);
        $("#totalEgr").val("0");
        $("#montoEgr").val("100");
        $("#tipoMovimientoCajaEgr").val("Egreso");

        $("#responsableEgr").val(
            data.responsable.nombres + " " + data.responsable.apellidopaterno
        );
        $("#conceptoPagoEgr").html(``);
        $.each(data.conceptopago, function (index, item) {
            $("#conceptoPagoEgr").append(
                `<option value="${item.id}"> ${item.nombre}</option>`
            );
        });
        $("#personasEgr").html(``);
        $.each(data.personas, function (index, item) {
            if (item.dni != null) {
                $("#personasEgr").html(
                    $("#personasEgr").html() +
                        `<option value="${item.id}"> ${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                );
            } else if (item.ruc != null) {
                $("#personasEgr").html(
                    $("#personasEgr").html() +
                        `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                );
            } else {
                $("#personasEgr").html(
                    $("#personasEgr").html() +
                        `<option value="${item.id}" selected>${item.nombres}</option>`
                );
            }
        });
        $("#fechaEgr").val(data.fechaActual);
        $("#modalEgresos").modal("show");

        $("#nuevoModalLabelEgr").text("NUEVO EGRESO");
    });
}
$(document).ready(function () {
    $("#modalCierre .btnPdf").on("click", function (e) {
        e.preventDefault();
        window.open("generar-pdf", "_blank");
    });

    $("#modalCierre .btnTicket").on("click", function (e) {
        e.preventDefault();
        window.open("export-ticketCaja", "_blank");
    });
    $("#modalCierre .btnCuadreCaja").on("click", function (e) {
        e.preventDefault();
        window.open(
            "generarReportes-cuadreCaja/" + $("#idMovApertura").val() + "/null",
            "_blank"
        );
        $("#modalCierre").modal("hide");
    });
});
function Cierre() {
    var columnsC = [
        {
            data: null,
            render: function (data, type, row, meta) {
                return meta.row + 1; // el número de fila iniciará en 1
            },
        },
        {
            data: "tipo",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    switch (data) {
                        case "Ingreso":
                            return (
                                '<strong style="background: #058ba0; color:white" class="btn-sm formatoBtn">' +
                                data +
                                "<strong/>"
                            );
                            break;

                        case "Egreso":
                            return (
                                '<strong style="background: red; color:white" class="btn-sm formatoBtn">' +
                                data +
                                "<strong/>"
                            );
                            break;
                    }
                }
            },
            orderable: false,
        },
        {
            data: "efectivoAcum",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    switch (full.tipo) {
                        case "Ingreso":
                            return "<p style='color:black'>" + data + "<p>";
                            break;

                        case "Egreso":
                            return data != 0 ? `-${data}` : data;
                            break;
                    }
                }
            },
            orderable: false,
        },

        {
            data: "tarjetaAcum",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    switch (full.tipo) {
                        case "Ingreso":
                            return "<p style='color:black'>" + data + "<p>";
                            break;

                        case "Egreso":
                            return data != 0 ? `-${data}` : data;
                            break;
                    }
                }
            },
            orderable: false,
        },

        {
            data: "yapeAcum",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    switch (full.tipo) {
                        case "Ingreso":
                            return "<p style='color:black'>" + data + "<p>";
                            break;

                        case "Egreso":
                            return data != 0 ? `-${data}` : data;
                            break;
                    }
                }
            },
            orderable: false,
        },
        {
            data: "plinAcum",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    switch (full.tipo) {
                        case "Ingreso":
                            return "<p style='color:black'>" + data + "<p>";
                            break;

                        case "Egreso":
                            return data != 0 ? `-${data}` : data;
                            break;
                    }
                }
            },
            orderable: false,
        },
        {
            data: "depositoAcum",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    switch (full.tipo) {
                        case "Ingreso":
                            return "<p style='color:black'>" + data + "<p>";
                            break;

                        case "Egreso":
                            return data != 0 ? `-${data}` : data;
                            break;
                    }
                }
            },
            orderable: false,
        },
        {
            data: "totalAcum",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    switch (full.tipo) {
                        case "Ingreso":
                            return (
                                '<strong style="background: #058ba0; color:white" class="btn-sm formatoBtn">' +
                                data +
                                "<strong/>"
                            );
                            break;

                        case "Egreso":
                            return (
                                '<strong style="background: red; color:white" class="btn-sm formatoBtn">-' +
                                data +
                                "<strong/>"
                            );
                            break;
                    }
                }
            },
            orderable: false,
        },
    ];

    $(document).ready(function () {
        //CERRAR MODAL
        $(document).on("click", "#cerrarModalC", function () {
            $("#modalCierre").modal("hide");
        });

        $.get("cajaChica/apertura/recuperar/Cierre", function (data) {
            console.log(data);
            console.log(data.idMovApertura);
            $("#idMovApertura").val(data.idMovApertura);
            $("#tipoMovimientoCajaCierr").val("Egreso");
            $("#totalCierr").val(data.montoApertura);

            $("#montoCaja").val(data.TotalCaja);
            $("#OtrosIngresos").val(
                (data.totalIngresos - data.TotalPagosCliente).toFixed(2)
            );
            $("#montoVentas").val(data.TotalPagosCliente);

            $("#montoCompras").val(
                data.TotaCompras == 0 ? "0" : "-" + data.TotaCompras
            );

            $("#otrosEgresos").val(
                data.TotalEgresos - data.TotaCompras === 0
                    ? "0"
                    : "-" + (data.TotalEgresos - data.TotaCompras)
            );

            $("#montoYape").val(data.yapeCaja);
            $("#montoEfectivo").val(data.efectivoCaja);
            $("#montoTarjeta").val(data.tarjetaCaja);
            $("#montoPlin").val(data.plinCaja);
            $("#montoDeposito").val(data.depositoCaja);

            $("#responsableCierr").val(
                data.responsable.nombres +
                    " " +
                    data.responsable.apellidopaterno
            );
            $("#conceptoPagoCierr").html(``);
            $("#conceptoPagoCierr").append(
                `<option value="${data.conceptopago.id}"> ${data.conceptopago.nombre}</option>`
            );
            $("#personas").html(``);
            $.each(data.personas, function (index, item) {
                if (item.dni != null) {
                    $("#personasCierr").html(
                        $("#personasCierr").html() +
                            `<option value="${item.id}"> ${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                    );
                } else if (item.ruc != null) {
                    $("#personasCierr").html(
                        $("#personasCierr").html() +
                            `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                    );
                } else {
                    $("#personasCierr").html(
                        $("#personasCierr").html() +
                            `<option value="${item.id}" selected>${item.nombres}</option>`
                    );
                }
            });
            $("#fechaCier").val(data.fechaActual);
            $("#tbDetalle").dataTable().fnDestroy();
            var table = $("#tbDetalle").DataTable({
                ajax: {
                    url: "cajaChica/detalleCierre",
                },
                orderCellsTop: true,
                scrollCollapse: true,
                columns: columnsC,
                paging: false,
                info: false,
                searching: false,
            });

            $("#modalCierre").modal("show");
        });
    });
}

$("#registroEgresos").submit(function (e) {
    e.preventDefault();
    var titulo = "0";
    if ($("#totalEgr").val() == 0) {
        titulo = "ADVERTENCIA";
    } else {
        titulo = "CONFIRMACIÓN DE ENVIO";
    }

    Swal.fire({
        title: titulo,
        text: "El monto es de: S/" + $("#totalEgr").val(),
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

            $(".registrosMontoTipoEgr")
                .find("tr")
                .each(function () {
                    var monto = $(this).find(".montoPagEgr").text();
                    var tipo = $(this).find(".megioPagoEgr").text();
                    var nota = $(this).find(".notaPagoEgr").text();

                    var registro = {
                        monto: monto,
                        nota: nota,

                        tipo: tipo,
                    };

                    registros.push(registro);
                });

            var registrosJSON = JSON.stringify(registros);
            formData.append("registroPagos", registrosJSON);



            $.ajax({
                type: "POST",
                url: "cajaChica/IngresoEgreso/guardar",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response) {
                        $("#registroApertura")[0].reset(); //limpiar campos
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Registro Guardado" + response,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#tbCaja").DataTable().ajax.reload(); //recargar datatable
                        $("#tbListadoEgresos").DataTable().ajax.reload(); //recargar datatable
                        $("#modalEgresos").modal("hide"); //ocultar modal
                        $(".btnApertura").addClass("d-none");
                        $(".btnCierre").removeClass("d-none");
                        $(".btnIngreso").removeClass("d-none");
                        $(".btnEgreso").removeClass("d-none");

                        actualizarcamposTotales();
                    } else {
                        alert("nO");
                    }
                },
            });
        }
    });
});

$(document).on("click", ".añadirMedioPagoEgr", function () {
    if ($("#montoEgr").val() > 0) {
        $(".registrosMontoTipoEgr").append(
            `<tr style="color:black" >
    <td class="montoPagEgr">${$("#montoEgr").val()}</td>
    <td class="notaPagoEgr">${$("#notaEgr").val() || "-".trim()}</td>
    <td class="megioPagoEgr">${$("#mediosPagoEgr").val()}</td>
    <td><button class="delete-button eliminarMontoEgr">
    <svg class="delete-svgIcon" viewBox="0 0 448 512">
                      <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                    </svg>
  </button></td>
      </tr>`
        );
        $("#totalEgr").val(
            parseFloat($("#totalEgr").val()) + parseFloat($("#montoEgr").val())
        );
        $("#montoEgr").val("0");
        $("#notaEgr").val("");
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


$(document).on("click", ".eliminarMontoEgr", function () {
    $(this).closest("tr").remove();
    var monto = $(this).closest("tr").find(".montoPagEgr").text();
    $("#totalEgr").val($("#totalEgr").val() - monto);
});