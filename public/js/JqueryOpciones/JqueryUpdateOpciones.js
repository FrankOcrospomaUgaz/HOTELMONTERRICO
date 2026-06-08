//ACTUALIZAR - DATOS
$(document).ready(function () {
    $("#registroOpcionE").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "opciones/editar/" + $("#idE").val(),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroOpcionE")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Editado " + response,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbOpciones").DataTable().ajax.reload(); //recargar datatable
                    $("#modalEditarOpcionE").modal("hide"); //ocultar modaL
                } else {
                    alert("nO");
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $(".error-messageE").removeClass("d-none"); //HaBilitar mensaje error
                    $.each(errors, function (field, messages) {
                        var element = $('[name="' + field + '"]');
                        var container = element
                            .closest(".form-group")
                            .find(".error-messageE");
                        container.text(messages[0]);
                    });
                } else {
                    // maneja otros errores aquí
                }
            },
        });
    });

    //quitar mensaje de alidacion al escribir en EDITARR
    $(document).ready(function () {
        $(".error-messageE")
            .closest(".form-group")
            .find("input")
            .on("input", function () {
                $(this).closest(".form-group").find(".error-messageE").empty();
            });
    });

    //quitar mensaje de alidacion al escribir en CRUD EDITARR
    $(document).ready(function () {
        $(".error-messageCRUD")
            .closest(".form-group")
            .find("input")
            .on("input", function () {
                $(this)
                    .closest(".form-group")
                    .find(".error-messageCRUD")
                    .empty();
            });
    });

    //ACTUALIZAR - DATOS

    $("#registroEditarCRUD").submit(function (e) {
        e.preventDefault();

        var idOpcionCrud = $("#idOpcionCrud").val();
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "/opciones/updateCRUD/" + idOpcionCrud,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroOpcionE")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Editado " + response,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbCRUD").DataTable().ajax.reload(); //recargar datatable
                    $("#modalCRUD").modal("show");
                    $("#modalEditarCRUD").modal("hide");
                } else {
                    alert("nO");
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $(".error-messageCRUD").removeClass("d-none"); //HaBilitar mensaje error
                    $.each(errors, function (field, messages) {
                        var element = $('[name="' + field + '"]');
                        var container = element
                            .closest(".form-group")
                            .find(".error-messageCRUD");
                        container.text(messages[0]);
                    });
                } else {
                    // maneja otros errores aquí
                }
            },
        });
    });
});
