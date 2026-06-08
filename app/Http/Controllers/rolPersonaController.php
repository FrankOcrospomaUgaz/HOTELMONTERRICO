<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Rol;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class rolPersonaController extends Controller
{
    public const idVista = 145;

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


            $estado = $request->input('estado');

            if ($estado == 'inactivos') {
                $rolesFiltrados = Rol::where('estado', 0)
                    ->get();
            } else if ($estado == 'activos') {
                $rolesFiltrados = Rol::where('estado', 1)
                    ->get();
            } else if ($estado == 'todos') {
                $rolesFiltrados  =  Rol::all();
            } else {
                $rolesFiltrados  =  Rol::all();
            }
            return datatables($rolesFiltrados)
                ->addColumn('action', function ($rol) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarRolPersona(' . $rol->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($rol) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                            $rol->id .
                            '" data-id="' .
                            $rol->id .
                            '" ' .
                            ($rol->estado == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switch-' .
                            $rol->id .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }


                    return $estadoR;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }
        return view('Modulos.RolPersona.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
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
                'descripcion' => 'required|unique:rols,descripcion',
            ],
            [
                'descripcion.required' => 'El campo Descripcion es obligatorio',
                'descripcion.unique' => 'Este Descripcion ya está en uso.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            Rol::create([
                'descripcion' => $request->input('descripcion'),
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
    public function show()
    {
        return response()->json(Rol::where('estado', 1)->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rol = Rol::where('id', $id)->first();
        return response()->json($rol);
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
                'descripcionE' => 'required|unique:rols,descripcion,' . $id,
            ],
            [
                'descripcionE.required' => 'El campo Descripción es obligatorio',
                'descripcionE.unique' => 'Este Descripción ya está en uso.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $rol = Rol::find($id);
            $rol->descripcion = $request->input('descripcionE');
            $rol->save();
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
        $rol = Rol::find($id);
        $cadena = ($rol->estado == 0) ? 'Activo' : 'Inactivo';
        $rol->estado = ($rol->estado == 0) ? 1 : 0;
        $rol->save();
        return response($cadena);
    }
}
