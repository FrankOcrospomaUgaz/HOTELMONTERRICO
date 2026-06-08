$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="consumoHab">HOTEL | CHECK OUT</a>`
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

    $(".btnPdf").removeClass("d-none");
    // Obtener la fecha de mañana
    // Obtener la fecha y hora actual en UTC
var fechaActual = new Date();

// Ajustar la fecha para Lima, Perú (UTC-5)
fechaActual.setHours(fechaActual.getHours() - 5);

// Obtener la fecha de mañana
var fechaManana = new Date(fechaActual);
fechaManana.setDate(fechaManana.getDate() + 1);

// Formatear la fecha en el campo de entrada
$("#calendarioInicio").val(fechaManana.toISOString().split("T")[0]);

});

$(document).ready(function () {
    actualizarcamposTotales();
});

var columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return meta.row + 1; //
        },
    },
    {
        data: "numero",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return (
                    "<div style='width:60px;font-size:14px' >" + data + "<div>"
                );
            }
        },
        orderable: false,
    },
    {
        data: "tipo",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                switch (data) {
                    case "Ingreso":
                        return (
                            '<strong style="background: #058ba0;font-size:14px; color:white" class="btn-sm formatoBtn">' +
                            data +
                            "<strong/>"
                        );
                        break;

                    case "Egreso":
                        return (
                            '<strong style="background: red;font-size:14px; color:white" class="btn-sm formatoBtn">' +
                            data +
                            "<strong/>"
                        );
                        break;
                }
            }
        },
        orderable: false,
    },
    {
        data: "conceptoPago",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                switch (data) {
                    case "Apertura de Caja":
                        return (
                            '<div style="width:150px"><strong style="background: #ffeb3b;font-size:14px; color:black" class="btn-sm formatoBtn">' +
                            data +
                            "<strong/></div>"
                        );
                        break;

                    default:
                        return (
                            '<div style="width:150px"><strong style="background: #ff9800; font-size:14px;color:white" class="btn-sm formatoBtn">' +
                            data +
                            "<strong/></div>"
                        );
                        break;
                }
            }
        },
        orderable: false,
    },
    {
        data: "nombreUsuario",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return (
                    "<div style='width:130px'><p style='font-size:14px'>" +
                    data +
                    "</p></div>"
                );
            }
        },
        orderable: false,
    },
    {
        data: "nombreResponsable",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return (
                    "<div style='width:90px;font-size:14px'><p>" + data + "</p>"
                );
            }
        },
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

                    case "Anulado":
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
        data: "numHab",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return (
                    "<div style='width:90px;font-size:14px'><p>" + data + "</p>"
                );
            }
        },
        orderable: false,
    },

    {
        data: "total",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<p style='font-size:14px'>" + data + "</p>";
            }
        },
        orderable: false,
    },

    {
        data: "fecha",
        render: function (data, type, full, meta) {
            var date = new Date(data);
            return (
                "<div style='width:100px;font-size:14px'>" +
                date.toLocaleString() +
                "<div>"
            );
        },
    },

    {
        className: "dt-control",
        orderable: false,
        data: null,
        defaultContent: "",
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
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
        orientation: "landscape", // Establece la orientación horizontal
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // las columnas que se exportarán
        },
    },
];
var lenghtMnenu = [
    [30, 50, -1],
    [30, 50, "All"],
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
                colIdx == 6 ||
                colIdx == 7
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

$("#tbCaja thead tr").clone(true).addClass("filters").appendTo("#tbCaja thead");
var table = $("#tbCaja").DataTable({
    ajax: {
        url: "consumoHab",
        data: { estado: "" },
    },
    orderCellsTop: true,

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
    const efectivo = d.efectivo ? `<dd>${d.efectivo}</dd>` : "<dd>0.00</dd>";
    const yape = d.yape ? `<dd>${d.yape}</dd>` : "<dd>0.00</dd>";
    const tarjeta = d.tarjeta ? `<dd>${d.tarjeta}</dd>` : "<dd>0.00</dd>";
    const deposito = d.deposito ? `<dd>${d.deposito}</dd>` : "<dd>0.00</dd>";
    const plin = d.plin ? `<dd>${d.plin}</dd>` : "<dd>0.00</dd>";
    var vuelto = "";
    var tituloVuelto = "";

    if (d.turno != null) {
        tituloVuelto = "Turno";
        vuelto = "<dd>" + d.turno + "</dd>";
    } else {
        tituloVuelto = "Vuelto";
        vuelto = d.vuelto
            ? `<dd>${(d.vuelto * -1.0).toFixed(2)}</dd>`
            : "<dd>0.00</dd>";
    }

    const comentario = d.comentario ? `<dd>${d.comentario}</dd>` : "<dd>-</dd>";
    return `
      <div class="row">
        <div class="col-md-6">
        <table class="table" style="background-color: white;">
            <tbody>
              <tr>
                <th>Efectivo:</th>
                <td>${efectivo}</td>
              </tr>
              <tr>
                <th>Yape:</th>
                <td>${yape}</td>
              </tr>
              <tr>
                <th>Plin:</th>
                <td>${plin}</td>
              </tr>
              <tr>
                <th>Comentario:</th>
                <td>${comentario}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-6">
        <table class="table" style="background-color: white;">
            <tbody>
              <tr>
                <th>Tarjeta:</th>
                <td>${tarjeta}</td>
              </tr>
              <tr>
                <th>Deposito:</th>
                <td>${deposito}</td>
              </tr>
             
              <tr>
              <th>${tituloVuelto}:</th>
              <td class="text-danger"><b>${vuelto}</b></td>



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

$(document).ready(function () {
    $("#verBusqueda").click(function (e) {
        e.preventDefault();
        $(".cajaVerBusqueda").toggleClass("d-none");
    });
});

$("#filtro").submit(function (e) {
    e.preventDefault();

    $("#tbCaja").dataTable().fnDestroy();
   table = $("#tbCaja").DataTable({
        ajax: {
            url: "consumoHab",
            data: {
                calendarioInicio: $("#calendarioInicio").val(),
                calendarioFin: $("#calendarioFin").val(),
                numero: $("#numeroHabit").val(),
            },
        },
        orderCellsTop: true,

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
    Swal.fire({
        title: 'Procesando Datos',
        text: 'Por favor, espere mientras se cargan los datos...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        },
        timer: 3000, // Tiempo de carga en milisegundos (3 segundos en este caso)
        timerProgressBar: true, // Muestra una barra de progreso
        customClass: {
            title: 'my-custom-title', // Clase de CSS personalizada para el título
            popup: 'my-custom-popup', // Clase de CSS personalizada para el fondo de la alerta
            icon: 'my-custom-icon' // Clase de CSS personalizada para el icono de carga
        }
    }).then(() => {
        Swal.close(); // Cierra la alerta después de que se complete la carga
    });
    
    
    
});
