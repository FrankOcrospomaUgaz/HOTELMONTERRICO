<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class reporteCierreController extends Controller
{
    public const idVista = 161;

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
        $fechaInicio = $request->input('calendarioInicio');
        $fechaFin = $request->input('calendarioFin');

        if (!$fechaFin) {
            $fechaFin = Carbon::now();

        }
        $fechaInicio = strtotime($fechaInicio);
        $fechaFin = strtotime($fechaFin);
        if ($request->ajax()) {
            $movReportesCierre = DB::select('call reportesCierres');

            if ($fechaInicio) {
                $movReportesCierre = array_filter($movReportesCierre, function ($acceso) use ($fechaInicio, $fechaFin) {
                    $fechaApertura = strtotime($acceso->fechaApertura);
                    return $fechaApertura >= $fechaInicio && $fechaApertura <= $fechaFin;
                });
            } else {
                $movReportesCierre = DB::select('call reportesCierres');
            }

            return datatables($movReportesCierre)
                ->addColumn('cuadreCaja', function ($movRepCierre) {

                    $cuadreCaja = '<div style="width:150px" class="text-center mx-auto"><a href="javascript:void(0)" data-tooltip="Detalle Cuadre Caja" onclick="reporteCuadreCaja(' . $movRepCierre->idApertura . ',' . $movRepCierre->idCierre . ')" style="margin:2px; color:white;" class="btn btn-primary btn-profesional btn-sm"><i class="fa-solid fa-book-open" style="color: #fff; font-size:15px;"></i></a>';

                    $cuadreCaja .= '</div>';

                    return $cuadreCaja;
                })->addColumn('action', function ($movRepCierre) {

                $acciones = '<div style="width:150px" class="text-center mx-auto">';

                $acciones .= '<a href="javascript:void(0)" data-tooltip="Generar PDF"  onclick="reporteCierrePdf(' . $movRepCierre->idApertura . ',' . $movRepCierre->idCierre . ')" style="margin:2px; color:white;" class="btn btn-danger btn-profesional"><i class="fa-solid fa-file-pdf" style="color: #fff; font-size:15px;"></i></a>';

                $acciones .= '<a href="javascript:void(0)" data-tooltip="Generar Ticket" onclick="reporteCierreTicket(' . $movRepCierre->idApertura . ',' . $movRepCierre->idCierre . ')" style="margin:2px; color:white;" class="btn btn-success btn-profesional btn-sm"><i class="fa-solid fa-receipt" style="color: #fff; font-size:15px;"></i></a>';
                $acciones .= '</div>';
                return $acciones;
            })->rawColumns(['action', 'cuadreCaja'])->make(true);
        } else {
        }

        return view('Modulos.Reportes.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
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
        //
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
}
