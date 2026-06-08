$(document).ready(function () {
    $("#tituloPagina").html(`<a class="nav-link active" href="catProductos">HOTEL | PRODUCTOS</a>`);
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

});
$("#filtroProducto").submit(function (e) {
    e.preventDefault();

    $("#tbProducto").dataTable().fnDestroy();
    var table = $("#tbProducto").DataTable({
        ajax: {
            url: "catProductos",
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
    $("#tbProducto").dataTable().fnDestroy();
    var table = $("#tbProducto").DataTable({
        ajax: {
            url: "catProductos",
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
$.extend($.fn.dataTable.ext.type.search, {
    // Agregar la opción "i" a la expresión regular para que no haga diferencia entre mayúsculas y minúsculas
    "html-case-insensitive": function (data) {
        return data
            .replace(/[\r\n]/g, " ") //elimina espaciados
            .replace(/<.*?>/g, "") //elimina caracterres html <>
            .toLowerCase(); //convierte en minusculas
    },
});

var columnsP = [
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
    {
        data: "estado",
        orderable: false,
    },
    {
        data: "action",
        orderable: false,
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
    [ 10, 50, -1],
    [ 10, 50, "All"],
];
var butomnsProd = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columnsP: [0, 1, 2, 3,4,5,6,7], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columnsP: [0, 1, 2, 3,4,5,6,7], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columnsP: [0, 1, 2, 3,4,5,6,7], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columnsP: [0, 1, 2, 3,4,5,6,7], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columnsP: [0, 1, 2, 3,4,5,6,7], // las columnas que se exportarán
        },
    },
];

var searchP = {
    regex: true,
    caseInsensitive: true,
    type: "html-case-insensitive",
};

//BORRAR COLUMNA DE LA TABLA SI NO TIENE PERMISO
var index = 9;
var index2 = 10;
if ($("#permisoElim").val() == "noeliminar") {
    columnsP.splice(index, 1);
    index2 = 9;
}

if ($("#permisoEdit").val() == "noeditar") {
    columnsP.splice(index2, 1);
}

var initP = function initP() {
    var api = this.api();
    
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            if (colIdx == 0 || colIdx == 1||
                colIdx == 2||colIdx == 3||colIdx == 4
                ||colIdx == 5||colIdx == 6) {
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

$("#tbProducto thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbProducto thead");

var table = $("#tbProducto").DataTable({
    ajax: {
        url: "catProductos",
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







