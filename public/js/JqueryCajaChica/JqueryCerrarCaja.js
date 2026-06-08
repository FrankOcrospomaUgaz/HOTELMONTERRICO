$(document).ready(function () {
    $("#registroCierre").submit(function (e) {
        e.preventDefault();

        Swal.fire({
            title: "Confirmación de Cerrar Caja",
            text: "Con un monto en caja de: S/" + $("#montoCaja").val(),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Confirmo!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: "cajaChica/cierre/guardarCierre",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response) {
                            $("#registroCierre")[0].reset(); //limpiar campos
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Registro Guardado con Exito",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            $("#tbCaja").DataTable().ajax.reload(); //recargar datatable
                            $("#tbListadoEgresos").DataTable().ajax.reload(); //recargar datatable
                            $("#modalCierre").modal("hide"); //ocultar modal
                            window.location.href = "cajaChica";
                        } else {
                            alert("nO");
                        }
                    },
                });
            }
        });
    });
});

$(document).ready(function () {
    $("#btnDetalleOtrosEgresos").click(function (e) {
        e.preventDefault();
        $.get("cajaChica/apertura/buscarApertura", function (data) {
            if (data == 1) {
                $(".cajaListadoEgresos").toggleClass("d-none");
                $("html, body").animate(
                    {
                        scrollTop: $("#lblTituloEgresos").offset().top,
                    },
                    400
                );
            }
        });
    });
    var columns1 = [
        {
            data: null,
            render: function (data, type, row, meta) {
                if (data === null) {
                } else {
                    return meta.row + 1; // El número de fila iniciará en 1
                }
            },
        },

        {
            data: "numero",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    return (
                        "<div style='width:200px;font-size:16px'>" +
                        data +
                        "<div>"
                    );
                }
            },
            orderable: false,
        },

        {
            data: "fecha",
            render: function (data, type, full, meta) {
                var date = new Date(data);
                if (!data) {
                    return "-";
                } else {
                    return (
                        "<div style='width:100px;font-size:14px'>" +
                        date.toLocaleString() +
                        "<div>"
                    );
                }
            },
        },
        {
            data: "ConceptoPago",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    return (
                        "<div style='width:100px;font-size:14px'>" +
                        data +
                        "<div>"
                    );
                }
            },
        },


        {
            data: "persona",
            render: function (data, type, full, meta) {
                if (!data) {
                    return "-";
                } else {
                    return "<p style='font-size:14px'>" + data + "</p>";
                }
            },
            orderable: false,
        },

        // {
        //     data: "situacion",
        //     render: function (data, type, full, meta) {
        //         if (!data) {
        //             return "-";
        //         } else {
        //             switch (data) {
        //                 case "Normal":
        //                     return (
        //                         '<strong style="background: #4caf50;font-size:14px; color:white" class="btn-sm formatoBtn">' +
        //                         data +
        //                         "<strong/>"
        //                     );
        //                     break;

        //                 case "Anulado":
        //                     return (
        //                         '<strong style="background: red;font-size:14px; color:white" class="btn-sm formatoBtn">' +
        //                         data +
        //                         "<strong/>"
        //                     );
        //                     break;
        //                 default:
        //                     return "-";
        //                     break;
        //             }
        //         }
        //     },
        //     orderable: false,
        // },
        {
            className: "dt-control",
            orderable: false,
            data: null,
            defaultContent: "",
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
    ];
    var buttons1 = [
        {
            extend: "copy",
            text: 'COPY <i class="fa-solid fa-copy"></i>',
            className: "btn-secondary copy",
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 7], // las columnas que se exportarán
            },
        },
        {
            extend: "csv",
            text: 'CSV <i class="fa-solid fa-file-csv"></i>',
            className: "btn-info csv ",
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 7], // las columnas que se exportarán
            },
        },
        {
            extend: "excel",
            text: 'EXCEL <i class="fas fa-file-excel"></i>',
            className: "excel btn-success",
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 7], // las columnas que se exportarán
            },
        },
        {
            extend: "pdf",

            text: 'PDF <i class="far fa-file-pdf"></i>',
            className: "btn-danger pdf",
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 7], // las columnas que se exportarán
            },
            orientation: "landscape", // Establece la orientación horizontal
        },
        // {
        //     extend: "",
        //     text: 'PRINT <i class="fa-solid fa-print"></i>',
        //     className: "btn-dark print printEGresos",
        //     exportOptions: {
        //         columns: [0, 1, 2, 3, 4, 5, 7], // las columnas que se exportarán
        //     },
        // },
    ];
    var lenghtMnenu1 = [
        [10, 50, -1],
        [10, 50, "All"],
    ];
    var lenguage1 = {
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
    var search1 = {
        regex: true,
        caseInsensitive: true,
        type: "html-case-insensitive",
    };

    var table1 = $("#tbListadoEgresos").DataTable({
        ajax: {
            url: "reporteEgresos",
        },
        orderCellsTop: true,

        scrollCollapse: true,

        columns: columns1,
        dom: "Bfrtip",
        buttons: buttons1,
        lengthMenu: lenghtMnenu1,
        language: lenguage1,
        search: search1,
        stripeClasses: ["odd-row", "even-row"],
    });

    // Add event listener for opening and closing details
    table1.on("click", "td.dt-control", function (e1) {
        let tr = e1.target.closest("tr");
        let row1 = table1.row(tr);

        if (row1.child.isShown()) {
            row1.child.hide();
        } else {
            formatEgreso(row1.data())
    .then(tabla => {
        row1.child(tabla).show();
        console.log(tabla); // Aquí puedes usar la variable 'tabla'
    });

        }
    });
});

//PROBANDO MOSTRAR DETALLE DATOS

function formatEgreso(d) {
    const efectivo = d.efectivo ? `<dd>${d.efectivo}</dd>` : "<dd>0.00</dd>";
    const yape = d.yape ? `<dd>${d.yape}</dd>` : "<dd>0.00</dd>";
    const tarjeta = d.tarjeta ? `<dd>${d.tarjeta}</dd>` : "<dd>0.00</dd>";
    const deposito = d.deposito ? `<dd>${d.deposito}</dd>` : "<dd>0.00</dd>";
    const plin = d.plin ? `<dd>${d.plin}</dd>` : "<dd>0.00</dd>";

    return new Promise((resolve, reject) => {
        $.get("cajaChica/obtenerDetalleEgresos/" + d.id, function (data) {
            let tablaCuerpo = '';

            data.forEach((element) => {
                tablaCuerpo += `
                    <tr>
                        <td>${element.nota}</td>
                        <td>${element.tipo}</td>
                        <td>${element.monto}</td>
                    </tr>
                `;
            });

            const tabla = `
                <table class="table table-secondary">
                    <thead>
                        <tr>
                            <th>Nota</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tablaCuerpo}
                    </tbody>
                </table>
            `;

            resolve(tabla+`<div class="row justify-content-center">
            <div class="col-md-2">
                <div class="custom-table">
                    <table class="table" style="background-color: white;">
                        <tbody>
                            <tr><th>Efectivo:</th><td>${efectivo}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <div class="custom-table">
                    <table class="table" style="background-color: white;">
                        <tbody>
                            <tr><th>Yape:</th><td>${yape}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <div class="custom-table">
                    <table class="table" style="background-color: white;">
                        <tbody>
                            <tr><th>Plin:</th><td>${plin}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <div class="custom-table">
                    <table class="table" style="background-color: white;">
                        <tbody>
                            <tr><th>Tarjeta:</th><td>${tarjeta}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <div class="custom-table">
                    <table class="table" style="background-color: white;">
                        <tbody>
                            <tr><th>Deposito:</th><td>${deposito}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        `);
        });
    });
}



$("#imprimirEgresos").click(function (e) {
    e.preventDefault();

    $.get("cajaChica/apertura/buscarApertura", function (data) {
        if (data == 1) {
            window.open("generarReportesEgresos", "_blank");
        }
    });
});
