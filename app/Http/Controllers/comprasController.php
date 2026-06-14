<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;
use App\Models\Movimiento;
use App\Models\Detallemovimiento;
use App\Models\Persona;
use App\Models\Producto;

class comprasController extends Controller
{
    //IMPORTANTE PONER EL IDE DEL PERMISO EN ESTE CONTROLADOR
    public const idVista = 165;

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
            $compras = DB::select('call showMovimientosCompras');
            if ($fechaInicio) {
                $compras = array_filter($compras, function ($acceso) use ($fechaInicio, $fechaFin) {
                    $fechaApertura = strtotime($acceso->fecha);
                    return $fechaApertura >= $fechaInicio && $fechaApertura <= $fechaFin;
                });
            } else {
                $compras = DB::select('call showMovimientosCompras');
            }

            return datatables($compras)
                ->addColumn('action', function ($compras) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        if ($compras->situacion == "Normal") {
                            $acciones = '<a href="javascript:void(0)" onclick="irDetalleCompras(' . $compras->id . ',\'' . $compras->operacion . '\')" style="background:orange; color:white;" class="btn btn-info"><i class="fa-solid fa-basket-shopping"></i></a>';
                        } else {
                            $acciones = '';
                        }
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }

        return view('Modulos.Compras.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function docCompras(Request $request)
    {
        $this->MenuDinamico(Auth::user()->id);

        $operacion = $request->input('operacion') == 'compras' ? 'compras' : 'Doc Almacen';
        $idMovCompra = $request->input('id');
        return view('Modulos.Compras.docCompras', compact('operacion', 'idMovCompra'));
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
        $cantCuotas = $request->input('cantCuotas') ?: 1;

        $movimiento = Movimiento::create([
            'fecha' => $request->input('fecha'),
            'persona_id' => $request->input('proveedores'),
            'usuario_id' => Auth::user()->id,
            'total' => $request->input('total'),
            'tipodocumento_id' => $request->input('tipo') == 'Boleta' ? '3' : ($request->input('tipo') == 'Factura' ? '4' : '10'), // INGRESO o EGRESO
            'operacion' => $request->input('operacion') == 'compras' ? 'compras' : 'Doc Almacen', //INGRESO o EGRESO
            'formaPago' => $request->input('formaPagoSelect'),
            'cantCuotas' => $cantCuotas,
            'comentario' => $request->input('comentario'),
        ]);

        $movimiento->numero =  $request->input('numCompra');
        $movimiento->save();
        return response($movimiento);
    }

    public function actualizar(Request $request, $id)
    {
        $movimiento = Movimiento::find($id);

        $movimiento->persona_id = $request->input('proveedores');
        $movimiento->tipodocumento_id = $request->input('tipo') == 'Boleta' ? '3' : ($request->input('tipo') == 'Factura' ? '4' : '10');

        $movimiento->numero = $request->input('numCompra');
        $movimiento->fecha = $request->input('fechaCompro');
        $movimiento->formaPago = $request->input('formaPagoSelect');
        $movimiento->cantCuotas = $request->input('cantCuotas') ?: 1;


        $movimiento->save();
        // dd($movimiento);
        return response($movimiento);
    }

    public function guardarDetalle(Request $request, $id)
    {

        $producto = $this->obtenerProductoActivo((int) $request->input('productos'));

        if (!$producto) {
            return response()->json(['message' => 'El producto está deshabilitado o no existe.'], 422);
        }

        $detalleMovimiento = Detallemovimiento::create([
            'movimiento_id' => $id,
            'cantidad' => $request->input('cantidadProductoEd'),
            'precioventa' => $producto->precioventa,
            'preciocompra' => $request->input('precioUnitario'),
            'descuento' => 0.00,
            'producto_id' => $request->input('productos'),
            'comentario' => $request->input('comentarioProducto'),
            'motivos_doc_almacens_id' =>5,//COMPRA PRODUCTO
        ]);
        $producto->stock = $producto->stock + $request->input('cantidadProductoEd');
        $producto->preciocompra = $request->input('precioUnitario');
        $producto->save();



        return response($producto);
    }

    

    public function verificarNumeroMovCompra($numero, $id)
    {
        $confirmacion = Movimiento::where('numero', $numero)->first();

        if ($id != 0) {
            $mov = Movimiento::find($id);

            if ($confirmacion) {
                if ($mov->numero == $numero) {
                    return "Numero Disponible";
                } else {
                    return "Ya usado";
                }
            } else {
                return "Numero Disponible";
            }
        } else {
            if ($confirmacion) {
                return "Ya usado";
            } else {
                return "Numero Disponible";
            }
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

        $movCompra = Movimiento::find($id);
      
        $detalleMovimientos = DB::select('call showDetalleProductos(?)', [$id]);
        $proveedores = DB::select('call ObtenerPersonasPorRol(?)', array(3));

        $datosUsuarios = [
            'responsable' =>  Persona::find($movCompra->usuario_id), //admin
            'movCompra' =>  $movCompra,
            'fecha' => Carbon::parse($movCompra->created_at)->format('Y F d, h:i A'),
            'detalleMovimientos' => $detalleMovimientos,
            'proveedores' => $proveedores,
            'formatoNumeroCompra' =>  $movCompra->numero

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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Movimiento = Movimiento::findOrFail($id);

        $detalleMovimientos = Detallemovimiento::where('movimiento_id', $Movimiento->id)
            ->whereNotNull('producto_id')
            ->get();


        foreach ($detalleMovimientos as $detalle) {
            $producto = Producto::find($detalle->producto_id);

            $producto->stock = $producto->stock - $detalle->cantidad;

            $producto->save();
        }


        $Movimiento->estado = 0; //eliminado
        $Movimiento->situacion = 'Eliminado'; //eliminado
        $Movimiento->save();



        $Movimiento->delete();
    }
}
