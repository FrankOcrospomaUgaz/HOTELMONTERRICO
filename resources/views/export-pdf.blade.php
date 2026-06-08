<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Arqueo de Caja</title>

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
    use App\Models\detallegreso;
    use Illuminate\Support\Facades\DB;
    ?>

    <h1>Reporte de Arqueo de Caja</h1>

    <hr>

    <section class="detalleCaja">
        <label for="Responsable"><b>Resp Apertura:</b><span id="nombreResponsable"> {{ $responsableA }}</span></label>
        <label for="MontoApertura"><b>Monto Apertura:</b><span id="MontoApertura"> S/{{ $montoApertura }}</span></label>
        <label for="fechaApertura"><b>F. apertura:</b><span id="nombrefechaApertura"> {{ $fechaApertura }}</span></label>
        <label for="fechaApertura"><b>Turno:</b><span id="nombrefechaApertura"> {{ $turno }}</span></label>

        <label for="Responsable"><b>Resp Cierre:</b><span id="nombreResponsable"> {{ $responsableC }}</span></label>
        <label for="montoCierre"><b>Monto Cierre:</b><span id="montoCierre"> S/{{ $montoCierre }}</span></label>
        <label for="fechaCierre"><b>F. Cierre:</b><span id="nombrefechaCierre"> {{ $fechaCierre }}</span></label>


        <label for="fechaImpresion"><b>F. impresión:</b><span id="nombrefechaImpresion">
                {{ $dateTime }}</span></label>
    </section>

    <hr>

    <br>

    <section class="tiposPagos  mb-3">
        <p>TOTALES POR TIPO DE PAGO</p>
        <table>
            <tr>
                <th></th>
                <th>Ingreso</th>
                <th>Egreso</th>
                <th><b>Totales</b></th>
            </tr>
            <tr>
                <td>Efectivo</td>
                <td class="total efectivoAcumIn">
                    <?php echo isset($ingreso->efectivoAcum) ? number_format($ingreso->efectivoAcum, 2, '.', ',') : '0.00'; ?>
                </td>
                <td class="total efectivoAcumEg">
                    <?php
                    $efectivoAcumEg = isset($egreso->efectivoAcum) ? $egreso->efectivoAcum : 0;
                    echo $efectivoAcumEg != 0 ? '-' . number_format($efectivoAcumEg, 2, '.', ',') : '0.00';
                    ?>
                </td>
                <td class="total efectivoAcumDiff">
                    <?php
                    $efectivoAcumIn = isset($ingreso->efectivoAcum) ? $ingreso->efectivoAcum : 0;
                    $efectivoAcumEg = isset($egreso->efectivoAcum) ? $egreso->efectivoAcum : 0;
                    $diferenciaEfectivo = $efectivoAcumIn - $efectivoAcumEg;
                    echo '<b>' . number_format($diferenciaEfectivo, 2, '.', ',') . '</b>';
                    ?>
                </td>
            </tr>
            <tr>
                <td>Tarjeta</td>
                <td class="total tarjetaAcumIn">
                    <?php echo isset($ingreso->tarjetaAcum) ? number_format($ingreso->tarjetaAcum, 2, '.', ',') : '0.00'; ?>
                </td>
                <td class="total tarjetaAcumEg">
                    <?php
                    $tarjetaAcumEg = isset($egreso->tarjetaAcum) ? $egreso->tarjetaAcum : 0;
                    echo $tarjetaAcumEg != 0 ? '-' . number_format($tarjetaAcumEg, 2, '.', ',') : '0.00';
                    ?>
                </td>
                <td class="total tarjetaAcumDiff">
                    <?php
                    $tarjetaAcumIn = isset($ingreso->tarjetaAcum) ? $ingreso->tarjetaAcum : 0;
                    $tarjetaAcumEg = isset($egreso->tarjetaAcum) ? $egreso->tarjetaAcum : 0;
                    $diferenciaTarjeta = $tarjetaAcumIn - $tarjetaAcumEg;
                    echo '<b>' . number_format($diferenciaTarjeta, 2, '.', ',') . '</b>';
                    ?>
                </td>
            </tr>
            <tr>
                <td>Yape</td>
                <td class="total yapeAcumIn">
                    <?php echo isset($ingreso->yapeAcum) ? number_format($ingreso->yapeAcum, 2, '.', ',') : '0.00'; ?>
                </td>
                <td class="total yapeAcumEg">
                    <?php
                    $yapeAcumEg = isset($egreso->yapeAcum) ? $egreso->yapeAcum : 0;
                    echo $yapeAcumEg != 0 ? '-' . number_format($yapeAcumEg, 2, '.', ',') : '0.00';
                    ?>
                </td>
                <td class="total yapeAcumDiff">
                    <?php
                    $yapeAcumIn = isset($ingreso->yapeAcum) ? $ingreso->yapeAcum : 0;
                    $yapeAcumEg = isset($egreso->yapeAcum) ? $egreso->yapeAcum : 0;
                    $diferenciaYape = $yapeAcumIn - $yapeAcumEg;
                    echo '<b>' . number_format($diferenciaYape, 2, '.', ',') . '</b>';
                    ?>
                </td>
            </tr>
            <tr>
                <td>Plin</td>
                <td class="total plinAcumIn">
                    <?php echo isset($ingreso->plinAcum) ? number_format($ingreso->plinAcum, 2, '.', ',') : '0.00'; ?>
                </td>
                <td class="total plinAcumEg">
                    <?php
                    $plinAcumEg = isset($egreso->plinAcum) ? $egreso->plinAcum : 0;
                    echo $plinAcumEg != 0 ? '-' . number_format($plinAcumEg, 2, '.', ',') : '0.00';
                    ?>
                </td>
                <td class="total plinAcumDiff">
                    <?php
                    $plinAcumIn = isset($ingreso->plinAcum) ? $ingreso->plinAcum : 0;
                    $plinAcumEg = isset($egreso->plinAcum) ? $egreso->plinAcum : 0;
                    $diferenciaPlin = $plinAcumIn - $plinAcumEg;
                    echo '<b>' . number_format($diferenciaPlin, 2, '.', ',') . '</b>';
                    ?>
                </td>
            </tr>
            <tr>
                <td>Depósito</td>
                <td class="total depositoAcumIn">
                    <?php echo isset($ingreso->depositoAcum) ? number_format($ingreso->depositoAcum, 2, '.', ',') : '0.00'; ?>
                </td>
                <td class="total depositoAcumEg">
                    <?php
                    $depositoAcumEg = isset($egreso->depositoAcum) ? $egreso->depositoAcum : 0;
                    echo $depositoAcumEg != 0 ? '-' . number_format($depositoAcumEg, 2, '.', ',') : '0.00';
                    ?>
                </td>
                <td class="total depositoAcumDiff">
                    <?php
                    $depositoAcumIn = isset($ingreso->depositoAcum) ? $ingreso->depositoAcum : 0;
                    $depositoAcumEg = isset($egreso->depositoAcum) ? $egreso->depositoAcum : 0;
                    $diferenciaDeposito = $depositoAcumIn - $depositoAcumEg;
                    echo '<b>' . number_format($diferenciaDeposito, 2, '.', ',') . '</b>';
                    ?>
                </td>
            </tr>
            <tr>
                <td><b>Total</b></td>
                <td class="total totalAcumIn">
                    <?php echo isset($ingreso->totalAcum) ? '<b>' . number_format($ingreso->totalAcum, 2, '.', ',') . '</b>' : '<b>0.00</b>'; ?>
                </td>
                <td class="total totalAcumEg">
                    <?php
                    $totalAcumEg = isset($egreso->totalAcum) ? $egreso->totalAcum : 0;
                    echo $totalAcumEg != 0 ? '<b>-' . number_format($totalAcumEg, 2, '.', ',') . '</b>' : '<b>0.00</b>';
                    ?>
                </td>
                <td class="total totalAcumDiff">
                    <?php
                    $totalAcumIn = isset($ingreso->totalAcum) ? $ingreso->totalAcum : 0;
                    $totalAcumEg = isset($egreso->totalAcum) ? $egreso->totalAcum : 0;
                    $diferenciaTotal = $totalAcumIn - $totalAcumEg;
                    echo '<b>' . number_format($diferenciaTotal, 2, '.', ',') . '</b>';
                    ?>
                </td>
            </tr>
        </table>
    </section>

    <br>
    <section class="conceptos mb-3">
        <p>TOTALES POR CONCEPTO DE PAGO</p>
        <table class="table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Apertura</td>
                    <td class="total">{{ $montoApertura }}</td>
                </tr>
                <tr>
                    <td>Ventas del día</td>
                    <td class="total">{{ $Ventas }}</td>
                </tr>
                <tr>
                    <td>Otros ingresos</td>
                    <td class="total">{{ $otrosIngresos }}</td>
                </tr>
                <tr>
                    <td>Compras</td>
                    <td class="total">
                        <?php
                        if ($Compras == 0) {
                            echo '0';
                        } else {
                            echo '-' . $Compras;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Otros Gastos</td>
                    <td class="total">
                        <?php
                        if ($otrosEgresos == 0) {
                            echo '0';
                        } else {
                            echo '-' . $otrosEgresos;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Total</b></td>
                    <td class="total"><b>{{ $TotalCaja }}</b></td>
                </tr>
            </tbody>
        </table>
    </section>

    </table>
    </section>

    <br>

    <div style="page-break-before: always;">
        <p>MOVIMIENTOS DE SALIDA DE CAJA</p>
        <?php $total = 0;foreach ($movimientosCajaEgresos as $movimiento): ?>
<br>
        <table class="table text-center">
            <tr>
                <th>Numero</th>
                <th>Fecha</th>
                <th><b>Monto</b></th>
            </tr>
            <tr>
                <td class="centroText "><?php echo $movimiento->numero; ?></td>
                <td class="centroText "><?php echo $movimiento->fecha; ?></td>
                <td class="total "><b><?php echo $movimiento->total; ?></b></td>
            </tr>

        </table>

        <br>

        <?php $detalleEgreso = detallegreso::where('movimiento_id', $movimiento->id)->get(); ?>

        <table class="table subtabla text-center">
            <thead>
                <!-- Encabezado de la subtabla -->
            </thead>
            <tbody>
                <?php $totalDetalle = 0;foreach ($detalleEgreso as $detalle): ?>
                <tr>

                    <td class="" style="text-align: center;"><?php echo $detalle->tipo; ?></td>
                    <td class="" style="text-align: right;"><?php echo $detalle->nota; ?></td>
                    <td class="" style="text-align: right;"><?php echo number_format($detalle->monto, 2); ?></td>
                </tr>
                <?php $totalDetalle += $detalle->monto;endforeach;?>
                <tr>
                    <td class="" style="text-align: center;" colspan="2"><?php echo '<b>TOTAL</b>'; ?></td>


                    <td class="" style="text-align: right;"><b><?php echo number_format($totalDetalle, 2); ?></b></td>
                </tr>
            </tbody>
        </table>

        <br>

        <?php $total += $movimiento->total;endforeach;?>

        <?php if ($total != 0): ?>
        <br>
        <table class="table text-center">

            <tr>
                <td colspan="2" style="text-align: center"><b>TOTAL ACUMULADO</b></td>

                <td class="total totalAcumDiff ">
                    <b><?php echo number_format($total, 2); ?></b>
                </td>
            </tr>
        </table>
        <?php else: ?>
        <table class="table text-center">
            <tr>
                <td class="centroText " colspan="3">No hubieron Movimientos de Salida</td>
            </tr>
        </table>
        <?php endif;?>

    </div>
    <br>
    <div style="page-break-before: always;">
        <section class="conceptos mb-3">
            <p>MOVIMIENTOS ANULADOS POR CONCEPTO DE PAGO</p>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0;
                    foreach ($movAnulados as $movimiento) : ?>
                    <tr>
                        <td class=""><?php echo $movimiento->conceptoPago; ?></td>
                        <td class="centroText"><?php echo $movimiento->fechaEliminacion; ?></td>
                        <td class="total"><?php echo $movimiento->total; ?></td>
                    </tr>
                    <?php $total += $movimiento->total;
                    endforeach; ?>
                    <?php if ($total != 0) { ?>
                    <tr>
                        <td class=""><b>Total</b></td>
                        <td class="centroText"></td>
                        <td class="total"><b><?php echo $total; ?>.00</b></td>
                    </tr>

                    <?php  }else{?>
                    <tr>
                        <td class="centroText" colspan="3">No hubieron Movimientos Eliminados</td>
                    </tr>

                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>





    <div class="footer">
        Copyright© 2023. Garzasoft. Todos los derechos reservados
    </div>
</body>

</html>
