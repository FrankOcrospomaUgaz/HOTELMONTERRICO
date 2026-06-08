$(".selectTwo").each(function () {
    var $p = $(this).parent();
    $(this).select2({
        dropdownParent: $p,
    });
});

var fechaCompro = flatpickr("#fechaCompro", {
    dateFormat: "Y-m-d",
    defaultDate: "today",
    maxDate: new Date(),
    allowInput: true,
});

var formaPagoSelect = document.getElementById("formaPagoSelect");
var numCuotasLbl = document.querySelector(".numCuotasLbl");

formaPagoSelect.addEventListener("change", function () {
    if (formaPagoSelect.value === "Credito") {
        numCuotasLbl.classList.remove("d-none");
    } else {
        numCuotasLbl.classList.add("d-none");
    }
});

if ($("#idMovCompra").val() != "") {
    $("#Actualizar").removeClass("d-none");
    $("#cajaAgregar").removeClass("d-none");
    $(".cajaProveedor").addClass("d-none");

    $(".btnEliminarMovCompra").removeClass("d-none");

    $.get("movCompra/show/" + $("#idMovCompra").val(), function (data) {
        console.log(data);
        // $("#responsableI").text(data.responsable.nombres);
        $("#numeroI").text(data.formatoNumeroCompra);
        $("#fechaCompro").val(data.movCompra.fecha.split(' ')[0]);
        $("#cantCuotas").val(data.movCompra.cantCuotas);

        $("#numCompra").val(data.movCompra.numero);
        $("#formaPagoSelect").val(data.movCompra.formaPago);
        $("#tipo").val(data.movCompra.tipodocumento_id == 3 ? "Boleta" : (data.movCompra.tipodocumento_id == 4 ? "Factura" : (data.movCompra.tipodocumento_id == 10 ? "Ticket" : "")));

        // Aquí va tu código existente para obtener los datos

        // Inicializar DataTable
        var tablaProductosComprados = $("#tablaProductosComprados").DataTable({
            destroy: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json",
            },
        });

        // Limpiar tabla antes de agregar nuevos datos
        tablaProductosComprados.clear().draw();

        // Agregar filas a la tabla con los datos obtenidos
        $.each(data.detalleMovimientos, function (index, item) {
            var total = (item.preciocompra * item.cantidad).toFixed(2);
        
            tablaProductosComprados.row
                .add([
                    `<a href="javascript:void(0)" onclick="eliminarProductoAgregado(${item.id})"><i class="fa-solid fa-trash" style="color: #0047c2;"></i></a>`,
                    item.nombre,
                    item.cantidad,
                    item.preciocompra,
                    total,
                    item.comentario,
                ])
                .nodes() // Obtener el nodo HTML de la fila agregada
                .to$() // Convertirlo a objeto jQuery
                .attr('id', item.id); // Establecer el atributo id con el valor de item.id
             
        });
        
        $.each(data.proveedores, function (index, item) {
            if (item.dni != null) {
                $("#proveedores").html(
                    $("#proveedores").html() +
                        `<option value="${item.id}"> ${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                );
            } else if (item.ruc != null) {
                $("#proveedores").html(
                    $("#proveedores").html() +
                        `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                );
            } else {
                $("#proveedores").html(
                    $("#proveedores").html() +
                        `<option value="${item.id}" selected>${item.nombres}</option>`
                );
            }
        });
        $("#proveedores").val(data.movCompra.persona_id);

        // Resto de tu código aquí
    });
} else {
    $("#cajaAgregar").removeClass("d-none");

    $.get("usuarios/show/3", function (data) {
        //3 son proveedores
        //OBTENER proveedores
        //console.log(data);
        $("#cajaAgregar").addClass("d-none");
        $("#Registrar").removeClass("d-none");
        $("#proveedores").html(``);
        // $("#responsableI").text(data.responsable.nombres);
        $("#numeroI").text(data.formatoNumeroCompra);
        $("#fechaI").text(data.fecha);
        console.log(data);
        $.each(data.proveedores, function (index, item) {
            if (item.dni != null) {
                $("#proveedores").html(
                    $("#proveedores").html() +
                        `<option value="${item.id}"> ${item.dni} - ${item.nombres} ${item.apellidopaterno} ${item.apellidomaterno}</option>`
                );
            } else if (item.ruc != null) {
                $("#proveedores").html(
                    $("#proveedores").html() +
                        `<option value="${item.id}"> ${item.ruc} - ${item.razonsocial}</option>`
                );
            } else {
                $("#proveedores").html(
                    $("#proveedores").html() +
                        `<option value="${item.id}" selected>${item.nombres}</option>`
                );
            }
        });
    });
}

$(document).on("click", "#Registrar", function (event) {
    $("#registroNuevoMovimientoCompra").submit();
});

$(document).on("click", "#Actualizar", function (event) {
    actualizarMovCOmpra();
});

$("#registroNuevoMovimientoCompra").submit(function (e) {
    e.preventDefault();
    var form = this;
    if ($("#proveedores").val() != 17) {
        //varios
        if ($("#numCompra").val() != "") {
            var idMovCompra = $("#idMovCompra").val() || 0;

            $.get(
                "movCompra/verificarNumeroMovCompra/" +
                    $("#numCompra").val() +
                    "/" +
                    idMovCompra,
                function (data) {
                    if (data) {
                        if (data == "Numero Disponible") {
                            var formData = new FormData(form);
                            formData.append(
                                "operacion",
                                $("#operacionI").text()
                            );
                            formData.append("fecha", $("#fechaCompro").val());
                            formData.append("numCompra", $("#numCompra").val());
                            formData.append(
                                "formaPagoSelect",
                                $("#formaPagoSelect").val()
                            );
                            formData.append(
                                "cantCuotas",
                                $("#cantCuotas").val()
                            );
                            $.ajax({
                                type: "POST",
                                url: "movCompras/guardar",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (response) {
                                    if (response) {
                                        window.location.href =
                                            "docCompras?operacion=compras&id=" +
                                            response.id;
                                    } else {
                                        alert("nO");
                                    }
                                },
                            });
                        } else {
                            Swal.fire({
                                title: "Este Número Compra ya está usado",
                                text: "Debe ingresar otro Número Compra",
                                icon: "warning",
                                confirmButtonColor: "#3085d6",
                            });
                        }
                    } else {
                        alert("No se pudo realizar la actualización.");
                    }
                }
            );
        } else {
            Swal.fire({
                title: "El campo Número Compra debe ser llenado",
                text: "Es necesario para realizar la compra",
                icon: "warning",
                confirmButtonColor: "#3085d6",
            });
          
        }
    } else {
        Swal.fire({
            title: "Es necesario que seleccione un Proveedor",
            text: "Es necesario para realizar la compra",
            icon: "warning",
            confirmButtonColor: "#3085d6",
        });
    }
});

function actualizarMovCOmpra() {
    if ($("#proveedores").val() != 17) {
        if ($("#numCompra").val() != "") {
            var idMovCompra = $("#idMovCompra").val() || 0;

            $.get(
                "movCompra/verificarNumeroMovCompra/" +
                    $("#numCompra").val() +
                    "/" +
                    idMovCompra,
                function (data) {
                    if (data) {
                        // if (data == "Numero Disponible") {
                        //varios
                        var data = {
                            proveedores: $("#proveedores").val(),
                            tipo: $("#tipo").val(),
                            numCompra: $("#numCompra").val(),
                            fechaCompro: $("#fechaCompro").val(),
                            formaPagoSelect: $("#formaPagoSelect").val(),
                            cantCuotas: $("#cantCuotas").val(),
                        };
                        var idMovCompra = $("#idMovCompra").val();
                        var serializedData = $.param(data);
                        $.get(
                            "movCompras/actualizar/" +
                                idMovCompra +
                                "?" +
                                serializedData,
                            function (data) {
                                if (data) {
                                    Swal.fire({
                                        title: "Actualización Realizada con Exito",
                                        text: "Debe ingresar otro Número Compra",
                                        icon: "success",
                                        confirmButtonColor: "#3085d6",
                                    });
                                    $(".cajaProveedor").toggleClass("d-none");
                                    // window.location.href =
                                    //     "docCompras?operacion=compras&id=" +
                                    //     data.id;
                                } else {
                                    alert(
                                        "No se pudo realizar la actualización."
                                    );
                                }
                            }
                        );
                        // }
                        // else {
                        // Swal.fire({
                        //     title: "Este Número Compra ya está usado",
                        //     text: "Debe ingresar otro Número Compra",
                        //     icon: "warning",
                        //     confirmButtonColor: "#3085d6",
                        // });
                        // }
                    }
                }
            );
        } else {
            Swal.fire({
                title: "Es necesario que agregue un N° Compra",
                text: "Es necesario para realizar la compra",
                icon: "warning",
                confirmButtonColor: "#3085d6",
            });
        }
    } else {
        Swal.fire({
            title: "Es necesario que seleccione un Proveedor",
            text: "Es necesario para realizar la compra",
            icon: "warning",
            confirmButtonColor: "#3085d6",
        });
    }
}
