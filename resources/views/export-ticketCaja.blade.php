<!DOCTYPE html>
<html>

<head>
    <title>Ticket de Cierre de Caja</title>
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

        .data-cell {
    font-size: 6px; /* Ajusta el tamaño de fuente según tus preferencias */
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
        .tiposPagos,
        .conceptos {
            font-size: 8px;
            margin-top: 5px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr:last-child {
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
    <?php
use App\Models\detallegreso;
use Illuminate\Support\Facades\DB;
?>

    <div class="ticket">
        <h1>Ticket de Cierre de Caja</h1>

        <hr>

        <section class="detalleCaja">
            <div class="detalleCaja-item">
                <label for="Responsable"><b>Responsable Apertura:</b></label>
                <span id="nombreResponsable">{{ $responsableA }}</span>
            </div>
            <div class="detalleCaja-item">
                <label for="MontoApertura"><b>Monto Apertura:</b></label>
                <span id="MontoApertura">S/{{ number_format($montoApertura, 2) }}</span>
            </div>
            <div class="detalleCaja-item">
                <label for="fechaApertura"><b>Fecha Apertura:</b></label>
                <span id="nombrefechaApertura">{{ $fechaApertura }}</span>
            </div>
            <div class="detalleCaja-item">
                <label for="turno"><b>Turno:</b></label>
                <span id="turno">{{ $turno }}</span>
            </div>

            <hr> <!-- Línea separadora -->

            <div class="detalleCaja-item">
                <label for="Responsable"><b>Responsable Cierre:</b></label>
                <span id="nombreResponsable">{{ $responsableC }}</span>
            </div>
            <div class="detalleCaja-item">
                <label for="montoCierre"><b>Monto Cierre:</b></label>
                <span id="montoCierre">S/{{ $montoCierre }}</span>
            </div>
            <div class="detalleCaja-item">
                <label for="fechaCierre"><b>Fecha Cierre:</b></label>
                <span id="nombrefechaCierre">{{ $fechaCierre }}</span>
            </div>

            <hr> <!-- Línea separadora -->

            <div class="detalleCaja-item">
                <label for="fechaImpresion"><b>Fecha Impresión:</b></label>
                <span id="nombrefechaImpresion">{{ $dateTime }}</span>
            </div>
        </section>


        <hr>

        <section class="tiposPagos  mb-4">
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
            <table>
                <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                </tr>
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
            </table>

        </section>
        <br>

        <section class="conceptos mb-3">
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
                    <td class="centroText data-cell"><?php echo $movimiento->numero; ?></td>
                    <td class="centroText data-cell"><?php echo $movimiento->fecha; ?></td>
                    <td class="total data-cell" style="text-align: right;"><?php echo $movimiento->total; ?></td>
                </tr>
            </table>
        
           <br>
        
        
            <?php $detalleEgreso = detallegreso::where("movimiento_id", $movimiento->id)->get();?>
        
            <table class="table subtabla text-center">
            <thead>
                <!-- Encabezado de la subtabla -->
            </thead>
            <tbody>
                <?php $totalDetalle = 0;foreach ($detalleEgreso as $detalle): ?>
                <tr>
                    
                    <td class="data-cell"><?php echo $detalle->tipo; ?>
                    </td><td class="data-cell" style="text-align: right;"><?php echo $detalle->nota; ?></td>
                    <td class="data-cell" style="text-align: right;"><?php echo number_format($detalle->monto, 2); ?></td>
                </tr>
                <?php $totalDetalle += $detalle->monto;endforeach;?>
                <tr>
                    <td class="data-cell"><?php echo '<b>TOTAL</b>'; ?></td>
                    <td class="data-cell"><?php echo ''; ?></td>
                    <td class="data-cell" style="text-align: right;"><?php echo number_format($totalDetalle, 2); ?></td>
                </tr>
            </tbody>
        </table>
        
        
                <br>
        
            <?php $total += $movimiento->total;endforeach;?>
        
            <?php if ($total != 0): ?>
                <br>
            <table class="table text-center">
        
                <tr>
                    <td><b>TOTAL ACUMULADO</b></td>
                    <td class="data-cell"></td>
                    <td class="total totalAcumDiff data-cell">
                        <?php echo number_format($total, 2); ?>
                    </td>
                </tr>
            </table>
            <?php else: ?>
            <table class="table text-center">
                <tr>
                    <td class="centroText data-cell" colspan="3">No hubieron Movimientos de Salida</td>
                </tr>
            </table>
            <?php endif;?>
        </section>
        <br>
        <section class="conceptos mb-3">
            <p>MOVIMIENTOS ANULADOS </p>
            <br>
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

        <!-- <div class="footer">
            Copyright© 2023. Garzasoft. Todos los derechos reservados
        </div> -->
    </div>
</body>

</html>
