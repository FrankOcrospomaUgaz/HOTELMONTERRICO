$(document).ready(function () {
    $("#tituloPagina").html(`<a class="nav-link active" href="stockProductos">HOTEL | STOCK PRODUCTOS</a>`);
$("#vistaPrincipal").html(
    `<a class="nav-link active btn-profesional" href="vistaPrincipal" data-tooltip="Vista Principal" ><i class="fa-solid fa-star"></i></a>`
);
$("#vistaTabla").html(
    `<a class="nav-link active btn-profesional" href="listaHab" data-tooltip="Vista Tabla" ><i class="fa-solid fa-table-list"></i></a>`
);
$("#vistaCaja").html(
    `<a class="nav-link active btn-profesional" href="cajaChica" data-tooltip="Caja" ><i class="fa-solid fa-cash-register"></i></a>`
);
$("#vistaAlmacen").html(
    `<a class="nav-link active btn-profesional" href="movAlmacen" data-tooltip="Documento Almacén" ><i class="fa-solid fa-store"></i></a>`
);

$("#imprimirStockProd").click(function (e) { 
    e.preventDefault();
    window.open("generarReportesStock", "_blank");

});

});
$("#filtroProducto").submit(function (e) {
    e.preventDefault();

    $("#tbProductoStock").dataTable().fnDestroy();
    var table = $("#tbProductoStock").DataTable({
        ajax: {
            url: "stockProductos",
            data: {
                estado: $("#activosProducto").val(),
            },
        },
        orderCellsTop: true,
        
        columns: columnsP,
        dom: "Bfrtip",
        buttons: butomnsProd,
        lengthMenu: lengthmenuP,
        language: lenguagP,
        search: searchP,
        initComplete: initP,
        stripeClasses: ['odd-row', 'even-row']
    });
});

$("#resetFiltroProducto").click(function (e) {
    e.preventDefault();
    $("#activosProducto").val("activos");
    $("#tbProductoStock").dataTable().fnDestroy();
    var table = $("#tbProductoStock").DataTable({
        ajax: {
            url: "stockProductos",
            data: {
                estado: "activos",
            },
        },
        orderCellsTop: true,
        

        columns: columnsP,
        dom: "Bfrtip",
        buttons: butomnsProd,
        lengthMenu: lengthmenuP,
        language: lenguagP,
        search: searchP,
        initComplete: initP,
        stripeClasses: ['odd-row', 'even-row']
    });
});

//CERRAR MODAL
$(document).on("click", "#cerrarModalProd", function () {
    $("#modalProducto").modal("hide");
});

$(document).on("click", "#cerrarModalEProd", function () {
    $("#modalProductoE").modal("hide");
});

//DATATABLE PRINCIPAL

//buscador SEARCH


var columnsP = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return meta.row + 1; // el número de fila iniciará en 1
        },
    },

    {
        data: "nombre",
        orderable: true,
    },
    {
        data: "codigo",
        orderable: false,
    },
    {
        data: "preciocompra",
        orderable: false,
    },
    {
        data: "precioventa",
        orderable: false,
    },
    {
        data: "unidad",
        orderable: false,
    },
    {
        data: "categoria",
        orderable: false,
    },{
        data: "estado",
        render: function (data, type, row) {
            if (data == '1') {
                return "Habilitado";
            } else {
                return "Deshabilitado";
            }
        },
        orderable: false,
    },{
        data: "stock_general",
        orderable: false,
    },
    {
        data: "stock_habitacion",
        orderable: false,
    },
    {
        data: "stock_total",
        orderable: false,
    },
    {
        data: "created_at",
        render: function (data, type, full, meta) {
            var date = new Date(data);
            return date.toLocaleString();
        },
    },
    {
        data: "action",
        orderable: false,
        searchable: false,
    },
    
];

var lenguagP = {
    lengthMenu: "Mostrar _MENU_ Registros por paginas",
    zeroRecords: "No hay Registros",
    info: "Mostrando la pagina _PAGE_ de _PAGES_",
    infoEmpty: "",
    infoFiltered: "Filtrado de _MAX_ entradas en total",
    search: "Buscar:",
    paginate: {
        next: "Siguiente",
        previous: "Anterior",
    },
};

var lengthmenuP = [
    [ 20, 50, -1],
    [ 20, 50, "All"],
];
var butomnsProd = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11], // las columnas que se exportarán
        },
    },
    // {
    //     extend: "",
    //     text: 'PRINT <i class="fa-solid fa-print"></i>',
    //     className: "btn-dark print",
    //     exportOptions: {
    //         columnsP: [0, 1, 2, 3,4,5,6,7,8], // las columnas que se exportarán
    //     },
    // },
];

var searchP = {
    regex: true,
    caseInsensitive: true,
    type: "html-case-insensitive",
};




var initP = function initP() {
    var api = this.api();
    
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            if (colIdx == 0 || colIdx == 1||
                colIdx == 2||colIdx == 3||colIdx == 4
                ||colIdx == 5||colIdx == 6||colIdx == 7) {
                var cell = $(".filters th").eq(
                    $(api.column(colIdx).header()).index()
                );
                var title = $(cell).text();
                $(cell).html(
                    '<input type="text" placeholder="Escribe aquí..." />'
                );
                if (colIdx == 0) {
                    $(cell).html(
                        '<input style="width: 30px;" type="text" placeholder="#" />'
                    );
                }

                $(
                    "input",
                    $(".filters th").eq($(api.column(colIdx).header()).index())
                )
                    .off("keyup change")
                    .on("keyup change", function (e) {
                        e.stopPropagation();

                        $(this).attr("title", $(this).val());
                        var regexr = "({search})";
                        var cursorPosition = this.selectionStart;

                        api.column(colIdx)
                            .search(
                                this.value != ""
                                    ? regexr.replace(
                                          "{search}",
                                          "(((" + this.value + ")))"
                                      )
                                    : "",
                                this.value != "",
                                this.value == ""
                            )
                            .draw();
                        $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
                    });
            } else {
                var cell = $(".filters th").eq(
                    $(api.column(colIdx).header()).index()
                );
                $(cell).html("");
            }
        });
};

$("#tbProductoStock thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbProductoStock thead");

var table = $("#tbProductoStock").DataTable({
        ajax: {
            url: "stockProductos",
            data: { estado: "activos" },
        },
    orderCellsTop: true,
    
    columns: columnsP,
    dom: "Bfrtip",
    buttons: butomnsProd,
    lengthMenu: lengthmenuP,
    language: lenguagP,
    search: searchP,
    initComplete: initP,
    stripeClasses: ['odd-row', 'even-row'],


});

var repartoRapidoHabitaciones = [];

function actualizarResumenRepartoRapido() {
    var seleccionadas = $(".habitacion-reparto-check:checked").length;
    $("#repartoRapidoSeleccionadas").text(seleccionadas);

    var cantidad = parseInt($("#cantidadPorHabitacionRapido").val(), 10);
    if (isNaN(cantidad) || cantidad < 1) {
        cantidad = 1;
    }

    $("#repartoRapidoCantidadPreview").text(cantidad);
    if (cantidad > 1) {
        $("#repartoRapidoModoContainer").removeClass("d-none");
    } else {
        $("#repartoRapidoModoContainer").addClass("d-none");
        $("#repartoRapidoExacto").prop("checked", false);
    }
}

function renderRepartoRapidoHabitaciones() {
    var html = "";

    repartoRapidoHabitaciones.forEach(function (habitacion) {
        var badge = habitacion.tiene_stock
            ? '<span class="badge bg-warning text-dark">Ya tiene stock</span>'
            : '<span class="badge bg-secondary">Sin stock</span>';

        html +=
            '<tr>' +
                '<td class="text-center"><input type="checkbox" class="form-check-input habitacion-reparto-check" value="' + habitacion.id + '" checked></td>' +
                '<td>Habitacion ' + habitacion.numero + '</td>' +
                '<td>' + habitacion.situacion + '</td>' +
                '<td class="text-center">' + habitacion.stock + ' ' + badge + '</td>' +
            '</tr>';
    });

    if (!html) {
        html = '<tr><td colspan="4" class="text-center text-muted">No hay habitaciones activas.</td></tr>';
    }

    $("#tablaRepartoRapidoStock").html(html);
    actualizarResumenRepartoRapido();
}

window.repartirStockHabitacion = function (id, nombre) {
    $.get("stockProductos/distribucion/" + id, function (data) {
        $("#repartoRapidoProductoId").val(data.producto.id);
        $("#modalRepartoRapidoStockLabel").text("REPARTO RAPIDO DE STOCK - " + data.producto.nombre);
        $("#repartoRapidoProductoNombre").text(data.producto.nombre);
        $("#repartoRapidoGeneral").text(data.stock_general);
        $("#repartoRapidoHabitaciones").text(data.stock_habitaciones);
        $("#repartoRapidoTotal").text(data.stock_total);
        $("#cantidadPorHabitacionRapido").val(1);
        $("#repartoRapidoExacto").prop("checked", false);
        $("#repartoRapidoSeleccionadas").text(0);
        $("#repartoRapidoCantidadPreview").text(1);

        repartoRapidoHabitaciones = data.habitaciones || [];
        renderRepartoRapidoHabitaciones();
        $("#modalRepartoRapidoStock").modal("show");
    }).fail(function () {
        Swal.fire({
            icon: "error",
            title: "No se pudo cargar",
            text: "No fue posible obtener la distribucion del producto.",
        });
    });
};

window.verDistribucionStock = function (id, nombre) {
    $.get("stockProductos/distribucion/" + id, function (data) {
        $("#productoDistribucionId").val(data.producto.id);
        $("#modalDistribucionStockLabel").text("DISTRIBUCION DE STOCK - " + data.producto.nombre);
        $("#productoDistribucionNombre").text(data.producto.nombre);
        $("#productoDistribucionGeneral").text(data.stock_general);
        $("#productoDistribucionHabitaciones").text(data.stock_habitaciones);
        $("#productoDistribucionTotal").text(data.stock_total);

        $("#habitacionDestinoStock").html("");
        $("#tablaDistribucionStock").html("");

    $.each(data.habitaciones, function (index, habitacion) {
        var badge = habitacion.tiene_stock
            ? '<span class="badge bg-success">Con stock</span>'
            : '<span class="badge bg-secondary">Sin stock</span>';
        var botonQuitar = habitacion.stock > 0
            ? "<button type='button' class='btn btn-sm btn-outline-danger btn-quitar-rapido' data-id='" + habitacion.id + "' data-stock='" + habitacion.stock + "'>Dejar en 0</button>"
            : "<span class='text-muted'>-</span>";

        $("#tablaDistribucionStock").append(
            "<tr>" +
                    "<td>" + habitacion.numero + "</td>" +
                    "<td>" + habitacion.situacion + "</td>" +
                    "<td>" + habitacion.stock + "</td>" +
                    "<td>" + badge + "</td>" +
                    "<td><button type='button' class='btn btn-sm btn-outline-primary btn-mover-rapido' data-id='" + habitacion.id + "'>Mover</button></td>" +
                    "<td>" + botonQuitar + "</td>" +
                "</tr>"
        );

            $("#habitacionDestinoStock").append(
                "<option value='" + habitacion.id + "'>Habitacion " + habitacion.numero + " (" + (habitacion.tiene_stock ? "Con stock" : "Sin stock") + ")</option>"
            );
        });

        if ($("#habitacionDestinoStock option").length > 0) {
            $("#habitacionDestinoStock").prop("selectedIndex", 0);
        }

        $("#modalDistribucionStock").modal("show");
    }).fail(function () {
        Swal.fire({
            icon: "error",
            title: "No se pudo cargar",
            text: "No fue posible obtener la distribucion del producto.",
        });
    });
};

$(document).on("click", ".btn-quitar-rapido", function () {
    var habitacionId = $(this).data("id");
    var stockActual = parseFloat($(this).data("stock") || 0);
    var idProducto = $("#productoDistribucionId").val();
    var nombreProducto = $("#productoDistribucionNombre").text();

    if (!idProducto || !habitacionId || stockActual <= 0) {
        return;
    }

    Swal.fire({
        title: "Dejar stock en 0",
        html:
            "<strong>Producto:</strong> " + nombreProducto +
            "<br><strong>Stock actual en habitacion:</strong> " + stockActual +
            "<br>Esto regresara todo ese stock al almacen general.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, quitar",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (!result.isConfirmed) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "stockProductos/retirar/" + idProducto,
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                habitacion_id: habitacionId,
                cantidad: stockActual,
            },
            success: function (data) {
                Swal.fire({
                    icon: "success",
                    title: "Stock ajustado",
                    html:
                        "<strong>Producto:</strong> " + data.producto +
                        "<br><strong>Habitacion:</strong> " + data.habitacion +
                        "<br><strong>Almacen general:</strong> " + data.stock_general +
                        "<br><strong>Stock habitacion:</strong> " + data.stock_habitacion +
                        "<br><strong>Total:</strong> " + data.stock_total,
                });

                $("#modalDistribucionStock").modal("hide");
                $("#tbProductoStock").DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "No se pudo quitar",
                    text: xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : "Ocurrio un error al devolver el stock.",
                });
            },
        });
    });
});

$(document).on("change", ".habitacion-reparto-check", function () {
    actualizarResumenRepartoRapido();
});

$("#cantidadPorHabitacionRapido").on("input change", function () {
    actualizarResumenRepartoRapido();
});

$("#btnMarcarTodasHabitaciones").on("click", function () {
    $(".habitacion-reparto-check").prop("checked", true);
    actualizarResumenRepartoRapido();
});

$("#btnDesmarcarTodasHabitaciones").on("click", function () {
    $(".habitacion-reparto-check").prop("checked", false);
    actualizarResumenRepartoRapido();
});

$(document).on("click", ".btn-mover-rapido", function () {
    $("#habitacionDestinoStock").val($(this).data("id"));
});

$("#formTransferirStock").on("submit", function (e) {
    e.preventDefault();

    var idProducto = $("#productoDistribucionId").val();
    var habitacionId = $("#habitacionDestinoStock").val();
    var cantidad = parseFloat($("#cantidadTransferirStock").val());

    if (!idProducto) {
        Swal.fire({
            icon: "warning",
            title: "Datos incompletos",
            text: "No se pudo identificar el producto.",
        });
        return;
    }

    if (!habitacionId) {
        Swal.fire({
            icon: "warning",
            title: "Selecciona una habitacion",
            text: "Debes elegir una habitacion destino.",
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

    $.ajax({
        type: "POST",
        url: "stockProductos/transferir/" + idProducto,
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            habitacion_id: habitacionId,
            cantidad: cantidad,
        },
        success: function (data) {
            Swal.fire({
                icon: "success",
                title: "Stock movido",
                html:
                    "<strong>Producto:</strong> " + data.producto +
                    "<br><strong>Habitacion:</strong> " + data.habitacion +
                    "<br><strong>Almacen general:</strong> " + data.stock_general +
                    "<br><strong>Stock de la habitacion:</strong> " + data.stock_habitacion +
                    "<br><strong>Almacen habitaciones:</strong> " + data.stock_habitaciones +
                    "<br><strong>Total:</strong> " + data.stock_total,
            });

            $("#modalDistribucionStock").modal("hide");
            $("#tbProductoStock").DataTable().ajax.reload(null, false);
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "No se pudo mover",
                text: xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : "Ocurrio un error al mover el stock.",
            });
        },
    });
});

$("#formRepartoRapidoStock").on("submit", function (e) {
    e.preventDefault();

    var idProducto = $("#repartoRapidoProductoId").val();
    var cantidad = parseInt($("#cantidadPorHabitacionRapido").val(), 10);
    var distribucionExacta = $("#repartoRapidoExacto").is(":checked") ? 1 : 0;
    var habitacionesIds = $(".habitacion-reparto-check:checked").map(function () {
        return $(this).val();
    }).get();

    if (!idProducto) {
        Swal.fire({
            icon: "warning",
            title: "Datos incompletos",
            text: "No se pudo identificar el producto.",
        });
        return;
    }

    if (isNaN(cantidad) || cantidad < 1) {
        Swal.fire({
            icon: "warning",
            title: "Cantidad invalida",
            text: "La cantidad por habitacion debe ser mayor a cero.",
        });
        return;
    }

    if (!habitacionesIds.length) {
        Swal.fire({
            icon: "warning",
            title: "Sin habitaciones",
            text: "Selecciona al menos una habitacion para repartir.",
        });
        return;
    }

    $.ajax({
        type: "POST",
        url: "stockProductos/repartir/" + idProducto,
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            cantidad_por_habitacion: cantidad,
            distribucion_exacta: distribucionExacta,
            habitaciones_ids: habitacionesIds,
        },
        success: function (data) {
            var repartidas = data.repartidas && data.repartidas.length ? data.repartidas.join(", ") : "Ninguna";
            var sinStock = data.sin_stock && data.sin_stock.length ? data.sin_stock.join(", ") : "Ninguna";
            var parciales = data.parciales && data.parciales.length ? data.parciales.join(", ") : "Ninguna";
            var modo = data.distribucion_exacta ? "exacto" : "proporcional";

            Swal.fire({
                icon: "success",
                title: "Stock repartido",
                html:
                    "<strong>Producto:</strong> " + data.producto +
                    "<br><strong>Modo:</strong> " + modo +
                    "<br><strong>Cantidad por habitacion:</strong> " + data.cantidad_por_habitacion +
                    "<br><strong>Almacen general:</strong> " + data.stock_general +
                    "<br><strong>Almacen habitaciones:</strong> " + data.stock_habitacion +
                    "<br><strong>Total:</strong> " + data.stock_total +
                    "<br><strong>Habitaciones repartidas:</strong> " + repartidas +
                    "<br><strong>Habitaciones parciales:</strong> " + parciales +
                    "<br><strong>Habitaciones sin stock:</strong> " + sinStock,
            });

            $("#modalRepartoRapidoStock").modal("hide");
            $("#tbProductoStock").DataTable().ajax.reload(null, false);
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "No se pudo repartir",
                text: xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : "Ocurrio un error al repartir el stock.",
            });
        },
    });
});

$("#modalRepartoRapidoStock").on("shown.bs.modal", function () {
    actualizarResumenRepartoRapido();
});
