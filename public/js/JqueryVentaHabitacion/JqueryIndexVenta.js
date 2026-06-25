const ventaRapidaState = {
    habitacion: null,
    movimiento: null,
    productos: [],
    servicios: [],
};

function formatearMoneda(valor) {
    return "S/" + parseFloat(valor || 0).toFixed(2);
}

function recalcularTotalVentaRapida(totalContexto) {
    if (typeof totalContexto !== "undefined" && totalContexto !== null) {
        $("#totalMasIgv").text(formatearMoneda(totalContexto));
        return;
    }

    let total = 0;

    $(".listaProductos tr, .listaServicios tr").each(function () {
        const $totalCelda = $(this).find(".totalFila");

        if (!$totalCelda.length) {
            return;
        }

        const texto = ($totalCelda.text() || "")
            .replace("S/", "")
            .replace(/,/g, "")
            .trim();
        const monto = parseFloat(texto);

        if (!Number.isNaN(monto)) {
            total += monto;
        }
    });

    $("#totalMasIgv").text(formatearMoneda(total));
}

function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr("content");
}

function getOrigenProductoRapido() {
    return $('input[name="fuenteProductoRapido"]:checked').val() || "general";
}

$(document).ready(function () {
    renderNavbarVenta();
    inicializarVistaVenta(window.__VENTA_RAPIDA_DATA__ || null);
    registrarEventosVenta();
});

function renderNavbarVenta() {
    const numHabitacion = $("#numHabitacion").val();

    $("#tituloPagina").html(
        `<a class="nav-link active" href="ventaHabitacion?id=${numHabitacion}">HOTEL | AGREGAR VENTA</a>`
    );
    $("#stockProductos").html(
        `<a class="nav-link active btn-profesional" href="stockProductos" data-tooltip="Reporte Stock"><i class="fa-solid fa-box"></i></a>`
    );
    $("#consumoHab").html(
        `<a class="nav-link active btn-profesional" href="consumoHab" data-tooltip="Reporte Check-Out"><i class="fa-regular fa-calendar-check"></i></a>`
    );
    $("#vistaPrincipal").html(
        `<a class="nav-link active btn-profesional" href="vistaPrincipal" data-tooltip="Vista Principal"><i class="fa-solid fa-star"></i></a>`
    );
    $("#vistaTabla").html(
        `<a class="nav-link active btn-profesional" href="listaHab" data-tooltip="Vista Tabla"><i class="fa-solid fa-table-list"></i></a>`
    );
    $("#vistaCaja").html(
        `<a class="nav-link active btn-profesional" href="cajaChica" data-tooltip="Caja"><i class="fa-solid fa-cash-register"></i></a>`
    );
    $("#vistaCompras").html(
        `<a class="nav-link active btn-profesional" href="movCompras" data-tooltip="Compras"><i class="fa-solid fa-cart-shopping"></i></a>`
    );
    $("#vistaAlmacen").html(
        `<a class="nav-link active btn-profesional" href="movAlmacen" data-tooltip="Documento Almacen"><i class="fa-solid fa-store"></i></a>`
    );
}

function inicializarSelect2($element) {
    if (!$element.length) {
        return;
    }

    if ($element.hasClass("select2-hidden-accessible")) {
        $element.select2("destroy");
    }

    const $parent =
        $element.closest(".modal").length > 0
            ? $element.closest(".modal")
            : $element.parent();

    $element.select2({
        dropdownParent: $parent,
        width: "100%",
    });
}

function inicializarVistaVenta(initialData) {
    if (initialData && initialData.habitacion) {
        hidratarVistaVenta(initialData);
        return;
    }

    refrescarVentaRapidaContexto();
}

function pintarResumenHabitacion(habitacion) {
    const fechaActual = new Date();
    const fechaFormateada = `${fechaActual.getDate()}/${
        fechaActual.getMonth() + 1
    }/${fechaActual.getFullYear()}`;

    $("#numeroI").text(`N° ${habitacion.numero}`);
    $("#tipoI").text(habitacion.tipo || "-");
    $("#estadoI").text(habitacion.situacion || "-");
    $("#fechaingresoI").text(fechaFormateada);
    $("#fechasalidaI").text("-");
    $("#nombreClienteI").text("-");
}

function cargarModoDisponible(contexto) {
    const habitacion = contexto.habitacion;
    $(".cajaCliente").removeClass("d-none");
    $(".cajaAccionesVenta").addClass("d-none");
    $("#panelOperacion").addClass("d-none");
    renderClientes(contexto.clientes || []);

    const idServicioDefault =
        habitacion.tipo == "VIP" ? 4 : habitacion.tipo == "Normal" ? 1 : 21;

    const servicioDefault = (contexto.serviciosCatalogo || []).find(function (item) {
        return parseInt(item.id, 10) === idServicioDefault;
    });

    if (servicioDefault) {
        $(".listaServicios").html(`
                <tr>
                    <td>-</td>
                    <td>${servicioDefault.nombre}</td>
                    <td>1</td>
                    <td>${servicioDefault.precioventa}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>S/ ${parseFloat(servicioDefault.precioventa).toFixed(2)}</td>
                </tr>
            `);
        recalcularTotalVentaRapida(parseFloat(servicioDefault.precioventa));
    }
}

function cargarModoOcupado(contexto) {
    $(".cajaCliente").addClass("d-none");
    $(".cajaAccionesVenta").removeClass("d-none");
    $("#panelOperacion").removeClass("d-none");
    $("#irDetalleHabitacion").removeClass("d-none");

    const movimiento = contexto.movimiento;
    ventaRapidaState.movimiento = movimiento;
    $("#idMovimiento").val(movimiento.id);
    $("#fechaingresoI").text(movimiento.fechaingreso || "-");
    $("#fechasalidaI").text(movimiento.fechaSalida || "-");

    const nombreCliente = [
        movimiento.nombreCliente,
        movimiento.apellidopaterno || "",
        movimiento.apellidomaterno || "",
    ]
        .join(" ")
        .trim();
    $("#nombreClienteI").text(nombreCliente || "VARIOS");

    renderTablaProductos(contexto.productosDetalle || []);
    renderTablaServicios(contexto.serviciosDetalle || []);
    renderCatalogoProductos(contexto.productosCatalogo || []);
    renderCatalogoServicios((contexto.serviciosCatalogo || []).filter(function (item) {
        return item.tipo !== "Tiempo";
    }));
    recalcularTotalVentaRapida(contexto.total || 0);
    bindDescuentoModal();
}

function refrescarOperacionCompleta() {
    refrescarVentaRapidaContexto();
}

function refrescarVentaRapidaContexto() {
    $.get(
        "ventaHabitacion/contexto/" + $("#numHabitacion").val(),
        function (contexto) {
            hidratarVistaVenta(contexto);
        }
    );
}

function hidratarVistaVenta(contexto) {
    ventaRapidaState.habitacion = contexto.habitacion;
    ventaRapidaState.movimiento = contexto.movimiento;
    ventaRapidaState.productos = contexto.productosCatalogo || [];
    ventaRapidaState.servicios = (contexto.serviciosCatalogo || []).filter(
        function (item) {
            return item.tipo !== "Tiempo";
        }
    );

    pintarResumenHabitacion(contexto.habitacion);

    if (contexto.habitacion.situacion === "Ocupada" && contexto.movimiento) {
        cargarModoOcupado(contexto);
        return;
    }

    cargarModoDisponible(contexto);
}

function renderClientes(clientes) {
    let opciones = "";
    $.each(clientes, function (_, item) {
        if (item.dni != null) {
            opciones += `<option value="${item.id}">${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`;
        } else if (item.ruc != null) {
            opciones += `<option value="${item.id}">${item.ruc} - ${item.razonsocial}</option>`;
        } else {
            opciones += `<option value="${item.id}" selected>${item.nombres}</option>`;
        }
    });

    $("#clientes").html(opciones);
    inicializarSelect2($("#clientes"));
}

function renderTablaProductos(data) {
    if (!data.length) {
        $(".listaProductos").html(
            `<tr><td colspan="7" class="text-center text-muted">Sin productos registrados.</td></tr>`
        );
        recalcularTotalVentaRapida();
        return;
    }

    let html = "";
    $.each(data, function (_, item) {
        const comentario = item.comentario ? item.comentario : "-";
        const totalFila =
            item.cantidad * item.precioventa -
            (item.descuento / 100) * item.precioventa * item.cantidad;

        html += `
            <tr id="${item.id}" data-detalle-id="${item.id}" data-producto-id="${item.producto_id}" data-cantidad="${item.cantidad}" data-origen="${item.origen || "habitacion"}">
                <td>
                    <div class="venta-rapida__row-actions">
                        <button type="button" class="venta-rapida__icon-btn venta-rapida__icon-btn-plus" title="Sumar 1" onclick="ajustarCantidadProductoRapido(${item.id}, 1, ${item.producto_id})">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <button type="button" class="venta-rapida__icon-btn venta-rapida__icon-btn-minus" title="Quitar 1" onclick="ajustarCantidadProductoRapido(${item.id}, -1, ${item.producto_id})">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <button type="button" class="venta-rapida__icon-btn venta-rapida__icon-btn-trash" title="Eliminar" onclick="eliminarProductoAgregado(${item.id})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
                <td>${item.nombre}</td>
                <td><strong>${item.cantidad}</strong></td>
                <td>${item.precioventa}</td>
                <td>${comentario}</td>
                <td>
                    <input type="number" class="form-control mx-auto w-75 text-center descuentoInput" id="descuento-${item.id}" min="0" max="100" step="0.01" value="${item.descuento}" data-base-total="${item.cantidad * item.precioventa}" data-cantidad="${item.cantidad}" data-precio="${item.precioventa}" readonly>
                </td>
                <td class="totalFila">S/ ${totalFila}</td>
            </tr>
        `;
    });

    $(".listaProductos").html(html);
    recalcularTotalVentaRapida();
}

function renderTablaServicios(data) {
    if (!data.length) {
        $(".listaServicios").html(
            `<tr><td colspan="7" class="text-center text-muted">Sin servicios registrados.</td></tr>`
        );
        recalcularTotalVentaRapida();
        return;
    }

    let html = "";
    $.each(data, function (index, item) {
        const totalFila =
            item.cantidad * item.precioventa -
            (item.descuento / 100) * item.precioventa * item.cantidad;

        const acciones =
            index === 0
                ? "<td>-</td>"
                : `<td><a href="javascript:void(0)" onclick="eliminarProductoAgregado(${item.id})" title="Eliminar"><i class="fa-solid fa-trash" style="color:#0047c2;"></i></a></td>`;

        html += `
            <tr id="${item.id}">
                ${acciones}
                <td>${item.nombre}</td>
                <td>${item.cantidad}</td>
                <td>${item.precioventa}</td>
                <td>${item.comentario}</td>
                <td>
                    <input type="number" class="form-control mx-auto w-75 text-center descuentoInput" id="descuento-${item.id}" min="0" max="100" step="0.01" value="${item.descuento}" data-base-total="${item.cantidad * item.precioventa}" data-cantidad="${item.cantidad}" data-precio="${item.precioventa}" readonly>
                </td>
                <td class="totalFila">S/ ${totalFila}</td>
            </tr>
        `;
    });

    $(".listaServicios").html(html);
    recalcularTotalVentaRapida();
}

function renderCatalogoProductos(productos) {
    let opciones = "";
    $.each(productos, function (_, item) {
        opciones += `<option value="${item.id}" data-precio="${item.precioventa}" data-stock-habitacion="${item.stock_habitacion || 0}" data-stock-general="${item.stock || 0}" data-nombre="${item.nombre}">
            ${item.nombre}
        </option>`;
    });

    $("#productoRapido").html(opciones);
    inicializarSelect2($("#productoRapido"));
    actualizarResumenProductoRapido();
}

function renderCatalogoServicios(servicios) {
    let opciones = "";
    $.each(servicios, function (_, item) {
        opciones += `<option value="${item.id}" data-precio="${item.precioventa}" data-nombre="${item.nombre}">
            ${item.nombre}
        </option>`;
    });

    $("#servicioRapido").html(opciones);
    inicializarSelect2($("#servicioRapido"));
    actualizarServicioRapido();
}

function actualizarResumenProductoRapido() {
    const $selected = $("#productoRapido option:selected");
    const precio = parseFloat($selected.data("precio") || 0).toFixed(2);
    const stockHabitacion = parseFloat(
        $selected.data("stock-habitacion") || 0
    );
    const stockGeneral = parseFloat($selected.data("stock-general") || 0);
    const origen = getOrigenProductoRapido();

    $("#precioProductoRapido").val(precio);
    $("#stockHabitacionRapido").text(stockHabitacion);
    $("#stockGeneralRapido").text(stockGeneral);
    $("#ayudaProductoRapido").text(
        origen === "general"
            ? "Se descontara del almacen general y se registrara directo en la venta."
            : "Se descontara del stock ya disponible en la habitacion."
    );
}

function actualizarServicioRapido() {
    const $selected = $("#servicioRapido option:selected");
    $("#precioServicioRapido").val($selected.data("precio") || 0);
}

function registrarEventosVenta() {
    $("#productoRapido").on("change", actualizarResumenProductoRapido);
    $('input[name="fuenteProductoRapido"]').on(
        "change",
        actualizarResumenProductoRapido
    );
    $("#servicioRapido").on("change", actualizarServicioRapido);

    $("#btnAgregarProductoRapido").on("click", agregarProductoRapido);
    $("#btnAgregarServicioRapido").on("click", agregarServicioRapido);

    $("#registroNuevoMovimientoAtencion").on("submit", registrarCheckInRapido);

    $("#agregarCliente").on("click", function () {
        prepararModalNuevoCliente();
    });
}

function registrarCheckInRapido(e) {
    e.preventDefault();
    $("#enviaCheckInBtn").prop("disabled", true);

    const formData = new FormData(this);
    formData.set("numHabitacion", $("#numHabitacion").val());

    $.get("movimiento/show/" + $("#numHabitacion").val(), function (data) {
        if (data != "null") {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Esta habitacion ya tiene un checking",
                showConfirmButton: false,
                timer: 1500,
            });
            $("#enviaCheckInBtn").prop("disabled", false);
            return;
        }

        $.ajax({
            type: "POST",
            url: "ventaHabitacion/guardar",
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Check-in registrado",
                    showConfirmButton: false,
                    timer: 1200,
                }).then(function () {
                    if ($("#modoModal").val() === "1") {
                        if (
                            window.parent &&
                            typeof window.parent.cerrarModalVentaRapida ===
                                "function"
                        ) {
                            window.parent.cerrarModalVentaRapida(true);
                            return;
                        }

                        refrescarVentaRapidaContexto();
                        return;
                    }

                    window.location.href =
                        "ventaHabitacion?id=" + $("#numHabitacion").val();
                });
            },
            error: function (xhr) {
                Swal.fire(
                    "No se pudo registrar",
                    xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : "Error al registrar el check-in.",
                    "error"
                );
            },
            complete: function () {
                $("#enviaCheckInBtn").prop("disabled", false);
            },
        });
    });
}

function agregarProductoRapido() {
    const $selected = $("#productoRapido option:selected");
    const productoId = $selected.val();
    const cantidad = parseInt($("#cantidadProductoRapido").val(), 10);
    const stockHabitacion = parseFloat($selected.data("stock-habitacion") || 0);
    const stockGeneral = parseFloat($selected.data("stock-general") || 0);
    const origen = getOrigenProductoRapido();

    if (!productoId || isNaN(cantidad) || cantidad <= 0) {
        Swal.fire("Cantidad invalida", "Selecciona un producto y una cantidad valida.", "warning");
        return;
    }

    if (origen === "habitacion" && cantidad > stockHabitacion) {
        Swal.fire("Stock insuficiente", "No hay suficiente stock en la habitacion.", "warning");
        return;
    }

    if (origen === "general" && cantidad > stockGeneral) {
        Swal.fire("Stock insuficiente", "No hay suficiente stock en el almacen general.", "warning");
        return;
    }

    guardarDetalleProducto(
        productoId,
        $selected.data("nombre"),
        cantidad,
        origen
    );
}

function guardarDetalleProducto(productoId, nombre, cantidad, origen) {
    const formData = new FormData();
    formData.append("_token", getCsrfToken());
    formData.append("idMovimiento", $("#idMovimiento").val());
    formData.append(
        "tableData",
        JSON.stringify([
            {
                id: productoId,
                nombre: nombre,
                cantidad: cantidad,
                origen: origen || "habitacion",
            },
        ])
    );

    $.ajax({
        type: "POST",
        url: "detalleMovimiento/guardar",
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            $("#cantidadProductoRapido").val(1);
            refrescarOperacionCompleta();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Producto agregado",
                showConfirmButton: false,
                timer: 1000,
            });
        },
        error: function (xhr) {
            Swal.fire(
                "No se pudo agregar",
                xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : "Error al registrar el producto.",
                "error"
            );
        },
    });
}

function ajustarCantidadProductoRapido(detalleId, delta, productoId) {
    const $fila = $(`tr[data-detalle-id="${detalleId}"]`);
    const cantidadActual = parseFloat($fila.data("cantidad") || 0);
    const origenFila = $fila.data("origen") || "habitacion";

    if (!detalleId || !productoId || !delta || cantidadActual <= 0) {
        return;
    }

    if (delta < 0 && cantidadActual <= 1) {
        eliminarProductoPorOrigen(detalleId);
        return;
    }
    const nuevaCantidad = cantidadActual + delta;

        actualizarCantidadDetalleProducto(
            detalleId,
            nuevaCantidad,
            true
        )
        .done(function () {
            $fila.attr("data-origen", origenFila);
        })
        .fail(function (xhr) {
            mostrarErrorAjusteCantidad(xhr);
        });
}

function actualizarCantidadDetalleProducto(detalleId, cantidad, refrescarAlFinal) {
    return $.ajax({
        type: "PUT",
        url: "detalleMovimiento/updateCantIdProd/" + detalleId,
        data: {
            _token: getCsrfToken(),
            cantidadProductoEd: cantidad,
            notaProductoE: "-",
        },
        success: function () {
            if (refrescarAlFinal !== false) {
                refrescarOperacionCompleta();
            }
        },
    });
}

function eliminarProductoPorOrigen(detalleId) {
    eliminarProductoAgregado(detalleId);
}

function moverStockGeneralAHabitacion(productoId, cantidad) {
    return $.ajax({
        type: "POST",
        url: "stockProductos/transferir/" + productoId,
        data: {
            _token: getCsrfToken(),
            habitacion_id: $("#numHabitacion").val(),
            cantidad: cantidad,
        },
    });
}

function moverStockHabitacionAGeneral(productoId, cantidad, refrescarAlFinal) {
    return $.ajax({
        type: "POST",
        url: "stockProductos/retirar/" + productoId,
        data: {
            _token: getCsrfToken(),
            habitacion_id: ventaRapidaState.habitacion
                ? ventaRapidaState.habitacion.id
                : $("#numHabitacion").val(),
            cantidad: cantidad,
        },
        success: function () {
            if (refrescarAlFinal !== false) {
                refrescarOperacionCompleta();
            }
        },
    });
}

function mostrarErrorAjusteCantidad(xhr) {
    Swal.fire(
        "No se pudo actualizar",
        xhr.responseJSON && xhr.responseJSON.message
            ? xhr.responseJSON.message
            : "Error al actualizar la cantidad del producto.",
        "error"
    );
}

function mostrarErrorMovimientoStock(xhr, fallbackMessage) {
    Swal.fire(
        "Stock no disponible",
        xhr.responseJSON && xhr.responseJSON.message
            ? xhr.responseJSON.message
            : fallbackMessage,
        "error"
    );
}

function agregarServicioRapido() {
    const servicioId = $("#servicioRapido").val();
    const cantidad = parseInt($("#cantidadServicioRapido").val(), 10);

    if (!servicioId || isNaN(cantidad) || cantidad <= 0) {
        Swal.fire("Cantidad invalida", "Selecciona un servicio y una cantidad valida.", "warning");
        return;
    }

    const formData = new FormData();
    formData.append("_token", getCsrfToken());
    formData.append("idMovimiento", $("#idMovimiento").val());
    formData.append("servicios", servicioId);
    formData.append("cantidadServicio", cantidad);
    formData.append("precioServicio", $("#precioServicioRapido").val());
    formData.append("comentarioServicio", $("#comentarioServicioRapido").val() || "-");

    $.ajax({
        type: "POST",
        url: "detalleMovimiento/guardarServicio",
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            $("#cantidadServicioRapido").val(1);
            $("#comentarioServicioRapido").val("-");
            refrescarOperacionCompleta();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Servicio agregado",
                showConfirmButton: false,
                timer: 1000,
            });
        },
        error: function (xhr) {
            Swal.fire(
                "No se pudo agregar",
                xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : "Error al registrar el servicio.",
                "error"
            );
        },
    });
}

function bindDescuentoModal() {
    $(".descuentoInput")
        .off("click")
        .on("click", function (e) {
            e.preventDefault();

            const $input = $(this);
            const valorActual = parseFloat($input.val() || 0);
            const id = $input.attr("id").split("-")[1];
            const baseTotal = parseFloat($input.data("base-total") || 0);
            const montoActual =
                baseTotal > 0 ? (baseTotal * valorActual) / 100 : 0;

            Swal.fire({
                title: "Configura el descuento",
                html: `
                    <div style="display:grid;gap:12px;text-align:left;">
                        <div>
                            <label style="display:block;font-weight:700;margin-bottom:6px;">Modo</label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid #dbe4f0;border-radius:12px;cursor:pointer;">
                                    <input type="radio" name="tipoDescuentoSwal" value="porcentaje" checked>
                                    <span>Por porcentaje</span>
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid #dbe4f0;border-radius:12px;cursor:pointer;">
                                    <input type="radio" name="tipoDescuentoSwal" value="monto">
                                    <span>Por monto</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label id="labelValorDescuento" style="display:block;font-weight:700;margin-bottom:6px;">Porcentaje (%)</label>
                            <input id="valorDescuentoSwal" type="number" class="swal2-input" style="margin:0;width:100%;" min="0" max="100" step="0.01" value="${valorActual.toFixed(2)}">
                        </div>
                        <div style="padding:10px 12px;border-radius:12px;background:#f8fafc;color:#334155;">
                            <div><strong>Base:</strong> S/ ${baseTotal.toFixed(2)}</div>
                            <div id="ayudaDescuentoEquivalente"><strong>Equivalente:</strong> S/ ${montoActual.toFixed(2)}</div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar",
                didOpen: () => {
                    const $modal = $(Swal.getHtmlContainer());
                    const $valor = $modal.find("#valorDescuentoSwal");
                    const $label = $modal.find("#labelValorDescuento");
                    const $ayuda = $modal.find("#ayudaDescuentoEquivalente");

                    function actualizarEquivalencia() {
                        const tipo =
                            $modal.find('input[name="tipoDescuentoSwal"]:checked').val() ||
                            "porcentaje";
                        const valor = parseFloat($valor.val() || 0);
                        const porcentaje =
                            tipo === "monto"
                                ? baseTotal > 0
                                    ? (valor / baseTotal) * 100
                                    : 0
                                : valor;
                        const monto =
                            tipo === "monto"
                                ? valor
                                : (baseTotal * valor) / 100;

                        $label.text(
                            tipo === "monto"
                                ? "Monto (S/)"
                                : "Porcentaje (%)"
                        );
                        $valor.attr(
                            "max",
                            tipo === "monto" ? baseTotal.toFixed(2) : 100
                        );
                        $ayuda.html(
                            tipo === "monto"
                                ? `<strong>Equivalente:</strong> ${porcentaje.toFixed(2)}%`
                                : `<strong>Equivalente:</strong> S/ ${monto.toFixed(2)}`
                        );
                    }

                    $modal
                        .find('input[name="tipoDescuentoSwal"]')
                        .on("change", actualizarEquivalencia);
                    $valor.on("input", actualizarEquivalencia);
                    actualizarEquivalencia();
                },
                preConfirm: () => {
                    const $modal = $(Swal.getHtmlContainer());
                    const tipo =
                        $modal.find('input[name="tipoDescuentoSwal"]:checked').val() ||
                        "porcentaje";
                    const valor = parseFloat(
                        $modal.find("#valorDescuentoSwal").val() || 0
                    );

                    if (Number.isNaN(valor) || valor < 0) {
                        Swal.showValidationMessage(
                            "Ingresa un valor de descuento valido."
                        );
                        return false;
                    }

                    if (tipo === "porcentaje" && valor > 100) {
                        Swal.showValidationMessage(
                            "El porcentaje debe estar entre 0 y 100."
                        );
                        return false;
                    }

                    if (tipo === "monto" && valor > baseTotal) {
                        Swal.showValidationMessage(
                            "El monto no puede ser mayor al total de la linea."
                        );
                        return false;
                    }

                    const porcentaje =
                        tipo === "monto"
                            ? baseTotal > 0
                                ? (valor / baseTotal) * 100
                                : 0
                            : valor;

                    return {
                        descuento: porcentaje.toFixed(2),
                    };
                },
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.get("detalleMovimiento/actualizarDescuento/" + id, {
                    descuento: result.value.descuento,
                })
                    .done(function () {
                        refrescarOperacionCompleta();
                    })
                    .fail(function (xhr) {
                        Swal.fire(
                            "No se pudo actualizar",
                            xhr.responseJSON && xhr.responseJSON.message
                                ? xhr.responseJSON.message
                                : "Error al actualizar el descuento.",
                            "error"
                        );
                    });
            });
        });
}

function prepararModalNuevoCliente() {
    $("#registroUsuario")[0].reset();
    $(".error-message").attr("class", "error-message ajuste d-none");
    $(".CajaRUC").addClass("d-none");
    $(".cajaUsuario").addClass("d-none");
    $(".CajaDNI").removeClass("d-none");
    $("#razonsocial").val("");
    $("#direccion").val("");

    $.get("usuarios/create", function (data) {
        $("#tipoUsuario").html("");
        $.each(data.roles, function (_, item) {
            $("#tipoUsuario").append(
                `<option value="${item.id}">${item.name}</option>`
            );
        });
    });

    $.get("rolPersona/show", function (data) {
        $(".cajaCheckBoxRoles").html("");
        $.each(data, function (index, item) {
            if (index == 3) {
                $(".cajaCheckBoxRoles").append(`
                    <label>Cliente</label>
                    <div class="form-check form-check-inline d-none">
                        <input class="form-check-input" checked="true" type="checkbox" id="${item.descripcion}" name="roles[]" value="${item.id}">
                        <label class="form-check-label" for="${item.descripcion}">${item.descripcion}</label>
                    </div>
                `);
            }
        });

        $("#Usuario")
            .off("change")
            .on("change", function () {
                $(".cajaUsuario").toggleClass(
                    "d-none",
                    !$("#Usuario").is(":checked")
                );
            });

        $("#modalNuevoUsuario").modal("show");
    });
}
