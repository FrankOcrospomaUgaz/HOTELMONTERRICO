<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\conceptoPago;
use App\Models\Habitacion;
use App\Models\Movimiento;
use App\Models\Persona;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class ChechOutListadoController extends Controller
{
    public const idVista = 177;

    public function __construct()
    {
        $this->middleware('auth');
        $permiso = Permission::find(self::idVista);
        $this->middleware('permission:' . $permiso->name, ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $this->MenuDinamico(Auth::user()->id);

        if ($request->ajax()) {
            $parametro = null;
          

            $calendarioInicio = $request->input('calendarioInicio', null);
        $calendarioFin = $request->input('calendarioFin', null);
        $numero = $request->input('numero', null);

         $movimientosCajaFiltrados = DB::select('call ReportesMovimientosCheckOut(?,?,?)', [$calendarioInicio, $calendarioFin, $numero]);



            return datatables($movimientosCajaFiltrados)
                ->addColumn('action', function ($movCaja) {

                    $acciones = '<div style="width:150px">';

                    $acciones .= '<a href="javascript:void(0)" data-tooltip="Detalle Venta"  onclick="verCarrito(' . $movCaja->id . ')" style="background:blue;margin:2px; color:white;" class="btn btn-info btn-profesional"><i class="fa-solid fa-cart-shopping"></i></a>';

                    $acciones .= '</div>';

                    return $acciones;
                })->rawColumns(['action'])->make(true);
        } else {
        }

        return view('Modulos.CajaChica.indexReporte');
    }
}
