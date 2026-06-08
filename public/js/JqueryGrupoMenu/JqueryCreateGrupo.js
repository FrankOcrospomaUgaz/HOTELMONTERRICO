//VALIDACIONES EN MENSAJE
$(document).ready(function () {
    $("#btonNuevo").click(function (e) {
        $("#registroEditarGrupoMenu")[0].reset();
        $(".error-messageGrupo").attr(
            "class",
            "error-messageGrupo ajuste d-none"
        ); //Desabilita Mensaje error
        $("#iconMuestraCRUD_E").attr("class", '');

        $("#modalCrearGrupoMenu").modal("show");
    });
});

$(document).ready(function () {
    // CREAR
    $("#iconoGrupoOpcion").on("input", function () {
        // Obtener el valor del icono capturado
        var icono = $(this).val();
        console.log(icono);
        // Mostrar el icono en el elemento <i> dentro del <span> con la clase "input-group-text"
        $("#iconMuestraCRUD_E").attr("class", icono);
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
    $("#registroEditarGrupoMenu")[0].reset(); //limpiar campos

    $("#registroEditarGrupoMenu").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "categoriaMenu/guardar",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroEditarGrupoMenu")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Guardado" + response,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbCategoriaMenu").DataTable().ajax.reload(); //recargar datatable
                    $("#modalCrearGrupoMenu").modal("hide"); //ocultar modal
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
