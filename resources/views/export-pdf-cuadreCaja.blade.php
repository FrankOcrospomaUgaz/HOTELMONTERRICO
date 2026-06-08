<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Cuadre de Caja</title>

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

        .text-left {
            text-align: left;
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
    use App\Models\Movimiento;
    ?>

    <h1>REPORTE DE DETALLE DE VENTAS</h1>

    <hr>

    <section class="detalleCaja">
        <label for="Responsable"><b>Resp Impresión:</b><span id="nombreResponsable"> {{ $responsable }}</span></label>
        <label for="fechaImpresion"><b>F. Impresión:</b><span id="nombrefechaImpresion">
                {{ $dateTime }}</span></label>
        <br>
        <label for="Responsable"><b>Resp Apertura:</b><span id="nombreResponsable">
                {{ $responsableA }}</span></label>
        <label for="MontoApertura"><b>Monto Apertura:</b><span id="MontoApertura">
                S/{{ $montoApertura }}</span></label>
        <label for="fechaApertura"><b>F. apertura:</b><span id="nombrefechaApertura">
                {{ $fechaApertura }}</span></label>
        <label for="fechaApertura"><b>Turno:</b><span id="turno">
                {{ $turno }}</span></label>
        <br>
        <label for="Responsable"><b>Resp Cierre:</b><span id="nombreResponsable">
                {{ $responsableC }}</span></label>
        <label for="montoCierre"><b>Monto Cierre:</b><span id="montoCierre"> S/{{ $montoCierre }}</span></label>
        <label for="fechaCierre"><b>F. Cierre:</b><span id="nombrefechaCierre"> {{ $fechaCierre }}</span></label>


    </section>



    <br>
    <hr>
    <p style="color:Red">Movimientos Pagos de clientes por tipo de pago</p>
    <hr>

    <?php imprimirTabla($movXEfectivo, 'Pago en Efectivo'); ?>
    <?php imprimirTabla($movXTarjeta, 'Pago en Tarjeta'); ?>
    <?php imprimirTabla($movXDeposito, 'Pago en Depósito'); ?>
    <?php imprimirTabla($movXYape, 'Pago en Yape'); ?>
    <?php imprimirTabla($movXPlin, 'Pago en Plin'); ?>
    <?php imprimirTabla($movXCombinado, 'Pagos Combinados'); ?>



    <?php
    function imprimirTabla($data, $title)
    {
        if ($title != 'Pagos Combinados') {
            echo "<section class='text-left'>";
            echo "<p>$title</p>";
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>N° Mov. Atención</th>';
            echo '<th>Fecha Ingreso</th>';
            echo '<th>N° Mov. Pago</th>';
            echo '<th>Horas Servicio</th>';
            echo '<th>Fecha Salida</th>';
            echo '<th>N° Habitación</th>';
            echo '<th>Monto Pagado</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
    
            $total = 0; // Inicializar el total en 0
    
            if (is_array($data) || is_object($data)) {
                foreach ($data as $item) {
                    echo '<tr>';
                    echo "<td style='text-align: center;'>{$item->numMovPadre}</td>";
                    echo "<td style='text-align: center;'>{$item->fechaIngreso}</td>";
                    echo "<td style='text-align: center;'>{$item->numMovPago}</td>";
                    echo "<td style='text-align: center;'>" . number_format($item->horasServicio, 0, '.', '') . '</td>';
                    echo "<td style='text-align: center;'>{$item->fechaSalida}</td>";
                    echo "<td style='text-align: center;'>{$item->numHab}</td>";
                    echo "<td style='text-align: center;'>{$item->montoPagado}</td>";
                    echo '</tr>';
    
                    // Acumular el monto pagado para calcular el total
                    $total += floatval($item->montoPagado);
                }
                $total = number_format($total, 2);
                // Mostrar el total en la última fila de la tabla
                echo '<tr>';
                echo '<td colspan="6" style="text-align: right;"><strong>Total:</strong></td>';
                echo "<td style='text-align: center;'><strong>{$total}</strong></td>";
                echo '</tr>';
            } else {
                echo '<tr>';
                echo "<td colspan='7' style='text-align: center;'>$data</td>";
                echo '</tr>';
            }
    
            echo '</tbody>';
            echo '</table>';
            echo '</section>';
        } else {
            echo '<br>';
            echo "<section class='text-left'>";
            echo "<p>$title</p>";
            $total = 0; // Inicializar el total en 0
    
            if (is_array($data) || is_object($data)) {
                $totalAcumulado = 0;
                foreach ($data as $item) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>N° Mov. Atención</th>';
                    echo '<th>Fecha Ingreso</th>';
                    echo '<th>N° Mov. Pago</th>';
                    echo '<th>Horas Servicio</th>';
                    echo '<th>Fecha Salida</th>';
                    echo '<th>N° Habitación</th>';
                    echo '<th>Monto Pagado</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    echo '<tr>';
                    echo "<td style='text-align: center;'>{$item->numMovPadre}</td>";
                    echo "<td style='text-align: center;'>{$item->fechaIngreso}</td>";
                    echo "<td style='text-align: center;'>{$item->numMovPago}</td>";
                    echo "<td style='text-align: center;'>" . number_format($item->horasServicio, 0, '.', '') . '</td>';
                    echo "<td style='text-align: center;'>{$item->fechaSalida}</td>";
                    echo "<td style='text-align: center;'>{$item->numHab}</td>";
                    echo "<td style='text-align: center;'>{$item->montoPagado}</td>";
                    echo '</tr>';
    
                    // Acumular el monto pagado para calcular el total
                    $total += floatval($item->montoPagado);
    
                    $total = number_format($total, 2);
    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</section>';
                    echo '<br>';
    
                    if ($item->idMovPago != null) {
                        $movCon = Movimiento::where('id', $item->idMovPago)->first();
    
                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Efectivo</th>';
                        echo '<th>Yape</th>';
                        echo '<th>Tarjeta</th>';
                        echo '<th>Plin</th>';
                        echo '<th>Deposito</th>';
                        echo '<th>Vuelto</th>';
                        echo '<th>Total</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        echo '<tr>';
                        echo "<td style='text-align: center;'>{$movCon->efectivo}</td>";
                        echo "<td style='text-align: center;'>{$movCon->yape}</td>";
                        echo "<td style='text-align: center;'>{$movCon->tarjeta}</td>";
                        echo "<td style='text-align: center;'>{$movCon->plin}</td>";
                        echo "<td style='text-align: center;'>{$movCon->deposito}</td>";
                        echo "<td style='text-align: center;'>{$movCon->vuelto}</td>";
    
                        $total = floatval($movCon->efectivo) + floatval($movCon->yape) + floatval($movCon->tarjeta) + floatval($movCon->plin) + floatval($movCon->deposito) + floatval($movCon->vuelto);
                        $total = number_format($total, 2);
                        $totalAcumulado += $total;
                        echo "<td style='text-align: center;'>{$total}</td>";
    
                        echo '</tr>';
                        echo '</tbody>';
                        echo '</table>';
                        echo '<br>';
                        echo '<br>';
                        echo '<br>';
                    }
    
                    // Calcular el Total
                }
                $total = number_format($totalAcumulado, 2);
                // Mostrar el total en la última fila de la tabla
    
                echo '<table>';
    
                echo '<tr>';
                echo '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>';
                echo "<td style='text-align: center;'><strong>{$total}</strong></td>";
                echo '</tr>';
                echo '</table>';
            } else {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Efectivo</th>';
                echo '<th>Yape</th>';
                echo '<th>Tarjeta</th>';
                echo '<th>Plin</th>';
                echo '<th>Deposito</th>';
                echo '<th>Vuelto</th>';
                echo '<th>Total</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                echo '<tr>';
                echo "<td colspan='7' style='text-align: center;'>$data</td>";
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
            }
        }
    }
    ?>





    <br>
    <br>

    <div style="page-break-before: always;">
        <hr>
        <p style="color:Red">Otros Movimientos INGRESOS</p>
        <hr>
        <?php imprimirTablaOtrosMov($movXEfectivoOtrosMov, 'Pago en Efectivo'); ?>
        <?php imprimirTablaOtrosMov($movXTarjetaOtrosMov, 'Pago en Tarjeta'); ?>
        <?php imprimirTablaOtrosMov($movXDepositoOtrosMov, 'Pago en Depósito'); ?>
        <?php imprimirTablaOtrosMov($movXYapeOtrosMov, 'Pago en Yape'); ?>
        <?php imprimirTablaOtrosMov($movXPlinOtrosMov, 'Pago en Plin'); ?>
        <?php imprimirTablaOtrosMov($movXCombinadoOtrosMov, 'Pagos Combinados'); ?>

    </div>

    <?php
    function imprimirTablaOtrosMov($data, $title)
    {
        if ($title != 'Pagos Combinados') {
            echo "<section class='text-left'>";
            echo "<p>$title</p>";
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>N° Movimiento</th>';
            echo '<th>Fecha</th>';
            echo '<th>Concepto</th>';
            echo '<th>Notas</th>';
            echo '<th>Monto Pagado</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
    
            $total = 0; // Inicializar el total en 0
    
            if (is_array($data) || is_object($data)) {
                foreach ($data as $item) {
                    echo '<tr>';
                    echo "<td style='text-align: center;'>{$item->numMovPadre}</td>";
                    echo "<td style='text-align: center;'>{$item->fechaIngreso}</td>";
                    echo "<td style='text-align: center;'>{$item->concepto}</td>";
                    echo "<td style='text-align: center;'>{$item->notas}</td>";
                    echo "<td style='text-align: center;'>{$item->total}</td>";
    
                    echo '</tr>';
    
                    // Acumular el monto pagado para calcular el total
                    $total += floatval($item->total);
                }
                $total = number_format($total, 2);
                // Mostrar el total en la última fila de la tabla
                echo '<tr>';
                echo '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>';
                echo "<td style='text-align: center;'><strong>{$total}</strong></td>";
                echo '</tr>';
            } else {
                echo '<tr>';
                echo "<td colspan='5' style='text-align: center;'>$data</td>";
                echo '</tr>';
            }
    
            echo '</tbody>';
            echo '</table>';
            echo '</section>';
        } else {
            echo '<br>';
            echo "<section class='text-left'>";
            echo "<p>$title</p>";
            $total = 0; // Inicializar el total en 0
            if (is_array($data) || is_object($data)) {
                $totalAcumulado = 0;
                foreach ($data as $item) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>N° Movimiento</th>';
                    echo '<th>Fecha</th>';
                    echo '<th>Concepto</th>';
                    echo '<th>Notas</th>';
                    echo '<th>Monto Pagado</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
    
                    echo '<tr>';
                    echo "<td style='text-align: center;'>{$item->numMovPadre}</td>";
                    echo "<td style='text-align: center;'>{$item->fechaIngreso}</td>";
                    echo "<td style='text-align: center;'>{$item->concepto}</td>";
                    echo "<td style='text-align: center;'>{$item->notas}</td>";
                    echo "<td style='text-align: center;'>{$item->total}</td>";
    
                    echo '</tr>';
    
                    // Acumular el monto pagado para calcular el total
                    $total += floatval($item->total);
    
                    $total = number_format($total, 2);
    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</section>';
                    echo '<br>';
    
                    if ($item->idMovPago != null) {
                        $movCon = Movimiento::where('id', $item->idMovPago)->first();
    
                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Efectivo</th>';
                        echo '<th>Yape</th>';
                        echo '<th>Tarjeta</th>';
                        echo '<th>Plin</th>';
                        echo '<th>Deposito</th>';
                        echo '<th>Total</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        echo '<tr>';
                        echo "<td style='text-align: center;'>{$movCon->efectivo}</td>";
                        echo "<td style='text-align: center;'>{$movCon->yape}</td>";
                        echo "<td style='text-align: center;'>{$movCon->tarjeta}</td>";
                        echo "<td style='text-align: center;'>{$movCon->plin}</td>";
                        echo "<td style='text-align: center;'>{$movCon->deposito}</td>";
    
                        $total = floatval($movCon->efectivo) + floatval($movCon->yape) + floatval($movCon->tarjeta) + floatval($movCon->plin) + floatval($movCon->deposito) + floatval($movCon->vuelto);
                        $total = number_format($total, 2);
                        $totalAcumulado += $total;
                        echo "<td style='text-align: center;'>{$total}</td>";
    
                        echo '</tr>';
                        echo '</tbody>';
                        echo '</table>';
                        echo '<br>';
                        echo '<br>';
                        echo '<br>';
                    }
    
                    // Calcular el Total
                }
                $total = number_format($totalAcumulado, 2);
                // Mostrar el total en la última fila de la tabla
    
                echo '<table>';
    
                echo '<tr>';
                echo '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>';
                echo "<td style='text-align: center;'><strong>{$total}</strong></td>";
                echo '</tr>';
                echo '</table>';
            } else {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Efectivo</th>';
                echo '<th>Yape</th>';
                echo '<th>Tarjeta</th>';
                echo '<th>Plin</th>';
                echo '<th>Deposito</th>';
                echo '<th>Vuelto</th>';
                echo '<th>Total</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                echo '<tr>';
                echo "<td colspan='7' style='text-align: center;'>$data</td>";
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
            }
        }
    }
    ?>

    <br>
    <br>

    <div style="page-break-before: always;">
        <hr>
        <p style="color:Red">Otros Movimientos SALIDA</p>
        <hr>
        <?php imprimirTablaOtrosMovEgreso($movXEfectivoOtrosMovEgreso, 'Pago en Efectivo'); ?>
        <?php imprimirTablaOtrosMovEgreso($movXTarjetaOtrosMovEgreso, 'Pago en Tarjeta'); ?>
        <?php imprimirTablaOtrosMovEgreso($movXDepositoOtrosMovEgreso, 'Pago en Depósito'); ?>
        <?php imprimirTablaOtrosMovEgreso($movXYapeOtrosMovEgreso, 'Pago en Yape'); ?>
        <?php imprimirTablaOtrosMovEgreso($movXPlinOtrosMovEgreso, 'Pago en Plin'); ?>
        <?php imprimirTablaOtrosMovEgreso($movXCombinadoOtrosMovEgreso, 'Pagos Combinados'); ?>

    </div>

    <?php
    function imprimirTablaOtrosMovEgreso($data, $title)
    {
        if ($title != 'Pagos Combinados') {
            echo "<section class='text-left'>";
            echo "<p>$title</p>";
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>N° Movimiento</th>';
            echo '<th>Fecha</th>';
            echo '<th>Concepto</th>';
            echo '<th>Notas</th>';
            echo '<th>Monto Pagado</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
    
            $total = 0; // Inicializar el total en 0
    
            if (is_array($data) || is_object($data)) {
                foreach ($data as $item) {
                    echo '<tr>';
                    echo "<td style='text-align: center;'>{$item->numMovPadre}</td>";
                    echo "<td style='text-align: center;'>{$item->fechaIngreso}</td>";
                    echo "<td style='text-align: center;'>{$item->concepto}</td>";
                    echo "<td style='text-align: center;'>{$item->notas}</td>";
                    echo "<td style='text-align: center;'>{$item->total}</td>";
    
                    echo '</tr>';
    
                    // Acumular el monto pagado para calcular el total
                    $total += floatval($item->total);
                }
                $total = number_format($total, 2);
                // Mostrar el total en la última fila de la tabla
                echo '<tr>';
                echo '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>';
                echo "<td style='text-align: center;'><strong>{$total}</strong></td>";
                echo '</tr>';
            } else {
                echo '<tr>';
                echo "<td colspan='5' style='text-align: center;'>$data</td>";
                echo '</tr>';
            }
    
            echo '</tbody>';
            echo '</table>';
            echo '</section>';
        } else {
            echo '<br>';
            echo "<section class='text-left'>";
            echo "<p>$title</p>";
            $total = 0; // Inicializar el total en 0
            if (is_array($data) || is_object($data)) {
                $totalAcumulado=0;
                foreach ($data as $item) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>N° Movimiento</th>';
                    echo '<th>Fecha</th>';
                    echo '<th>Concepto</th>';
                    echo '<th>Notas</th>';
                    echo '<th>Monto Pagado</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
    
                    echo '<tr>';
                    echo "<td style='text-align: center;'>{$item->numMovPadre}</td>";
                    echo "<td style='text-align: center;'>{$item->fechaIngreso}</td>";
                    echo "<td style='text-align: center;'>{$item->concepto}</td>";
                    echo "<td style='text-align: center;'>{$item->notas}</td>";
                    echo "<td style='text-align: center;'>{$item->total}</td>";
    
                    echo '</tr>';
    
                    // Acumular el monto pagado para calcular el total
                    $total += floatval($item->total);
    
                    $total = number_format($total, 2);
                   
    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</section>';
                    echo '<br>';
    
                    if ($item->idMovPago != null) {
                        $movCon = Movimiento::where('id', $item->idMovPago)->first();
    
                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Efectivo</th>';
                        echo '<th>Yape</th>';
                        echo '<th>Tarjeta</th>';
                        echo '<th>Plin</th>';
                        echo '<th>Deposito</th>';
                        echo '<th>Total</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        echo '<tr>';
                        echo "<td style='text-align: center;'>{$movCon->efectivo}</td>";
                        echo "<td style='text-align: center;'>{$movCon->yape}</td>";
                        echo "<td style='text-align: center;'>{$movCon->tarjeta}</td>";
                        echo "<td style='text-align: center;'>{$movCon->plin}</td>";
                        echo "<td style='text-align: center;'>{$movCon->deposito}</td>";
    
                        $total = floatval($movCon->efectivo) + floatval($movCon->yape) + floatval($movCon->tarjeta) + floatval($movCon->plin) + floatval($movCon->deposito) + floatval($movCon->vuelto);
                        $total = number_format($total, 2);
                        $totalAcumulado+=$total;
                        echo "<td style='text-align: center;'>{$total}</td>";
    
                        echo '</tr>';
                        echo '</tbody>';
                        echo '</table>';
                        echo '<br>';
                        echo '<br>';
                        echo '<br>';
                    }
    
                    // Calcular el Total
                }
                $total = number_format($totalAcumulado, 2);
                echo '<table>';
    
                echo '<tr>';
                echo '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>';
                echo "<td style='text-align: center;'><strong>{$total}</strong></td>";
                echo '</tr>';
                echo '</table>';
            } else {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Efectivo</th>';
                echo '<th>Yape</th>';
                echo '<th>Tarjeta</th>';
                echo '<th>Plin</th>';
                echo '<th>Deposito</th>';
                echo '<th>Vuelto</th>';
                echo '<th>Total</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                echo '<tr>';
                echo "<td colspan='7' style='text-align: center;'>$data</td>";
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
            }
        }
    }
    ?>



    <div style="page-break-before: always;">
        <hr>
        <p style="color:Red">RESUMEN DE VENTAS Por Métodos de pago</p>
        <hr>

        <section class="text-left">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center;">Método de Pago</th>
                        <th style="text-align: center;">Apertura</th>
                        <th style="text-align: center;">Mov Ventas</th>
                        <th style="text-align: center;">Mov Otros Ingresos</th>
                        <th style="text-align: center;">Mov Otros Egresos</th>
                        <th style="text-align: center;">Total General</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalApertura = 0;
                    $totalVentas = 0;
                    $totalOtrosIngresos = 0;
                    $totalOtrosEgresos = 0;
                    
                    foreach ($resumenCuadreCaja as $item) {
                        echo '<tr>';
                        echo '<td style="text-align: center;">' . $item->PaymentMethod . '</td>';
                        echo '<td style="text-align: center;">' . number_format($item->Apertura, 2) . '</td>';
                        echo '<td style="text-align: center;">' . number_format($item->Ventas, 2) . '</td>';
                        echo '<td style="text-align: center;">' . number_format($item->OtrosIngresos, 2) . '</td>';
                        // Mostrar Egresos en negativo y en rojo
                        echo '<td style="text-align: center; color: red;">' . number_format(-$item->OtrosEgresos, 2) . '</td>';
                        echo '<td style="text-align: center; font-weight: bold;">' . number_format($item->Apertura + $item->Ventas + $item->OtrosIngresos - $item->OtrosEgresos, 2) . '</td>';
                        echo '</tr>';
                    
                        // Sumar los totales
                        $totalApertura += $item->Apertura;
                        $totalVentas += $item->Ventas;
                        $totalOtrosIngresos += $item->OtrosIngresos;
                        $totalOtrosEgresos += $item->OtrosEgresos;
                    }
                    
                    // Total general por columnas
                    $totalGeneral = $totalApertura + $totalVentas + $totalOtrosIngresos - $totalOtrosEgresos;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: center; font-weight: bold;">Total</td>
                        <td style="text-align: center; font-weight: bold;"><?php echo number_format($totalApertura, 2); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo number_format($totalVentas, 2); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo number_format($totalOtrosIngresos, 2); ?></td>
                        <td style="text-align: center; color: red; font-weight: bold;"><?php echo number_format(-$totalOtrosEgresos, 2); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo number_format($totalGeneral, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </section>
<br>
        <section class="tiposPagos  mb-3">
            <hr>
            <p style="color:Red"> RESUMEN POR TIPO DE PAGO</p>
            <hr>
           
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
            <hr>
            <p style="color:Red">RESUMEN POR CONCEPTO DE PAGO</p>
            <hr>
            
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








    </div>

    <div class="footer">
        Copyright© 2023. Garzasoft. Todos los derechos reservados
    </div>
</body>

</html>
