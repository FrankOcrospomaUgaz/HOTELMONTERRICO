<!DOCTYPE html>
<html>

<head>
    <title>Movimientos de Egresos</title>
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
        .subtabla td {
    text-align: right;
    padding: 2px;
}

    </style>
</head>

<body>
<?php
use App\Models\detallegreso;
use Illuminate\Support\Facades\DB;
?>

    <div class="ticket">
        <h1>MOVIMINETOS DE SALIDA</h1>

        <hr>

        <section class="detalleCaja">
    <div class="detalleCaja-item">
        <label for="Responsable"><b>Responsable Apertura:</b></label>
        <span id="nombreResponsable"><?php echo $responsableA; ?></span>
    </div>
    <div class="detalleCaja-item">
        <label for="MontoApertura"><b>Monto Apertura:</b></label>
        <span id="MontoApertura">S/<?php echo number_format($montoApertura, 2); ?></span>
    </div>
    <div class="detalleCaja-item">
        <label for="fechaApertura"><b>Fecha Apertura:</b></label>
        <span id="nombrefechaApertura"><?php echo $fechaApertura; ?></span>
    </div>
    <div class="detalleCaja-item">
        <label for="turno"><b>Turno:</b></label>
        <span id="turno"><?php echo $turno; ?></span>
    </div>



    <div class="detalleCaja-item">
        <label for="fechaImpresion"><b>Fecha Impresión:</b></label>
        <span id="nombrefechaImpresion"><?php echo $dateTime; ?></span>
    </div>
</section>

        <hr>

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
            <td class="total data-cell"><?php echo $movimiento->total; ?></td>
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
            
            <td class="data-cell"><?php echo $detalle->tipo; ?></td><td class="data-cell"><?php echo $detalle->nota; ?></td>
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



        <!-- <div class="footer">
            Copyright© 2023. Garzasoft. Todos los derechos reservados
        </div> -->
    </div>
</body>

</html>
