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
use App\Models\Habitacion;

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
            $productosFiltrados = $this->enriquecerProductosConStockHabitacion($productosFiltrados);

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
            $productosFiltrados = $this->enriquecerProductosConStockHabitacion($productosFiltrados);

            return datatables($productosFiltrados)
                ->addColumn('action', function ($producto) {
                    return '
                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                            <a href="javascript:void(0)" onclick="verDistribucionStock(' . $producto->id . ', \'' . addslashes($producto->nombre) . '\')" style="background:#0b5ed7;margin:2px; color:white;" class="btn btn-sm btn-primary" title="Ver distribución">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="repartirStockHabitacion(' . $producto->id . ', \'' . addslashes($producto->nombre) . '\')" style="background:#198754;margin:2px; color:white;" class="btn btn-sm btn-success" title="Reparto rápido">
                                <i class="fa-solid fa-people-arrows"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
        }

        return view('Modulos.Productos.indexReporte');
    }

    public function showHabitacion($numHabitacion)
    {
        $habitacion = Habitacion::where('numero', $numHabitacion)->first();

        if (!$habitacion) {
            return response()->json([]);
        }

        $productos = DB::select('call showProductos');
        $productos = collect($productos)->map(function ($producto) use ($habitacion) {
            $producto->stock_habitacion = $this->obtenerStockHabitacionProducto($producto->id, $habitacion->id);
            $producto->stock_total = $this->obtenerStockTotalProducto($producto);

            return $producto;
        })->filter(function ($producto) {
            return $producto->estado == 1;
        })->values();

        return response()->json($productos);
    }

    public function distribucionProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $habitaciones = $this->obtenerHabitacionesActivas()->map(function ($habitacion) use ($producto) {
            $stockHabitacion = $this->obtenerStockHabitacionProducto($producto->id, $habitacion->id);

            return [
                'id' => $habitacion->id,
                'numero' => $habitacion->numero,
                'situacion' => $habitacion->situacion,
                'stock' => $stockHabitacion,
                'tiene_stock' => $stockHabitacion > 0,
            ];
        })->values();

        return response()->json([
            'producto' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
            ],
            'stock_general' => (float) $producto->stock,
            'stock_habitaciones' => $this->obtenerStockHabitacionesProducto($producto->id),
            'stock_total' => $this->obtenerStockTotalProducto($producto),
            'habitaciones' => $habitaciones,
        ]);
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

    public function stockHabitacion($productoId, $numHabitacion)
    {
        $habitacion = Habitacion::where('numero', $numHabitacion)->first();
        if (!$habitacion) {
            return response()->json(['stockHabitacion' => 0]);
        }

        return response()->json([
            'stockHabitacion' => $this->obtenerStockHabitacionProducto((int) $productoId, (int) $habitacion->id),
        ]);
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

    public function repartirStockHabitaciones(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $habitacionesIds = $request->input('habitaciones_ids', []);
        if (!is_array($habitacionesIds) || count($habitacionesIds) === 0) {
            $habitacionesIds = $this->obtenerHabitacionesActivas()->pluck('id')->toArray();
        }

        $cantidadPorHabitacion = (float) $request->input('cantidad_por_habitacion', 1);
        if ($cantidadPorHabitacion <= 0) {
            $cantidadPorHabitacion = 1;
        }

        $distribucionExacta = $request->boolean('distribucion_exacta', false);

        $resultado = DB::transaction(function () use ($producto, $habitacionesIds, $cantidadPorHabitacion, $distribucionExacta) {
            $habitaciones = Habitacion::whereIn('id', $habitacionesIds)
                ->where('estado', 1)
                ->orderBy('numero', 'asc')
                ->get();

            $stockDisponible = (float) $producto->stock;
            $habitacionesRepartidas = [];
            $habitacionesSinStock = [];
            $habitacionesParciales = [];
            $asignaciones = [];

            if ($distribucionExacta) {
                foreach ($habitaciones as $habitacion) {
                    if ($stockDisponible < $cantidadPorHabitacion) {
                        $habitacionesSinStock[] = $habitacion->numero;
                        $asignaciones[$habitacion->id] = 0;
                        continue;
                    }

                    $this->incrementarStockHabitacion($producto->id, $habitacion->id, $cantidadPorHabitacion);
                    $stockDisponible -= $cantidadPorHabitacion;
                    $asignaciones[$habitacion->id] = $cantidadPorHabitacion;
                }
            } else {
                $ordenBase = $habitaciones;
                if ($stockDisponible < $habitaciones->count()) {
                    $ordenBase = $habitaciones->shuffle()->values();
                }

                foreach ($ordenBase as $habitacion) {
                    if ($stockDisponible <= 0) {
                        break;
                    }

                    $this->incrementarStockHabitacion($producto->id, $habitacion->id, 1);
                    $stockDisponible -= 1;
                    $asignaciones[$habitacion->id] = ($asignaciones[$habitacion->id] ?? 0) + 1;
                }

                $puedeRecibirMas = function ($habitacion) use (&$asignaciones, $cantidadPorHabitacion) {
                    return (($asignaciones[$habitacion->id] ?? 0) < $cantidadPorHabitacion);
                };

                while ($stockDisponible > 0) {
                    $restantes = $habitaciones->filter($puedeRecibirMas)->values();
                    if ($restantes->isEmpty()) {
                        break;
                    }

                    $huboAsignacion = false;
                    foreach ($restantes->shuffle()->values() as $habitacion) {
                        if ($stockDisponible <= 0) {
                            break;
                        }

                        if (($asignaciones[$habitacion->id] ?? 0) >= $cantidadPorHabitacion) {
                            continue;
                        }

                        $this->incrementarStockHabitacion($producto->id, $habitacion->id, 1);
                        $stockDisponible -= 1;
                        $asignaciones[$habitacion->id] = ($asignaciones[$habitacion->id] ?? 0) + 1;
                        $huboAsignacion = true;
                    }

                    if (!$huboAsignacion) {
                        break;
                    }
                }
            }

            foreach ($habitaciones as $habitacion) {
                $asignado = (float) ($asignaciones[$habitacion->id] ?? 0);
                if ($asignado > 0) {
                    $habitacionesRepartidas[] = $habitacion->numero;
                }
                if ($asignado <= 0) {
                    $habitacionesSinStock[] = $habitacion->numero;
                    continue;
                }

                if (!$distribucionExacta && $asignado < $cantidadPorHabitacion) {
                    $habitacionesParciales[] = $habitacion->numero;
                }
            }

            $producto->stock = $stockDisponible;
            $producto->save();

            return [
                'producto' => $producto->nombre,
                'stock_general' => (float) $producto->stock,
                'stock_habitacion' => $this->obtenerStockHabitacionesProducto($producto->id),
                'stock_total' => $this->obtenerStockTotalProducto($producto),
                'repartidas' => $habitacionesRepartidas,
                'sin_stock' => $habitacionesSinStock,
                'parciales' => $habitacionesParciales,
                'cantidad_por_habitacion' => $cantidadPorHabitacion,
                'distribucion_exacta' => $distribucionExacta,
            ];
        });

        return response()->json($resultado);
    }

    public function transferirStockHabitacion(Request $request, $id)
    {
        $request->validate([
            'habitacion_id' => 'required|integer',
            'cantidad' => 'required|numeric|min:1',
        ]);

        $producto = Producto::findOrFail($id);
        $habitacionId = (int) $request->input('habitacion_id');
        $habitacion = Habitacion::find($habitacionId);

        if (!$habitacion) {
            $habitacion = Habitacion::where('numero', $habitacionId)->first();
        }

        if (!$habitacion) {
            return response()->json([
                'message' => 'La habitacion indicada no existe.',
            ], 422);
        }

        $cantidad = (float) $request->input('cantidad');

        if ((float) $producto->stock < $cantidad) {
            return response()->json([
                'message' => 'No existe suficiente stock en el almacén general.',
            ], 422);
        }

        $resultado = DB::transaction(function () use ($producto, $habitacion, $cantidad) {
            $producto->stock = (float) $producto->stock - $cantidad;
            $producto->save();

            $this->incrementarStockHabitacion($producto->id, $habitacion->id, $cantidad);

            return [
                'producto' => $producto->nombre,
                'habitacion' => $habitacion->numero,
                'stock_general' => (float) $producto->stock,
                'stock_habitacion' => $this->obtenerStockHabitacionProducto($producto->id, $habitacion->id),
                'stock_habitaciones' => $this->obtenerStockHabitacionesProducto($producto->id),
                'stock_total' => $this->obtenerStockTotalProducto($producto),
            ];
        });

        return response()->json($resultado);
    }

    public function retirarStockHabitacion(Request $request, $id)
    {
        $request->validate([
            'habitacion_id' => 'required|integer',
            'cantidad' => 'nullable|numeric|min:1',
        ]);

        $producto = Producto::findOrFail($id);
        $habitacionId = (int) $request->input('habitacion_id');
        $habitacion = Habitacion::find($habitacionId);

        if (!$habitacion) {
            return response()->json([
                'message' => 'La habitacion indicada no existe.',
            ], 422);
        }

        $stockHabitacionActual = $this->obtenerStockHabitacionProducto($producto->id, $habitacion->id);

        if ($stockHabitacionActual <= 0) {
            return response()->json([
                'message' => 'La habitacion no tiene stock para retirar.',
            ], 422);
        }

        $cantidad = $request->filled('cantidad')
            ? (float) $request->input('cantidad')
            : (float) $stockHabitacionActual;

        if ($cantidad > $stockHabitacionActual) {
            return response()->json([
                'message' => 'La cantidad supera el stock disponible en la habitacion.',
            ], 422);
        }

        $resultado = DB::transaction(function () use ($producto, $habitacion, $cantidad) {
            $producto->stock = (float) $producto->stock + $cantidad;
            $producto->save();

            $this->decrementarStockHabitacion($producto->id, $habitacion->id, $cantidad);

            return [
                'producto' => $producto->nombre,
                'habitacion' => $habitacion->numero,
                'stock_general' => (float) $producto->stock,
                'stock_habitacion' => $this->obtenerStockHabitacionProducto($producto->id, $habitacion->id),
                'stock_habitaciones' => $this->obtenerStockHabitacionesProducto($producto->id),
                'stock_total' => $this->obtenerStockTotalProducto($producto),
                'cantidad_retirada' => $cantidad,
            ];
        });

        return response()->json($resultado);
    }
}
