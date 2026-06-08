<?php

namespace App\Http\Controllers;

use App\Models\Detallemovimiento;
use App\Models\Habitacion;
use App\Models\Movimiento;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ventaHabitacionController extends Controller
{
    public const idVista = 121;

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

        $numFil = '';
        if ($request->input('id')) {
            $numFil = $request->input('id');
        }

        return view('Modulos.AgregarVenta.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar', 'numFil'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //CREA MOVIMIENTO DE ATENCION AL CLIENTE

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

        $habitacion = Habitacion::where('numero', $request->input('numHabitacion'))->first();

        $result = DB::select('call movAtencionxNumHabitacion(?)', [$request->input('numHabitacion')]);

        if (isset($result[0])) {
            // NO SE CREA MOVIMIENTO PORQUE LA HABITACIÓN TIENE UN MOVIMIENTO DE ATENCION ACTIVO
        } else {
            $idHabitacion = $habitacion->id;
            $movimiento = Movimiento::create([
                'fechaingreso' => Carbon::now()->format('Y-m-d H:i:s'),
                'persona_id' => $request->input('clientes'),
                'usuario_id' => Auth::user()->id,
                'habitacion_id' => $idHabitacion,
            ]);

            //SE CREA EL SERVICIO POR DEFAULT

            $servicio = Servicio::find($habitacion->tipo == 'VIP' ? 4 : ($habitacion->tipo == 'Normal' ? 1 : 21));

            $detalleMovimiento = Detallemovimiento::create([
                'movimiento_id' => $movimiento->id,
                'cantidad' => '1',
                'precioventa' => $servicio->precioventa,
                'servicio_id' => $servicio->id,
                'comentario' => 'Default',
            ]);

            $query = "SELECT calcularTotalDetalleMovimiento($movimiento->id) AS total";
            $resultado = DB::select(DB::raw($query));
            $movimiento->total = $resultado[0]->total;


            $habitacion->horaInicio = $detalleMovimiento->created_at;
            $habitacion->situacion = "Ocupada";
            $habitacion->idUltimoMovimiento = $movimiento->id;

            $habitacion->total = $movimiento->total;

            $habitacion->save();
            $movimiento->numero = "M003-" . str_pad($movimiento->id, 8, "0", STR_PAD_LEFT);
            $movimiento->save();
        }

        return response()->json($movimiento);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientes = DB::select('call showPersonasClientes');
        return response()->json($clientes);
    }

    public function showProveedores()
    {
        $proveedores = DB::select('call showProveedores');
     
        return response()->json($proveedores);
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

        $habitacionActual = Habitacion::where('numero', $request->input('numHabActual'))->first();
        $habitacionNueva = Habitacion::where('numero', $request->input('numHabNueva'))->first();

        $movimiento = Movimiento::findOrFail($request->input('idMovimiento'));

        $movimiento->habitacion_id = $habitacionNueva->id;
        $detalleServicioDefault = Detallemovimiento::where('movimiento_id', $movimiento->id)
            ->orderBy('id') // Ordenar por la columna 'id' en orden ascendente
            ->first();

            if ($habitacionNueva->tipo == "Normal") {

                $servici = Servicio::find(1);
                $detalleServicioDefault->servicio_id = 1;
                $detalleServicioDefault->precioventa = $servici->precioventa;
                $detalleServicioDefault->save();
            } else if ($habitacionNueva->tipo == "VIP") {
    
                $servici = Servicio::find(4);
                $detalleServicioDefault->servicio_id = 4;
                $detalleServicioDefault->precioventa = $servici->precioventa;
                $detalleServicioDefault->save();
            } else{
                $servici = Servicio::find(21);
                $detalleServicioDefault->servicio_id = 21;
                $detalleServicioDefault->precioventa = $servici->precioventa;
                $detalleServicioDefault->save();
            }
    

        $query = "SELECT calcularTotalDetalleMovimiento($movimiento->id) AS total";
        $resultado = DB::select(DB::raw($query));
        $movimiento->total = $resultado[0]->total;

        $movimiento->save();
        $habitacionActual->situacion = "Disponible";
        $habitacionNueva->horaInicio =  $habitacionActual->horaInicio;
        $habitacionNueva->total = $movimiento->total;
        $habitacionNueva->idUltimoMovimiento= $movimiento->id;

        $habitacionActual->horaInicio=null;
        $habitacionActual->total = 0.00;
        $habitacionActual->idUltimoMovimiento= null;

        $habitacionNueva->situacion = "Ocupada";

        
       


        $habitacionActual->save();
        $habitacionNueva->save();

        return response($habitacionNueva->numero);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
