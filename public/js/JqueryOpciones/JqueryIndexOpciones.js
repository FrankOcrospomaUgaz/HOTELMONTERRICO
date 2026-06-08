$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="opciones">HOTEL | OPCIONES</a>`
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

    $("#tbOpciones").dataTable().fnDestroy();
    var table = $("#tbOpciones").DataTable({
        ajax: {
            url: "opciones",
            data: {
                estado: $("#activos").val(),
                calendarioInicio: $("#calendarioInicio").val(),
                calendarioFin: $("#calendarioFin").val(),
            },
        },
        orderCellsTop: true,
        fixedHeader: true,

        columns: columnsT,
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
    $("#tbOpciones").dataTable().fnDestroy();
    var table = $("#tbOpciones").DataTable({
        ajax: {
            url: "opciones",
            data: {
                estado: $("#activos").val(),
                calendarioInicio: $("#calendarioInicio").val(),
                calendarioFin: $("#calendarioFin").val(),
            },
        },
        orderCellsTop: true,
        fixedHeader: true,

        columns: columnsT,
        dom: "Bfrtip",
        buttons: butomns,
        lengthMenu: lengthmenu,
        language: lenguag,
        search: search,
        initComplete: init,
        stripeClasses: ["odd-row", "even-row"],
    });
});

//ICONO ALADO DEL INPUT COMOMUESTRA DEL ICONO QUE SE VA AGREGAR
$("#icono").on("input", function () {
    // Obtener el valor del icono capturado
    var icono = $(this).val();

    // Mostrar el icono en el elemento <i> dentro del <span> con la clase "input-group-text"
    $("#iconMuestra").attr("class", icono);
});

$(document).on("click", "#cerrarModalCRUD", function () {
    $("#modalCRUD").modal("hide"); //CERRAR MODAL
});

//CERRAR MODAL
$(document).on("click", "#cerrarModalE", function () {
    $("#modalEditarOpcionE").modal("hide");
});

//DATATABLE PRINCIPAL

//buscador SEARCH
$.extend($.fn.dataTable.ext.type.search, {
    // Agregar la opción "i" a la expresión regular para que no haga diferencia entre mayúsculas y minúsculas
    "html-case-insensitive": function (data) {
        return data
            .replace(/[\r\n]/g, " ") //elimina espaciados
            .replace(/<.*?>/g, "") //elimina caracterres html <>
            .toLowerCase(); //convierte en minusculas
    },
});
var i = 1;

var columnsT = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return meta.row + 1; // el número de fila iniciará en 1
        },
    },
    {
        data: "namePermiso",
        orderable: false,
    },
    {
        data: "nameCategoria",
        orderable: false,
    },
    {
        data: "rutaPermiso",
        orderable: false,
    },
    {
        data: "iconoPermiso",
        render: function (data, type, full, meta) {
            return '<i class="' + data + '">';
        },
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
        data: "estado",
        orderable: false,
    },
    {
        data: "action",
        orderable: false,
    },
];

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
    [5, 10, 50, -1],
    [5, 10, 50, "All"],
];

var butomns = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 2, 3, 5], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columns: [0, 1, 2, 3, 5], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2, 3, 5], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2, 3, 5], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2, 3, 5], // las columnas que se exportarán
        },
    },
];

var search = {
    regex: true,
    caseInsensitive: true,
    type: "html-case-insensitive",
};

var init = function () {
    var api = this.api();
    // For each column
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            if (colIdx == 0 || colIdx == 1 || colIdx == 2 || colIdx == 3) {
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

//BORRAR COLUMNA DE LA TABLA SI NO TIENE PERMISO
var index = 7;
var index2 = 8;
if ($("#permisoElim").val() == "noeliminar") {
    columnsT.splice(index, 1);
    index2 = 7;
}

if ($("#permisoEdit").val() == "noeditar") {
    columnsT.splice(index2, 1);
}

$("#tbOpciones thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbOpciones thead");
$(document).ready(function () {
    var table = $("#tbOpciones").DataTable({
        ajax: {
            url: "opciones",
            data: { estado: "" },
        },
        orderCellsTop: true,
        fixedHeader: true,

        columns: columnsT,
        dom: "Bfrtip",
        buttons: butomns,
        lengthMenu: lengthmenu,
        language: lenguag,
        search: search,
        initComplete: init,
        stripeClasses: ["odd-row", "even-row"],
    });
});
