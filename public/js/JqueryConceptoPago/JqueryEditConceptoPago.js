function editarConceptoPagos(id) {
    $(".error-messageGrupoE").attr("class", "error-messageGrupoE ajuste d-none"); //Desabilita Mensaje error
    $("#registroConceptoPagosE")[0].reset(); //LIMPIAR CAMPOS

    $.get("conceptoPagos/recuperar/" + id, function (data) {
        $("#idE").val(data.id); //INPUT:HIDE
        $("#nombreConceptoPagoE").val(data.nombre);
        $("#tipoE option[value='" + data.tipo + "']").prop(
            "selected",
            true
        );
        $("#modalConceptoPagosE").modal("show");
    });
    
}