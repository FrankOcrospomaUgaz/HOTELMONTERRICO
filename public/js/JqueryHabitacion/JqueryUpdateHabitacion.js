

//ACTUALIZAR - DATOS
$(document).ready(function () {
    $("#registroHabitacionE").submit(function (e) {
        e.preventDefault();
        
        var formData = new FormData(this);

        $.get("catHabitaciones/recuperar/" + $("#idE").val(), function (data) {
            console.log(data);
            if (data.situacion != "Ocupada") {
                $.ajax({
                    type: "POST",
                    url: "catHabitaciones/editar/" + $("#idE").val(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response) {
                            $("#registroHabitacionE")[0].reset(); //limpiar campos
        
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Registro Editado "+response,
                                showConfirmButton: false,
                                timer: 1500,
                            });
        
                            $("#tbHabitacion").DataTable().ajax.reload(); //recargar datatable
                            $("#modalHabitacionE").modal("hide"); //ocultar modaL
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
            } else {
                Swal.fire({
                    title: "Habitación Ocupada",
                    text: "Para editar, debe estar libre",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#modalHabitacionE").modal("hide");
                        $("#tbHabitacion").DataTable().ajax.reload();
                    }
                });
                
            }
        });
       
    });
});