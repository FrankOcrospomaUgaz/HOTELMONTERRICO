function editarServicio(id) {
    $(".error-messageGrupoE").attr("class", "error-messageGrupoE ajuste d-none"); //Desabilita Mensaje error
    $("#registroServicioE")[0].reset(); //LIMPIAR CAMPOS

    $.get("catServicios/recuperar/" + id, function (data) {
        $("#idE").val(data.id); //INPUT:HIDE
        $("#nameE").val(data.nombre);
        $("#precioVentaE").val(data.precioventa);
        $("#modalServicioE").modal("show");
    });
}