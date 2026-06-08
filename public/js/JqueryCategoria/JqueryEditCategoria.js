function editarCategoria(id) {
    $(".error-messageGrupoE").attr("class", "error-messageGrupoE ajuste d-none"); //Desabilita Mensaje error
    $("#registroCategoriaE")[0].reset(); //LIMPIAR CAMPOS

    $.get("categoria/recuperar/" + id, function (data) {
        $("#idE").val(data.id); //INPUT:HIDE
        $("#nameECategoria").val(data.nombre);
        $("#modalCategoriaE").modal("show");
    });
}