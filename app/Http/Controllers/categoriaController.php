<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class categoriaController extends Controller
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
                $categoria = Categoria::where('estado', 0)
                    ->get();
            } elseif ($estado == 'activos') {
                $categoria = Categoria::where('estado', 1)
                    ->get();
            } else if ($estado == 'todos') {
                $categoria  =  Categoria::all();
            } else {
                $categoria  =  Categoria::all();
            }

            return datatables($categoria)
                ->addColumn('action', function ($categoria) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarCategoria(' . $categoria->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($categoria) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado-cat" id="switchCat-' .
                            $categoria->id .
                            '" data-id="' .
                            $categoria->id .
                            '" ' .
                            ($categoria->estado == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switchCat-' .
                            $categoria->id .
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
                'nameCategoria' => 'required|unique:categorias,nombre',
            ],
            [
                'nameCategoria.required' => 'El campo Nombre es obligatorio',
                'nameCategoria.unique' => 'Este Nombre ya está en uso en el Menu.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            Categoria::create([
                'nombre' => $request->input('nameCategoria'),
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
        $categoria = Categoria::where('id', $id)->first();
        return response()->json($categoria);
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
                'nameECategoria' => 'required|unique:categorias,nombre,' . $id,
            ],
            [
                'nameECategoria.required' => 'El campo Nombre es obligatorio',
                'nameECategoria.unique' => 'Este Nombre ya está en uso.',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $categoria = Categoria::find($id);
            $categoria->nombre = $request->input('nameECategoria');
            $categoria->save();
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
        $categoria = Categoria::find($id);
        $cadena = ($categoria->estado == 0) ? 'Activo' : 'Inactivo';
        $categoria->estado = ($categoria->estado == 0) ? 1 : 0;
        $categoria->save();
        return response($cadena);
    }
}
