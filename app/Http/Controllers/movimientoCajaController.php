<?php

namespace App\Http\Controllers;

use App\Models\conceptoPago;
use App\Models\detallegreso;
use App\Models\Habitacion;
use App\Models\Movimiento;
use App\Models\Persona;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class movimientoCajaController extends Controller
{
    public const idVista = 125;

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
    public function index(Request $request)
    {
        $this->MenuDinamico(Auth::user()->id);
        $permisosCrear = Permission::where('padreCrud', self::idVista)->get()[0];
        $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
        $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];

        if ($request->ajax()) {
            $movimientosCajaFiltrados = DB::select('call movimientosCaja');
            return datatables($movimientosCajaFiltrados)
                ->addColumn('action', function ($movCaja) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    // $permisoPadre = Movimiento::find($movCaja->movimiento_id);
                    if (Gate::allows($permisosEditar->name)) {

                        $acciones = '<div style="width:150px">';
                        if ($movCaja->situacion != 'Anulado') {

                            if ($movCaja->conceptopago_id == 4) {
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Detalle Venta"  onclick="verCarrito(' . $movCaja->id . ')" style="background:blue;margin:2px; color:white;" class="btn btn-info btn-profesional"><i class="fa-solid fa-cart-shopping"></i></a>';

                                $acciones .= '<a href="javascript:void(0)"  data-tooltip="Anular" onclick="anularMovCaj(' . $movCaja->id . ')" style="background:red;margin:2px; color:white;" class="btn btn-info btn-profesional"> <i class="fa-solid fa-ban"></i></a>';
                            } else if ($movCaja->conceptopago_id == 5) {
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Editar" onclick="editarMovCaja(' . $movCaja->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info btn-profesional"> <i class="fas fa-edit"></i></a>';
                            } else {
                                $acciones .= '<a href="javascript:void(0)"  data-tooltip="Editar" onclick="editarMovCajaIngresoEgreso(' . $movCaja->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info btn-profesional"> <i class="fas fa-edit"></i></a>';
                                $acciones .= '<a href="javascript:void(0)"  data-tooltip="Anular" onclick="anularMovCaj(' . $movCaja->id . ')" style="background:red;margin:2px; color:white;" class="btn btn-info btn-profesional"> <i class="fa-solid fa-ban"></i></a>';
                            }
                        } else {
                            if ($movCaja->conceptopago_id == 4) {
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Detalle Venta"  onclick="verCarrito(' . $movCaja->id . ')" style="background:blue;margin:2px; color:white;" class="btn btn-info btn-profesional"><i class="fa-solid fa-cart-shopping"></i></a>';

                                $acciones .= '<a href="javascript:void(0)"
                                    data-tooltip="Revertir" onclick="revertirMovPagoCliente(' . $movCaja->id . ')"
                                    style="background:#ff8300ba;margin:2px;
                                    color:white;"
                                    class="btn btn-info btn-profesional">
                                    <i class="fa-solid fa-clock-rotate-left"></i></a>
                                    ';
                            } else {
                                if ($movCaja->conceptopago_id != 5) {
                                    $acciones .= '<a href="javascript:void(0)"
                                    data-tooltip="Revertir" onclick="revertirMovCaja(' . $movCaja->id . ')"
                                    style="background:#ff8300ba;margin:2px;
                                    color:white;"
                                    class="btn btn-info btn-profesional">
                                    <i class="fa-solid fa-clock-rotate-left"></i></a>
                                    ';
                                }
                            }
                        }
                        $acciones .= '</div>';
                    }

                    return $acciones;
                })->addColumn('estado', function ($movCaja) {
                $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                if (Gate::allows($permisosEliminar->name)) {
                    $estadoR = '<div class="custom-control custom-switch">' .
                    '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                    $movCaja->id .
                    '" data-id="' .
                    $movCaja->id .
                    '" ' .
                    ($movCaja->estado == 1 ? "checked" : "") .
                    ">" .
                    '<label class="custom-control-label form-check-label" for="switch-' .
                    $movCaja->id .
                        '"></label>' .
                        "</div>";
                } else {
                    $estadoR = '';
                }

                return $estadoR;
            })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }

        return view('Modulos.CajaChica.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    public function recuperarMmovimiento($id)
    {
        $movimientosCajaFiltrados = DB::select('call movimientosCaja');

        $movimientoCaja = null;
        foreach ($movimientosCajaFiltrados as $registro) {
            if ($registro->id == $id) {
                $movimientoCaja = $registro;
                break;
            }
        }

        return response()->json($movimientoCaja);
    }

    public function revertirAnulacion($id)
    {
        $MovimientoXrevertir = Movimiento::withTrashed()->where('id', $id)->first();
        $MovimientoXrevertir->situacion = "Normal";
        $MovimientoXrevertir->deleted_at = null; // Establece deleted_at como null para activar el registro
        $MovimientoXrevertir->save();

        return response('con Exito');
    }

    public function revertirAnulacionMovAtencion($id)
    {
        $MovimientoAnulado = Movimiento::withTrashed()->where('id', $id)->first();
        $MovimientoAtencion = Movimiento::where('id', $MovimientoAnulado->movimiento_id)->first();
        $MovimientoAtencion->situacion = "Normal";
        $MovimientoAtencion->estado = 1;
        $MovimientoAtencion->save();
        $Habitacion = Habitacion::find($MovimientoAtencion->habitacion_id);
        $Habitacion->situacion = "Ocupada";
        $Habitacion->total = $MovimientoAtencion->total;
        $Habitacion->horaInicio =  $MovimientoAtencion->fechaingreso;
        $Habitacion->idUltimoMovimiento =  $MovimientoAtencion->id;
        $Habitacion->save();
        return response($Habitacion->numero);
    }

    public function revertirAnulacionMovAtencionOtraHab($id, $idHab)
    {
        $MovimientoAnulado = Movimiento::withTrashed()->where('id', $id)->first();
        $MovimientoAtencion = Movimiento::where('id', $MovimientoAnulado->movimiento_id)->first();
        $MovimientoAtencion->situacion = "Normal";
        $MovimientoAtencion->estado = 1;
        $MovimientoAtencion->habitacion_id = $idHab;
        $MovimientoAtencion->save();
        $Habitacion = Habitacion::find($idHab);
        $Habitacion->situacion = "Ocupada";
        $Habitacion->total = $MovimientoAtencion->total;
        $Habitacion->horaInicio =  $MovimientoAtencion->fechaingreso;
        $Habitacion->idUltimoMovimiento =  $MovimientoAtencion->id;
        $Habitacion->save();
        return response($Habitacion->numero);
    }

    public function situacionHabXreversion($id)
    {
        $situacion = DB::select('call situacionHabXidMovAtencion(?)', [$id]);

        $datosUsuarios = [
            'situacionHab' => $situacion[0]->situacion,
            'numHab' => $situacion[0]->numero,
            'estadoMovPadre' => $situacion[0]->estadoMovPadre,
        ];

        return response()->json($datosUsuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $yape = 0;
        $deposito = 0;
        $tarjeta = 0;
        $efectivo = 0;
        $plin = 0;
        $turno = null;

        if ($request->input('conceptoPago') == '5') {
            $turno = $request->input('turno');
        }

        $movimiento = Movimiento::create([
            'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
            'persona_id' => $request->input('personas'),
            'usuario_id' => Auth::user()->id,
            'total' => $request->input('total'),
            'tipodocumento_id' => $request->input('tipoMovimientoCaja') == 'Ingreso' ? '6' : '7', //INGRESO o EGRESO
            'conceptopago_id' => $request->input('conceptoPago'),
            'comentario' => $request->input('comentario'),
            'turno' => $turno,
        ]);
        $registros = json_decode($request->input('registroPagos'), true);

        foreach ($registros as $registro) {
            switch ($registro['tipo']) {
                case 'Efectivo':
                    $efectivo += $registro['monto'];
                    break;
                case 'Yape':
                    $yape += $registro['monto'];
                    break;
                case 'Tarjeta':
                    $tarjeta += $registro['monto'];
                    break;
                case 'Deposito':
                    $deposito += $registro['monto'];
                    break;
                case 'Plin':
                    $plin += $registro['monto'];
                    break;
            }
        }

        $movimiento->yape = $yape;
        $movimiento->efectivo = $efectivo;
        $movimiento->tarjeta = $tarjeta;
        $movimiento->deposito = $deposito;
        $movimiento->plin = $plin;

        $movimiento->numero = "M003-" . str_pad($movimiento->id, 8, "0", STR_PAD_LEFT);
        $movimiento->save();

        return response('con Exito');
    }
    public function storeIngresoEgreso(Request $request)
    {

        $yape = 0;
        $deposito = 0;
        $tarjeta = 0;
        $efectivo = 0;
        $plin = 0;

        $movimiento = Movimiento::create([
            'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
            'persona_id' => $request->input('personasEgr'),
            'usuario_id' => Auth::user()->id,
            'total' => $request->input('totalEgr'),
            'tipodocumento_id' => $request->input('tipoMovimientoCajaEgr') == 'Ingreso' ? '6' : '7', //INGRESO o EGRESO
            'conceptopago_id' => $request->input('conceptoPagoEgr'),

        ]);
        $registros = json_decode($request->input('registroPagos'), true);

        foreach ($registros as $registro) {
            switch ($registro['tipo']) {
                case 'Efectivo':
                    $efectivo += $registro['monto'];
                    break;
                case 'Yape':
                    $yape += $registro['monto'];
                    break;
                case 'Tarjeta':
                    $tarjeta += $registro['monto'];
                    break;
                case 'Deposito':
                    $deposito += $registro['monto'];
                    break;
                case 'Plin':
                    $plin += $registro['monto'];
                    break;
            }
        }

        $movimiento->yape = $yape;
        $movimiento->efectivo = $efectivo;
        $movimiento->tarjeta = $tarjeta;
        $movimiento->deposito = $deposito;
        $movimiento->plin = $plin;

        $movimiento->numero = "M003-" . str_pad($movimiento->id, 8, "0", STR_PAD_LEFT);
        $movimiento->save();
        $total = 0;
        foreach ($registros as $registro) {
            $DetalleMovimientoEgreso = detallegreso::create([
                'nota' => $registro['nota'],
                'tipo' => $registro['tipo'],
                'monto' => $registro['monto'],
                'movimiento_id' => $movimiento->id,
            ]);
            $total += $registro['monto'];
        }

        $movimiento->total = $total;
        $movimiento->save();

        return response('con Exito');
    }
    public function cierre(Request $request)
    {
        $movApertura = Movimiento::where('situacion', 'Normal')->where('conceptopago_id', '5')->first();

        $movimiento = Movimiento::create([
            'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
            'persona_id' => $request->input('personasCierr'),
            'usuario_id' => Auth::user()->id,
            'total' => $request->input('totalCierr'),
            'tipodocumento_id' => '7', //EGRESO
            'movimiento_id' => $movApertura->id,
            'conceptopago_id' => $request->input('conceptoPagoCierr'), //CIERRE CAJA
            'comentario' => $request->input('comentario'),
        ]);

        $registros = json_decode($request->input('registroPagos'), true);

        $movimiento->total = $request->input('montoCaja');
        $movimiento->yape = $request->input('montoYape');
        $movimiento->efectivo = $request->input('montoEfectivo');
        $movimiento->tarjeta = $request->input('montoTarjeta');
        $movimiento->deposito = $request->input('montoDeposito');
        $movimiento->plin = $request->input('montoPlin');

        $movimiento->numero = "M003-" . str_pad($movimiento->id, 8, "0", STR_PAD_LEFT);

        $movimiento->save();

        $movApertura->situacion = "Cierre";
        $movApertura->save();
        return response('con Exito');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $movimientosCajaFiltrados = DB::select('call detalleCierre');
            return datatables($movimientosCajaFiltrados)
                ->make(true);
        } else {
        }
    }

    public function showDetallesEgresos(Request $request, $id)
    {

        return detallegreso::where("movimiento_id", $id)->get();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function buscarApertura()
    {
        $movimiento = Movimiento::where('situacion', 'Normal')->where('conceptopago_id', '5')->first();

        $resultado = $movimiento ? '1' : 'null';

        return response($resultado);
    }

    public function editApertura($tipo)
    {
        $Responsable = User::find(Auth::user()->id);
        $persona = Persona::find($Responsable->persona_id);
        switch ($tipo) {
            case 'Ingreso':
                $conceptopago = ConceptoPago::where('tipo', 'Ingreso')->whereNotIn('id', [5, 4])->get();
                break;
            case 'Egreso':
                $conceptopago = ConceptoPago::where('tipo', 'Egreso')->where('id', '!=', '6')->get();
                break;
            case 'Apertura':
                $conceptopago = ConceptoPago::find('5');
                break;
            case 'Cierre':
                $conceptopago = ConceptoPago::find('6');
                break;
            default:
                $conceptopago = null;
                break;
        }

        $fechaActual = Carbon::now()->format('Y-m-d');
        $personas = Persona::where('estado', 1)->get();

        $totalIngresos = 0.00;
        $TotalEgresos = 0.00;
        $TotalCaja = 0.00;
        $efectivoCaja = 0.00;
        $yapeCaja = 0.00;
        $tarjetaCaja = 0.00;
        $plinCaja = 0.00;
        $depositoCaja = 0.00;
        $TotaPagosCliente = 0.00;
        $montoApertura = 0.00;
        $TotaefectivoVenta = 0.00;
        $TotaefectivoApertura = 0.00;

        $montoCaja = DB::select('call detalleCierre');
        $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompra(4) AS 'resultado'");

        $TotaPagosCliente = (float) $resultadoVenta[0]->resultado;

        $efectivoVenta = DB::select("SELECT calcularSumaEfectivo(4) AS 'resultado'");

        $TotaefectivoVenta = (float) $efectivoVenta[0]->resultado;

        $efectivoApertura = DB::select("SELECT calcularSumaEfectivo(5) AS 'resultado'");

        $TotaefectivoApertura = (float) $efectivoApertura[0]->resultado;

        $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompra(14) AS 'resultado'");

        $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;

        $movApertura = Movimiento::where('situacion', 'Normal')->where('conceptopago_id', '5')->first();

        $montoApertura = $movApertura ? (float) $movApertura->total : 0;

        if (count($montoCaja) > 0) {

            $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
            $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;

            if ($ingreso != null) {
                $totalIngresos = $ingreso->totalAcum;
                $TotalCaja = $ingreso->totalAcum;
                $yapeCaja = $ingreso->yapeAcum;
                $tarjetaCaja = $ingreso->tarjetaAcum;
                $depositoCaja = $ingreso->depositoAcum;
                $efectivoCaja = $ingreso->efectivoAcum;
                $plinCaja = $ingreso->plinAcum;
            }
            if ($egreso != null) {
                $TotalEgresos = $egreso->totalAcum;
                $TotalCaja = $egreso->totalAcum;
                $yapeCaja = $egreso->yapeAcum;
                $tarjetaCaja = $egreso->tarjetaAcum;
                $depositoCaja = $egreso->depositoAcum;
                $efectivoCaja = $egreso->efectivoAcum;
                $plinCaja = $egreso->plinAcum;
            }

            if ($egreso != null && $ingreso != null) {
                $TotalCaja = $ingreso->totalAcum - $egreso->totalAcum;
                $yapeCaja = $ingreso->yapeAcum - $egreso->yapeAcum;
                $tarjetaCaja = $ingreso->tarjetaAcum - $egreso->tarjetaAcum;
                $depositoCaja = $ingreso->depositoAcum - $egreso->depositoAcum;
                $efectivoCaja = $ingreso->efectivoAcum - $egreso->efectivoAcum;
                $plinCaja = $ingreso->plinAcum - $egreso->plinAcum;
            }
        }
        $datosUsuarios = [
            'idMovApertura' => $movApertura ? $movApertura->id : '',
            'responsable' => $persona,
            'conceptopago' => $conceptopago,
            'fechaActual' => $fechaActual,
            'personas' => $personas,
            'totalIngresos' => round((float) ($totalIngresos - $montoApertura), 2),
            'totalOtrosIngresos' => $totalIngresos - $montoApertura,
            'TotalPagosCliente' => $TotaPagosCliente,
            'TotalEgresos' => number_format((float) $TotalEgresos, 2, '.', ''),
            'TotaCompras' => number_format((float) $TotaCompras, 2, '.', ''),
            'TotalCaja' => number_format((float) $TotalCaja, 2, '.', ''),

            'efectivoCaja' => number_format($efectivoCaja, 2, '.', ''),
            'yapeCaja' => number_format((float) $yapeCaja, 2, '.', ''),
            'tarjetaCaja' => number_format((float) $tarjetaCaja, 2, '.', ''),
            'plinCaja' => number_format((float) $plinCaja, 2, '.', ''),
            'depositoCaja' => number_format((float) $depositoCaja, 2, '.', ''),

            'montoApertura' => $montoApertura,

            'TotaefectivoApertura' => $TotaefectivoApertura,
            'TotaefectivoVenta' => $TotaefectivoVenta,

        ];

        return response()->json($datosUsuarios);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $yape = 0;
        $deposito = 0;
        $tarjeta = 0;
        $efectivo = 0;
        $plin = 0;

        $movimiento = Movimiento::find($id);
        $registros = json_decode($request->input('registroPagos'), true);

        foreach ($registros as $registro) {
            switch ($registro['tipo']) {
                case 'Efectivo':
                    $efectivo += $registro['monto'];
                    break;
                case 'Yape':
                    $yape += $registro['monto'];
                    break;
                case 'Tarjeta':
                    $tarjeta += $registro['monto'];
                    break;
                case 'Deposito':
                    $deposito += $registro['monto'];
                    break;
                case 'Plin':
                    $plin += $registro['monto'];
                    break;
            }
        }

        $movimiento->yape = $yape;
        $movimiento->efectivo = $efectivo;
        $movimiento->tarjeta = $tarjeta;
        $movimiento->deposito = $deposito;
        $movimiento->plin = $plin;

        $movimiento->total = $request->input('totalE');
        $movimiento->comentario = $request->input('comentarioE');

        $movimiento->numero = "M003-" . str_pad($movimiento->id, 8, "0", STR_PAD_LEFT);
        $movimiento->save();
        return response('con Exito');
    }

    public function agregarNuevaEntrada(Request $request)
    {

        $idMovimiento = $request->input('idMovimientoEgrE');
        $monto = $request->input('montoEgrE');
        $nota = empty($request->input('notaEgrE')) ? '-' : $request->input('notaEgrE');

        // Ahora $nota tendrá un guion si estaba vacía

        $tipo = $request->input('mediosPagoEgrE');

        $detalleEgreso = Detallegreso::create([
            'nota' => $nota,
            'tipo' => $tipo, // Ajusta el valor del tipo según tus necesidades
            'monto' => $monto,
            'movimiento_id' => $idMovimiento,
        ]);

        $movimientoPadre = Movimiento::where('id', $idMovimiento)->first();

        $movimientoPadre->total = $this->montoTotalMovEgreso($request, $movimientoPadre->id);

        $movimientoPadre->save();
        // Puedes devolver una respuesta, por ejemplo, un mensaje de éxito
        $datosUsuarios = [
            'monto' => number_format($movimientoPadre->total, 2, '.', ''), // Formatea el monto con dos decimales
            'idNuevoMovimiento' => $detalleEgreso->id,
        ];

        return response()->json($datosUsuarios);
    }

    public function eliminarDetalleMovimientoSalida(Request $request)
    {

        $idMovimiento = $request->input('idMovimientoEgrE');

        $movimientoDetalle = detallegreso::where('id', $idMovimiento)->first();
        $idMovimientoPadre = $movimientoDetalle->movimiento_id;
        $movimientoDetalle->estado=0;
        $movimientoDetalle->save();

        $movimientoDetalle->delete();

        $movimientoPadre = Movimiento::where('id', $idMovimientoPadre)->first();

        $movimientoPadre->total = $this->montoTotalMovEgreso($request, $idMovimientoPadre);
        $movimientoPadre->save();

        // Puedes devolver una respuesta, por ejemplo, un mensaje de éxito
        $datosUsuarios = [
            'monto' => number_format($movimientoPadre->total, 2, '.', ''), // Formatea el monto con dos decimales

        ];

        return response()->json($datosUsuarios);
    }

    public function montoTotalMovEgreso(Request $request, $movimiento_id)
    {

        $registros = detallegreso::where('movimiento_id', $movimiento_id)
            ->whereNull('deleted_at')
            ->get();

        $total = 0;
        $yape = 0;
        $deposito = 0;
        $tarjeta = 0;
        $efectivo = 0;
        $plin = 0;

        $movimientoPadre = Movimiento::where('id', $movimiento_id)->first();

        foreach ($registros as $registro) {
            $total += $registro['monto'];
            switch ($registro->tipo) {
                case 'Efectivo':
                    $efectivo += $registro['monto'];
                    break;
                case 'Yape':
                    $yape += $registro['monto'];
                    break;
                case 'Tarjeta':
                    $tarjeta += $registro['monto'];
                    break;
                case 'Deposito':
                    $deposito += $registro['monto'];
                    break;
                case 'Plin':
                    $plin += $registro['monto'];
                    break;
            }
        }

        $movimientoPadre->yape = $yape;
        $movimientoPadre->efectivo = $efectivo;
        $movimientoPadre->tarjeta = $tarjeta;
        $movimientoPadre->deposito = $deposito;
        $movimientoPadre->plin = $plin;
        $movimientoPadre->save();

        return $total;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Movimiento = Movimiento::findOrFail($id);
        $Movimiento->situacion = 'Anulado'; //eliminado
        $Movimiento->save();
        $persona = Persona::where('id', $Movimiento->persona_id)->first();
        $tipoDoc = "";
        if ($Movimiento->movimiento_id) {
            $MovimientoPadre = Movimiento::where('id', $Movimiento->movimiento_id)->first();
            $tipoDoc = $MovimientoPadre->tipodocumento_id;
        } else {
            $MovimientoPadre = '';
            $tipoDoc = '';
        }

        $datosUsuarios = [
            'movimientoCaja' => $Movimiento,
            //--ESTA POR AGREGAR MÁS
            'dni' => $persona->dni,
            'ruc' => $persona->ruc,
            'cliente' => $persona->nombres,
            'razonsocial' => $persona->razonsocial,
            'direccion' => $persona->direccion,
            'numMov' => $Movimiento->numero,
            'tipoDocumento' => $tipoDoc,
            'MovPadre' => $MovimientoPadre,
        ];

        $Movimiento->delete();
        return response()->json($datosUsuarios);

    }
}
