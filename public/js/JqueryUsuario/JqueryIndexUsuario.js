$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="usuarios">HOTEL | PERSONAS</a>`
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
//CERRAR MODAL
$(document).ready(function () {
    $(document).on("click", "#cerrarModalUsuario", function () {
        $("#modalNuevoUsuario").modal("hide");
    });
    //CERRAR MODAL
    $(document).on("click", "#cerrarModalUsuarioE", function () {
        $("#modalNuevoUsuarioE").modal("hide");
    });
});

$("#filtro").submit(function (e) {
    e.preventDefault();

    $("#tbUsuarios").dataTable().fnDestroy();
    var table = $("#tbUsuarios").DataTable({
        ajax: {
            url: "usuarios",
            data: {
                estado: $("#activos").val(),
            },
        },
        orderCellsTop: true,

        scrollX: true,
        scrollCollapse: true,
        columns: columns,
        dom: "Bfrtip",
        buttons: buttons,
        lengthMenu: lenghtMnenu,
        language: lenguage,
        search: search,
        initComplete: init,
        stripeClasses: ["odd-row", "even-row"],
    });
});
$("#resetFiltro").click(function (e) {
    e.preventDefault();
    $("#activos").val("todos");
    $("#tbUsuarios").dataTable().fnDestroy();
    var table = $("#tbUsuarios").DataTable({
        ajax: {
            url: "usuarios",
            data: {
                estado: $("#activos").val(),
            },
        },
        orderCellsTop: true,

        scrollX: true,
        scrollCollapse: true,
        columns: columns,
        dom: "Bfrtip",
        buttons: buttons,
        lengthMenu: lenghtMnenu,
        language: lenguage,
        search: search,
        initComplete: init,
        stripeClasses: ["odd-row", "even-row"],
    });
});
//DATATABLE

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
        data: "nombres",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<p>" + data + "</p>";
            }
        },
        orderable: false,
    },
    {
        data: "apellidos",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<p>" + data + "</p>";
            }
        },
        orderable: false,
    },
    {
        data: "dni",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<p>" + data + "</p>";
            }
        },
        orderable: false,
    },
    {
        data: "ruc",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<p>" + data + "</p>";
            }
        },
        orderable: false,
    },
    {
        data: "razonsocial",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<p>" + data + "</p>";
            }
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
        className: "dt-control",
        orderable: false,
        data: null,
        defaultContent: "",
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
var buttons = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
        },
        orientation: "landscape", // Establece la orientación horizontal
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6], // las columnas que se exportarán
        },
    },
];
var lenghtMnenu = [
    [10, 50, -1],
    [10, 50, "All"],
];
var lenguage = {
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
            // Add filter only for columns 0 and 1
            if (
                colIdx == 0 ||
                colIdx == 1 ||
                colIdx == 2 ||
                colIdx == 3 ||
                colIdx == 4 ||
                colIdx == 5 ||
                colIdx == 6
            ) {
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

$("#tbUsuarios thead tr")
    .clone(true)
    .addClass("filters")
    .appendTo("#tbUsuarios thead");
var table = $("#tbUsuarios").DataTable({
    ajax: {
        url: "usuarios",
        data: { estado: "" },
    },
    orderCellsTop: true,

    scrollX: true,
    scrollCollapse: true,
    columns: columns,
    dom: "Bfrtip",
    buttons: buttons,
    lengthMenu: lenghtMnenu,
    language: lenguage,
    search: search,
    initComplete: init,
    stripeClasses: ["odd-row", "even-row"],
});

//PROBANDO MOSTRAR DETALLE DATOS

function format(d) {
    const phoneNumber = d.telefono ? `<dd>${d.telefono}</dd>` : "<dd>-</dd>";
    const email = d.email ? `<dd>${d.email}</dd>` : "<dd>-</dd>";
    const tipoUsuario = d.tipoUsuario
        ? `<dd>${d.tipoUsuario}</dd>`
        : "<dd>-</dd>";
    const username = d.username ? `<dd>${d.username}</dd>` : "<dd>-</dd>";
    const direccion = d.direccion ? `<dd>${d.direccion}</dd>` : "<dd>-</dd>";
    return `
      <div class="row">
        <div class="col-md-6">
          <table class="table  ">
            <tbody>
              <tr>
                <th>Telefono:</th>
                <td>${phoneNumber}</td>
              </tr>
              <tr>
                <th>Email:</th>
                <td>${email}</td>
              </tr>
              <tr>
                <th>Dirección:</th>
                <td>${direccion}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table  ">
            <tbody>
              <tr>
                <th>Tipo Usuario:</th>
                <td>${tipoUsuario}</td>
              </tr>
              <tr>
                <th>Username:</th>
                <td>${username}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    `;
}

// Add event listener for opening and closing details
table.on("click", "td.dt-control", function (e) {
    let tr = e.target.closest("tr");
    let row = table.row(tr);

    if (row.child.isShown()) {
        row.child.hide();
    } else {
        row.child(format(row.data())).show();
    }
});
