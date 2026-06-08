function editarGrupoMenu(id) {
    $(".error-messageGrupoE").attr("class", "error-messageGrupoE ajuste d-none"); //Desabilita Mensaje error
    $("#registroEditarGrupoMenuE")[0].reset(); //LIMPIAR CAMPOS
    $("#iconMuestraE").removeClass(); //limpia el icono muestra

    $.get("categoriaMenu/recuperar/" + id, function (data) {
        $("#idE").val(data.id); //INPUT:HIDE
        $("#nameGrupoOpcionE").val(data.nombre);
        $("#iconoGrupoOpcionE").val(data.icono);

        //Recuperar ICONO MUESTRA
        var icono = $("#iconoGrupoOpcionE").val();

        $("#iconMuestraE").attr("class", icono);
        //MOSTRAR NUEVO
        $("#iconoGrupoOpcionE").on("input", function () {
            // Obtener el valor del icono capturado
            var icono = $(this).val(); // Mostrar el icono en el elemento <i> dentro del <span> con la clase "input-group-text"
            $("#iconMuestraE").attr("class", icono);
        });

        $("#modalEditarGrupoMenu").modal("show");
    });
}