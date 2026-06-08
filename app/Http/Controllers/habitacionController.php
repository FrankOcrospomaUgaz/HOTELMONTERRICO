<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habitacion;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class habitacionController extends Controller
{
    public const idVista = 133;

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
            $fechaInicio = $request->input('calendarioInicioProducto');
            $fechaFin = $request->input('calendarioFinProducto');

            if (!$fechaFin) {
                $fechaFin = Carbon::now();
            }
            $estado = $request->input('estado');
            if ($estado == 'inactivos') {
                $habitacionFiltrados = Habitacion::where('estado', 0)
                    ->get();
            } elseif ($estado == 'activos') {
                $habitacionFiltrados = Habitacion::where('estado', 1)
                    ->get();
            } else if ($estado == 'todos') {
                $habitacionFiltrados  =  Habitacion::all();
            } else {
                $habitacionFiltrados  =  Habitacion::all();
            }
            return datatables($habitacionFiltrados)
                ->addColumn('action', function ($habitacion) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarHabitacion(' . $habitacion->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($habitacion) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                            $habitacion->id .
                            '" data-id="' .
                            $habitacion->id .
                            '" ' .
                            ($habitacion->estado == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switch-' .
                            $habitacion->id .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }


                    return $estadoR;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }
        return view('Modulos.Habitacion.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:habitacions,numero',
            ],
            [
                'name.required' => 'El campo Nombre es obligatorio',
                'name.unique' => 'Este Nombre ya está en uso en el Menu.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            Habitacion::create([
                'numero' => $request->input('name'),
                'tipo' => $request->input('tipo'),
            ]);

            return response('con Exito');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return response()->json(Habitacion::find($id));
    }

    public function habitacionesXsituacion($situacion)
    {
        return response()->json(Habitacion::where('situacion', $situacion)->where('estado', 1)->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $habitacion = Habitacion::where('id', $id)->first();
        return response()->json($habitacion);
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
        $validator = Validator::make(
            $request->all(),
            [
                'nameE' => 'required|unique:habitacions,numero,' . $id,
            ],
            [
                'nameE.required' => 'El campo Nombre es obligatorio',
                'nameE.unique' => 'Este Nombre ya está en uso.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $habitacion = Habitacion::find($id);
            $habitacion->numero = $request->input('nameE');
            $habitacion->situacion = $request->input('situacionCambio');
            $habitacion->tipo = $request->input('tipoE');
            $habitacion->save();
            return response('con Exito');
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
        $habitacion = Habitacion::find($id);
        $cadena = ($habitacion->estado == 0) ? 'Activo' : 'Inactivo';
        $habitacion->estado = ($habitacion->estado == 0) ? 1 : 0;
        $habitacion->save();
        return response($cadena);
    }
}
