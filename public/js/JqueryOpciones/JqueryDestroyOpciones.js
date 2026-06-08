// DESABILITAR ESTADO
$(document).ready(function () {
    $(document).on("click", ".switch-estado", function () {
        const id = $(this).data("id");
        //    METODO AJAX
        $.ajax({
            url: "opciones/eliminar/" + id,
            // url: "categorias/eliminar/"+id,
            success: function (data) {
                Swal.fire("Estado Cambiado a " + data, "", "success");
                // $("#tbOpciones").DataTable().ajax.reload(); //recargar datatable
            },
        });
    });
});