//CERRAR MODAL
$(document).on("click", "#cerrarModal", function () {
    $("#modalNuevoOpcion").modal("hide");
});

// CREAR NUEVO OPCION
$(document).ready(function () {
    $("#btonNuevo").click(function (e) {
        e.preventDefault();
        $(".error-message").attr("class", "error-message ajuste d-none");

        // MOSTRANDO MODAL CREAR
        $("#iconMuestra").removeClass(); //limpia el icono muestra
        // LLENAR CAMPOS CATEGORIA DE OPCION MENU

        $.get("opciones/create", function (data) {
            console.log(data);
            $("#categoria").html("");
            $.each(data, function (index, item) {
                $("#categoria").html(
                    $("#categoria").html() +
                        `<option value="${item.id}"> ${item.nombre}</option>`
                );
            });
        });

        $("#registroOpcion")[0].reset();

        $("#modalNuevoOpcion").modal("show");
    });

    //LENAR CAMPOS DE CAATEGORIA O GRUPO DE MENU
    $.get("opciones/crear", function (data) {
        console.log(data);
        $("#categoria").html("");
        $.each(data, function (index, item) {
            $("#categoria").html(
                $("#categoria").html() +
                    `<option value="${item.id}"> ${item.nombre}</option>`
            );
        });
    });

    //quitar mensaje de alidacion al escribir en CREAR
    $(document).ready(function () {
        $(".error-message")
            .closest(".form-group")
            .find("input")
            .on("input", function () {
                $(this).closest(".form-group").find(".error-message").empty();
            });
    });

    $(document).ready(function () {
        $("#registroOpcion")[0].reset(); //limpiar campos

        $("#registroOpcion").submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "opciones/guardar",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response) {
                        $("#registroOpcion")[0].reset(); //limpiar campos

                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Registro Guardado",
                            showConfirmButton: false,
                            timer: 1500,
                        });

                        $("#tbOpciones").DataTable().ajax.reload(); //recargar datatable
                        $("#modalNuevoOpcion").modal("hide"); //ocultar modal
                    } else {
                        alert("nO");
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;

                        $(".error-message").removeClass("d-none"); //HaBilitar mensaje error
                        $.each(errors, function (field, messages) {
                            var element = $('[name="' + field + '"]');
                            var container = element
                                .closest(".form-group")
                                .find(".error-message");
                            container.text(messages[0]);
                        });
                    } else {
                        // maneja otros errores aquí
                    }
                },
            });
        });
    });
});
