<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class servicioController extends Controller
{
    public const idVista = 113;

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
                $servicioFiltrados = Servicio::where('estado', 0)
                    ->get();
            } else if ($estado == 'activos') {
                $servicioFiltrados = Servicio::where('estado', 1)
                    ->get();
            } else if ($estado == 'todos') {
                $servicioFiltrados = Servicio::orderBy('created_at', 'desc')->get();

            } else {
                $servicioFiltrados = Servicio::orderBy('created_at', 'desc')->get();

            }
            return datatables($servicioFiltrados)
                ->addColumn('action', function ($servicio) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {

                        if ($servicio->tipo != 'Tiempo') {
                            $acciones = '<a href="javascript:void(0)" onclick="editarServicio(' . $servicio->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';

                        } else {
                            $acciones = '';
                        }

                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($servicio) {
                $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                if (Gate::allows($permisosEliminar->name)) {
                    if ($servicio->tipo != 'Tiempo') {
                        $estadoR = '<div class="custom-control custom-switch">' .
                        '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                        $servicio->id .
                        '" data-id="' .
                        $servicio->id .
                        '" ' .
                        ($servicio->estado == 1 ? "checked" : "") .
                        ">" .
                        '<label class="custom-control-label form-check-label" for="switch-' .
                        $servicio->id .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }

                } else {
                    $estadoR = '';
                }

                return $estadoR;
            })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }
        return view('Modulos.Servicio.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
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
                'name' => [
                    'required',
                    'unique:servicios,nombre',
                    'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s]+$/',
                ],
                'precioventa' => 'required',
            ],
            [
                'name.required' => 'El campo Nombre es obligatorio',
                'name.unique' => 'Este Nombre ya está en uso en el Menu.',
                'precioventa.required' => 'El campo Precio Venta es obligatorio',
                'name.regex' => 'El campo Nombre contiene caracteres especiales no permitidos',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            Servicio::create([
                'nombre' => $request->input('name'),
                'precioventa' => $request->input('precioventa'),

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
        return response()->json(Servicio::where('estado', 1)->orderBy('created_at', 'desc')->get());
    }

    public function showId($id)
    {
        return response()->json(Servicio::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $servicio = Servicio::where('id', $id)->first();
        return response()->json($servicio);
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
                'nameE' => 'required|unique:servicios,nombre,' . $id,
                'precioVentaE' => 'required',
            ],
            [
                'nameE.required' => 'El campo Nombre es obligatorio',
                'precioVentaE.required' => 'El campo Precio Venta es obligatorio',
                'nameE.unique' => 'Este Nombre ya está en uso.',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $servicio = Servicio::find($id);
            $servicio->nombre = $request->input('nameE');
            $servicio->precioventa = $request->input('precioVentaE');
            $servicio->save();
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
        $servicio = Servicio::find($id);
        $cadena = ($servicio->estado == 0) ? 'Activo' : 'Inactivo';
        $servicio->estado = ($servicio->estado == 0) ? 1 : 0;
        $servicio->save();
        return response($cadena);
    }
}
