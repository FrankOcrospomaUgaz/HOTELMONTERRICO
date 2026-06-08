<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\conceptoPago;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class conceptoPagoController extends Controller
{
    public const idVista = 149;

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
                $conceptoPago = conceptoPago::where('estado', 0)
                    ->get();
            } else if ($estado == 'activos') {
                $conceptoPago = conceptoPago::where('estado', 1)
                    ->get();
            } else if ($estado == 'todos') {
                $conceptoPago  =  conceptoPago::get();
            } else {
                $conceptoPago  =  conceptoPago::get();
            }
            return datatables($conceptoPago)
                ->addColumn('action', function ($concepto) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        $acciones = '<a href="javascript:void(0)" onclick="editarConceptoPagos(' . $concepto->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($concepto) {
                    $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                    if (Gate::allows($permisosEliminar->name)) {
                        $estadoR = '<div class="custom-control custom-switch">' .
                            '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                            $concepto->id .
                            '" data-id="' .
                            $concepto->id .
                            '" ' .
                            ($concepto->estado == 1 ? "checked" : "") .
                            ">" .
                            '<label class="custom-control-label form-check-label" for="switch-' .
                            $concepto->id .
                            '"></label>' .
                            "</div>";
                    } else {
                        $estadoR = '';
                    }

                    return $estadoR;
                })->rawColumns(['action', 'estado'])->make(true);
        } else {
        }
        return view('Modulos.ConceptoPago.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    
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
                'nombreConceptoPago' => 'required|unique:concepto_pagos,nombre',
            ],
            [
                'nombreConceptoPago.required' => 'El campo Nombre es obligatorio',
                'nombreConceptoPago.unique' => 'Este Nombre ya está en uso.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            conceptoPago::create([
                'nombre' => $request->input('nombreConceptoPago'),
                'tipo' => $request->input('tipo'),
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
        $conceptoPago = conceptoPago::where('id', $id)->first();
        return response()->json($conceptoPago);
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
                'nombreConceptoPagoE' => 'required|unique:concepto_pagos,nombre,' . $id,
            ],
            [
                'nombreConceptoPagoE.required' => 'El campo Nombre es obligatorio',
                'nombreConceptoPagoE.unique' => 'Este Nombre ya está en uso.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $concepto = conceptoPago::find($id);
            $concepto->nombre = $request->input('nombreConceptoPagoE');
            $concepto->tipo = $request->input('tipoE');
            $concepto->save();
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
        $conceptoPago = conceptoPago::find($id);
        $cadena = ($conceptoPago->estado == 0) ? 'Activo' : 'Inactivo';
        $conceptoPago->estado = ($conceptoPago->estado == 0) ? 1 : 0;
        $conceptoPago->save();
        return response($cadena);
    }
}
