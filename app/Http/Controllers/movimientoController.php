<?php

namespace App\Http\Controllers;

use App\Models\detallemovimiento;
use App\Models\Habitacion;
use App\Models\Movimiento;
use App\Models\Persona;
use App\Models\Producto;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class movimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = DB::select('call movAtencionxNumHabitacion(?)', [$id]);

        if (isset($result[0])) {
            return response()->json($result[0]);
        } else {
            return response('null');
        }
    }

    public function showId($id)
    {
        return response()->json(Movimiento::find($id));
    }

    public function showEgresos(Request $request)
    {
        if ($request->ajax()) {
            $movimientosCajaEgresos = DB::select('CALL showEgresos(?, ?)', [null, null]);

            return datatables($movimientosCajaEgresos)
                ->make(true);
        }

        return response()->json([]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    public function pagarMovimientoAtencion(Request $request, $id)
    {
        $MovimientoVenta = Movimiento::findOrFail($id);
        $totalEnviado = floatval($request->input('pagoPlin')) +
            floatval($request->input('pagoEfectivo')) +
            floatval($request->input('pagoTarjeta')) +
            floatval($request->input('pagoYape')) +
            floatval($request->input('pagoDeposito'));
        $persona = Persona::where('id', $request->input('clientes'))->first();

        if ($totalEnviado <= 0) {
            return response()->json([
                'mensaje' => 'Debe ingresar al menos un monto de pago.',
                'error'   => true,
            ], 400);
        }

        if ($MovimientoVenta->moviment_venta) {
            return response()->json([
                'mensaje' => 'Este checking ya tiene una venta asignada.',
                'error'   => true,
            ], 422);
        }

        if ($totalEnviado >= $MovimientoVenta->total) {
            $numeroDocumento = $request->input('numTipoDocumento');
            $movimientoExistente = Movimiento::where('numero', $numeroDocumento)->first();
            $persona = Persona::where('id', $request->input('clientes'))->first();

            if ($movimientoExistente == null) {
                $habitacion = Habitacion::where('id', $MovimientoVenta->habitacion_id)->first();

                $MovimientoVenta->fechasalida = Carbon::now()->format('Y-m-d H:i:s');
                $MovimientoVenta->plin = $request->input('pagoPlin');
                $MovimientoVenta->efectivo = $request->input('pagoEfectivo');
                $MovimientoVenta->tarjeta = $request->input('pagoTarjeta');
                $MovimientoVenta->yape = $request->input('pagoYape');
                $MovimientoVenta->deposito = $request->input('pagoDeposito');
                $MovimientoVenta->situacion = 'Pagado';
                $MovimientoVenta->tipodocumento_id = $request->input('tipoDocumentos');
                $MovimientoVenta->estado = '0';
                $MovimientoVenta->save();

                if ($habitacion) {
                    $habitacion->situacion = 'Limpieza';
                    $habitacion->horaInicio = null;
                    $habitacion->total = 0.00;
                    $habitacion->idUltimoMovimiento = null; // SIN MOVIMIENTO ATENCIÃ“N HASTA QUE VUELVA A CREARSE
                    $habitacion->save();
                }

                $movimientoCaja = Movimiento::create([
                    'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
                    'persona_id' => $persona->id,
                    'usuario_id' => Auth::user()->id,
                    'total' => $MovimientoVenta->total,
                    'vuelto' => $totalEnviado - $MovimientoVenta->total,
                    'tipodocumento_id' => '6',
                    'conceptopago_id' => '4',
                    'comentario' => $request->input('comentario'),
                    'movimiento_id' => $MovimientoVenta->id,
                    'yape' => $MovimientoVenta->yape,
                    'efectivo' => $MovimientoVenta->efectivo,
                    'tarjeta' => $MovimientoVenta->tarjeta,
                    'deposito' => $MovimientoVenta->deposito,
                    'plin' => $MovimientoVenta->plin,
                    'numero' => $request->input('numTipoDocumento'),
                ]);

                $movimientoCaja->save();

                $detalleVentas = DB::select('call detalleMovimientosCarrito(?)', [$movimientoCaja->movimiento_id]);

                $dniRuc = $persona->dni == null ? $persona->ruc : $persona->dni;
                $nombresRazonSocial = $persona->nombres == null ? $persona->razonsocial : $persona->nombres . ' ' . $persona->apellidopaterno . ' ' . $persona->apellidomaterno;

                $datosUsuarios = [
                    'movimientoCaja' => $movimientoCaja,
                    'dni' => $persona->dni,
                    'ruc' => $persona->ruc,
                    'cliente' => $persona->nombres,
                    'razonsocial' => $persona->razonsocial,
                    'dniRuc' => $dniRuc,
                    'nombresRazonSocial' => $nombresRazonSocial,
                    'detalleMovimientos' => $detalleVentas,
                    'direccion' => $persona->direccion,
                    "tipoDocumento" => $MovimientoVenta->tipodocumento_id,
                    'stockLiberado' => [],
                    'mensaje' => 'exito',
                ];
                return response()->json($datosUsuarios);
            } else {
                return response('error');
            }
        } else {
            return response('error');
        }
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

        $detalleMovimientos = Detallemovimiento::where('movimiento_id', $Movimiento->id)
            ->whereNotNull('producto_id')
            ->get();

        DB::transaction(function () use ($Movimiento, $detalleMovimientos) {
            foreach ($detalleMovimientos as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->stock = $producto->stock + $detalle->cantidad;
                    $producto->save();
                }
            }

            if ($Movimiento->habitacion_id) {
                $habitacion = Habitacion::where('id', $Movimiento->habitacion_id)->first();
                if ($habitacion) {
                    $this->liberarStockHabitacion($habitacion->id);
                    $habitacion->situacion = 'Disponible';
                    $habitacion->horaInicio = null;
                    $habitacion->total = 0.00;
                    $habitacion->idUltimoMovimiento = null;
                    $habitacion->save();
                }
            }

            $Movimiento->fechasalida = Carbon::now()->format('Y-m-d H:i:s');
            $Movimiento->estado = 0;
            $Movimiento->situacion = 'Eliminado';
            $Movimiento->save();

            $Movimiento->delete();
        });
    }

    public function send(string $to = '', string $subject = '', string $body = '')
    {
        $client = new Client();

        $url = "https://facturae-garzasoft.com/facturacion/enviaEmail/envia_correo_json.php";

        $fields = [
            "token" => "qusEj_w7aHEpX",
            "to" => [
                $to,
            ],
            "bcc" => [],
            "cc" => [],
            "subject" => $subject,
            "body" => $body,
            "attachments" => [],
        ];

        $response = $client->request('POST', $url, [
            'json' => $fields,
        ]);

        if ($response->getStatusCode() != 200) {
            abort(500, 'Error al enviar e-mail');
        }

        $datos = json_decode($response->getBody());
        if ($datos->code != 0) {
            abort(500, 'Error al enviar e-mail');
        }

        // -------------2
        $fieldsCorreoDos = [
            "token" => "qusEj_w7aHEpX",
            "to" => [
                'martinampuero@hotmail.com',
            ],
            "bcc" => [],
            "cc" => [],
            "subject" => $subject,
            "body" => $body,
            "attachments" => [],
        ];
        $responseDos = $client->request('POST', $url, [
            'json' => $fieldsCorreoDos,
        ]);

        if ($responseDos->getStatusCode() != 200) {
            abort(500, 'Error al enviar e-mail');
        }

        $datosDos = json_decode($responseDos->getBody());
        if ($datosDos->code != 0) {
            abort(500, 'Error al enviar e-mail');
        }

        // -------------3
        $fieldsCorreoDosTres = [
            "token" => "qusEj_w7aHEpX",
            "to" => [
                'Luceroluceroisaias6@gmail.com',
            ],
            "bcc" => [],
            "cc" => [],
            "subject" => $subject,
            "body" => $body,
            "attachments" => [],
        ];
        $responseTres = $client->request('POST', $url, [
            'json' => $fieldsCorreoDosTres,
        ]);

        if ($responseTres->getStatusCode() != 200) {
            abort(500, 'Error al enviar e-mail');
        }

        $datosDos = json_decode($responseTres->getBody());
        if ($datosDos->code != 0) {
            abort(500, 'Error al enviar e-mail');
        }

        return $datos;
    }
}
