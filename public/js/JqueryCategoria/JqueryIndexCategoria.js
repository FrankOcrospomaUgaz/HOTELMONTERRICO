
$("#filtroCategoria").submit(function (e) {
    e.preventDefault();
    $("#tbCategoria").dataTable().fnDestroy();
    var tableCat = $("#tbCategoria").DataTable({
        ajax: {
            url: "categoria",
            data: {
                estado: $("#activosCategoria").val(),
            },
        },
        orderCellsTop: true,
        
        columns: columnsCat,
        dom: "Bfrtip",
        buttons: butomnsCat,
        lengthMenu: lengthmenuCat,
        language: lenguagCat,
        search: searchCat,
        initComplete: initCat,
        stripeClasses: ['odd-row', 'even-row']
    });
});

$("#resetFiltroCategoria").click(function (e) {
    e.preventDefault();
    $("#activosCategoria").val("todos");
    $("#tbCategoria").dataTable().fnDestroy();
    var tableCat = $("#tbCategoria").DataTable({
        ajax: {
            url: "categoria",
            data: {
                estado: "",
            },
        },
        orderCellsTop: true,
        

        columns: columnsCat,
        dom: "Bfrtip",
        buttons: butomnsCat,
        lengthMenu: lengthmenuCat,
        language: lenguagCat,
        search: searchCat,
        initComplete: initCat,
        stripeClasses: ['odd-row', 'even-row']
    });
});

//CERRAR MODAL
$(document).on("click", "#cerrarModalCat", function () {
    $("#modalCategoria").modal("hide");
});

$(document).on("click", "#cerrarModalEcat", function () {
    $("#modalCategoriaE").modal("hide");
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

var columnsCat = [
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

var lenguagCat = {
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

var lengthmenuCat = [
    [ 10, 50, -1],
    [ 10, 50, "All"],
];
var butomnsCat = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 2], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columns: [0, 1, 2], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2], // las columnas que se exportarán
        },
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2], // las columnas que se exportarán
        },
    },
];

var searchCat = {
    regex: true,
    caseInsensitive: true,
    type: "html-case-insensitive",
};

//BORRAR COLUMNA DE LA TABLA SI NO TIENE PERMISO
var indexCat = 3;
var index2Cat = 4;
if ($("#permisoElim").val() == "noeliminar") {
    columnsCat.splice(indexCat, 1);
    index2Cat = 3;
}

if ($("#permisoEdit").val() == "noeditar") {
    columnsCat.splice(index2Cat, 1);
}

var initCat = function () {
    var api = this.api();
    
    api.columns()
        .eq(0)
        .each(function (colIdx) {
            if (colIdx == 0 || colIdx == 1) {
                var cell = $(".filtersCat th").eq(
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
                    $(".filtersCat th").eq($(api.column(colIdx).header()).index())
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
                var cell = $(".filtersCat th").eq(
                    $(api.column(colIdx).header()).index()
                );
                $(cell).html("");
            }
        });
};

$("#tbCategoria thead tr")
    .clone(true)
    .addClass("filtersCat")
    .appendTo("#tbCategoria thead");


var tableCat = $("#tbCategoria").DataTable({
    ajax: {
        url: "categoria",
        data: { estado: "" },
    },
    orderCellsTop: true,
    
    columns: columnsCat,
    dom: "Bfrtip",
    buttons: butomnsCat,
    lengthMenu: lengthmenuCat,
    language: lenguagCat,
    search: searchCat,
    initComplete: initCat,
    stripeClasses: ['odd-row', 'even-row']
});
