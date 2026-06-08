function editarRolPersona(id) {
    $(".error-messageGrupoE").attr("class", "error-messageGrupoE ajuste d-none"); //Desabilita Mensaje error
    $("#registroRolPersonaE")[0].reset(); //LIMPIAR CAMPOS

    $.get("rolPersona/recuperar/" + id, function (data) {
        $("#idE").val(data.id); //INPUT:HIDE
        $("#descripcionE").val(data.descripcion);
        $("#modalRolPersonaE").modal("show");
    });
    
}