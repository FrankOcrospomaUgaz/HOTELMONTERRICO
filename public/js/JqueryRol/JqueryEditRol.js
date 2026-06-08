//FUNCION EDITAR
function editarRol(id) {
    // LIMPIAR CHECKBOX
    $("#opcionesE").html("");
    //LIMPIAR MENSAJE DE VALIDACION
    $(".error-messageGrupoE").attr(
        "class",
        "error-messageGrupoE ajuste d-none"
    );

    $("#registroRolE")[0].reset(); //LIMPIAR CAMPOS
    $.get("roles/recuperar/" + id, function (data) {
        //LENAR CAMPOS DE CATEGORIA EN CHECKBOX

        $("#idE").val(data.role.id); //INPUT:HIDE
        $("#nameE").val(data.role.name);

        $("#opcionesE").html("");

        var count = 1;

        var listaPermisos = Object.values(data.rolePermissions); //Convertir a arreglo
        var isChecked;

        $.each(data.GrupoMenu, function (index, item) {
            $.get("roles/opcionesXrol/" + item.id, function (data) {
                var count = 1;
                console.log(data);
                $.each(data, function (index, item) {});
                $("#opcionesE").append(
                    "<tr class='opcionesHeadMenuE'><td colspan='4' style='text-align: center'>" +
                        item.nombre.toUpperCase() +
                        "</td></tr>"
                );
                $.each(data, function (index, item) {
                    if (count === 1) {
                        $("#opcionesE").append("<tr>");
                    }

                    // Capturar Checkbox marcados
                    var isChecked = listaPermisos.includes(item.id); //Da true si esque el arrelo incluye el id
                    // Agregar la celda actual a la fila actual
                    $("#opcionesE tr:last-child").append(`
                            <td>
                             <label id="labelPermission">
                               <input type="checkbox" value="${
                                   item.id
                               }"  name="permissionE[]" ${isChecked ? "checked" : ""}> ${item.name} 
                               <i class="${item.icono} "></i></a>
                             </label>
                              </td>
                             `);

                    // Incrementar count y agregar una nueva fila si count es igual a 4
                    if (count === 4) {
                        count = 1;
                        $("#opcionesE").append("</tr>");
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

        setTimeout(function () {
            $("#modalEditarRolE").modal("show");
        }, 1000);
    });
}

//ACTIVAR MENSAJE DE VALIDACION EN MODAL EDITAR
$(document).ready(function () {
    $(document).ready(function () {
        $(".error-messageE")
            .closest(".form-group")
            .find("input")
            .on("input", function () {
                $(this).closest(".form-group").find(".error-messageE").empty();
            });
    });

    //ACTIVAR MENSAJE DE VALIDACION EN EL MODAL DE EDITAR OPCION CRUD
    $(document).ready(function () {
        $(".error-messageCRUD")
            .closest(".form-group")
            .find("input")
            .on("input", function () {
                $(this)
                    .closest(".form-group")
                    .find(".error-messageCRUD")
                    .empty();
            });
    });
});

$(document).ready(function () {
    //MARCAR-DESMARCARA "TODOS" LOS PERMISOS EDITAR
    $("#selectE").click(function (event) {
        if (this.checked) {
            // Iterar sobre cada checkbox con la clase 'select-checkbox' y marcarlo
            $('[name="permissionE[]"]').each(function () {
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
            // Iterar sobre cada checkbox con la clase 'select-checkbox' y desmarcarlo
            $('[name="permissionE[]"]').each(function () {
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

    $("#grupoMenuE").change(function () {
        $(document).on("shown.bs.modal", "#modalEditarRolE", function () {
            $("table tr").each(function () {
                var $fila = $(this);
                if (
                    !$fila
                        .find('td:first-child input[type="checkbox"]')
                        .is(":checked")
                ) {
                    console.log(
                        $fila.find('td:first-child input[type="checkbox"]')
                    );
                    $fila
                        .find('td input[type="checkbox"]')
                        .not(":first")
                        .prop("disabled", true);
                }
            });
        });
    });

    //DESABILITAR LAS CELDAS SIN TOCAR LA PRIMERA QUE ES LA VISTA
    $(document).on("shown.bs.modal", "#modalEditarRolE", function () {
        $("table tr").each(function () {
            var $fila = $(this);
            if (
                !$fila
                    .find('td:first-child input[type="checkbox"]')
                    .is(":checked")
            ) {
                console.log(
                    $fila.find('td:first-child input[type="checkbox"]')
                );
                $fila
                    .find('td input[type="checkbox"]')
                    .not(":first")
                    .prop("disabled", true);
            }
        });
    });

    //CERRAR MODAL EDITAR
    $(document).on("click", "#cerrarModalE", function () {
        $("#modalEditarRolE").modal("toggle");
    });
});
