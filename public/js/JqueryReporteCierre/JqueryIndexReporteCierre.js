$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="cajaChica">HOTEL | REPORTE CIERRES</a>`
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
    dateFormat: "Y-m-d ",
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
    dateFormat: "Y-m-d ",
    maxDate: new Date(),
});

$("#filtro").submit(function (e) {
    e.preventDefault();

    $("#tbCaja").dataTable().fnDestroy();
    var table = $("#tbCaja").DataTable({
        ajax: {
            url: "repCierreCaja",
            data: {
                calendarioInicio: $("#calendarioInicio").val(),
                calendarioFin: $("#calendarioFin").val(),
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
        stripeClasses: ["odd-row", "even-row"],
    });
});

$("#resetFiltro").click(function (e) {
    e.preventDefault();
    $("#calendarioInicio").val("");
    $("#calendarioFin").val("");


    $("#tbCaja").dataTable().fnDestroy();
    var table = $("#tbCaja").DataTable({
        ajax: {
            url: "repCierreCaja",
        },
        orderCellsTop: true,
        scrollCollapse: true,
        columns: columns,
        dom: "Bfrtip",
        buttons: buttons,
        lengthMenu: lenghtMnenu,
        language: lenguage,
        search: search,
        stripeClasses: ["odd-row", "even-row"],
    });
    
});



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
        data: "fechaApertura",
        render: function (data, type, full, meta) {
            var date = new Date(data);
            return "<div style='width:100px' class='text-center mx-auto'><p>" + date.toLocaleString()+ "</p></div>";
        },
    },
    {
        data: "turno",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<div style='width:100px' class='text-center mx-auto'><p>" + data + "</p></div>";
            }
        },
    },
    {
        data: "nombres",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<div style='width:100px' class='text-center mx-auto'><p>" + data + "</p></div>";
            }
        },
    },
    {
        data: "totalApertura",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<div style='width:100px' class='text-center mx-auto'><p>" + data + "</p></div>";
            }
        },
        orderable: false,
    },
    {
        data: "fechaCierre",
        render: function (data, type, full, meta) {
            if (data == "En curso") {
                return `<div style=' width: 100px;border-radius: 10px;
                border: solid 1 px black;color: white; background-color: #00a8b7; '
                class='text-center mx-auto'><p>` + data + `</p></div>`;
            } else {
                var date = new Date(data);
                return date.toLocaleString();
            }
        },
    },
    {
        data: "totalCierre",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                return "<div style='width:100px' class='text-center mx-auto'><p>" + data + "</p></div>";
            }
        },
        orderable: false,
    },

    {
        data: "action",
        orderable: false,
    },
    {
        data: "cuadreCaja",
        orderable: false,
    },
];
var buttons = [
    {
        extend: "copy",
        text: 'COPY <i class="fa-solid fa-copy"></i>',
        className: "btn-secondary copy",
        exportOptions: {
            columns: [0, 1, 2, 3, 4], // las columnas que se exportarán
        },
    },
    {
        extend: "csv",
        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
        className: "btn-info csv ",
        exportOptions: {
            columns: [0, 1, 2, 3, 4], // las columnas que se exportarán
        },
    },
    {
        extend: "excel",
        text: 'EXCEL <i class="fas fa-file-excel"></i>',
        className: "excel btn-success",
        exportOptions: {
            columns: [0, 1, 2, 3, 4], // las columnas que se exportarán
        },
    },
    {
        extend: "pdf",

        text: 'PDF <i class="far fa-file-pdf"></i>',
        className: "btn-danger pdf",
        exportOptions: {
            columns: [0, 1, 2, 3, 4], // las columnas que se exportarán
        },
        orientation: "landscape", // Establece la orientación horizontal
    },
    {
        extend: "print",
        text: 'PRINT <i class="fa-solid fa-print"></i>',
        className: "btn-dark print",
        exportOptions: {
            columns: [0, 1, 2, 3, 4], // las columnas que se exportarán
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


var table = $("#tbCaja").DataTable({
    ajax: {
        url: "repCierreCaja",
    },
    orderCellsTop: true,
    
    scrollCollapse: true,

    columns: columns,
    dom: "Bfrtip",
    buttons: buttons,
    lengthMenu: lenghtMnenu,
    language: lenguage,
    search: search,
    stripeClasses: ["odd-row", "even-row"],
});

