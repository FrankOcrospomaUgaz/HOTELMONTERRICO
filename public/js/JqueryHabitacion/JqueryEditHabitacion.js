function editarHabitacion(id) {
    $(".error-messageGrupoE").attr(
        "class",
        "error-messageGrupoE ajuste d-none"
    ); //Desabilita Mensaje error
    $("#registroHabitacionE")[0].reset(); //LIMPIAR CAMPOS
    $.get("catHabitaciones/recuperar/" + id, function (data) {
        console.log(data);
        if (data.situacion != "Ocupada") {
            $("#idE").val(data.id); //INPUT:HIDE
            $("#nameE").val(data.numero);
            $("#situacionCambio option[value='" + data.situacion + "']").prop(
                "selected",
                true
            );
            $("#tipoE option[value='" + data.tipo + "']").prop(
                "selected",
                true
            );
            $("#modalHabitacionE").modal("show");
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
            });
        }
    });
}
