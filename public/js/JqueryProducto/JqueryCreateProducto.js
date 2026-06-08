//VALIDACIONES EN MENSAJE
$(document).ready(function () {
    $("#btonNuevoProducto").click(function (e) {
        $("#registroProducto")[0].reset();
        $(".error-messageGrupo").attr(
            "class",
            "error-messageGrupo ajuste d-none"
        ); //Desabilita Mensaje error

        $.get("catProductos/create", function (data) {
           console.log(data);
            
            $("#categorias").html("");
            $.each(data.categorias, function (index, item) {
                $("#categorias").html(
                    $("#categorias").html() +
                        `<option value="${item.id}"> ${item.nombre}</option>`
                );
                if(item.id==4){//unidades
                    $("#categorias option[value='" + item.id + "']").prop(
                        "selected",
                        true
                    );
                }
            });

            $("#unidades").html("");
            console.log(data.unidades);
            $.each(data.unidades, function (index, item) {
                $("#unidades").html(
                    $("#unidades").html() +
                        `<option value="${item.id}"> ${item.nombre}</option>`
                );
                if(item.id==3){//unidades
                    $("#unidades option[value='" + item.id + "']").prop(
                        "selected",
                        true
                    );
                }
            });
        });

        $("#modalProducto").modal("show");
    });
});

//quitar mensaje de alidacion AL ESCRIBIR
$(document).ready(function () {
    $(".error-messageGrupo")
        .closest(".form-group")
        .find("input")
        .on("input", function () {
            $(this).closest(".form-group").find(".error-messageGrupo").empty();
        });
});

$(document).ready(function () {
    $(".error-messageGrupoE")
        .closest(".form-group")
        .find("input")
        .on("input", function () {
            $(this).closest(".form-group").find(".error-messageGrupoE").empty();
        });
});

$(document).ready(function () {
    // <!-- CREAR NUEVA REGISTRO-->
    $("#registroProducto")[0].reset(); //limpiar campos

    $("#registroProducto").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "catProductos/guardar",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#registroProducto")[0].reset(); //limpiar campos

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Guardado" + response,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#tbProducto").DataTable().ajax.reload(); //recargar datatable
                    $("#modalProducto").modal("hide"); //ocultar modal
                } else {
                    alert("nO");
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
                    });
                } else {
                    // maneja otros errores aquí
                }
            },
        });
    });
});
