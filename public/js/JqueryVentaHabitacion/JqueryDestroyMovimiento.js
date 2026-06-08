$("#btonEliminarMovimientoAtencion").click(function () {
    $.get("movimiento/showId" + "/" + $("#idMovimiento").val(), function (data) {
        Swal.fire({
            title: "Quieres Eliminar el Check-in?",
            showDenyButton: true,
            confirmButtonText: "Eliminar",
            denyButtonText: `No Eliminar`,
        }).then((result) => {
            var destinatario = "suitemonterrico2022@gmail.com";
            var subject =
                "CODIGO PARA ELIMINACION MOVIMIENTO ATENCIÓN N° " + data.numero;
            var valorRandom = Math.floor(100000 + Math.random() * 900000); // Generar valor de 6 dígitos
            var body =
                `Código de Verificación

        Estimado usuario,
        
        Aquí tienes tu código de verificación: ` +
                valorRandom +
                `
        
        Por favor, ingrésalo en la página de verificación para completar el proceso de Eliminación.
        
        ¡Gracias por elegir nuestro servicio!
        
        Saludos!`;
            if (result.isConfirmed) {
                $.get(
                    "movimiento/send" +
                        "/" +
                        destinatario +
                        "/" +
                        subject +
                        "/" +
                        body,
                    function (data) {
                        console.log(data);
                        Swal.fire({
                            title: "Ingresa el Código de COnfirmación",
                            input: "number",
                            inputValidator: (value) => {
                                return new Promise((resolve) => {
                                    if (value == valorRandom) {
                                        resolve();
                                    } else {
                                        resolve("Codigo No Válido");
                                    }
                                });
                            },

                            showCancelButton: true,
                            confirmButtonText: "Guardar",
                            showLoaderOnConfirm: true,
                            cancelButtonText: "Cancelar",
                        }).then((result) => {
                            if (result.isConfirmed && result.value) {
                                destroyMovimiento();
                            }
                        });
                    }
                );
            }
        });
    });
});

function destroyMovimiento() {
    $.ajax({
        url: "movimiento/eliminar/" + $("#idMovimiento").val(),
        success: function (data) {
            window.location.href = "vistaPrincipal";
        },
    });
}
