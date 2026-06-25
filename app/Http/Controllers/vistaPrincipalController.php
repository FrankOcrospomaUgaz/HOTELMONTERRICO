<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Habitacion;
use App\Models\Detallemovimiento;
use App\Models\Movimiento;
use App\Models\Servicio;

class vistaPrincipalController extends Controller
{
    public const idVista = 117;

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

        return view('Modulos.VistaPrincipal.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
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
    public function show()
    {
        $habitaciones = collect(DB::select('call shoHabitacionesYHora'));
        $habitacionesConStockCero = $this->obtenerHabitacionesConStockCero();

        $habitaciones = $habitaciones->map(function ($habitacion) use ($habitacionesConStockCero) {
            $cantidad = $habitacionesConStockCero[$habitacion->numero] ?? 0;
            $habitacion->tiene_stock_cero = $cantidad > 0;
            $habitacion->cantidad_stock_cero = $cantidad;

            return $habitacion;
        });

        return response()->json($habitaciones->values());
    }

    public function sumarHorasHab($num, $cant, $coment, $modoHoraAdicional)
    {
        $Movimiento = DB::select('call movAtencionxNumHabitacion(?)', [$num]);



        $Servicio1Hora = Servicio::where('id', $modoHoraAdicional)->first();

        $detalleMovimiento = Detallemovimiento::create([
            'movimiento_id' => $Movimiento[0]->id,
            'cantidad' => $cant,
            'descuento' => 0.00,
            'precioventa' => $Servicio1Hora->precioventa,
            'servicio_id' => $Servicio1Hora->id,
            'comentario' => $coment,
        ]);

        $query = "SELECT calcularTotalDetalleMovimiento(".$Movimiento[0]->id.") AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento = Movimiento::find($Movimiento[0]->id);
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();

        
        $habitacion = Habitacion::where('numero', $num)->first();


        $habitacion->total = $total;
        $habitacion->save();

        return response('exito');
    }

    public function situacion($id)
    {
        return response()->json(Habitacion::where('id', $id)->first());
    }

    public function situacionNumeroHab($num)
    {
        return response()->json(Habitacion::where('numero', $num)->first());
    }

    public function updateSituacion(Request $request, $id)
    {
        $habitacion = Habitacion::where('id', $id)->first();
        $habitacion->situacion = $request->input('situacionCambio');

        $habitacion->save();
        return response('con Exito');
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

    private function obtenerHabitacionesConStockCero(): array
    {
        $subquery = DB::table('stock_habitacions')
            ->select('habitacion_id', 'producto_id', DB::raw('SUM(cantidad) as cantidad'))
            ->groupBy('habitacion_id', 'producto_id');

        return DB::table('habitacions as h')
            ->crossJoin('productos as p')
            ->leftJoinSub($subquery, 'sh', function ($join) {
                $join->on('sh.habitacion_id', '=', 'h.id')
                    ->on('sh.producto_id', '=', 'p.id');
            })
            ->where('h.estado', 1)
            ->where('p.estado', 1)
            ->whereRaw('COALESCE(sh.cantidad, 0) <= 0')
            ->groupBy('h.numero')
            ->pluck(DB::raw('COUNT(*)'), 'h.numero')
            ->map(function ($cantidad) {
                return (int) $cantidad;
            })
            ->toArray();
    }
}
