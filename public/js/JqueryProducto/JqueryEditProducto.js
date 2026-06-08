function editarProducto(id) {
    $(".error-messageGrupoE").attr("class", "error-messageGrupoE ajuste d-none"); //Desabilita Mensaje error
    $("#registroProductoE")[0].reset(); //LIMPIAR CAMPOS

    $.get("catProductos/recuperar/" + id, function (data) {
        $("#idE").val(id); //INPUT:HIDE

        $("#nameE").val(data.producto.nombre);
        $("#codigoE").val(data.producto.codigo);
        $("#precioventaE").val(data.producto.precioventa);
        $("#preciocompraE").val(data.producto.preciocompra);
        
        $("#categoriasE").html("");
        $.each(data.categorias, function (index, item) {
            $("#categoriasE").append(
                `<option value="${item.id}"> ${item.nombre}</option>`
            );

            // Si el valor de item.id coincide con el valor que deseas seleccionar,
            // establece la propiedad "selected" en "true" para esa opción.
            if (item.id == data.productoCategoria) {
                $("#categoriasE option[value='" + item.id + "']").prop(
                    "selected",
                    true
                );
            }
        });

        $("#unidadesE").html("");
        $.each(data.unidades, function (index, item) {
            $("#unidadesE").append(
                `<option value="${item.id}"> ${item.nombre}</option>`
            );

            // Si el valor de item.id coincide con el valor que deseas seleccionar,
            // establece la propiedad "selected" en "true" para esa opción.
            if (item.id == data.productoUnidad) {
                $("#unidadesE option[value='" + item.id + "']").prop(
                    "selected",
                    true
                );
            }
        });

        $("#modalProductoE").modal("show");
    });
}