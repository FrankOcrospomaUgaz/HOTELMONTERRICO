<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GrupoMenu;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GrupoMenuController extends Controller
{
    public const idVista = 33;

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

        $fechaInicio = Carbon::parse($request->input('calendarioInicio'))->format('Y-m-d H:i:s');
        $fechaFin = Carbon::parse($request->input('calendarioFin'))->format('Y-m-d H:i:s');

        if ($fechaInicio == Carbon::now()) {
            $fechaInicio = Carbon::createFromFormat('Y-m-d H:i:s', '1950-01-01 00:00:00');
        }

        if ($request->ajax()) {
            $estado = $request->input('estado');

            if ($estado == 'inactivos') {
                $grupoMenu = GrupoMenu::where('estado', 0)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get();
            } elseif ($estado == 'activos') {
                $grupoMenu = GrupoMenu::where('estado', 1)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get();
            } else if ($estado == 'todos') {
                $grupoMenu = GrupoMenu::whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get();
            } else {
                $grupoMenu  =  GrupoMenu::all();
            }




            return datatables($grupoMenu)
                ->addColumn('action', function ($grupoMenu) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarGrupoMenu(' . $grupoMenu->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($grupoMenu) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                            $grupoMenu->id .
                            '" data-id="' .
                            $grupoMenu->id .
                            '" ' .
                            ($grupoMenu->estado == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switch-' .
                            $grupoMenu->id .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }


                    return $estadoR;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }

        return view('Modulos.GrupoMenu.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
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
                'nameGrupoOpcion' => 'required|unique:grupo_menus,nombre',
                'iconoGrupoOpcion' => 'required',
            ],
            [
                'nameGrupoOpcion.required' => 'El campo Nombre es obligatorio',
                'iconoGrupoOpcion.required' => 'El campo Icono es obligatorio',

                'nameGrupoOpcion.unique' => 'Este Nombre ya está en uso en el Menu.',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            GrupoMenu::create([
                'nombre' => $request->input('nameGrupoOpcion'),
                'icono' => $request->input('iconoGrupoOpcion'),
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
        $gupoMenu = GrupoMenu::where('id', $id)->first();
        return response()->json($gupoMenu);
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
                'nameGrupoOpcionE' => 'required|unique:grupo_menus,nombre,' . $id,
                'iconoGrupoOpcionE' => 'required',
            ],
            [
                'nameGrupoOpcionE.required' => 'El campo Nombre es obligatorio',
                'iconoGrupoOpcionE.required' => 'El campo Icono es obligatorio',

                'nameGrupoOpcionE.unique' => 'Este Nombre ya está en uso en el Menu.',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $grupoMenuEditado = GrupoMenu::find($id);
            $grupoMenuEditado->nombre = $request->input('nameGrupoOpcionE');
            $grupoMenuEditado->icono = $request->input('iconoGrupoOpcionE');
            $grupoMenuEditado->save();
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
        $grupoMenu = GrupoMenu::find($id);
        $cadena = ($grupoMenu->estado == 0) ? 'Activo' : 'Inactivo';
        $grupoMenu->estado = ($grupoMenu->estado == 0) ? 1 : 0;
        $grupoMenu->save();
        return response($cadena);
    }
}
