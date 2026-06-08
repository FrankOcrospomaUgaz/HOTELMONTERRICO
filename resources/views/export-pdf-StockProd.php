<!DOCTYPE html>
<html>

<head>
    <title>Stock Productos</title>
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
    <div class="ticket">
        <h1>STOCK PRODUCTOS</h1>

        <hr>

        <section class="detalleCaja">


        <div class="detalleCaja-item">
        <label for="fechaImpresion"><b>Responsable Impresión:</b></label>
        <span id="nombrefechaImpresion"><?php echo $responsable; ?></span>
    </div>

    <div class="detalleCaja-item">
        <label for="fechaImpresion"><b>Fecha Impresión:</b></label>
        <span id="nombrefechaImpresion"><?php echo $dateTime; ?></span>
    </div>
</section>

       

        <section class="conceptos mb-3">
          
            <br>
            <table class="table text-center">
                <tr>
                    <th>Nombre</th>

                    <th><b>Stock</b></th>
                </tr>


                <?php if (count($Productos) > 0) {?>

<?php
foreach ($Productos as $movimiento): ?>

                <tr>
                    <td class="centroText data-cell"><?php echo $movimiento->nombre; ?></td>
                    <td class="centroText data-cell"><?php echo $movimiento->stock; ?></td>

                </tr>
                <?php

    endforeach;?>

<tr>
                    <td class="centroText data-cell"></td>
                    <td class="centroText data-cell"></td>

                </tr>

                <?php } else {?>
                <tr>
                    <td class="centroText data-cell" colspan="2">No hubieron Movimientos de Salida</td>
                </tr>

                <?php }?>

            </table>
        </section>



        <!-- <div class="footer">
            Copyright© 2023. Garzasoft. Todos los derechos reservados
        </div> -->
    </div>
</body>

</html>
