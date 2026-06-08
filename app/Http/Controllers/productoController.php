<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Unidad;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class productoController extends Controller
{
    public const idVista = 109;
    public const idVistaStock = 173;

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
                $productos = DB::select('call showProductos');

                $productosFiltrados = array_filter($productos, function ($producto) {
                    return $producto->estado == 0;
                });
            } elseif ($estado == 'activos') {
                $productos = DB::select('call showProductos');
                $productosFiltrados = array_filter($productos, function ($producto) {
                    return $producto->estado > 0;
                });
            } else if ($estado == 'todos') {
                $productosFiltrados  = DB::select('call showProductos');
            } else {
                $productosFiltrados  = DB::select('call showProductos');
            }
            return datatables($productosFiltrados)
                ->addColumn('action', function ($producto) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarProducto(' . $producto->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($producto) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                            $producto->id .
                            '" data-id="' .
                            $producto->id .
                            '" ' .
                            ($producto->estado == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switch-' .
                            $producto->id .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }


                    return $estadoR;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }

        return view('Modulos.Productos.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    }
    public function indexReporte(Request $request)
    {
        $this->MenuDinamico(Auth::user()->id);
        $permisosCrear = Permission::where('padreCrud', self::idVistaStock)->get()[0];
        if ($request->ajax()) {

            $estado = $request->input('estado');
            if ($estado == 'inactivos') {
                $productos = DB::select('call showProductos');

                $productosFiltrados = array_filter($productos, function ($producto) {
                    return $producto->estado == 0;
                });
            } elseif ($estado == 'activos') {
                $productos = DB::select('call showProductos');
                $productosFiltrados = array_filter($productos, function ($producto) {
                    return $producto->estado > 0;
                });
            } else if ($estado == 'todos') {
                $productosFiltrados = DB::select('call showProductos');
            } else {
                $productosFiltrados = DB::select('call showProductos');
            }
            return datatables($productosFiltrados)->make(true);
        } else {
        }

        return view('Modulos.Productos.indexReporte');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoria = Categoria::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $unidad = Unidad::where('estado', 1)->orderBy('nombre', 'asc')->get();


        $datosUsuarios = [
            'unidades' =>  $unidad,
            'categorias' => $categoria,

        ];

        return response()->json($datosUsuarios);
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
                    'unique:productos,nombre',
                    'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s]+$/',
                ],
                'codigo' => 'required|unique:productos,nombre',
                'precioventa' => 'required',
                'preciocompra' => 'required',
            ],
            [
                'name.required' => 'El campo Nombre es obligatorio',
                'name.unique' => 'Este Nombre ya está en uso.',

                'codigo.required' => 'El campo Codigo es obligatorio',
                'codigo.unique' => 'Este Codigo ya está en uso.',

                'precioventa.required' => 'El campo Precio Venta es obligatorio',
                'preciocompra.required' => 'El campo Precio Compra es obligatorio',
                'name.regex' => 'El campo Nombre contiene caracteres especiales no permitidos',
            ]
        );


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            Producto::create([
                'nombre' => $request->input('name'),
                'codigo' => $request->input('codigo'),
                'preciocompra' => $request->input('preciocompra'),
                'precioventa' => $request->input('precioventa'),
                'unidad_id' =>  $request->input('unidades'),
                'categoria_id' => $request->input('categorias'),

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
        return response()->json(Producto::where('estado', 1)->orderBy('nombre')->get());

    }

    public function showId($id)
    {
        return response()->json(Producto::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::where('id', $id)->first();
        $productoCategoria = $producto->categoria_id;
        $productoUnidad = $producto->unidad_id;
        $categoria = Categoria::where('estado', 1)->orWhere('id', $productoCategoria)->orderBy('nombre', 'asc')->get();
        $unidad = Unidad::where('estado', 1)->orWhere('id', $productoUnidad)->orderBy('nombre', 'asc')->get();

        $datosUsuarios = [
            'unidades' =>  $unidad,
            'categorias' => $categoria,
            'producto' => $producto,

            'productoCategoria' => $productoCategoria,
            'productoUnidad' => $productoUnidad,
        ];

        return response()->json($datosUsuarios);
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
                'nameE' => 'required|unique:productos,nombre,' . $id,
                'codigoE' => 'required|unique:productos,nombre,' . $id,
                'precioventaE' => 'required',
                'preciocompraE' => 'required',
            ],
            [
                'nameE.required' => 'El campo Nombre es obligatorio',
                'nameE.unique' => 'Este Nombre ya está en uso.',

                'codigoE.required' => 'El campo Codigo es obligatorio',
                'codigoE.unique' => 'Este Codigo ya está en uso.',

                'precioventaE.required' => 'El campo Precio Venta es obligatorio',
                'preciocompraE.required' => 'El campo Precio Compra es obligatorio',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $producto = Producto::find($id);
            $producto->nombre = $request->input('nameE');
            $producto->unidad_id  = $request->input('unidadesE');
            $producto->categoria_id = $request->input('categoriasE');
            $producto->codigo = $request->input('codigoE');
            $producto->precioventa = $request->input('precioventaE');
            $producto->preciocompra = $request->input('preciocompraE');

            $producto->save();
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
        $producto = Producto::find($id);
        $cadena = ($producto->estado == 0) ? 'Activo' : 'Inactivo';
        $producto->estado = ($producto->estado == 0) ? 1 : 0;
        $producto->save();
        return response($cadena);
    }
}
