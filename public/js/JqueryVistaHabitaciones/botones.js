var idMovimiento = 0;
var numHab = 0;
function cambiarHabitacion(num) {
    numHab = num;
    $("#numHabActual").val(num);

    $.get(
        "catHabitaciones/habitacionesXsituacion/" + "Disponible",
        function (data) {
            $("#numHabNueva").html(``);
            $.each(data, function (index, item) {
                $("#numHabNueva").html(
                    $("#numHabNueva").html() +
                        `<option value="${item.numero}"> ${item.numero}</option>`
                );
            });

            $("#modalCambiarHabitacion").modal("show");
        }
    );
}

$("#registroCambiarHabitacion").submit(function (e) {
    e.preventDefault();
    var form = this;
    $.get("movimiento/show/" + $("#numHabActual").val(), function (data) {
        idMovimiento = data.id;
        var formData = new FormData(form);
        console.log(idMovimiento);
        formData.append("idMovimiento", idMovimiento);
        $.ajax({
            type: "POST",
            url: "ventaHabitacion/editar",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    window.location.href = "listaHab";
                    // $("#tbHabitacionesVista").DataTable().ajax.reload(); //recargar datatable
                    $("#modalCambiarHabitacion").modal("hide");
                } else {
                    alert("nO");
                }
            },
        });
    });
});

function agregarVenta(num) {
    window.location.href = "ventaHabitacion?id=" + num;
}

function pagarVenta(num) {
    window.location.href = "detalleHabitacion?id=" + num;
}

function confirmarChecking(num) {
    window.location.href = "ventaHabitacion?id=" + num;
}
var idHabitacion = 0;
function cambiarSituacion(id, numero) {
    $.get("movimiento/show/" + numero, function (data) {
        console.log(data);
        if (data == "null") {
            $.get("vistaPrincipal/situacion/" + id, function (data) {
                $("#numero").val(data.numero);

                $("#situacionCambio option[value='" + "Disponible" + "']").prop(
                    "selected",
                    true
                );

                idHabitacion = id;
                $("#modalCambiarSituacion").modal("show");
            });
        } else {
            window.location.href = "listaHab";
        }
    });
}

$("#registroCambiarSituacion").submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    console.log("actualizo");
    $.ajax({
        type: "POST",
        url: "vistaPrincipal/editar/" + idHabitacion,
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response) {
                $("#registroCambiarSituacion")[0].reset(); //limpiar campos
                $("#modalCambiarSituacion").modal("hide");
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Estado cambiado a " + response,
                    showConfirmButton: false,
                    timer: 1500,
                });
            }
            $("#tbHabitacionesVista").DataTable().ajax.reload(); //recargar datatable
        },
    });
});
