function eliminarProductoAgregado(id) {
    Swal.fire({
        title: "Quieres Eliminarlo de la lista?",
        showDenyButton: true,
        confirmButtonText: "Eliminar",
        denyButtonText: `No Eliminar`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                url: "detalleMovimiento/eliminar/" + id,

                success: function () {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro Eliminado con Exito",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    if (typeof refrescarVentaRapidaContexto === "function") {
                        refrescarVentaRapidaContexto();
                    }
                },
            });
        }
    });
}
