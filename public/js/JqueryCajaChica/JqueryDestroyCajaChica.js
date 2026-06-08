function actualizarcamposTotales() {
    $.get("cajaChica/apertura/recuperar/Cierre", function (data) {
        console.log(data);

        $("#montoAperturaV").val(data.montoApertura.toFixed(2));

        $("#montoCajaV").val(data.TotalCaja);
        $("#OtrosIngresosV").val((data.totalIngresos-data.TotalPagosCliente).toFixed(2));
        $("#montoVentasV").val(data.TotalPagosCliente.toFixed(2));

        $("#montoComprasV").val(
            data.TotaCompras === 0 ? "0" : "-" + data.TotaCompras
        );

        $("#otrosEgresosV").val(
            data.TotalEgresos - data.TotaCompras === 0
                ? "0"
                : "-" + (data.TotalEgresos - data.TotaCompras)
        );

        $("#montoYapeV").val(data.yapeCaja);
        $("#montoEfectivoV").val(data.efectivoCaja);
        $("#montoTarjetaV").val(data.tarjetaCaja);
        $("#montoPlinV").val(data.plinCaja);
        $("#montoDepositoV").val(data.depositoCaja);
    });
}

function anularMovCaj(id) {
    $.get("movimiento/showId" + "/" + id, function (data) {
        Swal.fire({
            title: "Confirmación para Anular",
            text: "Movimiento de caja",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Confirmo!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            var destinatario = "suitemonterrico2022@gmail.com";
            var subject =
                "CODIGO PARA ELIMINACION MOVIMIENTO CAJA N° " + data.numero;
            var valorRandom = Math.floor(100000 + Math.random() * 900000); // Generar valor de 6 dígitos

            var movAnulado = data;
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
                                destroyMovCaja(id);
                                
                            }
                        });
                    }
                );
            }
        });
    });
}

function destroyMovCaja(id) {
    $.ajax({
        url: "cajaChica/apertura/eliminar/" + id,
        // url: "categorias/eliminar/"+id,
        success: function (data) {
            console.log("tipo docu");
            console.log(data);
            Swal.fire(
                "Anulado!",
                "El movimiento Caja ha sido eliminado.",
                "success"
            );
            $("#tbCaja").DataTable().ajax.reload(); //recargar datatable
            $("#tbListadoEgresos").DataTable().ajax.reload(); //recargar datatable

            if (data.movimientoCaja.conceptopago_id == 4) {
                //solopagos clientes
                if (data.MovPadre.tipodocumento_id != 5) {
                    //No tickets
                    //ENVIAR A ANULAR MOVIMIENTO A CLIFACTURACION

                    var url = "/clifacturacion/controlador/contComprobante.php";
                    var funcion_ =
                        data.tipoDocumento == 1
                            ? "enviarResumenBoletasAnuladas"
                            : "enviarComunicacionBajas";

                    var parametros = {
                        funcion: funcion_,
                        numMov: data.numMov,
                        total: data.movimientoCaja.total,
                        ruc: data.ruc != null ? data.ruc : "",
                        dni: data.dni != null ? data.dni : "",
                    };

                    $.ajax({
                        type: "GET",
                        url: url,
                        data: parametros,

                        success: function (result) {
                            console.log(result);
                            actualizarcamposTotales();
                        },
                        error: function (e) {
                            console.log("error:".e);
                        },
                    });
                }
            }
            actualizarcamposTotales();
        },
    });
}
