<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\GrupoMenu;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;


class RolController extends Controller
{
    //IMPORTANTE PONER EL IDE DEL PERMISO EN ESTE CONTROLADOR
    public const idVista = 5;


    public function __construct()
    {
        $this->middleware('auth');
        $permiso = Permission::find(self::idVista);
        $permisosCrear = Permission::where('padreCrud', self::idVista)->get()[0];
        $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
        $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];

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

        $fechaInicio = Carbon::parse($request->input('calendarioInicio'))->format('Y-m-d H:i:s');
        $fechaFin = Carbon::parse($request->input('calendarioFin'))->format('Y-m-d H:i:s');

        if ($fechaInicio==Carbon::now()) {
            $fechaInicio = Carbon::createFromFormat('Y-m-d H:i:s', '1950-01-01 00:00:00');
        }

        if ($request->ajax()) {
            $estado = $request->input('estado');
            if ($estado == 'inactivos') {
                $roles = Role::where('estado', 0)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get();
            } elseif ($estado == 'activos') {
                $roles = Role::where('estado', 1)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get();
            } else if ($estado == 'todos') {
                $roles = Role::whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get();
            } else {
                $roles  =  Role::all();
            }


            return datatables($roles)
                ->addColumn('action', function ($roles) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarRol(' . $roles->id . ')" style="background:#ffc107; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    } else {
                        $acciones = '';
                    }


                    return $acciones;
                })->addColumn('estado', function ($roles) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                            $roles->id .
                            '" data-id="' .
                            $roles->id .
                            '" ' .
                            ($roles->estado == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switch-' .
                            $roles->id .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }


                    return $estadoR;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }

        return view('Modulos.roles.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $GrupoMenu = GrupoMenu::where('estado', 1)->get();

        return response()->json($GrupoMenu);
    }

    public function opcionesXrol($id)
    {

        $permiso = Permission::where('grupomenu_id', $id)->get();

        return response()->json($permiso);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            ['name' => 'required|unique:roles,name'],
            [
                'name.required' => 'El campo Nombre es obligatorio',
                'name.unique' => 'El campo Nombre ya esta en Uso',
            ]
        );

        $role = Role::create(
            ['name' => $request->input('name')],
        );

        $role->syncPermissions($request->input('permission'));
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
        $GrupoMenu = GrupoMenu::where('estado', 1)->get();
        $role = Role::find($id);
 
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        $datosRecuperado = [
            'role' => $role,

            'rolePermissions' => $rolePermissions,
            'GrupoMenu' => $GrupoMenu,
        ];
        return response()->json($datosRecuperado);
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
        $this->validate(
            $request,
            ['nameE' => 'required|unique:roles,name,'. $id,],
            [
                'nameE.required' => 'El campo Nombre es obligatorio',
                'nameE.unique' => 'El campo Nombre ya esta en Uso',
            ]
        );

        $role = Role::find($id);
        $role->name = $request->input('nameE');


        $role->save();
        $role->syncPermissions($request->input('permissionE'));
        $role->touch(); //actualizar fecha

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $roles = Role::find($id);
        $cadena = ($roles->estado == 0) ? 'Activo' : 'Inactivo';
        $roles->estado = ($roles->estado == 0) ? 1 : 0;
        $roles->save();
        return response($cadena);
    }
}
