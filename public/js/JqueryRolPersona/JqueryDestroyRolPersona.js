// DESABILITAR ESTADO
$(document).ready(function () {
    $(document).on("click", ".switch-estado", function () {
        const id = $(this).data("id");
        //    METODO AJAX
        if(id!=1){
           $.ajax({
            url: "rolPersona/eliminar/" + id,
            // url: "categorias/eliminar/"+id,
            success: function (data) {
                Swal.fire("Estado Cambiado a " + data, "", "success");
                // $("#tbCategoriaMenu").DataTable().ajax.reload(); //recargar datatable
            },
        }); 
        }else{
         
                Swal.fire("Rol Usuario no puede ser Deshabilitado");
                // $("#tbCategoriaMenu").DataTable().ajax.reload(); //recargar datatable
            
        }
        
    });
});