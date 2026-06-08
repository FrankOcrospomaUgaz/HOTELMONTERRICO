<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Unidad;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class unidadController extends Controller
{
    public const idVista = 109;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $estado = $request->input('estado');

            if ($estado == 'inactivos') {
                $unidad = Unidad::where('estado', 0)
                    ->get();
            } elseif ($estado == 'activos') {
                $unidad = Unidad::where('estado', 1)
                    ->get();
            } else if ($estado == 'todos') {
                $unidad  =  Unidad::all();
            } else {
                $unidad  =  Unidad::all();
            }

            return datatables($unidad)
                ->addColumn('action', function ($unidad) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarUnidad(' . $unidad->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($unidad) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado-uni" id="switchUni-' .
                            $unidad->id .
                            '" data-id="' .
                            $unidad->id .
                            '" ' .
                            ($unidad->estado == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switchUni-' .
                            $unidad->id .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }


                    return $estadoR;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }

        return view('Modulos.Producto.index');
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
                'nameUnidad' => 'required|unique:unidads,nombre',
            ],
            [
                'nameUnidad.required' => 'El campo Nombre es obligatorio',
                'nameUnidad.unique' => 'Este Nombre ya está en uso en el Menu.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            Unidad::create([
                'nombre' => $request->input('nameUnidad'),
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
        $unidad = Unidad::where('id', $id)->first();
        return response()->json($unidad);
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
                'nameEUnidad' => 'required|unique:unidads,nombre,' . $id,
            ],
            [
                'nameEUnidad.required' => 'El campo Nombre es obligatorio',
                'nameEUnidad.unique' => 'Este Nombre ya está en uso.',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $unidad = Unidad::find($id);
            $unidad->nombre = $request->input('nameEUnidad');
            $unidad->save();
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
        $unidad = Unidad::find($id);
        $cadena = ($unidad->estado == 0) ? 'Activo' : 'Inactivo';
        $unidad->estado = ($unidad->estado == 0) ? 1 : 0;
        $unidad->save();
        return response($cadena);
    }
}
