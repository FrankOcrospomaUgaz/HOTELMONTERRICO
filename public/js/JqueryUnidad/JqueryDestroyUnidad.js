// DESABILITAR ESTADO
$(document).ready(function () {
    $(document).on("click", ".switch-estado-uni", function () {
        const id = $(this).data("id");
        //    METODO AJAX
        $.ajax({
            url: "unidad/eliminar/" + id,
            // url: "categorias/eliminar/"+id,
            success: function (data) {
                Swal.fire("Estado Cambiado a " + data, "", "success");
                // $("#tbCategoriaMenu").DataTable().ajax.reload(); //recargar datatable
            },
        });
    });
});