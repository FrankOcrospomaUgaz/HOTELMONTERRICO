<!DOCTYPE html>
<html>


<head>
    <title>DOCUMENTO DE PAGO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }


        .ticket {
            width: 40mm;
            height: 95mm;


            padding: 5px;
            box-sizing: border-box;
        }


        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-size: 10px;
        }


        .text-center {
            text-align: center;
            margin: auto;
        }






        p {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 8px;
            margin: 5px 0;
        }


        .footer {
            text-align: center;
            font-size: 6px;
            color: #888;
        }


        .detalleCaja,
        .detalles,
        .conceptos {
            font-size: 8px;
            margin-top: 5px;
            text-align: center;
        }


        .totales {
            font-size: 8px;
            margin-top: 5px;
            text-align: center;
        }


        .totales table {
            width: 100%;
        }


        .detalles table {
            width: 100%;
            border-collapse: collapse;
        }


        .detalles table tr:last-child {
            border-top: 1px solid #000;
        }


        th,
        td {
            text-align: center;
            padding: 2px;
        }


        th {
            background-color: white;
            color: black;
            font-size: 8px;
            border-bottom: 1px solid #000;
            /* Agregamos borde superior a las celdas de encabezado */
        }


        td {
            font-size: 8px;
        }


        label {
            display: block;
            margin: 2px;
            padding: 0;
            font-size: 8px;
        }
    </style>
</head>


<body>
    <div class="ticket">
        <h1 style="font-size:15px">SUIT MONTERRICO S.A.C.</h1>


        <section class="detalleCaja">
            <label for="RUC"><b>RUC:<span id="RUC">20602664946</span></b> </label>
            <!-- <label for="direccion"><span style="font-size:7px" id="direccion">PRO. BOLOGNESI NRO. SN LAMBAYEQUE CHICLAYO CHICLAYO</span></label> -->
            <label for="direccion"><span style="font-size:7px" id="direccion">CHICLAYO CHICLAYO LAMBAYEQUE</span></label>
            <label for="tipoElectronica"><b><span style="font-size:10px"
                        id="nombretipoElectronica">{{ $tipoElectronica }}</span></b></label>
            <label for="numeroVenta"><b><span style="font-size:9px"
                        id="numeroVenta">{{ $numeroVenta }}</span></b></label>
        </section>


        <hr>


        <section class="totales">
            <table>
                <tr>
                    <td style="text-align: left;">
                        <label for="fechaemision"><b>FECHA:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="">{{ $fechaemision }}</label>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <label for="ruc_dni"><b>DNI/RUC:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="">{{ $ruc_dni }}</label>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <label for="cliente"><b>NOMBRE:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="">{{ $cliente }}</label>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <label for="direccion"><b>DIRECCIÓN:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="">{{ $direccion }}</label>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <label for="direccion"><b>N° HABITACIÓN:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="">{{ $numeroHab }}</label>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <label for="direccion"><b>FECHA INICIO:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="">{{ $fechaInicio }}</label>
                    </td>
                </tr>
            </table>
        </section>




        <section class="detalles">


            <table class="table">
                <tr>
                    <th><b>Producto</b></th>
                    <th><b>Cant.</b></th>
                    <th><b>Unit.</b></th>
                    <th><b>Subt.</b></th>
                </tr>




                <?php
                $totalDetalle = 0;
                foreach ($detalles as $detHab) :
                ?>


                <tr>
                    <td class="centroText"><?php echo $detHab->descripcion; ?></td>

                    <td class="centroText"><?php echo $detHab->cantidad; ?></td>
                    <td class="centroText"><?php echo $detHab->precioventaunitarioxitem; ?></td>
                    <td class="total"><?php echo number_format($detHab->precioventaunitarioxitem * $detHab->cantidad, 2); ?></td>
                </tr>


                <?php $totalDetalle += $detHab->precioventaunitarioxitem
                        * $detHab->cantidad;
                endforeach; ?>
                <tr>
                    <td class="centroText"><b>Total</b></td>
                    <td class="centroText"></td>
                    <td class="centroText"></td>
                    <td class="total"><b><?php echo number_format($totalDetalle, 2); ?></b></td>
                </tr>


                </tbody>
            </table>
        </section>
        <br>
        <section>
            <table>
                <tbody class="conceptos">
                    <tr >
                        <td style="text-align: left;">
                            <label for="fechaemision"><b>FORMA DE PAGO:</b></label>
                        </td>
                        <td style="text-align: right;">
                            <label for="">{{ $formaPago }}</label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>


        <hr>
        <section class="totales">
            <table>


                <?php
                if ($linkRevisarFact) {
                    echo '<tr>
                                        <td style="text-align: left;">
                                            <label for="opGravada"><b>Op. Gravada:</b></label>
                                        </td>
                                        <td style="text-align: right;">
                                            <label for="opGravada">' .
                        number_format($totalDetalle / 1.18, 2) .
                        '</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">
                                            <label for="igv"><b>I.G.V.(18%):</b></label>
                                        </td>
                                        <td style="text-align: right;">
                                            <label for="igv">' .
                        number_format($totalDetalle - $totalDetalle / 1.18, 2) .
                        '</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">
                                            <label for="opInafecta"><b>Op. Inafecta:</b></label>
                                        </td>
                                        <td style="text-align: right;">
                                            <label for="opInafecta">0.00</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">
                                            <label for="opExonerada"><b>Op. Exonerada:</b></label>
                                        </td>
                                        <td style="text-align: right;">
                                            <label for="opExonerada">0.00</label>
                                        </td>
                                    </tr> <br>';
                }
                ?>






                <?php if ($formaPago != 'Tarjeta' && $formaPago != 'Yape'): ?>
                <tr>
                    <td style="text-align: left;">
                        <label for="total"><b>TOTAL APAGAR:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="opGravada"><?php echo number_format($totalDetalle, 2); ?></label>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <label for="total"><b>EFECTIVO:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="opGravada"><?php echo number_format($totalPagado, 2); ?></label>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <label for="total"><b>VUELTO:</b></label>
                    </td>
                    <td style="text-align: right;">
                        <label for="opGravada"><?php echo number_format($vuelto, 2); ?></label>
                    </td>
                </tr>
                <?php endif; ?>


                <br>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <label for="total"><b>SON:<?php
                        $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                        $totalEnPalabras = $formatter->format(floor($totalDetalle)); // Redondeamos hacia abajo para quitar la parte decimal
                        if ($totalDetalle != floor($totalDetalle)) {
                            $parteDecimal = round(($totalDetalle - floor($totalDetalle)) * 100); // Convertimos la parte decimal a centavos
                            echo ucfirst($totalEnPalabras) . " CON $parteDecimal/100 SOLES";
                        } else {
                            echo ucfirst($totalEnPalabras) . ' CON 00/100 SOLES';
                        }
                        ?></b></label>
                    </td>

                </tr>
            </table>







        </section>
        <br>
        <?php
        if ($linkRevisarFact) {
            echo '<section class="detalleCaja">
                            <label for="gracias" style="font-size:12px">¡ Gracias por su compra !</label>
                            <br>
                            <label for=""><b>Representación impresa del Comprobante Electrónico, consulta en <a style="text-decoration: none;color:black" href="http://facturae-garzasoft.com" target="_blank">http://facturae-garzasoft.com</a> </b></label>
                          </section>';
        }
        ?>


        <!-- <div class="footer">
            Copyright© 2023. Garzasoft. Todos los derechos reservados
        </div> -->


    </div>
</body>


</html>
