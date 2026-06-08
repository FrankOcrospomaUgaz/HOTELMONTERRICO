<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Emergencia</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        p {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }

        .total {
            text-align: right;
        }

        .centroText {
            text-align: center;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #888;
        }

        .detalleCaja {
            text-align: center;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }

        th {
            background-color: #058ba0;
            color: white;
            padding: 8px;
            border: 1px solid black;
        }

        td {
            padding: 8px;
            border: 1px solid black;
        }


        label {
            display: block;
            margin: 5px;
            padding: 1px;
        }
    </style>
</head>

<body>

    <?php

    use Illuminate\Support\Facades\DB;


    ?>

    <h1>REPORTE DE EMERGENCIA</h1>

    <hr>

    <section class="detalleCaja">
        <label for="Responsable"><b>Responsable:</b><span id="nombreResponsable"> {{ $responsable }}</span></label>
        <label for="fechaImpresion"><b>F. impresión:</b><span id="nombrefechaImpresion"> {{ $dateTime }}</span></label>
    </section>

    <hr>

    <br>



    <section class="conceptos mb-3">
        <p>HABITACIONES OCUPADAS</p>
        <table class="table text-center">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Tipo</th>
                    <th>F.Ingreso</th>
                    <th>Horas Servicio</th>
                    <th>F.Salida</th>
                    <th><b>Totales</b></th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0;
                foreach ($habitacionesOcupadas as $habitacion) : ?>
                    <tr>
                        <td class="centroText"><?php echo $habitacion->numero; ?></td>
                        <td class="centroText"><?php echo $habitacion->tipo; ?></td>
                        <td class="centroText"><?php echo $habitacion->fechaMovimiento; ?></td>
                        <td class="centroText"><?php echo $habitacion->horas; ?> horas</td>
                        <td class="centroText"><?php echo $habitacion->fecha_salida; ?></td>
                        <td class="total"><?php echo $habitacion->total; ?></td>
                    </tr>
                <?php $total += $habitacion->total;
                endforeach; ?>
                <?php if ($total != 0) { ?>
                    <tr>
                        <td class="centroText"><b>Total</b></td>
                        <td class="centroText"></td>
                        <td class="centroText"></td>
                        <td class="centroText"></td>
                        <td class="centroText"></td>
                        <td class="total"><b><?php echo $total; ?>.00</b></td>
                    </tr>

                <?php  } else { ?>
                    <tr>
                        <td class="centroText" colspan="6">No hubieron Habitaciones Ocupadas</td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </section>
    <div style="page-break-before: always;">


        <section class="conceptos mb-3">


            <?php $total = 0;
            foreach ($habitacionesOcupadas as $habitacion) : ?>
                <p>DETALLES DE LAS HABITACION N° <?php echo $habitacion->numero ?></p>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio Venta</th>
                            <th>Cantidad</th>
                            <th>Descuento</th>
                            <th><b>Totales</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $DetalleHabitacion = DB::select('call detalleMovimientosCarrito(?)', [$habitacion->idMovimiento]);
                        $totalDetalle = 0;
                        foreach ($DetalleHabitacion as $detHab) :
                        ?>

                            <tr>
                                <td class="centroText"><?php echo $detHab->producto_servicio_nombre; ?></td>
                                <td class="centroText"><?php echo $detHab->precioventa; ?></td>
                                <td class="centroText"><?php echo $detHab->cantidad; ?></td>
                                <td class="centroText"><?php echo $detHab->descuento; ?></td>
                                <td class="total"><?php echo number_format ($detHab->precioventa * $detHab->cantidad - $detHab->precioventa * $detHab->cantidad * $detHab->descuento / 100, 2); ?></td>
                            </tr>

                        <?php $totalDetalle += $detHab->precioventa * $detHab->cantidad - $detHab->precioventa * $detHab->cantidad * $detHab->descuento / 100;
                        endforeach; ?>
                        <tr>
                            <td class="centroText"><b>Total</b></td>
                            <td class="centroText"></td>
                            <td class="centroText"></td>
                            <td class="centroText"></td>
                            <td class="total"><b><?php echo number_format($totalDetalle, 2) ?></b></td>
                        </tr>



                    </tbody>
                </table>
                </br> </br>
            <?php $total += $habitacion->total;
            endforeach; ?>

        </section>



    </div>








    <div class="footer">
        Copyright© 2023. Garzasoft. Todos los derechos reservados
    </div>
</body>

</html>