<?php

namespace App\Http\Controllers;

use App\Models\Detallemovimiento;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Habitacion;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class detallMovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $tableDataJSON = $request->input('tableData');

        // Convierte el JSON en un arreglo PHP
        $tableData = json_decode($tableDataJSON, true);

        foreach ($tableData as $detalleMov) {

            $nombreSinEspacios = preg_replace('/\s+/', '', $detalleMov['nombre']);

            $producto = Producto::whereRaw("REPLACE(nombre, ' ', '') REGEXP '^" . $nombreSinEspacios . "$'")->first();

            $detalleMovimiento = Detallemovimiento::create([
                'movimiento_id' => $request->input('idMovimiento'),
                'cantidad' => $detalleMov['cantidad'],
                'precioventa' => $producto->precioventa,
                'preciocompra' => $producto->preciocompra,
                'descuento' => 0.00,
                'motivos_doc_almacens_id' => 10, //VENTA PRODUCTO
                'producto_id' => $producto->id,
                'comentario' => '',
            ]);
            $producto->stock = $producto->stock - $detalleMov['cantidad'];
            $producto->save();
        }

        $datosRecuperado = [
            'detalleMovimiento' => $detalleMovimiento,
            'servicio' => $producto,
        ];
        return response()->json($datosRecuperado);

    }

    public function storeServicio(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cantidadServicio' => 'required',
            ],
            [
                'cantidadServicio.required' => 'El campo Cantidad es obligatorio',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            $servicio = Servicio::find($request->input('servicios'));
            $detalleMovimiento = Detallemovimiento::create([
                'movimiento_id' => $request->input('idMovimiento'),
                'cantidad' => $request->input('cantidadServicio'),
                'descuento' => 0.00,
                'precioventa' => $request->input('precioServicio'),
                'servicio_id' => $request->input('servicios'),
                'comentario' => $request->input('comentarioServicio'),
            ]);

            // $this->cantTotalMovCompra($request->input('idMovimiento'));

  

            $datosRecuperado = [
                'detalleMovimiento' => $detalleMovimiento,
                'servicio' => $servicio,
            ];
            return response()->json($datosRecuperado);
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

        $query = "SELECT calcularTotalDetalleMovimiento($id) AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento = Movimiento::find($id);
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();

        // dd($movimiento);

        $habitacion = Habitacion::where('id', $movimiento->habitacion_id)->first();


        $habitacion->total = $resultado[0]->total;
        $habitacion->save();


        $datosRecuperados = [
            'total' => $total,
        ];

        return response()->json($datosRecuperados);
    }

    public function showDocAlmacen($id)
    {

        $query = "SELECT calcularTotalDetalleMovimientoDocAlmacen($id) AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento = Movimiento::find($id);
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();

        $datosRecuperados = [
            'total' => $total,
        ];

        return response()->json($datosRecuperados);
    }

    public function cantTotalMovCompra($id)
    {

        $query = "SELECT calcularTotalDetalleMovimientoCompra($id) AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento = Movimiento::find($id);
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();



        $habitacion = Habitacion::where('id', $movimiento->habitacion_id)->first();


        $habitacion->total = $resultado[0]->total;
        $habitacion->save();

        $datosRecuperados = [
            'total' => $total,
        ];

        return response()->json($datosRecuperados);
    }

    public function cantTotalMovComprado($id)
    {

        $query = "SELECT calcularTotalDetalleMovimientoCompra($id) AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento = Movimiento::find($id);
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();


        $datosRecuperados = [
            'total' => $total,
        ];

        return response()->json($datosRecuperados);
    }

    public function showId($id)
    {

        $movimiento = Detallemovimiento::find($id);
        $producto = Producto::find($movimiento->producto_id);

        $datosRecuperados = [
            'movimiento' => $movimiento,
            'producto' => $producto,
        ];

        return response()->json($datosRecuperados);
    }

    public function updateCantIdProd(Request $request, $id)
    {

        $Dmovimiento = Detallemovimiento::find($id);
        $cantTenia = $Dmovimiento->cantidad;
        $Dmovimiento->cantidad = $request->input('cantidadProductoEd');
        $Dmovimiento->comentario = $request->input('notaProductoE');
        $Dmovimiento->save();

        $producto = Producto::find($Dmovimiento->producto_id);
        $producto->stock = $producto->stock - $request->input('cantidadProductoEd') + $cantTenia;
        $producto->save();

        return response('Exito');
    }

    public function updateCantIdProdCompra(Request $request, $id)
    {
        $Dmovimiento = Detallemovimiento::find($id);
        $cantTenia = $Dmovimiento->cantidad;
        $Dmovimiento->cantidad = $request->input('cantidadProductoE');
        $Dmovimiento->comentario = $request->input('notaProductoE');
        $Dmovimiento->save();

        $producto = Producto::find($Dmovimiento->producto_id);

        if ($Dmovimiento->tipo != null) {
            if ($Dmovimiento->tipo == 'Ingreso') {
                $producto->stock = $producto->stock + $request->input('cantidadProductoE') - $cantTenia;
                $producto->save();
            } else {
                $producto->stock = $producto->stock - $request->input('cantidadProductoE') + $cantTenia;
                $producto->save();
            }
        } else {

            $producto->stock = $producto->stock + $request->input('cantidadProductoE') - $cantTenia;
            $producto->save();
        }

        return response('Exito');
    }

    public function actualizarDescuento(Request $request, $id)
    {

        $detalleMovimiento = Detallemovimiento::find($id);
        $detalleMovimiento->descuento = $request->input('descuento');
        $detalleMovimiento->save();

        $movimiento = Movimiento::find($detalleMovimiento->movimiento_id);

        $query = "SELECT calcularTotalDetalleMovimiento($movimiento->id) AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento->total = $resultado[0]->total;

        $movimiento->save();

    
        $habitacion = Habitacion::where('numero', $movimiento->habitacion_id)->first();


        $habitacion->total = $movimiento->total;
        $habitacion->save();


        $datosRecuperados = [
            'total' => $resultado[0]->total,
            'detalleMovimiento' => $detalleMovimiento,
        ];

        return response()->json($datosRecuperados);
    }

    public function showDetalleProductos($id)
    {

        return response()->json(DB::select('call showDetalleProductos(?)', [$id]));
    }

    public function getNumTipoDocumento($tipoDoc)
    {
        $tipoChart = '';
        switch ($tipoDoc) {
            case 1:
                $tipoChart = 'B';
                break;

            case 2:
                $tipoChart = 'F';
                break;
            case 5:
                $tipoChart = 'T';
                break;
        }
        $query = "SELECT obtenerSiguienteNumero('$tipoChart') AS num";
        $resultado = DB::select(DB::raw($query));
        $num = $resultado[0]->num;

        // if ($tipoDoc == 1) {
        //     $num = $num + 1;
        // }
        return $num;
    }

    public function showDetalleServicios($id)
    {
        return response()->json(DB::select('call showDetalleServicios(?)', [$id]));
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

    public function obtenerDocumentosVenta()
    {

        return response()->json(TipoDocumento::where('tipomovimiento_id', '2')->get());
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
    public function destroy($id)
    {
        $detalleMov = Detallemovimiento::findOrFail($id);
        $detalleMov->estado = 0; //eliminado pero se suma denuevo
        $detalleMov->save();
        $detalleMov->delete();

        if ($detalleMov->producto_id) {
            $producto = Producto::find($detalleMov->producto_id);
            $producto->stock = $producto->stock + $detalleMov->cantidad;
            $producto->save();
        }

        return response($id);
    }

    public function destroyCompra($id)
    {
        $detalleMov = Detallemovimiento::findOrFail($id);
        $detalleMov->estado = 0; //eliminado pero se resta del stok que se tiene
        $detalleMov->save();
        $detalleMov->delete();

        $producto = Producto::find($detalleMov->producto_id);
        $producto->stock = $producto->stock - $detalleMov->cantidad;
        $producto->save();

        return response($id);
    }
}
