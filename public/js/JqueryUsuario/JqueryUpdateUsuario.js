//ACTUALIZAR - DATOS
$(document).ready(function () {
    $("#registroUsuarioE").submit(function (e) {
        e.preventDefault();
        console.log($("#idE").val());
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "usuarios/editar/" + $("#idE").val(),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroUsuarioE")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Editado " + response,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbUsuarios").DataTable().ajax.reload(); //recargar datatable
                    $("#modalNuevoUsuarioE").modal("hide"); //ocultar modaL
                } else {
                    alert("nO");
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $(".error-messageE").removeClass("d-none"); //HaBilitar mensaje error
                    $.each(errors, function (field, messages) {
                        console.log(messages[0]);
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
});
