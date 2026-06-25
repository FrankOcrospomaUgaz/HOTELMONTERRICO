mostrarHabitaciones();
$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="vistaPrincipal">HOTEL | VISTA PRINCIPAL</a>`
    );
    $(document).on("click", "#cerrarModal", function () {
        $("#modalCambiarSituacion").modal("hide");
    });
    $("#modalVentaRapida").on("hidden.bs.modal", function () {
        $("#iframeVentaRapida").attr("src", "about:blank");
        mostrarHabitaciones();
    });
    $("#modalReposicionRapida").on("hidden.bs.modal", function () {
        $("#tablaReposicionRapida").html(
            `<tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>`
        );
        mostrarHabitaciones();
    });
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
        `<a class="nav-link active btn-profesional" href="movAlmacen" data-tooltip="Documento Almacen" ><i class="fa-solid fa-store"></i></a>`
    );
});

function mostrarHabitaciones() {
    $.get("vistaPrincipal/show", function (data) {
        let contenidoHabitaciones = "SIN HABITACIONES";

        if (data[0]) {
            let temporizador = ``;
            let tipo = ``;
            let tarjetasHtml = "";

            $.each(data, function (index, item) {
                if (item.horaInicio !== null) {
                    temporizador = `<div class="temporizador" id="temporizadorDigital${index}">${item.horaInicio}</div>
                    <div class="temporizador-text"><b>S/. ${item.total}</b></div>
                    <div class="temporizador-text">${item.horaInicio}</div>`;

                    setInterval(function () {
                        updateTimer(
                            `#temporizadorDigital${index}`,
                            item.horaInicio,
                            item.horas
                        );
                    });
                } else {
                    temporizador = `
                    <div class="temporizador" id="temporizadorDigital${index}">00:00:00</div>
                    <div class="temporizador-text"></div>
                    <div class="temporizador-text"></div>`;
                }

                if (item.tipo == "VIP") {
                    tipo = `<div class="row"><label style="color:white;">VIP</label></div>`;
                } else {
                    tipo = `<div class="row"><label style="color:white;">${item.tipo.toUpperCase()}</label></div>`;
                }

                let color = `<div class='habitacion' id="${item.id}" style='background-color: rgb(6 225 0);'>`;
                switch (item.situacion) {
                    case "Disponible":
                        color = `<div class='habitacion' id="${item.id}" style='background-color: rgb(6 225 0);'>`;
                        break;
                    case "Ocupada":
                        color = `<div class='habitacion habitacion-ocupada animar-escala' id="${item.id}" style='background-color: #FF8000;'>`;
                        break;
                    case "FueraTiempo":
                        color = `<div class='habitacion' id="${item.id}" style='background-color: rgb(213 0 0);'>`;
                        break;
                    case "Mantenimiento":
                        color = `<div class='habitacion' id="${item.id}" style='background-color: rgb(176 0 211);'>`;
                        break;
                    case "Limpieza":
                        color = `<div class='habitacion' id="${item.id}" style='background-color: rgb(0 140 255);'>`;
                        break;
                }

                tarjetasHtml += `${color}
                    <div class="centro">
                        ${tipo}
                        <div class="row mb-1">
                            <div class="numero" value="${item.numero}">${item.numero}</div>
                        </div>
                        <div class="row">
                            ${temporizador}
                        </div>
                    </div>
                    <div class="Inferior">
                        <div class="estado">
                            ${item.situacion}${item.situacion == "Ocupada" ? `(${item.horas}h)` : ""} <i class="fa-solid fa-circle-right" style="color: #f255f;"></i>
                        </div>
                        <div class="acciones-habitacion">
                            ${renderAccionesHabitacion(item)}
                        </div>
                    </div>
                </div>`;
            });

            contenidoHabitaciones = tarjetasHtml;
        }

        $(".contenedorHabitaciones").html(contenidoHabitaciones);
        bindHabitacionActions();
    });
}

function renderAccionesHabitacion(item) {
    const accionReposicion = item.tiene_stock_cero
        ? `
            <button type="button" class="accion-habitacion accion-stock" data-action="reponer" data-numero="${item.numero}" data-tooltip="Reponer productos">
                <i class="fa-solid fa-box-open"></i>
            </button>
        `
        : ``;

    const botonDisponible = `
        <button type="button" class="accion-habitacion accion-success" data-action="situacion" data-id="${item.id}" data-numero="${item.numero}" data-situacion="Disponible" data-tooltip="Disponible">
            <i class="fa-solid fa-circle-check"></i>
        </button>
    `;

    const botonLimpieza = `
        <button type="button" class="accion-habitacion accion-secondary" data-action="situacion" data-id="${item.id}" data-numero="${item.numero}" data-situacion="Limpieza" data-tooltip="Limpieza">
            <i class="fa-solid fa-bell-concierge"></i>
        </button>
    `;

    const botonMantenimiento = `
        <button type="button" class="accion-habitacion accion-info" data-action="situacion" data-id="${item.id}" data-numero="${item.numero}" data-situacion="Mantenimiento" data-tooltip="Mantenimiento">
            <i class="fa-solid fa-toolbox"></i>
        </button>
    `;

    if (item.situacion === "Ocupada" || item.situacion === "FueraTiempo") {
        return `
            <button type="button" class="accion-habitacion accion-principal" data-action="venta" data-numero="${item.numero}" data-tooltip="Agregar Venta">
                <i class="fa-solid fa-store"></i>
            </button>
            <button type="button" class="accion-habitacion accion-danger" data-action="pagar" data-numero="${item.numero}" data-tooltip="Pagar">
                <i class="fa-solid fa-sack-dollar"></i>
            </button>
            <button type="button" class="accion-habitacion accion-warning" data-action="tiempo" data-numero="${item.numero}" data-tooltip="Agregar Tiempo">
                <i class="fa-solid fa-hourglass-half"></i>
            </button>
            ${accionReposicion}
        `;
    }

    if (item.situacion === "Disponible") {
        return `
            <button type="button" class="accion-habitacion accion-success" data-action="checkin" data-numero="${item.numero}" data-tooltip="Confirmar Check-in">
                <i class="fa-solid fa-circle-check"></i>
            </button>
            ${botonLimpieza}
            ${botonMantenimiento}
            ${accionReposicion}
        `;
    }

    if (item.situacion === "Limpieza") {
        return `
            ${botonDisponible}
            ${botonMantenimiento}
            ${accionReposicion}
        `;
    }

    if (item.situacion === "Mantenimiento") {
        return `
            ${botonDisponible}
            ${botonLimpieza}
            ${accionReposicion}
        `;
    }

    return `
        ${botonDisponible}
        ${botonLimpieza}
        ${botonMantenimiento}
        ${accionReposicion}
    `;
}

function bindHabitacionActions() {
    $(".accion-habitacion").off("click").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const action = $(this).data("action");
        const numero = $(this).data("numero");
        const id = $(this).data("id");
        const situacion = $(this).data("situacion");

        if (action === "venta") {
            abrirModalVentaRapida(numero, "Agregar venta");
            return;
        }

        if (action === "pagar") {
            window.location.href = "detalleHabitacion?id=" + numero;
            return;
        }

        if (action === "tiempo") {
            sumarTiempoHabTabla(numero);
            return;
        }

        if (action === "checkin") {
            abrirModalVentaRapida(numero, "Check-in rapido");
            return;
        }

        if (action === "reponer") {
            abrirModalReposicionRapida(numero);
            return;
        }

        if (action === "situacion") {
            actualizarSituacionRapida(id, numero, situacion);
        }
    });
}

function abrirModalVentaRapida(numeroHabitacion, titulo) {
    $("#tituloModalVentaRapida").text(
        `${titulo} - Habitacion N\u00b0 ${numeroHabitacion}`
    );
    $("#iframeVentaRapida").attr(
        "src",
        "ventaHabitacion?id=" + numeroHabitacion + "&modoModal=1"
    );
    $("#modalVentaRapida").modal("show");
}

function cerrarModalVentaRapida(refrescar = false) {
    $("#modalVentaRapida").modal("hide");
    if (refrescar) {
        mostrarHabitaciones();
    }
}

function abrirModalReposicionRapida(numeroHabitacion) {
    $("#tituloModalReposicion").text(
        `Reposicion rapida - Habitacion N\u00b0 ${numeroHabitacion}`
    );
    $("#tablaReposicionRapida").html(
        `<tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>`
    );
    $("#modalReposicionRapida").modal("show");

    $.get(
        "catProductos/showSinStockHabitacion/" + numeroHabitacion,
        function (productos) {
            if (!productos.length) {
                $("#tablaReposicionRapida").html(
                    `<tr><td colspan="5" class="text-center text-muted">No hay productos con stock 0 en esta habitacion.</td></tr>`
                );
                return;
            }

            let html = "";
            $.each(productos, function (_, item) {
                const stockHabitacion = parseFloat(item.stock_habitacion || 0);
                const stockGeneral = parseFloat(item.stock || 0);
                html += `
                    <tr>
                        <td>${item.nombre}</td>
                        <td>${stockHabitacion}</td>
                        <td>${stockGeneral}</td>
                        <td>
                            <input type="number" min="1" value="1" class="form-control form-control-sm cantidad-reposicion" data-producto-id="${item.id}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary btn-reponer-rapido" data-producto-id="${item.id}" data-numero="${numeroHabitacion}" ${stockGeneral <= 0 ? "disabled" : ""}>
                                Reponer
                            </button>
                        </td>
                    </tr>
                `;
            });

            $("#tablaReposicionRapida").html(html);
        }
    );
}

$(document).on("click", ".btn-reponer-rapido", function () {
    const productoId = $(this).data("producto-id");
    const numeroHabitacion = $(this).data("numero");
    const cantidad = parseFloat(
        $(`.cantidad-reposicion[data-producto-id="${productoId}"]`).val()
    );

    if (!cantidad || cantidad <= 0) {
        Swal.fire("Cantidad invalida", "Ingresa una cantidad valida.", "warning");
        return;
    }

    $.ajax({
        type: "POST",
        url: "stockProductos/transferir/" + productoId,
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            habitacion_id: numeroHabitacion,
            cantidad: cantidad,
        },
        success: function () {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Producto repuesto",
                showConfirmButton: false,
                timer: 1100,
            });
            abrirModalReposicionRapida(numeroHabitacion);
            mostrarHabitaciones();
        },
        error: function (xhr) {
            Swal.fire(
                "No se pudo reponer",
                xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : "Error al mover stock.",
                "error"
            );
        },
    });
});

$(document).on("click", "#btnReponerTodos", function () {
    const numeroHabitacion = $("#tituloModalReposicion")
        .text()
        .match(/Habitacion N° (\d+)/);
    const numHab = numeroHabitacion ? numeroHabitacion[1] : null;

    if (!numHab) {
        Swal.fire("No se pudo identificar", "No se encontro la habitacion.", "error");
        return;
    }

    const filas = $("#tablaReposicionRapida tr").filter(function () {
        return $(this).find(".btn-reponer-rapido").length > 0;
    });

    const pendientes = [];

    filas.each(function () {
        const $fila = $(this);
        const $boton = $fila.find(".btn-reponer-rapido");
        const productoId = $boton.data("producto-id");
        const stockGeneral = parseFloat($fila.find("td").eq(2).text()) || 0;
        const cantidad = parseFloat(
            $fila.find(".cantidad-reposicion").val()
        );

        if (stockGeneral > 0 && cantidad > 0) {
            pendientes.push({
                productoId: productoId,
                cantidad: cantidad,
            });
        }
    });

    if (!pendientes.length) {
        Swal.fire(
            "Nada para reponer",
            "No hay productos con stock general disponible o cantidades validas.",
            "warning"
        );
        return;
    }

    $("#btnReponerTodos").prop("disabled", true).text("Reponiendo...");

    $.ajax({
        type: "POST",
        url: "stockProductos/transferir-masivo",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            habitacion_id: numHab,
            items: pendientes.map(function (item) {
                return {
                    producto_id: item.productoId,
                    cantidad: item.cantidad,
                };
            }),
        },
        success: function (response) {
            const total = response && response.total_productos
                ? response.total_productos
                : pendientes.length;

            Swal.fire({
                position: "center",
                icon: "success",
                title: `Repuestos ${total} productos`,
                showConfirmButton: false,
                timer: 1600,
            });
            abrirModalReposicionRapida(numHab);
            mostrarHabitaciones();
        },
        error: function (xhr) {
            Swal.fire(
                "No se pudo reponer",
                xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : "Ningun producto pudo ser repuesto.",
                "error"
            );
        },
        complete: function () {
            $("#btnReponerTodos").prop("disabled", false).text("Reponer todos");
        },
    });
});

function actualizarSituacionRapida(idHabitacion, numeroHabitacion, nuevaSituacion) {
    $.get("movimiento/show/" + numeroHabitacion, function (data) {
        if (data != "null") {
            window.location.href = "listaHab";
            return;
        }

        const formData = new FormData();
        formData.append("_method", "PUT");
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        formData.append("situacionCambio", nuevaSituacion);

        $.ajax({
            type: "POST",
            url: "vistaPrincipal/editar/" + idHabitacion,
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                mostrarHabitaciones();
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Estado cambiado a " + nuevaSituacion,
                    showConfirmButton: false,
                    timer: 1400,
                });
            },
        });
    });
}

$("#registroCambiarSituacion").submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        type: "POST",
        url: "vistaPrincipal/editar/" + $("#idHabitacion").val(),
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response) {
                $("#registroCambiarSituacion")[0].reset();
                $("#modalCambiarSituacion").modal("hide");
                window.location.href = "vistaPrincipal";
            } else {
                alert("nO");
            }
        },
    });
});

function getElapsedTime(startTime) {
    const now = new Date();
    const startDate = new Date(startTime);

    let elapsedTime = now.getTime() - startDate.getTime();
    elapsedTime = Math.max(elapsedTime, 0);

    return elapsedTime;
}

function updateTimer(elemento, startTime, horasServicio) {
    const elapsedTime = getElapsedTime(startTime);

    const hours = Math.floor(elapsedTime / (1000 * 60 * 60));
    const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);

    const formattedTime = `${hours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;

    if (hours >= horasServicio) {
        $(elemento).closest(".habitacion").addClass("estiloRojo");
    } else {
        $(elemento).closest(".habitacion").removeClass("estiloRojo");
    }

    $(elemento).html(`<span>${formattedTime}</span>`);
}

function sumarTiempoHabTabla(numhab) {
    var html = ``;
    $.get("catServicios/show", function (dataModoHoras) {
        html = `<div class="row">
    <div class="col-md-6">
        <label for="modoHoraAdicional" class=" labelFormato">Modo Hora Adicional:</label>
        <br><select name="modoHoraAdicional" style="font-size:15px" class="form-control" id="modoHoraAdicional">
    `;
        $.each(dataModoHoras, function (index, item) {
            if (
                item.tipo == "Tiempo" &&
                item.id != 1 &&
                item.id != 4 &&
                item.id != 21
            ) {
                if (item.id === 7) {
                    html += `<option value="${item.id}" selected>${item.nombre}</option>`;
                } else {
                    html += `<option value="${item.id}">${item.nombre}</option>`;
                }
            }
        });
        html += `</select></div>
    <div class="col-md-6">
    <label for="horasInput" class="form-label labelFormato">Cantidad Horas:</label>
    <input id="horasInput" class="form-control" type="number" value="1" min="1" placeholder="Anadir Horas">
    </div>
    </div><div class="row">
    <div class="col-md-12">
        <label for="textoInput" class="form-label labelFormato">Notas:</label>
        <input id="textoInput" class="form-control" type="text" placeholder="Notas..." value="-">
    </div>
</div>`;

        Swal.fire({
            title: "Anadir mas tiempo a la Habitacion N\u00b0 " + numhab,
            html: html,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            showLoaderOnConfirm: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const horas = parseFloat(
                    document.getElementById("horasInput").value
                );
                const texto = document.getElementById("textoInput").value;
                const modoHoraAdicional =
                    document.getElementById("modoHoraAdicional").value;
                $.get(
                    "vistaPrincipal/sumarHorasHab" +
                        "/" +
                        numhab +
                        "/" +
                        horas +
                        "/" +
                        texto +
                        "/" +
                        modoHoraAdicional,
                    function () {
                        window.location.href = "vistaPrincipal";
                    }
                );
            }
        });
    });
}
