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
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $tableDataJSON = $request->input('tableData');
        $tableData = json_decode($tableDataJSON, true);
        $movimiento = Movimiento::findOrFail($request->input('idMovimiento'));
        $habitacion = Habitacion::findOrFail($movimiento->habitacion_id);

        try {
            $resultado = DB::transaction(function () use ($tableData, $movimiento, $habitacion) {
                $agrupadoPorProducto = [];

                foreach ($tableData as $detalleMov) {
                    $productoId = $detalleMov['id'] ?? null;
                    if (!$productoId) {
                        $nombreSinEspacios = preg_replace('/\s+/', '', $detalleMov['nombre']);
                        $producto = Producto::where('estado', 1)->whereRaw("REPLACE(nombre, ' ', '') REGEXP '^" . $nombreSinEspacios . "$'")->first();
                    } else {
                        $producto = $this->obtenerProductoActivo((int) $productoId);
                    }

                    if (!$producto) {
                        throw new \RuntimeException('El producto ' . $detalleMov['nombre'] . ' está deshabilitado o no existe.');
                    }

                    $cantidad = (float) $detalleMov['cantidad'];
                    $origen = ($detalleMov['origen'] ?? 'habitacion') === 'general'
                        ? 'general'
                        : 'habitacion';

                    if ($origen === 'habitacion') {
                        $agrupadoPorProducto[$producto->id] = ($agrupadoPorProducto[$producto->id] ?? 0) + $cantidad;
                        continue;
                    }

                    if ((float) $producto->stock < $cantidad) {
                        throw new \RuntimeException('El producto ' . $producto->nombre . ' no tiene stock suficiente en el almacén general.');
                    }
                }

                foreach ($agrupadoPorProducto as $productoId => $cantidadTotal) {
                    $stockHabitacion = $this->obtenerStockHabitacionProducto((int) $productoId, (int) $habitacion->id);

                    if ($stockHabitacion < $cantidadTotal) {
                        $producto = Producto::find($productoId);
                        throw new \RuntimeException('El producto ' . $producto->nombre . ' no tiene stock suficiente en la habitación ' . $habitacion->numero . '.');
                    }
                }

                $ultimaDetalle = null;
                $ultimoProducto = null;

                foreach ($tableData as $detalleMov) {
                    $productoId = $detalleMov['id'] ?? null;
                    if ($productoId) {
                        $producto = $this->obtenerProductoActivo((int) $productoId);
                    } else {
                        $nombreSinEspacios = preg_replace('/\s+/', '', $detalleMov['nombre']);
                        $producto = Producto::where('estado', 1)->whereRaw("REPLACE(nombre, ' ', '') REGEXP '^" . $nombreSinEspacios . "$'")->first();
                    }

                    if (!$producto) {
                        throw new \RuntimeException('El producto ' . $detalleMov['nombre'] . ' está deshabilitado o no existe.');
                    }

                    $cantidad = (float) $detalleMov['cantidad'];
                    $origen = ($detalleMov['origen'] ?? 'habitacion') === 'general'
                        ? 'general'
                        : 'habitacion';

                    $detalleMovimiento = Detallemovimiento::create([
                        'movimiento_id' => $movimiento->id,
                        'cantidad' => $cantidad,
                        'precioventa' => $producto->precioventa,
                        'preciocompra' => $producto->preciocompra,
                        'descuento' => 0.00,
                        'motivos_doc_almacens_id' => 10,
                        'producto_id' => $producto->id,
                        'comentario' => $this->construirComentarioDetalle($origen),
                    ]);

                    if ($origen === 'general') {
                        $producto->stock = (float) $producto->stock - $cantidad;
                        $producto->save();
                    } else {
                        $this->decrementarStockHabitacion($producto->id, $habitacion->id, $cantidad);
                    }

                    $ultimaDetalle = $detalleMovimiento;
                    $ultimoProducto = $producto;
                }

                return [
                    'detalleMovimiento' => $ultimaDetalle,
                    'servicio' => $ultimoProducto,
                ];
            });

            return response()->json($resultado);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => [
                    'cantidadProducto' => [$e->getMessage()],
                ],
            ], 422);
        }
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
        }

        $servicio = Servicio::find($request->input('servicios'));
        $detalleMovimiento = Detallemovimiento::create([
            'movimiento_id' => $request->input('idMovimiento'),
            'cantidad' => $request->input('cantidadServicio'),
            'descuento' => 0.00,
            'precioventa' => $request->input('precioServicio'),
            'servicio_id' => $request->input('servicios'),
            'comentario' => $request->input('comentarioServicio'),
        ]);

        return response()->json([
            'detalleMovimiento' => $detalleMovimiento,
            'servicio' => $servicio,
        ]);
    }

    public function show($id)
    {
        $query = "SELECT calcularTotalDetalleMovimiento($id) AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento = Movimiento::find($id);
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();

        $habitacion = Habitacion::where('id', $movimiento->habitacion_id)->first();
        $habitacion->total = $resultado[0]->total;
        $habitacion->save();

        return response()->json([
            'total' => $total,
        ]);
    }

    public function showDocAlmacen($id)
    {
        $query = "SELECT calcularTotalDetalleMovimientoDocAlmacen($id) AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento = Movimiento::find($id);
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();

        return response()->json([
            'total' => $total,
        ]);
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

        return response()->json([
            'total' => $total,
        ]);
    }

    public function cantTotalMovComprado($id)
    {
        $query = "SELECT calcularTotalDetalleMovimientoCompra($id) AS total";
        $resultado = DB::select(DB::raw($query));
        $total = $resultado[0]->total;

        $movimiento = Movimiento::find($id);
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();

        return response()->json([
            'total' => $total,
        ]);
    }

    public function showId($id)
    {
        $movimiento = Detallemovimiento::find($id);
        $producto = Producto::find($movimiento->producto_id);
        $movimientoPadre = Movimiento::find($movimiento->movimiento_id);
        $stockHabitacionDisponible = 0;

        if ($movimientoPadre && $movimientoPadre->habitacion_id) {
            $stockHabitacionDisponible = $this->obtenerStockHabitacionProducto($producto->id, (int) $movimientoPadre->habitacion_id);
        }

        return response()->json([
            'movimiento' => $movimiento,
            'producto' => $producto,
            'stockHabitacionDisponible' => $stockHabitacionDisponible,
        ]);
    }

    public function updateCantIdProd(Request $request, $id)
    {
        $Dmovimiento = Detallemovimiento::findOrFail($id);
        $movimiento = Movimiento::findOrFail($Dmovimiento->movimiento_id);
        $habitacion = Habitacion::findOrFail($movimiento->habitacion_id);
        $cantTenia = (float) $Dmovimiento->cantidad;
        $nuevaCantidad = (float) $request->input('cantidadProductoEd');
        $delta = $nuevaCantidad - $cantTenia;
        $producto = Producto::findOrFail($Dmovimiento->producto_id);
        $origen = $this->obtenerOrigenDetalle($Dmovimiento->comentario);

        if ($delta > 0) {
            $stockDisponible = $origen === 'general'
                ? (float) $producto->stock
                : $this->obtenerStockHabitacionProducto($producto->id, $habitacion->id);

            if ($stockDisponible < $delta) {
                $mensaje = $origen === 'general'
                    ? 'No existe stock suficiente en el almacén general.'
                    : 'No existe stock suficiente en la habitación.';

                return response()->json([
                    'message' => $mensaje,
                    'errors' => [
                        'cantidadProductoEd' => [$mensaje],
                    ],
                ], 422);
            }
        }

        DB::transaction(function () use ($Dmovimiento, $request, $delta, $producto, $habitacion, $origen) {
            $nuevaCantidad = (float) $request->input('cantidadProductoEd');

            if ($delta > 0) {
                if ($origen === 'general') {
                    $producto->stock = (float) $producto->stock - $delta;
                    $producto->save();
                } else {
                    $this->decrementarStockHabitacion($producto->id, $habitacion->id, $delta);
                }
            } elseif ($delta < 0) {
                if ($origen === 'general') {
                    $producto->stock = (float) $producto->stock + abs($delta);
                    $producto->save();
                } else {
                    $this->incrementarStockHabitacion($producto->id, $habitacion->id, abs($delta));
                }
            }

            $Dmovimiento->cantidad = $nuevaCantidad;
            $comentarioVisible = $request->filled('notaProductoE') && $request->input('notaProductoE') !== '-'
                ? $request->input('notaProductoE')
                : $this->limpiarComentarioDetalle($Dmovimiento->comentario);
            $Dmovimiento->comentario = $this->construirComentarioDetalle($origen, $comentarioVisible);
            $Dmovimiento->save();
        });

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
            } else {
                $producto->stock = $producto->stock - $request->input('cantidadProductoE') + $cantTenia;
            }
        } else {
            $producto->stock = $producto->stock + $request->input('cantidadProductoE') - $cantTenia;
        }

        $producto->save();

        return response('Exito');
    }

    public function actualizarDescuento(Request $request, $id)
    {
        $request->validate([
            'descuento' => 'nullable|numeric|min:0|max:100',
        ]);

        $detalleMovimiento = Detallemovimiento::findOrFail($id);
        $detalleMovimiento->descuento = (float) $request->input('descuento', 0);
        $detalleMovimiento->save();

        $movimiento = Movimiento::findOrFail($detalleMovimiento->movimiento_id);

        $query = "SELECT calcularTotalDetalleMovimiento($movimiento->id) AS total";
        $resultado = DB::select(DB::raw($query));
        $movimiento->total = $resultado[0]->total;
        $movimiento->save();

        $habitacion = Habitacion::find($movimiento->habitacion_id);
        if ($habitacion) {
            $habitacion->total = $movimiento->total;
            $habitacion->save();
        }

        return response()->json([
            'total' => $resultado[0]->total,
            'detalleMovimiento' => $detalleMovimiento,
        ]);
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

        return $resultado[0]->num;
    }

    public function showDetalleServicios($id)
    {
        return response()->json(DB::select('call showDetalleServicios(?)', [$id]));
    }

    public function edit($id)
    {
        //
    }

    public function obtenerDocumentosVenta()
    {
        return response()->json(TipoDocumento::where('tipomovimiento_id', '2')->get());
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $detalleMov = Detallemovimiento::findOrFail($id);
        $movimiento = Movimiento::findOrFail($detalleMov->movimiento_id);
        $habitacion = Habitacion::findOrFail($movimiento->habitacion_id);
        $origen = $this->obtenerOrigenDetalle($detalleMov->comentario);

        DB::transaction(function () use ($detalleMov, $habitacion, $origen) {
            if ($detalleMov->producto_id) {
                if ($origen === 'general') {
                    $producto = Producto::find($detalleMov->producto_id);
                    if ($producto) {
                        $producto->stock = (float) $producto->stock + (float) $detalleMov->cantidad;
                        $producto->save();
                    }
                } else {
                    $this->incrementarStockHabitacion($detalleMov->producto_id, $habitacion->id, (float) $detalleMov->cantidad);
                }
            }

            $detalleMov->estado = 0;
            $detalleMov->save();
            $detalleMov->delete();
        });

        return response($id);
    }

    public function destroyCompra($id)
    {
        $detalleMov = Detallemovimiento::findOrFail($id);
        $detalleMov->estado = 0;
        $detalleMov->save();
        $detalleMov->delete();

        $producto = Producto::find($detalleMov->producto_id);
        $producto->stock = $producto->stock - $detalleMov->cantidad;
        $producto->save();

        return response($id);
    }
}
