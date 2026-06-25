$(document).ready(function () {
    $("#btonNuevo").click(function () {
        $("#registroUsuario")[0].reset();
        $(".error-message").attr("class", "error-message ajuste d-none");
        $(".CajaRUC").addClass("d-none");
        $(".cajaUsuario").addClass("d-none");
        $(".CajaDNI").removeClass("d-none");
        $("#razonsocial").val("");
        $("#direccion").val("");

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
                    $(".error-message").removeClass("d-none");
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
                    $(".error-message").removeClass("d-none");
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
        $(".error-message").attr("class", "error-message ajuste d-none");
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
    $("#registroUsuario")[0].reset();

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
                if (!response) {
                    alert("nO");
                    return;
                }

                const personaCreada = response;
                $("#registroUsuario")[0].reset();

                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Registro Guardado con Exito",
                    showConfirmButton: false,
                    timer: 1500,
                });

                if ($("#tbUsuarios").length > 0 && $.fn.DataTable) {
                    $("#tbUsuarios").DataTable().ajax.reload();
                }

                $("#modalNuevoUsuario").modal("hide");

                if ($("#clientes").length > 0) {
                    $.get("ventaHabitacion/show", function (data) {
                        let opciones = "";

                        $.each(data, function (_, item) {
                            const selected =
                                parseInt(item.id, 10) ===
                                parseInt(personaCreada.id, 10)
                                    ? " selected"
                                    : "";

                            if (item.dni != null) {
                                opciones += `<option value="${item.id}"${selected}>${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`;
                            } else if (item.ruc != null) {
                                opciones += `<option value="${item.id}"${selected}>${item.ruc} - ${item.razonsocial}</option>`;
                            } else {
                                opciones += `<option value="${item.id}"${selected}>${item.nombres}</option>`;
                            }
                        });

                        $("#clientes").html(opciones);
                        $("#clientes")
                            .val(String(personaCreada.id))
                            .trigger("change");
                    });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;

                    $(".error-message").removeClass("d-none");
                    $.each(errors, function (field, messages) {
                        var element = $('[name="' + field + '"]');
                        var container = element
                            .closest(".form-group")
                            .find(".error-message");
                        container.text(messages[0]);
                    });
                }
            },
        });
    });
});

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
