<?php

namespace App\Http\Controllers;

use App\Models\Detallemovimiento;
use App\Models\Habitacion;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ventaHabitacionController extends Controller
{
    public const idVista = 121;

    public function __construct()
    {
        $this->middleware('auth');
        $permiso = Permission::find(self::idVista);
        $this->middleware('permission:' . $permiso->name, ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $this->MenuDinamico(Auth::user()->id);
        $permisosCrear = Permission::where('padreCrud', self::idVista)->get()[0];
        $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
        $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
        $modoModal = $request->boolean('modoModal');

        $numFil = '';
        if ($request->input('id')) {
            $numFil = $request->input('id');
        }

        $vista = $modoModal ? 'Modulos.AgregarVenta.modal' : 'Modulos.AgregarVenta.index';
        $initialData = $this->buildVentaContext((int) $numFil);

        return view($vista, compact('permisosCrear', 'permisosEditar', 'permisosEliminar', 'numFil', 'modoModal', 'initialData'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $numHabitacion = (int) $request->input('numHabitacion');
        $habitacion = Habitacion::where('numero', $numHabitacion)->first();

        if (!$habitacion) {
            return response()->json([
                'message' => 'La habitacion no existe.',
            ], 422);
        }

        $result = DB::select('call movAtencionxNumHabitacion(?)', [$numHabitacion]);
        if (isset($result[0]) && isset($result[0]->id)) {
            return response()->json([
                'message' => 'La habitacion ya tiene un check-in activo.',
                'movimiento' => $result[0],
            ], 409);
        }

        $servicio = $this->obtenerServicioDefaultPorHabitacion($habitacion);
        if (!$servicio) {
            return response()->json([
                'message' => 'No se encontro el servicio por defecto para la habitacion ' . $habitacion->numero . '.',
            ], 422);
        }

        $movimiento = Movimiento::create([
            'fechaingreso' => Carbon::now()->format('Y-m-d H:i:s'),
            'persona_id' => $request->input('clientes'),
            'usuario_id' => Auth::user()->id,
            'habitacion_id' => $habitacion->id,
        ]);

        $detalleMovimiento = Detallemovimiento::create([
            'movimiento_id' => $movimiento->id,
            'cantidad' => '1',
            'precioventa' => $servicio->precioventa,
            'servicio_id' => $servicio->id,
            'comentario' => 'Default',
        ]);

        $query = "SELECT calcularTotalDetalleMovimiento($movimiento->id) AS total";
        $resultado = DB::select(DB::raw($query));
        $movimiento->total = $resultado[0]->total;

        $habitacion->horaInicio = $detalleMovimiento->created_at;
        $habitacion->situacion = "Ocupada";
        $habitacion->idUltimoMovimiento = $movimiento->id;
        $habitacion->total = $movimiento->total;

        $habitacion->save();
        $movimiento->numero = "M003-" . str_pad($movimiento->id, 8, "0", STR_PAD_LEFT);
        $movimiento->save();

        return response()->json($movimiento);
    }

    public function show($id)
    {
        $clientes = DB::select('call showPersonasClientes');
        return response()->json($clientes);
    }

    public function showProveedores()
    {
        $proveedores = DB::select('call showProveedores');

        return response()->json($proveedores);
    }

    public function contexto($num)
    {
        return response()->json($this->buildVentaContext((int) $num));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $habitacionActual = Habitacion::where('numero', $request->input('numHabActual'))->first();
        $habitacionNueva = Habitacion::where('numero', $request->input('numHabNueva'))->first();

        $movimiento = Movimiento::findOrFail($request->input('idMovimiento'));

        $movimiento->habitacion_id = $habitacionNueva->id;
        $detalleServicioDefault = Detallemovimiento::where('movimiento_id', $movimiento->id)
            ->orderBy('id')
            ->first();

        $servicioDefault = $this->obtenerServicioDefaultPorHabitacion($habitacionNueva);
        if ($detalleServicioDefault && $servicioDefault) {
            $detalleServicioDefault->servicio_id = $servicioDefault->id;
            $detalleServicioDefault->precioventa = $servicioDefault->precioventa;
            $detalleServicioDefault->save();
        }

        $query = "SELECT calcularTotalDetalleMovimiento($movimiento->id) AS total";
        $resultado = DB::select(DB::raw($query));
        $movimiento->total = $resultado[0]->total;

        $movimiento->save();
        $habitacionActual->situacion = "Disponible";
        $habitacionNueva->horaInicio = $habitacionActual->horaInicio;
        $habitacionNueva->total = $movimiento->total;
        $habitacionNueva->idUltimoMovimiento = $movimiento->id;

        $habitacionActual->horaInicio = null;
        $habitacionActual->total = 0.00;
        $habitacionActual->idUltimoMovimiento = null;

        $habitacionNueva->situacion = "Ocupada";

        $habitacionActual->save();
        $habitacionNueva->save();

        return response($habitacionNueva->numero);
    }

    public function destroy($id)
    {
        //
    }

    private function buildVentaContext(int $numeroHabitacion): array
    {
        $habitacion = Habitacion::where('numero', $numeroHabitacion)->first();

        if (!$habitacion) {
            return [
                'habitacion' => null,
                'movimiento' => null,
                'clientes' => [],
                'productosCatalogo' => [],
                'serviciosCatalogo' => [],
                'productosDetalle' => [],
                'serviciosDetalle' => [],
                'total' => 0,
            ];
        }

        $movimientoActivo = DB::select('call movAtencionxNumHabitacion(?)', [$numeroHabitacion]);
        $movimiento = $movimientoActivo[0] ?? null;

        $productosCatalogo = Producto::where('estado', 1)
            ->orderBy('nombre')
            ->get()
            ->map(function ($producto) use ($habitacion) {
                $producto->stock_habitacion = $this->obtenerStockHabitacionProducto((int) $producto->id, (int) $habitacion->id);
                $producto->stock_total = (float) $producto->stock + (float) $producto->stock_habitacion;
                return $producto;
            })
            ->values();

        $serviciosCatalogo = Servicio::where('estado', 1)
            ->orderBy('nombre')
            ->get()
            ->values();

        $clientes = DB::select('call showPersonasClientes');

        $productosDetalle = [];
        $serviciosDetalle = [];
        $total = (float) ($habitacion->total ?? 0);

        if ($movimiento && isset($movimiento->id)) {
            $productosDetalle = collect(DB::select('call showDetalleProductos(?)', [$movimiento->id]))
                ->map(function ($item) {
                    $item->origen = $this->obtenerOrigenDetalle($item->comentario ?? '');
                    $item->comentario = $this->limpiarComentarioDetalle($item->comentario ?? '');
                    return $item;
                })
                ->values()
                ->all();
            $serviciosDetalle = DB::select('call showDetalleServicios(?)', [$movimiento->id]);
            $resultadoTotal = DB::select(
                DB::raw("SELECT calcularTotalDetalleMovimiento(?) AS total"),
                [$movimiento->id]
            );
            $total = (float) ($resultadoTotal[0]->total ?? $movimiento->total ?? $total);

            Movimiento::where('id', $movimiento->id)->update(['total' => $total]);
            Habitacion::where('id', $habitacion->id)->update(['total' => $total]);
            $movimiento->total = $total;
            $habitacion->total = $total;
        }

        return [
            'habitacion' => $habitacion,
            'movimiento' => $movimiento,
            'clientes' => $clientes,
            'productosCatalogo' => $productosCatalogo,
            'serviciosCatalogo' => $serviciosCatalogo,
            'productosDetalle' => $productosDetalle,
            'serviciosDetalle' => $serviciosDetalle,
            'total' => $total,
        ];
    }

    private function obtenerServicioDefaultPorHabitacion(Habitacion $habitacion): ?Servicio
    {
        $tipo = strtolower(trim((string) $habitacion->tipo));

        if ($tipo === 'vip') {
            return Servicio::find(4);
        }

        if ($tipo === 'normal') {
            return Servicio::find(1);
        }

        return Servicio::find(21);
    }
}
