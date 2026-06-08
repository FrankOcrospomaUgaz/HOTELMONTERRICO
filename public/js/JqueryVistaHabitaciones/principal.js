mostrarHabitaciones();
$(document).ready(function () {
    $("#tituloPagina").html(
        `<a class="nav-link active" href="vistaPrincipal">HOTEL | VISTA PRINCIPAL</a>`
    );
    $(document).on("click", "#cerrarModal", function () {
        $("#modalCambiarSituacion").modal("hide");
    });
    $("#stockProductos").html(
        `<a class="nav-link active btn-profesional" href="stockProductos" data-tooltip="Reporte Stock" ><i class="fa-solid fa-box"></i></a>`
    );
   $("#consumoHab").html(
        `<a class="nav-link active btn-profesional" href="consumoHab" data-tooltip="Reporte Check-Out" ><i class="fa-regular fa-calendar-check"></i></a>`
    );



    $("#vistaPrincipal").html(
        `<a class="nav-link active btn-profesional" href="vistaPrincipal" data-tooltip="Vista Principal" ><i class="fa-solid fa-star"></i></a>`
    );
    $("#vistaTabla").html(
        `<a class="nav-link active btn-profesional" href="listaHab" data-tooltip="Vista Tabla" ><i class="fa-solid fa-table-list"></i></a>`
    );
    $("#vistaCaja").html(
        `<a class="nav-link active btn-profesional" href="cajaChica" data-tooltip="Caja" ><i class="fa-solid fa-cash-register"></i></a>`
    );
    $("#vistaCompras").html(
        `<a class="nav-link active btn-profesional" href="movCompras" data-tooltip="Compras" ><i class="fa-solid fa-cart-shopping"></i></a>`
    );

    $("#vistaAlmacen").html(

        `<a class="nav-link active btn-profesional" href="movAlmacen" data-tooltip="Documento Almacén" ><i class="fa-solid fa-store"></i></a>`
    );
});

function mostrarHabitaciones() {
    $(".contenedorHabitaciones").html("");
    $.get("vistaPrincipal/show", function (data) {
        if (data[0]) {
            console.log(data);
            let temporizador = ``;
            let tipo = ``;

            $.each(data, function (index, item) {
                console.log(data);
                if (item.horaInicio !== null) {
                    temporizador = `<div class="temporizador" id="temporizadorDigital${index}">${item.horaInicio} 
                   
                    </div> 

                    <div class="temporizador-text"><b>S/. ${item.total}</b></div>
                    <div class="temporizador-text">${item.horaInicio}</div>
                    
                  
                  `;



                    setInterval(function () {
                        updateTimer(
                            `#temporizadorDigital${index}`,
                            item.horaInicio,
                            item.horas
                        );
                    });
                } else {
                    temporizador = `
                    <div class="temporizador" id="temporizadorDigital${index}">00:00:00 </div> 
                    <div class="temporizador-text"></div>
                    <div class="temporizador-text"></div> `;
                
                }
                tipo = ``;
                if (item.tipo == "VIP") {
                    tipo = `<div class="row">
                <label style="color:white;">✮VIP✮</label>
                </div>`;
                } else {
                    tipo = `<div class="row">
                <label style="color:white;">`+item.tipo.toUpperCase()+`</label>
                </div>`;
                }


                switch (item.situacion) {
                    case "Disponible":
                        color =
                            `<div class='habitacion' id="` +
                            item.id +
                            `" style='background-color: rgb(6 225 0);'>`;
                        break;

                    case "Ocupada":
                        color =
                            `<div class='habitacion animar-escala' id="` +
                            item.id +
                            `" style='background-color: #FF8000;'>`;
                        break;
                    case "FueraTiempo":
                        color =
                            `<div class='habitacion' id="` +
                            item.id +
                            `" style='background-color: rgb(213 0 0);'>`;
                        break;
                    case "Mantenimiento":
                        color =
                            `<div class='habitacion' id="` +
                            item.id +
                            `" style='background-color: rgb(176 0 211);'>`;
                        break;
                    case "Limpieza":
                        color =
                            `<div class='habitacion' id="` +
                            item.id +
                            `" style='background-color: rgb(0 140 255);'>`;
                        break;
                }

                $(".contenedorHabitaciones").append(
                    color +
                        `
                    <div class="centro">` +
                        tipo +
                        `
                    
                    
                    <div class="row mb-1">

<div class="numero" value="${item.numero}">` +
                        item.numero +
                        `</div></div><div class="row">
                        
   
` +
                        temporizador +
                        `

</div>


                        
                        
                    </div>
                    <div class="Inferior">
    <div class="estado">
        ` +
                        item.situacion +
                        (item.situacion == "Ocupada"
                            ? `(${item.horas}h)`
                            : "") +
                        ` <i class="fa-solid fa-circle-right" style="color: #f255f;"></i>
    </div>
</div>

                </div>
                    `
                );
            });
        } else {
            $(".contenedorHabitaciones").append("SIN HABITACIONES");
        }
        click();
    });
}

function click() {
    $(".habitacion").click(function () {
        var numeroHabitacion = $(this).find(".numero").text();
        $.get(
            "vistaPrincipal/situacion/" + $(this).attr("id"),
            function (data) {
                var idHabitacion = data.id;
                $("#idHabitacion").val(data.id);
                if (data.situacion == "Ocupada") {
                    Swal.fire({
                        title: "Habitación N° " + numeroHabitacion + ":",
                        showConfirmButton: true,
                        confirmButtonText: "Agregar Venta",
                        confirmButtonColor: "#0044ff",
                        showDenyButton: true,
                        denyButtonText: "Pagar",
                        denyButtonColor: "#ff0000",
                        showCancelButton: true,
                        cancelButtonText: "Agregar Tiempo",
                        cancelButtonColor: "#ffbe00",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "ventaHabitacion?id=" + numeroHabitacion;
                        } else if (result.isDenied) {
                            window.location.href = "detalleHabitacion?id=" + numeroHabitacion;
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Esta parte del código se ejecutará cuando se cancele el cuadro de diálogo
                            sumarTiempoHabTabla(numeroHabitacion);
                        }
                    });
                    
                } else if (data.situacion == "Disponible") {
                    Swal.fire({
                        title: "Habitación N° " + numeroHabitacion + ":",
                        showConfirmButton: true,
                        confirmButtonText: "Confirmar Checking",
                        confirmButtonColor: "rgb(6 225 0)",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "ventaHabitacion?id=" + numeroHabitacion;
                        }
                    });
                } else {
                    $.get(
                        "vistaPrincipal/situacion/" + idHabitacion,
                        function (data) {
                            $("#numero").val(data.numero);

                            $(
                                "#situacionCambio option[value='" +
                                    "Disponible" +
                                    "']"
                            ).prop("selected", true);

                            $("#modalCambiarSituacion").modal("show");
                        }
                    );
                }
            }
        );
    });
}

$("#registroCambiarSituacion").submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        type: "POST",
        url: "vistaPrincipal/editar/" + $("#idHabitacion").val(),
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response) {
                $("#registroCambiarSituacion")[0].reset(); //limpiar campos

                $("#modalCambiarSituacion").modal("hide");
                window.location.href = "vistaPrincipal";
            } else {
                alert("nO");
            }
        },
    });
});

// Función para obtener la diferencia de tiempo entre la hora específica y la hora actual
function getElapsedTime(startTime) {
    const now = new Date();
    const startDate = new Date(startTime);

    let elapsedTime = now.getTime() - startDate.getTime();
    elapsedTime = Math.max(elapsedTime, 0); // Asegurarse de que el temporizador no sea negativo

    return elapsedTime;
}

// Función para actualizar el temporizador
function updateTimer(elemento, startTime, horasServicio) {
    const elapsedTime = getElapsedTime(startTime);

    // Convertir la diferencia de tiempo a horas, minutos y segundos
    const hours = Math.floor(elapsedTime / (1000 * 60 * 60));
    const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);

    // Formatear el tiempo en HH:MM:SS
    const formattedTime = `${hours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;

    if (hours >= horasServicio) {
        $(elemento).closest(".habitacion").addClass("estiloRojo");
    } else {
        $(elemento).closest(".habitacion").removeClass("estiloRojo");
    }

    // Actualizar el contenido del elemento con el temporizador
    $(elemento).html(`<span>${formattedTime}</span>`);
}

function sumarTiempoHabTabla(numhab) {
    var html = ``;
    $.get("catServicios/show", function (dataModoHoras) {
        html = `<div class="row">
    <div class="col-md-6">
        <label for="modoHoraAdicional" class=" labelFormato">Modo Hora Adicional:</label>
        <br><select name="modoHoraAdicional" style="font-size:15px" class="form-control" id="modoHoraAdicional">
    `;
        $.each(dataModoHoras, function (index, item) {
            if (item.tipo == "Tiempo" && item.id != 1 && item.id != 4 && item.id != 21) {
                if (item.id === 7) {
                    html += `<option value="${item.id}" selected>${item.nombre}</option>`;
                } else {
                    html += `<option value="${item.id}">${item.nombre}</option>`;
                }
            }
        });
        html += `</select></div>
    <div class="col-md-6">
    <label for="horasInput" class="form-label labelFormato">Cantidad Horas:</label>
    <input id="horasInput" class="form-control" type="number" value="1" min="1" placeholder="Añadir Horas">
    </div>
    </div><div class="row">
    <div class="col-md-12">
        <label for="textoInput" class="form-label labelFormato">Notas:</label>
        <input id="textoInput" class="form-control" type="text" placeholder="Notas..." value="-">
    </div>
</div>`;

        Swal.fire({
            title: "Añadir más tiempo a la Habitación N° " + numhab,
            html: html,

            showCancelButton: true,
            confirmButtonText: "Guardar",
            showLoaderOnConfirm: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const horas = parseFloat(
                    document.getElementById("horasInput").value
                );
                const texto = document.getElementById("textoInput").value;
                const modoHoraAdicional =
                    document.getElementById("modoHoraAdicional").value;
                $.get(
                    "vistaPrincipal/sumarHorasHab" +
                        "/" +
                        numhab +
                        "/" +
                        horas +
                        "/" +
                        texto +
                        "/" +
                        modoHoraAdicional,
                    function (data) {
                        //OBTENER tipodocumentos de venta

                        window.location.href = "vistaPrincipal";
                    }
                );
            }
        });
    });
}
