function editarUnidad(id) {
    $(".error-messageGrupoE").attr("class", "error-messageGrupoE ajuste d-none"); //Desabilita Mensaje error
    $("#registroUnidadE")[0].reset(); //LIMPIAR CAMPOS

    $.get("unidad/recuperar/" + id, function (data) {
        $("#idE").val(data.id); //INPUT:HIDE
        $("#nameEUnidad").val(data.nombre);
        $("#modalUnidadE").modal("show");
    });
}