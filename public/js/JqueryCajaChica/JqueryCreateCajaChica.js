$(document).ready(function () {
    function actualizarcamposTotales() {
        $.get("cajaChica/apertura/recuperar/Cierre", function (data) {
            console.log(data);

            $("#montoAperturaV").val(data.montoApertura.toFixed(2));

            $("#montoCajaV").val(data.TotalCaja);
            $("#OtrosIngresosV").val(
                (data.totalIngresos - data.TotalPagosCliente).toFixed(2)
            );

            $("#montoVentasV").val(data.TotalPagosCliente.toFixed(2));

            $("#montoComprasV").val(
                data.TotaCompras == 0 ? "0" : "-" + data.TotaCompras
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

    $("#registroApertura").submit(function (e) {
        e.preventDefault();

        var titulo = "0";
        if ($("#total").val() == 0) {
            titulo = "ADVERTENCIA";
        } else {
            titulo = "CONFIRMACIÓN DE ENVIO";
        }

        Swal.fire({
            title: titulo,
            text: "El monto de Apertura es de: S/" + $("#total").val(),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Confirmo!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData(this);

                var registros = [];

                $(".registrosMontoTipo")
                    .find("tr")
                    .each(function () {
                        var monto = $(this).find(".montoPag").text();
                        var tipo = $(this).find("td:nth-child(2)").text();

                        var registro = {
                            monto: monto,
                            tipo: tipo,
                        };

                        registros.push(registro);
                    });

                var registrosJSON = JSON.stringify(registros);
                formData.append("registroPagos", registrosJSON);

                $.ajax({
                    type: "POST",
                    url: "cajaChica/apertura/guardar",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response) {
                            $("#registroApertura")[0].reset(); //limpiar campos
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Registro Guardado" + response,
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            $("#tbCaja").DataTable().ajax.reload(); //recargar datatable
                            $("#tbListadoEgresos").DataTable().ajax.reload(); //recargar datatable
                            $("#modalApertura").modal("hide"); //ocultar modal
                            $(".btnApertura").addClass("d-none");
                            $(".btnCierre").removeClass("d-none");
                            $(".btnIngreso").removeClass("d-none");
                            $(".btnEgreso").removeClass("d-none");

                            actualizarcamposTotales();
                        } else {
                            alert("nO");
                        }
                    },
                });
            }
        });
    });

    $("#registroApertura")[0].reset(); //limpiar campos

    $(document).on("click", ".añadirMedioPago", function () {
        if ($("#monto").val() > 0) {
            var i = $(".registrosMontoTipo").children().length;
            console.log(i);

            $(".registrosMontoTipo").append(
                `<tr>
        <td class="montoPag">${$("#monto").val()}</td>
        <td>${$("#mediosPago").val()}</td>
        <td><button class="delete-button eliminarMonto">
        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                          <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                        </svg>
      </button></td>
          </tr>`
            );
            $("#total").val(
                parseFloat($("#total").val()) + parseFloat($("#monto").val())
            );
            $("#monto").val("0");
        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Corrija el Monto",
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });
    $(document).on("click", ".eliminarMonto", function () {
        $(this).closest("tr").remove();
        var monto = $(this).closest("tr").find(".montoPag").text();
        $("#total").val($("#total").val() - monto);
    });

});
