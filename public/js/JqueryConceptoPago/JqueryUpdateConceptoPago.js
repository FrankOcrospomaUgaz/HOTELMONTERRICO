

//ACTUALIZAR - DATOS
$(document).ready(function () {
    $("#registroConceptoPagosE").submit(function (e) {
        e.preventDefault();
        
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "conceptoPagos/editar/" + $("#idE").val(),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroConceptoPagosE")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Editado "+response,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbConceptoPagos").DataTable().ajax.reload(); //recargar datatable
                    $("#modalConceptoPagosE").modal("hide"); //ocultar modaL
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