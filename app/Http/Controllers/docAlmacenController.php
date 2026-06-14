<?php

namespace App\Http\Controllers;

use App\Models\Detallemovimiento;
use App\Models\Movimiento;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class docAlmacenController extends Controller
{
    //IMPORTANTE PONER EL IDE DEL PERMISO EN ESTE CONTROLADOR
    public const idVista = 157;

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
        $fechaInicio = $request->input('calendarioInicio');
        $fechaFin = $request->input('calendarioFin');

        if (!$fechaFin) {
            $fechaFin = Carbon::now();
        }
        $fechaInicio = strtotime($fechaInicio);
        $fechaFin = strtotime($fechaFin);

        if ($request->ajax()) {
            $docAlmacen = DB::select('call showMovimientosDocAlmacen');
            if ($fechaInicio) {
                $docAlmacen = array_filter($docAlmacen, function ($acceso) use ($fechaInicio, $fechaFin) {
                    $fechaApertura = strtotime($acceso->fecha);
                    return $fechaApertura >= $fechaInicio && $fechaApertura <= $fechaFin;
                });
            } else {
                $docAlmacen = DB::select('call showMovimientosDocAlmacen');
            }

            return datatables($docAlmacen)
                ->addColumn('action', function ($docAlmacen) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {

                        $acciones = '<a href="javascript:void(0)" onclick="irDetalleCompras(' . $docAlmacen->id . ',\'' . $docAlmacen->operacion . '\')" style="background:#07b3ff; color:white;" class="btn btn-info"><i class="fa-solid fa-list-check"></i></a>';

                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }

        return view('Modulos.DocAlmacen.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    }

    public function docAlmacen(Request $request)
    {
        $this->MenuDinamico(Auth::user()->id);

        $idMovCompra = $request->input('id');
        $operacion = $request->input('operacion');
        $tipo = $request->input('tipo');

        $agregarDocALmacen = 'true';

        $movDocAlmacen = Movimiento::where('id', $idMovCompra)->first();

        if(!$tipo){
            $agregarDocALmacen = 'false';
        }

        if ($movDocAlmacen !== null) {
           
            $operacion = $movDocAlmacen->operacion;
            $tipo = $movDocAlmacen->tipodocumento_id == 11 ? 'Ingreso' : 'Egreso';
        }

        return view('Modulos.DocAlmacen.docAlmacen', compact('operacion', 'idMovCompra', 'tipo', 'agregarDocALmacen'));
    }

    public function guardarDetalleCuadre(Request $request)
    {
        // dd($request->all());
        $idMovPadre = $request->input('idMovimientoPadre');

        if ($idMovPadre != null) {
            $producto = $this->obtenerProductoActivo((int) $request->input('productos'));

            if (!$producto) {
                return response()->json(['message' => 'El producto está deshabilitado o no existe.'], 422);
            }
            
            $detalleMovimiento = Detallemovimiento::create([
                'movimiento_id' => $idMovPadre,
                'cantidad' => $request->input('cantidadProductoEd'),
                'precioventa' => $producto->precioventa,
                'preciocompra' => $producto->preciocompra,
                'descuento' => 0.00,
                'tipo' => $request->input('tipo'),
                'producto_id' => $request->input('productos'),
                'comentario' => $request->input('comentarioProducto'),
                'motivos_doc_almacens_id' => $request->input('motivos'),
            ]);
            // dd($detalleMovimiento);
            if ($request->input('tipo') == 'Ingreso') {
                $producto->stock = $producto->stock + $request->input('cantidadProductoEd');
                $producto->save();
            } else {
                $producto->stock = $producto->stock - $request->input('cantidadProductoEd');
                $producto->save();
            }
            return response($idMovPadre);
        } else {
            $user = User::where('id', Auth::user()->id)->first();
            $movimiento = Movimiento::create([
                'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
                'usuario_id' => $user->id,
                'total' => $request->input('total'),
                'persona_id' => 17, //varios

                'tipodocumento_id' => $request->input('tipo') == 'Ingreso' ? '11' : '8', //almacen cuadre
                'operacion' => 'Doc Almacen',
                'comentario' => $request->input('comentario'),
            ]);

            $producto = $this->obtenerProductoActivo((int) $request->input('productos'));

            if (!$producto) {
                return response()->json(['message' => 'El producto está deshabilitado o no existe.'], 422);
            }

            $detalleMovimiento = Detallemovimiento::create([
                'movimiento_id' => $movimiento->id,
                'cantidad' => $request->input('cantidadProductoEd'),
                'precioventa' => $producto->precioventa,
                'preciocompra' => $producto->preciocompra,
                'descuento' => 0.00,
                'tipo' => $request->input('tipo'),
                'motivos_doc_almacens_id' => $request->input('motivos'),
                'producto_id' => $request->input('productos'),
                'comentario' => $request->input('comentarioProducto'),
            ]);
            if ($request->input('tipo') == 'Ingreso') {
                $producto->stock = $producto->stock + $request->input('cantidadProductoEd');
                $producto->save();
            } else {
                $producto->stock = $producto->stock - $request->input('cantidadProductoEd');
                $producto->save();
            }

            $movimiento->numero = "M003-" . str_pad($movimiento->id, 8, "0", STR_PAD_LEFT);
            $movimiento->save();
            return response($movimiento->id);
        }

        // return response($producto);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $movAlmacen = Movimiento::find($id);
        $detalleMovimientos = DB::select('call showDetalleProductosDocAlmacen(?)', [$id]);

        // dd($detalleMovimientos);

        $datosUsuarios = [
            'responsable' => Persona::find(User::find($movAlmacen->usuario_id)->persona_id), //admin
            'movAlmacen' => $movAlmacen,
            'fecha' => Carbon::parse($movAlmacen->created_at)->format('Y F d, h:i A'),
            'detalleMovimientos' => $detalleMovimientos,

            'formatoNumeroCompra' => $movAlmacen->numero,

        ];

        return response()->json($datosUsuarios);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $tipo)
    {
        $detalleMov = Detallemovimiento::findOrFail($id);
        $detalleMov->estado = 0; //eliminado pero se suma denuevo
        $detalleMov->save();
        $detalleMov->delete();

        $producto = Producto::find($detalleMov->producto_id);
        if ($tipo == 'Ingreso') {
            $producto->stock = $producto->stock - $detalleMov->cantidad;
            $producto->save();
        } else {
            $producto->stock = $producto->stock + $detalleMov->cantidad;
            $producto->save();
        }

        return response($id);
    }
}
