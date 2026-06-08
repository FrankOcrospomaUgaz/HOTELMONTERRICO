function editarOpcion(id) {
    $(".error-messageE").attr("class", "error-messageE ajuste d-none"); //Desabilita Mensaje error
    $("#registroOpcionE")[0].reset(); //LIMPIAR CAMPOS
    $("#iconMuestraE").removeClass(); //limpia el icono muestra

    $.get("opciones/recuperar/" + id, function (data) {
        console.log(data.permissions.id);
        $("#idE").val(data.permissions.id); //INPUT:HIDE
        $("#nameE").val(data.permissions.name);
        $("#rutaE").val(data.permissions.ruta);
        $("#iconoE").val(data.permissions.icono);

        $("#categoriaE").html("");
        $.each(data.Grupos, function (index, item) {
            $("#categoriaE").append(
                `<option value="${item.id}"> ${item.nombre}</option>`
            );

            // Si el valor de item.id coincide con el valor que deseas seleccionar,
            // establece la propiedad "selected" en "true" para esa opción.
            if (item.id == data.permissions.grupomenu_id) {
                $("#categoriaE option[value='" + item.id + "']").prop(
                    "selected",
                    true
                );
            }
        });


        //Recuperar ICONO MUESTRA
        var icono = $("#iconoE").val();
        $("#iconMuestraE").attr("class", icono);
        //MOSTRAR NUEVO
        $("#iconoE").on("input", function () {
            // Obtener el valor del icono capturado
            var icono = $(this).val(); // Mostrar el icono en el elemento <i> dentro del <span> con la clase "input-group-text"
            $("#iconMuestraE").attr("class", icono);
        });

        $("#modalEditarOpcionE").modal("show");
    });
}

$("#iconoE").on("input", function () {
    // Obtener el valor del icono capturado
    var icono = $(this).val();

    // Mostrar el icono en el elemento <i> dentro del <span> con la clase "input-group-text"
    $("#iconMuestraE").attr("class", icono);
});

function editarCRUD(id) {
    // DATTATABLE DEL MODAL
    $(document).ready(function () {
        $("#tbCRUD").dataTable().fnDestroy();
        ($columns = [0, 1, 3, 4]), // las columnas que se exportarán
            $("#tbCRUD").DataTable({
                ajax: "opciones/CRUD/" + id,
                columns: [
                    {
                        data: "name",
                    },
                    {
                        data: "ruta",
                    },
                    {
                        data: "icono",
                        render: function (data, type, full, meta) {
                            return '<i class="' + data + '">';
                        },
                    },

                    {
                        data: "created_at",
                        render: function (data, type, full, meta) {
                            var date = new Date(data);
                            return date.toLocaleString();
                        },
                    },
                    {
                        data: "updated_at",
                        render: function (data, type, full, meta) {
                            var date = new Date(data);
                            return date.toLocaleString();
                        },
                    },
                    {
                        data: "action",
                        orderable: false,
                    },
                ],

                dom: "Bfrtip",

                buttons: [
                    {
                        extend: "copy",
                        text: 'COPY <i class="fa-solid fa-copy"></i>',
                        className: "btn-secondary copy",
                        exportOptions: {
                            columns: $columns, // las columnas que se exportarán
                        },
                    },
                    {
                        extend: "csv",
                        text: 'CSV <i class="fa-solid fa-file-csv"></i>',
                        className: "btn-info csv ",
                        exportOptions: {
                            columns: $columns, // las columnas que se exportarán
                        },
                    },
                    {
                        extend: "excel",
                        text: 'EXCEL <i class="fas fa-file-excel"></i>',
                        className: "excel btn-success",
                        exportOptions: {
                            columns: $columns, // las columnas que se exportarán
                        },
                    },
                    {
                        extend: "pdf",

                        text: 'PDF <i class="far fa-file-pdf"></i>',
                        className: "btn-danger pdf",
                        exportOptions: {
                            columns: $columns, // las columnas que se exportarán
                        },
                    },
                    {
                        extend: "print",
                        text: 'PRINT <i class="fa-solid fa-print"></i>',
                        className: "btn-dark print",
                        exportOptions: {
                            columns: $columns, // las columnas que se exportarán
                        },
                    },
                ],
            });

            setTimeout(function () {
                $("#modalCRUD").modal("show");
            }, 700);
       
    });
   
}

function editarOpcionCRUD(idCrud) {
    $(".error-messageCRUD").attr("class", "error-messageCRUD ajuste d-none"); //Desabilita Mensaje error
    $("#idOpcionCrud").val(idCrud);

    $("#registroEditarCRUD")[0].reset(); //LIMPIAR CAMPOS
    $("#iconMuestraE").removeClass(); //limpia el icono muestra

    $.get("opciones/editarCRUD/" + idCrud, function (dataCrud) {
        $("#nameCRUD_E").val(dataCrud.name);
        $("#rutaCRUD_E").val(dataCrud.ruta);
        $("#iconoCRUD_E").val(dataCrud.icono);

        //Recuperar ICONO MUESTRA
        var icono = $("#iconoCRUD_E").val();
        $("#iconMuestraCRUD_E").attr("class", icono);
        //MOSTRAR NUEVO
        $("#iconoCRUD_E").on("input", function () {
            // Obtener el valor del icono capturado
            var icono = $(this).val(); // Mostrar el icono en el elemento <i> dentro del <span> con la clase "input-group-text"
            $("#iconMuestraCRUD_E").attr("class", icono);
        });
    });

    $("#modalCRUD").modal("hide");
    $("#modalEditarCRUD").modal("show");
    //CERRAR MODAL

    $(document).on("click", "#cerrarModalCRUD_E", function () {
        $("#tbCRUD").DataTable().ajax.reload(); //recargar datatable
        // editarCRUD(idOpcionPadre);
        $("#modalCRUD").modal("show");

        $("#modalEditarCRUD").modal("hide");
    });
}
