$(document).ready(function () {
    $("#btonNuevo").click(function (e) {
        $("#registroUsuario")[0].reset();
        $(".error-message").attr("class", "error-message ajuste d-none"); //Desabilita Mensaje error
        $(".CajaRUC").addClass("d-none");
        $(".cajaUsuario").addClass("d-none");
        $(".CajaDNI").removeClass("d-none");
        $("#razonsocial").val("");
        $("#direccion").val("");
        // LLENAR TIPOS DE USUARIO
        $.get("usuarios/create", function (data) {
            $("#tipoUsuario").html("");
            $.each(data.roles, function (index, item) {
                $("#tipoUsuario").html(
                    $("#tipoUsuario").html() +
                        `<option value="${item.id}"> ${item.name}</option>`
                );
            });
        });

        $.get("rolPersona/show", function (data) {
            $(".cajaCheckBoxRoles").html("");
            $.each(data, function (index, item) {
                if (index == 0) {
                    $(".cajaCheckBoxRoles").append(
                        `<div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="Usuario"  name="roles[]" value="` +
                            item.id +
                            `">
                                            <label class="form-check-label" for="Usuario">` +
                            item.descripcion +
                            `</label>
                        </div>`
                    );
                } else {
                    $(".cajaCheckBoxRoles").append(
                        `<div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="` +
                            item.descripcion +
                            `"  name="roles[]" value="` +
                            item.id +
                            `">
                                        <label class="form-check-label" for="` +
                            item.descripcion +
                            `">` +
                            item.descripcion +
                            `</label>
                    </div>`
                    );
                }
            });
            $("#Usuario").change(function () {
                if ($("#Usuario").is(":checked")) {
                    $(".cajaUsuario").removeClass("d-none");
                } else {
                    $(".cajaUsuario").addClass("d-none");
                }
            });
            $("#modalNuevoUsuario").modal("show");
        });
    });

    $(document).on("click", "#dniBuscar", function () {
        if ($("#selectDNI-RUC").val() == "DNI") {
            $.get("usuarios/buscarDNI/" + $("#dni").val(), function (data) {
                if (data.mensaje) {
                    $(".error-message").removeClass("d-none"); //HaBilitar mensaje error
                    $("#nombre").val("");
                    $("#apellPaterno").val("");
                    $("#apellMaterno").val("");
                    var element = $("[name=dni]");
                    var container = element
                        .closest(".form-group")
                        .find(".error-message");
                    container.text(data.mensaje);
                } else {
                    $("#nombre").val(data.nombres);
                    $("#apellPaterno").val(data.apepat);
                    $("#apellMaterno").val(data.apemat);
                }
            }).fail(function (xhr, status, error) {
                console.log("Hubo un error: " + error);
            });
        } else {
            $.get("usuarios/buscarRUC/" + $("#dni").val(), function (data) {
                if (data.mensaje) {
                    $(".error-message").removeClass("d-none"); //HaBilitar mensaje error
                    $("#razonsocial").val("");
                    $("#direccion").val("");
                    var element = $("[name=dni]");
                    var container = element
                        .closest(".form-group")
                        .find(".error-message");
                    container.text(data.mensaje);
                } else {
                    $("#razonsocial").val(data.RazonSocial);
                    $("#direccion").val(data.Direccion);
                }
            }).fail(function (xhr, status, error) {
                console.log("Hubo un error: " + error);
            });
        }
    });
});

$(document).ready(function () {
    $("#selectDNI-RUC").change(function () {
        $(".error-message").attr("class", "error-message ajuste d-none"); //Desabilita Mensaje error
        $("#dni").val("");
        if ($("#selectDNI-RUC").val() == "DNI") {
            $(".CajaRUC").addClass("d-none");
            $(".CajaDNI").removeClass("d-none");
            $("#razonsocial").val("");
            $("#direccion").val("");
        } else {
            $(".CajaDNI").addClass("d-none");
            $(".CajaRUC").removeClass("d-none");
            $("#nombre").val("");
            $("#apellPaterno").val("");
            $("#apellMaterno").val("");
        }
    });
});

$(document).ready(function () {
    // <!-- CREAR NUEVA REGISTRO-->
    $("#registroUsuario")[0].reset(); //limpiar campos

    $("#registroUsuario").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "usuarios/guardar",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroUsuario")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Guardado con Exito" ,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbUsuarios").DataTable().ajax.reload(); //recargar datatable
                    $("#modalNuevoUsuario").modal("hide"); //ocultar modal

                    if ($("#clientes").length > 0) {
                        $.get("ventaHabitacion/show", function (data) {
                            //OBTENER CLIENTES
                            console.log(data);

                            $("#clientes").html(``);

                            $.each(data, function (index, item) {
                                if (item.dni != null) {
                                    $("#clientes").html(
                                        $("#clientes").html() +
                                            `<option value="${item.id}">${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                                    );
                                } else if (item.ruc != null) {
                                    $("#clientes").html(
                                        $("#clientes").html() +
                                            `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                                    );
                                } else {
                                    $("#clientes").html(
                                        $("#clientes").html() +
                                            `<option value="${item.id}">${item.nombres}</option>`
                                    );
                                }
                                if (index === data.length - 1) {
                                    console.log("Último índice:", index);
                                  }
                            });
                        });
                    }
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

//quitar mensaje de alidacion AL ESCRIBIR
$(document).ready(function () {
    $(".error-message")
        .closest(".form-group")
        .find("input")
        .on("input", function () {
            $(this).closest(".form-group").find(".error-message").empty();
        });
});
$(document).ready(function () {
    $(".error-messageE")
        .closest(".form-group")
        .find("input")
        .on("input", function () {
            $(this).closest(".form-group").find(".error-messageE").empty();
        });
});
