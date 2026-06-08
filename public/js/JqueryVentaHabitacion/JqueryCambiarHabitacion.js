
function cambiarHabitacion(num) {
    $("#numHabActual").val($("#numHabitacion").val());

    $.get(
        "catHabitaciones/habitacionesXsituacion/" + "Disponible",
        function (data) {
            console.log(data);
            $("#numHabNueva").html(``);
            $.each(data, function (index, item) {
                $("#numHabNueva").html(
                    $("#numHabNueva").html() +
                        `<option value="${item.numero}"> ${item.numero}</option>`
                );
            });$("#modalCambiarHabitacion").modal("show");
        }
    );

    
}

$("#registroCambiarHabitacion").submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    console.log($("#idMovimiento").val());
    formData.append('idMovimiento', $("#idMovimiento").val());
    $.ajax({
        type: "POST",
        url: "ventaHabitacion/editar",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response) {
                window.location.href =
                "ventaHabitacion?id=" +
                response;
                // $("#modalNuevoUsuario").modal("hide"); //ocultar modal
            } else {
                alert("nO");
            }
        },
    });
});

$(document).on("click", "#cerrarModalCambiarHabitacion", function () {
    $("#modalCambiarHabitacion").modal("hide");
});
