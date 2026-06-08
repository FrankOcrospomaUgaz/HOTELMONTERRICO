<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Movimiento;
use App\Models\Persona;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Spatie\Permission\Models\Permission;

class detalleHabitacionController extends Controller
{
    public const idVista = 137;

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

        $numFil = '';
        if ($request->input('id')) {
            $numFil = $request->input('id');
        }

        return view('Modulos.BuscarDetalle.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar', 'numFil'));
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
    public function obtenerClientes()
    {
        $persona = Persona::get();
        return response()->json($persona);
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

    //DETALLE VENTA DEL MODAL CARRITO
    public function show($id)
    {

        $movCajaPagoCliente = Movimiento::withTrashed()->find($id);
        $MovimientoPadre = Movimiento::withTrashed()->find($movCajaPagoCliente->movimiento_id);
        $detalleVentas = DB::select('call detalleMovimientosCarrito(?)', [$movCajaPagoCliente->movimiento_id]);
        $tipoDocumento = TipoDocumento::find($MovimientoPadre->tipodocumento_id);
        $persona = Persona::find($movCajaPagoCliente->persona_id);
        $responsable = Persona::find(User::find($movCajaPagoCliente->usuario_id)->persona_id);
        $numHab = Habitacion::find($MovimientoPadre->habitacion_id)->numero;
        $total = 0;
        foreach ($detalleVentas as $detalle) {
            $total += $detalle->total;
        }

        $datosUsuarios = [
            'detalleVentas' => $detalleVentas,
            'total' => $total,
            'movCajaPagoCliente' => $movCajaPagoCliente,
            'MovimientoPadre' => $MovimientoPadre,
            'tipoDocumento' => $tipoDocumento->nombre,
            'persona' => $persona->nombres != null ? $persona->nombres : $persona->razonsocial,
            'responsable' => $responsable->nombres,
            'numHab' => $numHab,
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
    public function destroy($id)
    {
        //
    }
}
