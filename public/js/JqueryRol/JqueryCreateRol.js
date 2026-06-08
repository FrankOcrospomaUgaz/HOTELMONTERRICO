// BOTON NUEVO

$(document).ready(function () {
    //BTON NUEEVO ROL

    $("#btonNuevo").click(function (e) {
        $("#registroRol")[0].reset();

        $(".error-messageGrupo").attr(
            "class",
            "error-messageGrupo ajuste d-none"
        );

        $.get("roles/create", function (data) {
            $("#opciones").html("");
            $.each(data, function (index, item) {
                // Agregar una fila si count es igual a 1
                $.get("roles/opcionesXrol/" + item.id, function (data) {
                    var count = 1;
                    console.log(data);
                    $.each(data, function (index, item) {});
                    $("#opciones").append(
                        "<tr class='opcionesHeadMenu'><td colspan='4' style='text-align: center'>" +
                            item.nombre.toUpperCase() +
                            "</td></tr>"
                    );
                    $.each(data, function (index, item) {
                        if (count === 1) {
                            $("#opciones").append("<tr>");
                        }

                        // Agregar la celda actual a la fila actual
                        $("#opciones tr:last-child").append(`
        
                        <td>
                         <label id="labelPermission">
                           <input type="checkbox" value="${item.id}"  name="permission[]"> ${item.name} 
                           <i class="${item.icono} "></i></a>
                         </label>
                          </td>
                         `);

                        // Incrementar count y agregar una nueva fila si count es igual a 4
                        if (count === 4) {
                            count = 1;
                            $("#opciones").append("</tr>");
                        } else {
                            count++;
                        }
                    });
                    // bloquea celdas por defecto
            $('table tr td:first-child input[type="checkbox"]').each(
                function () {
                    var $fila = $(this).closest("tr"); //captura la fila
                    if (!$(this).is(":checked")) {
                        $fila
                            .find('td input[type="checkbox"]')
                            .not(":first")
                            .prop("disabled", true);
                    }
                }
            );
                });
            });
            setTimeout(function() {
                $("#modalNuevoRol").modal("show");
              }, 1100); 
            
        });
    });

    //CERRAR MODAL
    $(document).on("click", "#cerrarModal", function () {
        $("#modalNuevoRol").modal("hide");
    });
});

//MARCAR-DESMARCARA TODOS LOS PERMISOS CREAR
$(document).ready(function () {
    $("#select").click(function (event) {
        if (this.checked) {
            // Iterar sobre cada checkbox Y marcar todos
            $('[name="permission[]"]').each(function () {
                this.checked = true;
            });
            // Habilitar los checkboxes que estaban deshabilitados
            $("table tr").each(function () {
                $(this)
                    .find('td input[type="checkbox"]')
                    .not(":first")
                    .prop("disabled", false);
            });
        } else {
            // Iterar y desmarcar
            $('[name="permission[]"]').each(function () {
                this.checked = false;
            });

            // Deshabilitar los checkboxes que no son la primera celda
            $("table tr").each(function () {
                $(this)
                    .find('td input[type="checkbox"]')
                    .not(":first")
                    .prop("disabled", true);
            });
        }
    });
});

//MARCAR PERMISOS POR FILA

$(document).ready(function () {
    //MARCAR PERMISOS POR FILA

    $(document).on(
        "change",
        'table tr td:first-child input[type="checkbox"]',
        function () {
            var $fila = $(this).closest("tr"); //captura la fila
            if ($(this).is(":checked")) {
                //primera celda check
                $fila
                    .find('td input[type="checkbox"]')
                    .not(":first")
                    .prop("disabled", false);
                $fila
                    .find('td input[type="checkbox"]')
                    .not(":first")
                    .prop("checked", true); //marcas a las demás
                $fila.addClass("fila-seleccionada");
            } else {
                $fila
                    .find('td input[type="checkbox"]')
                    .not(":first")
                    .prop("disabled", true);
                $fila
                    .find('td input[type="checkbox"]')
                    .not(":first")
                    .prop("checked", false); //no las marca
                $fila.removeClass("fila-seleccionada");
            }
        }
    );

    //GUARDAR VALORES
    //quitar mensaje de alidacion al escribir en CREAR
    $(document).ready(function () {
        $(".error-messageGrupo")
            .closest(".form-group")
            .find("input")
            .on("input", function () {
                $(this)
                    .closest(".form-group")
                    .find(".error-messageGrupo")
                    .empty();
            });
    });

    $(document).ready(function () {
        // <!-- CREAR NUEVA REGISTRO-->
        $("#registroRol")[0].reset(); //limpiar campos
        $("#registroRol").submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "roles/guardar",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (!response) {
                        $("#registroRol")[0].reset(); //limpiar campos
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Registro Guardado",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#tbRoles").DataTable().ajax.reload(); //recargar datatable
                        $("#modalNuevoRol").modal("hide"); //ocultar modal
                    } else {
                        alert(response);
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
                            console.log(messages[0]);
                        });
                    } else {
                        // maneja otros errores aquí
                    }
                },
            });
        });
    });
});
