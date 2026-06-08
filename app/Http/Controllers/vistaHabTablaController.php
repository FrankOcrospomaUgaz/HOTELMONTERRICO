<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class vistaHabTablaController extends Controller
{
    public const idVista = 153;

    public function __construct()
    {
        $this->middleware('auth');
        $permiso = Permission::find(self::idVista);
        $this->middleware('permission:' . $permiso->name, ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        $this->MenuDinamico(Auth::user()->id);
        $permisosCrear = Permission::where('padreCrud', self::idVista)->get()[0];
        $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
        $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
        if ($request->ajax()) {
            $habitacion = DB::select('call shoHabitacionesYHora');

            return datatables($habitacion)
                ->addColumn('action', function ($habitacion) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {


                        $acciones = '<div style="width:250px">';
                        switch ($habitacion->situacion) {
                            case 'Disponible':
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Confirmar Checking"  onclick="confirmarChecking(' . $habitacion->numero . ')" style="background:#2ECC71;margin:3px; color:white;" class="btn btn-sm btn-profesional"><i class="fa-regular fa-circle-check"></i></a>';
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Cambiar Situación"  onclick="cambiarSituacion(' . $habitacion->id . ', '. $habitacion->numero .')" style="background:#5DADE2;margin:3px; color:white;" class="btn btn-sm btn-profesional"><i class="fa-solid fa-bars"></i></a>';
                                break;
                            case 'Limpieza':
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Cambiar Situación"  onclick="cambiarSituacion(' . $habitacion->id . ', '. $habitacion->numero .')" style="background:#5DADE2;margin:3px; color:white;" class="btn btn-sm btn-profesional"><i class="fa-solid fa-bars"></i></a>';

                                break;

                            case 'Mantenimiento':
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Cambiar Situación"  onclick="cambiarSituacion(' . $habitacion->id . ', '. $habitacion->numero .')" style="background:#5DADE2;margin:3px; color:white;" class="btn btn-sm btn-profesional"><i class="fa-solid fa-bars"></i></a>';

                                break;
                            case 'Ocupada':
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Agregar Venta"  onclick="agregarVenta(' . $habitacion->numero . ')" style="background:blue;margin:3px; color:white;" class="btn btn-sm btn-profesional"><i class="fa-solid fa-store"></i></a>';
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Pagar"  onclick="pagarVenta(' . $habitacion->numero . ')" style="background:red;margin:3px; color:white;" class="btn btn-sm btn-profesional"><i class="fa-solid fa-sack-dollar"></i></a>';
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Agregar Tiempo"  onclick="sumarTiempoHabTabla(' . $habitacion->numero . ')" style="background:#ffbe00  ;margin:3px; color:white;" class="btn btn-sm btn-profesional"><i class="fa-solid fa-hourglass-half"></i></a>';
                                $acciones .= '<a href="javascript:void(0)" data-tooltip="Cambiar Habitación"  onclick="cambiarHabitacion(' . $habitacion->numero . ')" style="background:#8E44AD  ;margin:3px; color:white;" class="btn btn-sm btn-profesional"><i class="fa-solid fa-door-open"></i></a>';

                                break;


                            default:
                                # code...
                                break;
                        }


                        $acciones .= '</div>';
                    }

                    return $acciones;
                })->rawColumns(['action'])->make(true);
        } else {
        }
        return view('Modulos.VistaPrincipal.indexTabla', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    }
}
