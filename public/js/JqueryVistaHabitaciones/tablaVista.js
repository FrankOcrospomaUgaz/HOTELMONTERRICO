$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="vistaPrincipal">HOTEL | VISTA TABLA</a>`
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

function reporteEmergenciaPdf() {
    window.open("generarReporte-emergencia", "_blank");
}

$(document).on("click", "#cerrarModal", function () {
    $("#modalCambiarSituacion").modal("hide");
});
$(document).on("click", "#cerrarModalCambiarHabitacion", function () {
    $("#modalCambiarHabitacion").modal("hide");
});

//DATATABLE PRINCIPAL

//buscador SEARCH

var columnsCat = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return meta.row + 1; // el número de fila iniciará en 1
        },
    },

    {
        data: "numero",
        render: function (data, type, row, meta) {
            return '<div style="width:80px; margin:auto">' + data + "</div>";
        },
        orderable: false,
    },
    {
        data: "tipo",
        render: function (data, type, full, meta) {
            if (!data) {
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
        data: "situacion",
        render: function (data, type, full, meta) {
            if (!data) {
                return "-";
            } else {
                switch (data) {
                    case "Disponible":
                        return (
                            "<b style='text-transform: uppercase; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); color:green'>" +
                            data +
                            "</b>"
                        );
                        break;

                    case "Ocupada":
                        return (
                            "<b style='text-transform: uppercase; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); color:red'>" +
                            data +
                            " (" +
                            full.horas +
                            "h)</b>"
                        );
                        break;
                    case "Mantenimiento":
                        return (
                            "<b style='text-transform: uppercase; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); color:rgb(176 0 211)'>" +
                            data +
                            "</b>"
                        );
                        break;
                    case "Limpieza":
                        return (
                            "<b style='text-transform: uppercase; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); color:blue'>" +
                            data +
                            "</b>"
                        );
                        break;
                    default:
                        return "-";
                        break;
                }
            }
        },
    },
    {
        data: "horaInicio",
        render: function (data, type, full, meta) {
            
            if (data !== null) {
                var date = new Date(data);
                return '<div style="width:100px; margin:auto">'+date.toLocaleString()+'</div>';
            } else {
                return '<div style="width:100px; margin:auto">-</div>';
            }
        },
    },
    {
        data: "horaInicio",
        render: function (data, type, full, meta) {
            temporizador = `<div style="width:150px" class="mx-auto text-center">`;
            if (data !== null) {
                temporizador += `<div for="temporizadorTablaDigital${full.id}" class="mx-auto w-75"><div class="temporizadorTabla estiloOcupado"  href="javascript:void(0)" onclick="sumarTiempoHabTabla(${full.numero})"  id="temporizadorTablaDigital${full.id}">${data}</div></div>`;
                setInterval(function () {
                    updateTimerTabla(
                        `#temporizadorTablaDigital${full.id}`,
                        data,
                        full.horas
                    );
                });
            } else {
                temporizador += `<div class="mx-auto w-75"><div class="temporizadorTabla" id="temporizadorDigital${meta.row}">00:00:00</div></div>`;
            }
            temporizador += `</div>`;

            return temporizador;
        },
    },
    {
        data: "total",
        render: function (data, type, row, meta) {
            if (data != null) {
                return (
                    '<div style="width:50px; margin:auto"><b>' +
                    data +
                    "</b></div>"
                );
            } else {
                return '<div style="width:50px; margin:auto"><b>00.00</b></div>';
            }
        },
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
    [60, 100, -1],
    [60, 100, "All"],
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
            if (colIdx == 0 || colIdx == 1 || colIdx == 2 || colIdx == 3) {
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
                    $(".filtersCat th").eq(
                        $(api.column(colIdx).header()).index()
                    )
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

$("#tbHabitacionesVista thead tr")
    .clone(true)
    .addClass("filtersCat")
    .appendTo("#tbHabitacionesVista thead");

$(document).ready(function () {
    var tableCat = $("#tbHabitacionesVista").DataTable({
        ajax: {
            url: "listaHab",
        },
        fixedHeader: true,
        columns: columnsCat,
        orderCellsTop: true,
        scrollCollapse: true,
        paging: false,
        info: false,
        initComplete: initCat,
        stripeClasses: ["odd-row", "even-row"],
    });
});

// Función para obtener la diferencia de tiempo entre la hora específica y la hora actual
function getElapsedTime(startTime) {
    const now = new Date();
    const startDate = new Date(startTime);

    let elapsedTime = now.getTime() - startDate.getTime();
    elapsedTime = Math.max(elapsedTime, 0); // Asegurarse de que el temporizador no sea negativo

    return elapsedTime;
}

function opcionesHabVenta(id) {
    $.get("vistaPrincipal/situacion/" + id, function (data) {
        $("#idHabitacion").val(data.id);
        var numeroHabitacion = data.numero;
        var idHabitacion = data.id;
        if (data.situacion == "Ocupada") {
            Swal.fire({
                title: "Habitación N° " + numeroHabitacion + ":",
                showConfirmButton: true,
                confirmButtonText: "Agregar Venta",
                confirmButtonColor: "#0044ff",
                showDenyButton: true,
                denyButtonText: "Pagar",
                denyButtonColor: "#ffc107",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        "ventaHabitacion?id=" + numeroHabitacion;
                } else if (result.isDenied) {
                    window.location.href =
                        "detalleHabitacion?id=" + numeroHabitacion;
                }
            });
        } else if (data.situacion == "Disponible") {
            Swal.fire({
                title: "Habitación N° " + numeroHabitacion + ":",
                showConfirmButton: true,
                confirmButtonText: "Confirmar Checking",
                confirmButtonColor: "rgb(6 225 0)",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        "ventaHabitacion?id=" + numeroHabitacion;
                }
            });
        } else {
            $.get("vistaPrincipal/situacion/" + idHabitacion, function (data) {
                $("#numero").val(data.numero);

                $(
                    "#situacionCambio option[value='" + data.situacion + "']"
                ).prop("selected", true);

                $("#modalCambiarSituacion").modal("show");
            });
        }
    });
}

// Función para actualizar el temporizador
var objeto = null;

function updateTimerTabla(elemento, startTime, horasServicio) {
    const elapsedTime = getElapsedTime(startTime);

    // Convertir la diferencia de tiempo a horas, minutos y segundos
    const hours = Math.floor(elapsedTime / (1000 * 60 * 60));
    const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);

    // Formatear el tiempo en HH:MM:SS
    const formattedTime = `${hours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;

    // Actualizar el contenido del elemento con el temporizador
    $(elemento).html(`${formattedTime}`);

    if (hours.toString().padStart(2, "0") >= horasServicio) {
        // Si la hora actual es mayor o igual a la hora de servicio, aplicamos los estilos de .estiloRojo
        $(elemento).addClass("estiloRojo");
        $(elemento).removeClass("estiloOcupado");
    } else {
    }
}

function sumarTiempoHabTabla(numhab) {
    var html = ``;
    $.get("catServicios/show", function (dataModoHoras) {
        html = `<div class="row">
    <div class="col-md-6">
        <label for="modoHoraAdicional" class=" labelFormato">Modo Hora Adicional:</label>
        <br><select name="modoHoraAdicional" style="font-size:15px" class="form-control" id="modoHoraAdicional">
    `;
    $.each(dataModoHoras, function (index, item) {
        if (item.tipo == "Tiempo" && item.id != 1 && item.id != 4 && item.id != 21) {
            if (item.id === 7) {
                html += `<option value="${item.id}" selected>${item.nombre}</option>`;
            } else {
                html += `<option value="${item.id}">${item.nombre}</option>`;
            }
        }
        
    });
        html += `</select></div>
    <div class="col-md-6">
    <label for="horasInput" class="form-label labelFormato">Cantidad Horas:</label>
    <input id="horasInput" class="form-control" type="number" value="1" min="1" placeholder="Añadir Horas">
    </div>
    </div><div class="row">
    <div class="col-md-12">
        <label for="textoInput" class="form-label labelFormato">Notas:</label>
        <input id="textoInput" class="form-control" type="text" placeholder="Notas..." value="-">
    </div>
</div>`;

        Swal.fire({
            title: "Añadir más tiempo a la Habitación N° " + numhab,
            html: html,

            showCancelButton: true,
            confirmButtonText: "Guardar",
            showLoaderOnConfirm: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const horas = parseFloat(
                    document.getElementById("horasInput").value
                );
                const texto = document.getElementById("textoInput").value;
                const modoHoraAdicional =
                    document.getElementById("modoHoraAdicional").value;
                $.get(
                    "vistaPrincipal/sumarHorasHab" +
                        "/" +
                        numhab +
                        "/" +
                        horas +
                        "/" +
                        texto +
                        "/" +
                        modoHoraAdicional,
                    function (data) {
                        //OBTENER tipodocumentos de venta

                        $("#tbHabitacionesVista").DataTable().ajax.reload(); //recargar datatable

                        window.location.href = "listaHab";
                    }
                );
            }
        });
    });
}
