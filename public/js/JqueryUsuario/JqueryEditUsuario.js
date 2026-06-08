// Funcion EDITAR OPCION"#idE"
function editarUsuario(id) {
    $(".error-messageE").attr("class", "error-messageE ajuste d-none"); //Desabilita Mensaje error
    $("#registroUsuarioE")[0].reset(); //LIMPIAR CAMPOS
    $("#iconMuestraE").removeClass(); //limpia el icono muestra

    $.get("usuarios/recuperar/" + id, function (data) {
        console.log(data);
        $("#idE").val(data.persona.id);
        $("#nombreUsuarioE").val(data.nameUser);
        
        if (data.persona.dni == null) {
            $("#selectDNI-RUC-E option[value='RUC']").prop("selected", true);
            $(".CajaDNI-E").addClass("d-none");
            $(".CajaRUC-E").removeClass("d-none");
            $("#dniE").val(data.persona.ruc);
            $("#razonsocialE").val(data.persona.razonsocial);
            $("#direccionE").val(data.persona.direccion);
        } else {
            $("#selectDNI-RUC-E option[value='DNI']").prop("selected", true);
            $(".CajaRUC-E").addClass("d-none");
            $(".CajaDNI-E").removeClass("d-none");
            $("#dniE").val(data.persona.dni);
            $("#nombreE").val(data.persona.nombres);
            $("#apellPaternoE").val(data.persona.apellidopaterno);
            $("#apellMaternoE").val(data.persona.apellidomaterno);
        }
        $("#selectDNI-RUC-E").change(function () {
            $(".error-messageE").attr("class", "error-messageE ajuste d-none"); //Desabilita Mensaje error
            if ($("#selectDNI-RUC-E").val() == "DNI") {
                $(".CajaRUC-E").addClass("d-none");
                $(".CajaDNI-E").removeClass("d-none");
                $("#razonsocialE").val("");
                $("#direccionE").val("");

            } else {
                $(".CajaDNI-E").addClass("d-none");
                $(".CajaRUC-E").removeClass("d-none");
                $("#nombreE").val("");
                $("#apellPaternoE").val("");
            }
            $("#dniE").val('');
        });
        $("#telefonoE").val(data.persona.telefono);
        
        $("#emailE").val(data.persona.email);
        $("#tipoUsuarioE").html("");
        $.each(data.tipoUsuarios, function (index, item) {
            $("#tipoUsuarioE").append(
                `<option value="${item.id}"> ${item.name}</option>`
            );
            if (item.id == data.miTipoUsuario) {
                $("#tipoUsuarioE option[value='" + item.id + "']").prop(
                    "selected",
                    true
                );
            }
        });

        $.get("rolPersona/show", function (dataRoles) {
            $(".cajaCheckBoxRolesE").html("");
            $.each(dataRoles, function (index, itemRoles) {
                if (index == 0) {
                    $(".cajaCheckBoxRolesE").append(
                        `<div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="UsuarioE"  name="rolesE[]" value="` +
                            itemRoles.id +
                            `">
                                                        <label class="form-check-label" for="UsuarioE">` +
                            itemRoles.descripcion +
                            `</label>
                                    </div>`
                    );
                } else {
                    $(".cajaCheckBoxRolesE").append(
                        `<div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="` +
                            itemRoles.descripcion +
                            `"  name="rolesE[]" value="` +
                            itemRoles.id +
                            `">
                                                    <label class="form-check-label" for="` +
                            itemRoles.descripcion +
                            `">` +
                            itemRoles.descripcion +
                            `</label>
                                </div>`
                    );
                }
                $.each(data.misRolesPersona, function (index, item) {
                    if (item.rol_id == itemRoles.id) {
                        $(
                            ".cajaCheckBoxRolesE input[value='" +
                                itemRoles.id +
                                "']"
                        ).prop("checked", true);
                    }
                });
            });
            if ($("#UsuarioE").is(":checked")) {
                $(".cajaUsuarioE").removeClass("d-none");
            } else {
                $(".cajaUsuarioE").addClass("d-none");
            }
            $("#UsuarioE").change(function () {
                if ($("#UsuarioE").is(":checked")) {
                    $(".cajaUsuarioE").removeClass("d-none");
                } else {
                    $(".cajaUsuarioE").addClass("d-none");
                }
            });
        });

        
        setTimeout(function() {
            $("#modalNuevoUsuarioE").modal("show");
          }, 600); 
    });
}

$(document).ready(function () {
    $(document).on("click", "#dniBuscarE", function () {
        if ($("#selectDNI-RUC-E").val() == "DNI") {
            $.get("usuarios/buscarDNI/" + $("#dniE").val(), function (data) {
                if (data.mensaje) {
                    $(".error-messageE").removeClass("d-none"); //HaBilitar mensaje error
                    $("#nombreE").val("");
                    $("#apellPaternoE").val("");
                    $("#apellMaternoE").val("");
                    var element = $("[name=dniE]");
                    var container = element
                        .closest(".form-group")
                        .find(".error-messageE");
                    container.text(data.mensaje);
                } else {
                    $("#nombreE").val(data.nombres);
                    $("#apellPaternoE").val(data.apepat);
                    $("#apellMaternoE").val(data.apemat);
                }
            }).fail(function (xhr, status, error) {
                console.log("Hubo un error: " + error);
            });
        } else {
            $.get("usuarios/buscarRUC/" + $("#dniE").val(), function (data) {
                if (data.mensaje) {
                    $(".error-messageE").removeClass("d-none"); //HaBilitar mensaje error
                    $("#razonsocialE").val("");
                    $("#direccionE").val("");
                    var element = $("[name=dniE]");
                    var container = element
                        .closest(".form-group")
                        .find(".error-messageE");
                    container.text(data.mensaje);
                } else {
                    $("#razonsocialE").val(data.RazonSocial);
                    $("#direccionE").val(data.Direccion);
                }
            }).fail(function (xhr, status, error) {
                console.log("Hubo un error: " + error);
            });
        }
    });
});



// CUBRIR Y DESCUBRIR CONTRASEÑA
$(document).ready(function () {
    $("#mostrar-contrasena").on("mousedown touchstart", function () {
        var contrasenaInput = $("#password");
        var tipoInput = contrasenaInput.attr("type");
        if (tipoInput === "password") {
            contrasenaInput.attr("type", "text");
        } else {
            contrasenaInput.attr("type", "password");
        }
    });
    $("#mostrar-contrasena").on("mouseup touchend", function () {
        var contrasenaInput = $("#password");
        contrasenaInput.attr("type", "password");
    });
});
// CUBRIR Y DESCUBRIR CONTRASEÑA CONFIRMACION DE CONTRAÑSEÑA
$(document).ready(function () {
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
});
