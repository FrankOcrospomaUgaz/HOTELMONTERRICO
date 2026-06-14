<?php

namespace App\Http\Controllers;

use App\Exports\KardexExport;
use App\Models\Producto;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class KardexController extends Controller
{
    public const idVista = 169;

    public function __construct()
    {
        $this->middleware('auth');
        $permiso = Permission::find(self::idVista);

        $this->middleware('permission:' . $permiso->name, ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $this->MenuDinamico(Auth::user()->id);

        return view('Modulos.Kardex.index');

    }

    public function export($idProducto, $fechaI, $fechaT)
    {
        $exportData = [];
        $productos = Producto::where('estado', 1)->get();
        if ($idProducto == 'todos') {

            foreach ($productos as $prod) {
                // -----------------------------------------------------------------------------------------------------
                $idProducto = $prod->id;
                $query = "CALL movCalculoPrecioAntesKardex($idProducto, '$fechaI')";
                $resultado = DB::select(DB::raw($query));
                $kardexData = [];
                $contadorIncial = 0;
                $precioSaldoInicial = 0;
                $montoAcumuladoSubtotales = 0;

                foreach ($resultado as $result) {

                    if ($result->Tipo == 'Ingreso') {
                        $montoAcumuladoSubtotales += number_format($result->SubTotal, 2);
                        $contadorIncial += $result->Cantidad;
                        $precioSaldoInicial = $montoAcumuladoSubtotales / $contadorIncial;
                    } else if ($result->Tipo == 'Egreso') {
                        $montoAcumuladoSubtotales -= number_format($precioSaldoInicial, 2) * $result->Cantidad;
                        $contadorIncial -= $result->Cantidad;
                    } else {
                        // Maneja otros casos si es necesario
                    }

                }

                // ------------------------------------------------------------------------------------------------------

                $productoName = Producto::find($idProducto)->nombre;
                $productoId = $idProducto;

                $fechaInicio = $fechaI;

                $resultado = DB::selectOne('SELECT SaldoInicialStock(?, ?) AS saldo', [$idProducto, $fechaInicio]);

                $saldoInicial = isset($resultado->saldo) ? $resultado->saldo : '0.00';
                $precioInicial = $saldoInicial == '0.00' ? '0.00' : $precioSaldoInicial;
                $subtotalInicial = $saldoInicial == '0.00' ? '0.00' : $precioInicial * $saldoInicial;
                $montoAcumuladoSubtotales = $subtotalInicial;
                $headers = [
                    ['REPORTE KARDEX'],
                    ['PRODUCTO: ' . $productoName],
                    [''],
                    [], // Dejar una fila en blanco como separador
                    ['FECHA', 'NUMERO', 'ENTRADAS', null, null, 'SALIDAS', null, null, 'SALDO'],
                    [null, null, 'Cant', 'Precio', 'Sub Total', 'Cant', 'Precio', 'Sub Total', 'Cant', 'Precio', 'Sub Total'],
                    [null, 'Saldo Inicial', null, null, null, null, null, null, $saldoInicial, $precioInicial, $subtotalInicial],
                ];

                $query = "CALL ReporteKardexProducto($idProducto, '$fechaI', '$fechaT')";
                $resultado = DB::select(DB::raw($query));
                $kardexData = [];
                $contador = $saldoInicial;
                $precioSaldo = $precioInicial;

                $precioSaldoAnterior = 0; // Inicializa la variable para el precioSaldo anterior

                foreach ($resultado as $result) {

                    if ($result->Tipo == 'Ingreso') {
                        $montoAcumuladoSubtotales += number_format($result->Cantidad * number_format($result->Precio, 2), 2);

                        $contador += $result->Cantidad;
                        $precioSaldo = $montoAcumuladoSubtotales / $contador;
                        $precioFormateado = number_format($result->Precio, 2);
                        $subtotalFormateado = number_format($result->SubTotal, 2);
                        $kardexData[] = [
                            $result->Fecha,
                            $result->Numero,
                            $result->Cantidad,
                            $precioFormateado,
                            $subtotalFormateado,
                            null,
                            null,
                            null,
                            ($contador == 0 ? '0' : $contador),
                            ($contador == 0 ? 0 : number_format($precioSaldo, 2)), // Formatear $precioSaldo con dos decimales
                            number_format($precioSaldo * $contador, 2), // Formatear $precioSaldo * $contador con dos decimales
                        ];

                    } else if ($result->Tipo == 'Egreso') {
                        $montoAcumuladoSubtotales -= number_format($precioSaldo, 2) * $result->Cantidad;
                        $contador -= $result->Cantidad;
                        $precioFormateado = number_format($result->Precio, 2);
                        $subtotalFormateado = number_format($result->SubTotal, 2);
                        $kardexData[] = [
                            $result->Fecha,
                            $result->Numero,
                            null,
                            null,
                            null,
                            $result->Cantidad,
                            number_format($precioSaldo, 2), // Formatear $result->Precio con dos decimales
                            number_format($precioSaldo * $result->Cantidad, 2), // Formatear $result->SubTotal con dos decimales
                            ($contador == 0 ? '0' : $contador),
                            ($contador == 0 ? 0 : number_format($precioSaldo, 2)), // Formatear $precioSaldo con dos decimales
                            number_format($precioSaldo * $contador, 2), // Formatear $precioSaldo * $contador con dos decimales
                        ];
                    } else {
                        // Maneja otros casos si es necesario
                    }

                }

                $kardexData[] = [''];
                $kardexData[] = [''];
                $exportData = array_merge($exportData, $headers, $kardexData);

            }
        } else {
            $productoActivo = $this->obtenerProductoActivo((int) $idProducto);

            if (!$productoActivo) {
                abort(404, 'El producto está deshabilitado o no existe.');
            }

            // -----------------------------------------------------------------------------------------------------

            $query = "CALL movCalculoPrecioAntesKardex($idProducto, '$fechaI')";
            $resultado = DB::select(DB::raw($query));
            $kardexData = [];
            $contadorIncial = 0;
            $precioSaldoInicial = 0;
            $montoAcumuladoSubtotales = 0;

            foreach ($resultado as $result) {

                if ($result->Tipo == 'Ingreso') {
                    $montoAcumuladoSubtotales += number_format($result->SubTotal, 2);
                    $contadorIncial += $result->Cantidad;
                    $precioSaldoInicial = $montoAcumuladoSubtotales / $contadorIncial;
                } else if ($result->Tipo == 'Egreso') {
                    $contadorIncial -= $result->Cantidad;
                } else {
                    // Maneja otros casos si es necesario
                }

            }

// ------------------------------------------------------------------------------------------------------

            $productoName = Producto::find($idProducto)->nombre;
            $productoId = $idProducto;

            $fechaInicio = $fechaI;

            $resultado = DB::selectOne('SELECT SaldoInicialStock(?, ?) AS saldo', [$idProducto, $fechaInicio]);

            $saldoInicial = isset($resultado->saldo) ? $resultado->saldo : '0.00';
            $precioInicial = $saldoInicial == '0.00' ? '0.00' : $precioSaldoInicial;
            $subtotalInicial = $saldoInicial == '0.00' ? '0.00' : $precioInicial * $saldoInicial;
            $montoAcumuladoSubtotales = $subtotalInicial;
            $headers = [
                ['REPORTE KARDEX'],
                ['PRODUCTO: ' . $productoName],
                [''],
                [], // Dejar una fila en blanco como separador
                ['FECHA', 'NUMERO', 'ENTRADAS', null, null, 'SALIDAS', null, null, 'SALDO'],
                [null, null, 'Cant', 'Precio', 'Sub Total', 'Cant', 'Precio', 'Sub Total', 'Cant', 'Precio', 'Sub Total'],
                [null, 'Saldo Inicial', null, null, null, null, null, null, $saldoInicial, $precioInicial, $subtotalInicial],
            ];

            $query = "CALL ReporteKardexProducto($idProducto, '$fechaI', '$fechaT')";
            $resultado = DB::select(DB::raw($query));
            $kardexData = [];
            $contador = $saldoInicial;
            $precioSaldo = $precioInicial;

            $precioSaldoAnterior = 0; // Inicializa la variable para el precioSaldo anterior

            foreach ($resultado as $result) {

                if ($result->Tipo == 'Ingreso') {
                    $montoAcumuladoSubtotales += number_format($result->Cantidad * number_format($result->Precio, 2), 2);

                    $contador += $result->Cantidad;
                    $precioSaldo = $montoAcumuladoSubtotales / $contador;
                    $precioFormateado = number_format($result->Precio, 2);
                    $subtotalFormateado = number_format($result->SubTotal, 2);
                    $kardexData[] = [
                        $result->Fecha,
                        $result->Numero,
                        $result->Cantidad,
                        $precioFormateado,
                        $subtotalFormateado,
                        null,
                        null,
                        null,
                        ($contador == 0 ? '0' : $contador),
                        number_format($precioSaldo, 2), // Formatear $precioSaldo con dos decimales
                        number_format($precioSaldo * $contador, 2), // Formatear $precioSaldo * $contador con dos decimales
                    ];

                } else if ($result->Tipo == 'Egreso') {
                    $montoAcumuladoSubtotales -= number_format($precioSaldo, 2) * $result->Cantidad;
                    $contador -= $result->Cantidad;
                    $precioFormateado = number_format($result->Precio, 2);
                    $subtotalFormateado = number_format($result->SubTotal, 2);
                    $kardexData[] = [
                        $result->Fecha,
                        $result->Numero,
                        null,
                        null,
                        null,
                        $result->Cantidad,
                        number_format($precioSaldo, 2), // Formatear $result->Precio con dos decimales
                        number_format($precioSaldo * $result->Cantidad, 2), // Formatear $result->SubTotal con dos decimales
                        ($contador == 0 ? '0' : $contador),
                        number_format($precioSaldo, 2), // Formatear $precioSaldo con dos decimales
                        number_format($precioSaldo * $contador, 2), // Formatear $precioSaldo * $contador con dos decimales
                    ];
                } else {
                    // Maneja otros casos si es necesario
                }

            }

            // Luego de este bucle, $kardexData contendrá todas las filas de datos que has obtenido de la base de datos.

            // Combina los encabezados con los datos del kardex
            $exportData = array_merge($headers, $kardexData);

        }
        return Excel::download(new KardexExport($exportData, "REPORTE KARDEX DEL $fechaI AL $fechaT"), 'kardex.xlsx');

    }
}
