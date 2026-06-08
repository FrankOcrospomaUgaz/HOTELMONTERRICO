$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="conceptoPagos">HOTEL | CONCEPTO PAGOS</a>`
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
$("#filtro").submit(function (e) {
    e.preventDefault();

    $("#tbConceptoPagos").dataTable().fnDestroy();


    var table = $("#tbConceptoPagos").DataTable({
        ajax: {
             url: "conceptoPagos",
            data: {
                estado: $("#activos").val(),
            },
        },
        orderCellsTop: true,
        fixedHeader: true,
        columns: columns,
        dom: "Bfrtip",
        buttons: butomns,
        lengthMenu: lengthmenu,
        language: lenguag,
        search: search,
        initComplete: init,
        stripeClasses: ['odd-row', 'even-row']
    });
});

$("#resetFiltro").click(function (e) {
    e.preventDefault();
    $("#activos").val("todos");
    $("#tbConceptoPagos").dataTable().fnDestroy();
    var table = $("#tbConceptoPagos").DataTable({
        ajax: {
             url: "conceptoPagos",
            data: {
                estado: " ",
            },
        },
        orderCellsTop: true,
        fixedHeader: true,

        columns: columns,
        dom: "Bfrtip",
        buttons: butomns,
        lengthMenu: lengthmenu,
        language: lenguag,
        search: search,
        initComplete: init,
        stripeClasses: ['odd-row', 'even-row']
    });
});

//CERRAR MODAL
$(document).on("click", "#cerrarModal", function () {
    $("#modalConceptoPagos").modal("hide");
});

$(document).on("click", "#cerrarModalE", function () {
    $("#modalConceptoPagosE").modal("hide");
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

var columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return meta.row + 1; // el número de fila iniciará en 1
        },
    },
    {
        data: "nombre",
        orderable: false,
    },

    {
        data: "tipo",
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
    [ 10, 50, -1],
    [ 10, 50, "All"],
];
var butomns = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 2,3], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columns: [0, 1, 2,3], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2,3], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2,3], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2,3], // las columnas que se exportarán
        },
    },
];

var search = {
    regex: true,
    caseInsensitive: true,
    type: "html-case-insensitive",
};

//BORRAR COLUMNA DE LA TABLA SI NO TIENE PERMISO
var index = 4;
var index2 = 5;
if ($("#permisoElim").val() == "noeliminar") {
    columns.splice(index, 1);
    index2 = 4;
}

if ($("#permisoEdit").val() == "noeditar") {
    columns.splice(index2, 1);
}

var init = function () {
    var api = this.api();
    
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            if (colIdx == 0 || colIdx == 1 || colIdx ==2) {
                var cell = $(".filtersConceptoPagos th").eq(
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
                    $(".filtersConceptoPagos th").eq($(api.column(colIdx).header()).index())
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
                var cell = $(".filtersConceptoPagos th").eq(
                    $(api.column(colIdx).header()).index()
                );
                $(cell).html("");
            }
        });
};

$("#tbConceptoPagos thead tr")
    .clone(true)
    .addClass("filtersConceptoPagos")
    .appendTo("#tbConceptoPagos thead");

var table = $("#tbConceptoPagos").DataTable({
    ajax: {
         url: "conceptoPagos",
        data: { estado: "" },
    },
    orderCellsTop: true,
    fixedHeader: true,
    columns: columns,
    dom: "Bfrtip",
    buttons: butomns,
    lengthMenu: lengthmenu,
    language: lenguag,
    search: search,
    initComplete: init,
    stripeClasses: ['odd-row', 'even-row']
});

