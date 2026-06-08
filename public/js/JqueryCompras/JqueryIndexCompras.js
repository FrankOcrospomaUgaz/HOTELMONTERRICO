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
    $("#vistaCompras").html(
        `<a class="nav-link active btn-profesional" href="movCompras" data-tooltip="Compras" ><i class="fa-solid fa-cart-shopping"></i></a>`
    );

    $("#vistaAlmacen").html(

    `<a class="nav-link active btn-profesional" href="movAlmacen" data-tooltip="Documento Almacén" ><i class="fa-solid fa-store"></i></a>`
);

var calendarioInicio = flatpickr("#calendarioInicio", {
    dateFormat: "Y-m-d H:i",
    enableTime: true,
    maxDate: new Date(),
    onChange: function (selectedDates, dateStr, instance) {
        if (selectedDates.length > 0) {
            var fechaInicio = new Date(selectedDates[0]);
            calendarioFin.set("minDate", fechaInicio);
        } else {
            calendarioFin.set("minDate", null);
        }
    },
});

var calendarioFin = flatpickr("#calendarioFin", {
    dateFormat: "Y-m-d H:i",
    enableTime: true,
    maxDate: new Date(),
});

$("#filtro").submit(function (e) {
    e.preventDefault();

    $("#tbCompraProductos").dataTable().fnDestroy();
    var table = $("#tbCompraProductos").DataTable({
        ajax: {
            url: "movCompras",
            data: {
                calendarioInicio: $("#calendarioInicio").val(),
                calendarioFin: $("#calendarioFin").val(),
            },
        },
        columns: columns,
        orderCellsTop: true,

        dom: "Bfrtip",
        buttons: butomns,
        lengthMenu: lengthmenu,
        language: lenguag,
        search: search,
        initComplete: init,
        stripeClasses: ["odd-row", "even-row"],
    });
});

$("#resetFiltro").click(function (e) {
    e.preventDefault();
    $("#calendarioInicio").val("");
    $("#calendarioFin").val("");

    $("#activos").val("todos");
    $("#tbCompraProductos").dataTable().fnDestroy();
    var table = $("#tbCompraProductos").DataTable({
        ajax: {
            url: "movCompras",
            data: {
                estado: "",
            },
        },
        columns: columns,
        orderCellsTop: true,

        dom: "Bfrtip",
        buttons: butomns,
        lengthMenu: lengthmenu,
        language: lenguag,
        search: search,
        initComplete: init,
        stripeClasses: ["odd-row", "even-row"],
    });
});

//DATATABLE PRINCIPAL

//buscador SEARCH
// $.extend($.fn.dataTable.ext.type.search, {
//     // Agregar la opción "i" a la expresión regular para que no haga diferencia entre mayúsculas y minúsculas
//     "html-case-insensitive": function (data) {
//         return data
//             .replace(/[\r\n]/g, " ") //elimina espaciados
//             .replace(/<.*?>/g, "") //elimina caracterres html <>
//             .toLowerCase(); //convierte en minusculas
//     },
// });

var columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return meta.row + 1; // el número de fila iniciará en 1
        },
    },

    {
        data: "numero",
        orderable: false,
    },

    {
        data: "tipoDocumento",
        render: function (data, type, full, meta) {
            if (data == "VARIOS") {
                return "-";
            } else {
                return (
                    '<div style="width:100px; margin:auto">' + data + "</div>"
                );
            }
        },
        orderable: false,
    },
    {
        data: "nombreUsuario",
        render: function (data, type, full, meta) {
            if (data == "VARIOS") {
                return "-";
            } else {
                return (
                    '<div style="width:200px; margin:auto">' + data + "</div>"
                );
            }
        },
        orderable: false,
    },
    {
        data: "nombreResponsable",
        orderable: false,
    },

    {
        data: "situacion",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                switch (data) {
                    case "Normal":
                        return (
                            '<strong style="background: #4caf50;font-size:14px; color:white" class="btn-sm formatoBtn">' +
                            data +
                            "<strong/>"
                        );
                        break;

                    case "Eliminado":
                        return (
                            '<strong style="background: red;font-size:14px; color:white" class="btn-sm formatoBtn">' +
                            data +
                            "<strong/>"
                        );
                        break;
                    default:
                        return "-";
                        break;
                }
            }
        },
        orderable: false,
    },

    {
        data: "total",
        render: function (data, type, full, meta) {
            if (data == null) {
                return "0.00";
            } else {
                return data;
            }
        },
        orderable: false,
    },
    {
        data: "created_at",
        render: function (data, type, full, meta) {
            var date = new Date(data);

            return (
                '<div style="width:80px; margin:auto">' +
                date.toLocaleString() +
                "</div>"
            );
        },
    },

    {
        data: "action",
        orderable: false,
    },
];
var init = function () {
    var api = this.api();
    // For each column
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            if (
                colIdx == 0 ||
                colIdx == 1 ||
                colIdx == 2 ||
                colIdx == 3 ||
                colIdx == 4 ||
                colIdx == 5
            ) {
                var cell = $(".filters th").eq(
                    $(api.column(colIdx).header()).index()
                );
                var title = $(cell).text();
                $(cell).html(
                    '<input type="text" placeholder="Escribe aquí..." />'
                );

                $(
                    "input",
                    $(".filters th").eq($(api.column(colIdx).header()).index())
                )
                    .off("keyup change")
                    .on("keyup change", function (e) {
                        e.stopPropagation();
                        // Get the search value
                        $(this).attr("title", $(this).val());
                        var regexr = "({search})";
                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
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

var lenguag = {
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

var lengthmenu = [
    [10, 20, 50, -1],
    [10, 20, 50, "All"],
];
var butomns = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columns: [0, 1, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 3, 4, 5, 6, 7], // las columnas que se exportarán
        },
    },
];

var search = {
    regex: true,
    caseInsensitive: true,
    type: "html-case-insensitive",
};

//BORRAR COLUMNA DE LA TABLA SI NO TIENE PERMISO
// var index = 7;
// var index2 = 8;
// if ($("#permisoElim").val() == "noeliminar") {
//     columns.splice(index, 1);
//     index2 = 5;
// }

// if ($("#permisoEdit").val() == "noeditar") {
//     columns.splice(index2, 1);
// }
$("#tbCompraProductos thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbCompraProductos thead");

var table = $("#tbCompraProductos").DataTable({
    ajax: {
        url: "movCompras",
    },
    orderCellsTop: true,

    columns: columns,
    dom: "Bfrtip",
    buttons: butomns,
    lengthMenu: lengthmenu,
    language: lenguag,
    search: search,
    initComplete: init,
    stripeClasses: ["odd-row", "even-row"],
});

$(document).on("click", "#btonNuevo", function () {
    window.location.href = "docCompras?operacion=compras";
});

function irDetalleCompras(id, operacion) {
    window.location.href = "docCompras?operacion=" + operacion + "&id=" + id;
}

