//quitar mensaje de alidacion al escribir en EDITARR
$(document).ready(function () {
    $(".error-messageE")
        .closest(".formGrupo")
        .find("input")
        .on("input", function () {
            $(this).closest(".formGrupo").find(".error-messageE").empty();
        });
});

$("#mostrar-contrasenaAnterior").on("mousedown touchstart", function () {
    var contrasenaInput = $("#anteriorContraseña");
    var tipoInput = contrasenaInput.attr("type");
    if (tipoInput === "password") {
        contrasenaInput.attr("type", "text");
    } else {
        contrasenaInput.attr("type", "password");
    }
});

$("#mostrar-contrasenaAnterior").on("mouseup touchend", function () {
    var contrasenaInput = $("#anteriorContraseña");
    contrasenaInput.attr("type", "password");
});

$("#mostrar-contrasena").on("mousedown touchstart", function () {
    var contrasenaInput = $("#nuevaContraseña");
    var tipoInput = contrasenaInput.attr("type");
    if (tipoInput === "password") {
        contrasenaInput.attr("type", "text");
    } else {
        contrasenaInput.attr("type", "password");
    }
});

$("#mostrar-contrasena").on("mouseup touchend", function () {
    var contrasenaInput = $("#nuevaContraseña");
    contrasenaInput.attr("type", "password");
});

$("#mostrar-contrasenaConfirm").on("mousedown touchstart", function () {
    var contrasenaInput = $("#password-confirm");
    var tipoInput = contrasenaInput.attr("type");
    if (tipoInput === "password") {
        contrasenaInput.attr("type", "text");
    } else {
        contrasenaInput.attr("type", "password");
    }
});
$("#mostrar-contrasenaConfirm").on("mouseup touchend", function () {
    var contrasenaInput = $("#password-confirm");
    contrasenaInput.attr("type", "password");
});

$("#updatePerfil").submit(function (e) {
    e.preventDefault();
    $(".error-messageE").attr("class", "error-messageE ajuste d-none"); //Desabilita Mensaje error
    var formData = new FormData(this);

    $.ajax({
        type: "POST",
        url: "perfil/editar/" + $("#idInfo").val(),
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Registro Editado con Exito",
                    showConfirmButton: false,
                    timer: 1500,
                });
                console.log(response);
                $("#nombreInfo").text(
                    response.nombres + " " + response.apellidos
                );
                $("#emailInfo").text(response.email);
                $("#nombreUsuarioInfo").text(response.username);
                $("#telefonoInfo").text(response.telefono);
                $("#rolInfo").text(response.tipoUsuario);
                $("#nombresE").val(response.nombres);
                $("#apPaternoE").val(response.apellidopaterno);
                $("#apMaternoE").val(response.apellidomaterno);
                $("#nombreUsuarioE").val(response.username);
                $("#dniE").val(response.dni);
                $("#correoE").val(response.email);
                $("#telefonoE").val(response.telefono);
                console.log(response);
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
                        .closest(".formGrupo")
                        .find(".error-messageE");
                    container.text(messages[0]);
                });
            } else {
                //maneja otros errores aquí
            }
        },
    });
});

$("#contraseñaNueva").submit(function (e) {
    e.preventDefault();
    $(".error-messageE").attr("class", "error-messageE ajuste d-none"); //Desabilita Mensaje error
    var formData = new FormData(this);

    $.ajax({
        type: "POST",
        url: "perfil/editarPass/" + $("#idPass").val(),
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Contraseña Cambiada " + response,
                    showConfirmButton: false,
                    timer: 1500,
                });
                $("#anteriorContraseña").val("");
                $("#nuevaContraseña").val("");
                $("#password-confirm").val("");
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
                        .closest(".formGrupo")
                        .find(".error-messageE");
                    container.text(messages[0]);
                });
            } else {
                //maneja otros errores aquí
            }
        },
    });
});
