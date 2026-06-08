//VALIDACIONES EN MENSAJE
$(document).ready(function () {
    $("#btonNuevoCategoria").click(function (e) {
        $("#registroCategoria")[0].reset();
        $(".error-messageGrupo").attr(
            "class",
            "error-messageGrupo ajuste d-none"
        ); //Desabilita Mensaje error

        $("#modalCategoria").modal("show");
    });
});

//quitar mensaje de alidacion AL ESCRIBIR
$(document).ready(function () {
    $(".error-messageGrupo")
        .closest(".form-group")
        .find("input")
        .on("input", function () {
            $(this).closest(".form-group").find(".error-messageGrupo").empty();
        });
});

$(document).ready(function () {
    $(".error-messageGrupoE")
        .closest(".form-group")
        .find("input")
        .on("input", function () {
            $(this).closest(".form-group").find(".error-messageGrupoE").empty();
        });
});

$(document).ready(function () {
    // <!-- CREAR NUEVA REGISTRO-->
    $("#registroCategoria")[0].reset(); //limpiar campos

    $("#registroCategoria").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "categoria/guardar",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroCategoria")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Guardado" + response,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbCategoria").DataTable().ajax.reload(); //recargar datatable
                    $("#modalCategoria").modal("hide"); //ocultar modal
                } else {
                    alert("nO");
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $(".error-messageGrupo").removeClass("d-none"); //HaBilitar mensaje error
                    $.each(errors, function (field, messages) {
                        var element = $('[name="' + field + '"]');
                        var container = element
                            .closest(".form-group")
                            .find(".error-messageGrupo");
                        container.text(messages[0]);
                    });
                } else {
                    // maneja otros errores aquí
                }
            },
        });
    });
});
