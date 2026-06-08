<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\GrupoMenu;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class OpcionMenuController extends Controller
{
    public const idVista = 9;

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
        $permisosCrear = Permission::where('padreCrud', self::idVista)->get()[0];
        $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
        $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
        $this->MenuDinamico(Auth::user()->id);

        $fechaInicio = $request->input('calendarioInicio');
        $fechaFin = $request->input('calendarioFin');
        if (!$fechaFin) {
            $fechaFin = Carbon::now();
        }

        if ($request->ajax()) {
            $estado = $request->input('estado');

            if ($estado == 'inactivos') {
                $accesos = DB::select('call accesos');
                $fechaInicio = strtotime($fechaInicio); // Convertir una fecha en formato de texto a UNIX timestamp
                $fechaFin = strtotime($fechaFin); // Convertir una fecha en formato de texto a UNIX timestamp

                $accesosFiltrados = array_filter($accesos, function ($acceso) use ($fechaInicio, $fechaFin) {
                    $fechaAcceso = strtotime($acceso->created_at); // Convertir la fecha del acceso en UNIX timestamp
                    return $fechaAcceso >= $fechaInicio && $fechaAcceso <= $fechaFin && $acceso->estadoPermiso == 0; // Comprobar si la fecha del acceso está dentro del rango especificado
                });
            } elseif ($estado == 'activos') {
                $accesos = DB::select('call accesos');
                $fechaInicio = strtotime($fechaInicio); // Convertir una fecha en formato de texto a UNIX timestamp
                $fechaFin = strtotime($fechaFin); // Convertir una fecha en formato de texto a UNIX timestamp

                $accesosFiltrados = array_filter($accesos, function ($acceso) use ($fechaInicio, $fechaFin) {
                    $fechaAcceso = strtotime($acceso->created_at); // Convertir la fecha del acceso en UNIX timestamp
                    return $fechaAcceso >= $fechaInicio && $fechaAcceso <= $fechaFin && $acceso->estadoPermiso > 0; // Comprobar si la fecha del acceso está dentro del rango especificado
                });
            } else if ($estado == 'todos') {
                $accesos = DB::select('call accesos');
                $fechaInicio = strtotime($fechaInicio); // Convertir una fecha en formato de texto a UNIX timestamp
                $fechaFin = strtotime($fechaFin); // Convertir una fecha en formato de texto a UNIX timestamp

                $accesosFiltrados = array_filter($accesos, function ($acceso) use ($fechaInicio, $fechaFin) {
                    $fechaAcceso = strtotime($acceso->created_at); // Convertir la fecha del acceso en UNIX timestamp
                    return $fechaAcceso >= $fechaInicio && $fechaAcceso <= $fechaFin; // Comprobar si la fecha del acceso está dentro del rango especificado
                });
            } else {
                $accesosFiltrados  = DB::select('call accesos');
            }

            return datatables($accesosFiltrados)
                ->addColumn('action', function ($accesos) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];

                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarOpcion(' . $accesos->idPermiso . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                        $acciones .= '<a href="javascript:void(0)" onclick="editarCRUD(' . $accesos->idPermiso . ')" style="background:#f26100; margin:2px; color:white; border:solid 1px black" class="btn btn-warning"><strong>CRUD</strong></a>';
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($accesos) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                            $accesos->idPermiso .
                            '" data-id="' .
                            $accesos->idPermiso .
                            '" ' .
                            ($accesos->estadoPermiso == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switch-' .
                            $accesos->idPermiso .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }
                    return $estadoR;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }


        return view('Modulos.OpcionesMenu.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    }


    public function obtenerCRUD(Request $request, $id)
    {

        if ($request->ajax()) {
            $accesos = Permission::where('padreCrud', $id);

            return datatables($accesos)
                ->addColumn('action', function ($accesos) {

                    $acciones = '<a href="javascript:void(0)" onclick="editarOpcionCRUD(' . $accesos->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    return $acciones;
                })->rawColumns(['action'])->make(true);
        } else {
        }

        return view('Modulos.OpcionesMenu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Grupos = GrupoMenu::where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get();


        return response()->json($Grupos);
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
                'name' => 'required|unique:permissions,name',
                'ruta' => 'required|unique:permissions,ruta',
                'icono' => 'required',
            ],
            [
                'name.required' => 'El campo Nombre es obligatorio',
                'ruta.required' => 'El campo Ruta es obligatorio',
                'icono.required' => 'El campo Icono es obligatorio',

                'name.unique' => 'Este Nombre ya está en uso entre las Opciones.',
                'ruta.unique' => 'Esta Ruta ya está en uso por otra Opcion.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $nombre = str_replace('-', '', $request->input('name'));

            Permission::create([
                'name' => $nombre, 'ruta' => $request->input('ruta'),
                'icono' => $request->input('icono'), 'grupomenu_id' => $request->input('categoria'),
            ]);

            $permissions = Permission::where('ruta', $request->input('ruta'))
                ->first();
            $idPermisoPadre = $permissions->id;

            $CRUD = [
                ["Crear-$nombre", "$nombre.create", 'fa-solid fa-square-plus', $idPermisoPadre, $permissions->grupomenu_id],
                ["Editar-$nombre", "$nombre.edit", 'fa-solid fa-pen-to-square', $idPermisoPadre, $permissions->grupomenu_id],
                ["Eliminar-$nombre", "$nombre.destroy", 'fa-solid fa-circle-xmark', $idPermisoPadre, $permissions->grupomenu_id],
            ];

            foreach ($CRUD as $propiedad) {
                Permission::create([
                    'name' => $propiedad[0], 'ruta' => $propiedad[1],
                    'icono' => $propiedad[2], 'padreCrud' => $propiedad[3],
                    'grupomenu_id' => $propiedad[4],
                ]);
            }
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
        $permissions = Permission::where('id', $id)
            ->whereNull('padreCrud')
            ->first();
        $Grupos =  GrupoMenu::where('estado', 1)->orWhere('id', $permissions->grupomenu_id)->get();

        $datosRecuperado = [
            'permissions' => $permissions,
            'Grupos' => $Grupos,
        ];

        return response()->json($datosRecuperado);
    }
    public function editarCRUD($id)
    {
        $permissions = Permission::where('id', $id)->first();
        return response()->json($permissions);
    }

    public function updateCRUD(Request $request, $id)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'nameCRUD_E' => 'required|unique:permissions,name,' . $id,
                'rutaCRUD_E' => 'required|unique:permissions,ruta,' . $id,
                'iconoCRUD_E' => 'required',
            ],
            [
                'nameCRUD_E.required' => 'El campo Nombre es obligatorio',
                'rutaCRUD_E.required' => 'El campo Ruta es obligatorio',
                'iconoCRUD_E.required' => 'El campo Icono es obligatorio',

                'nameCRUD_E.unique' => 'Este Nombre ya está en uso entre las Opciones.',
                'rutaCRUD_E.unique' => 'Esta Ruta ya está en uso por otra Opcion.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $permissions = Permission::find($id);
            $permissions->name = $request->input('nameCRUD_E');
            $permissions->ruta = $request->input('rutaCRUD_E');
            $permissions->icono = $request->input('iconoCRUD_E');

            $permissions->save();
            return response('con Exito');
        }
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
                'nameE' => 'required|unique:permissions,name,' . $id,
                'rutaE' => 'required|unique:permissions,ruta,' . $id,
                'iconoE' => 'required',
            ],
            [
                'nameE.required' => 'El campo Nombre es obligatorio',
                'rutaE.required' => 'El campo Ruta es obligatorio',
                'iconoE.required' => 'El campo Icono es obligatorio',

                'nameE.unique' => 'Este Nombre ya está en uso entre las Opciones.',
                'rutaE.unique' => 'Esta Ruta ya está en uso por otra Opcion.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $permissions = Permission::find($id);
            $permissions->name = str_replace('-', '', $request->input('nameE'));
            $permissions->ruta = $request->input('rutaE');
            $permissions->icono = $request->input('iconoE');
            $permissions->grupomenu_id = $request->input('categoriaE');

            $permisosHijos = Permission::where('padreCrud', $id)->get();

            foreach ($permisosHijos as $permisoHijo) {
                $permisoHijo->grupomenu_id = $permissions->grupomenu_id;
                $permisoHijo->save();
            }

            $permissions->save();
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
        $permissions = Permission::find($id);
        $cadena = ($permissions->estado == 0) ? 'Activo' : 'Inactivo';
        $permissions->estado = ($permissions->estado == 0) ? 1 : 0;
        $permissions->save();

        $permissionsHijos = Permission::where('padreCrud', $id)->get();

        foreach ($permissionsHijos as $permissionsHijo) {
            $permissionsHijo->estado = ($permissionsHijo->estado == 0) ? 1 : 0;
            $permissionsHijo->save();
        }



        return response($cadena);
    }
}
