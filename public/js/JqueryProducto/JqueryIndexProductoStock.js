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
    $("#activosProducto").val("todos");
    $("#tbProductoStock").dataTable().fnDestroy();
    var table = $("#tbProductoStock").DataTable({
        ajax: {
            url: "stockProductos",
            data: {
                estado: "",
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
        data: "stock",
        orderable: false,
    },
    {
        data: "created_at",
        render: function (data, type, full, meta) {
            var date = new Date(data);
            return date.toLocaleString();
        },
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
            columnsP: [0, 1, 2, 3,4,5,6,7,8], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columnsP: [0, 1, 2, 3,4,5,6,7,8], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columnsP: [0, 1, 2, 3,4,5,6,7,8], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columnsP: [0, 1, 2, 3,4,5,6,7,8], // las columnas que se exportarán
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
        data: { estado: "" },
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







