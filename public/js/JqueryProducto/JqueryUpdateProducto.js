

//ACTUALIZAR - DATOS
$(document).ready(function () {
    $("#registroProductoE").submit(function (e) {
        e.preventDefault();
        
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "catProductos/editar/" + $("#idE").val(),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroProducto")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Editado "+response,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbProducto").DataTable().ajax.reload(); //recargar datatable
                    $("#modalProductoE").modal("hide"); //ocultar modaL
                } else {
                    alert("nO");
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $('.error-messageGrupoE').removeClass('d-none');//HaBilitar mensaje error
                    $.each(errors, function (field, messages) {
                        var element = $('[name="' + field + '"]');
                        var container = element
                            .closest(".form-group")
                            .find(".error-messageGrupoE");
                        container.text(messages[0]);
                    });
                } else {
                    // maneja otros errores aquí
                }
            },
        });
    });
});