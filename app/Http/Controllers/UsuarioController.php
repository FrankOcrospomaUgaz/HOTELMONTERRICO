<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Rol;
use App\Models\Rol_persona;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public const idVista = 1;

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
                $persona = DB::select('call showUsersPersonas');
                $personasFiltrados = array_filter($persona, function ($Person) {
                    $fechaUser = strtotime($Person->created_at); // Convertir la fecha del User en UNIX timestamp
                    return $Person->estadoUsuario == 0; // Comprobar si la fecha del User está dentro del rango especificado
                });
            } elseif ($estado == 'activos') {
                $persona = DB::select('call showUsersPersonas');

                $personasFiltrados = array_filter($persona, function ($Person) {
                    $fechaUser = strtotime($Person->created_at); // Convertir la fecha del User en UNIX timestamp
                    return $Person->estadoUsuario > 0; // Comprobar si la fecha del User está dentro del rango especificado
                });
            } else if ($estado == 'todos') {
                $personasFiltrados = DB::select('call showUsersPersonas');
            } else {
                $personasFiltrados = DB::select('call showUsersPersonas');
            }

            return datatables($personasFiltrados)
                ->addColumn('action', function ($persona) {
                    $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
                    if (Gate::allows($permisosEditar->name)) {
                        if (!in_array($persona->id, [1, 17])) {
                            $acciones = '<a href="javascript:void(0)" onclick="editarUsuario(' . $persona->id . ')" style="background:#ffc107;margin:2px; color:white;" class="btn btn-info"> <i class="fas fa-edit"></i></a>';

                        } else {
                            $acciones = '';
                        }
                    } else {
                        $acciones = '';
                    }

                    return $acciones;
                })->addColumn('estado', function ($persona) {
                $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
                if (Gate::allows($permisosEliminar->name)) {
                    if (!in_array($persona->id, [1, 17])) {

                        $estadoR = '<div class="custom-control custom-switch">' .
                        '<input type="checkbox" class="custom-control-input form-check-input switch-estado" id="switch-' .
                        $persona->id .
                        '" data-id="' .
                        $persona->id .
                        '" ' .
                        ($persona->estado == 1 ? "checked" : "") .
                        ">" .
                        '<label class="custom-control-label form-check-label" for="switch-' .
                        $persona->id .
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

        return view('Modulos.Usuario.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('estado', 1)->get();

        $datosUsuarios = [
            'roles' => $roles,
        ];

        return response()->json($datosUsuarios);
    }

    public function buscarDNI($dni)
    {

        $respuesta = array();
        $client = new Client();
        $res = $client->get('http://facturae-garzasoft.com/facturacion/buscaCliente/BuscaCliente2.php?' . 'dni=' . $dni . '&fe=N&token=qusEj_w7aHEpX');
        if ($res->getStatusCode() == 200) { // 200 OK
            $response_data = $res->getBody()->getContents();
            $respuesta = json_decode($response_data);
        }
        return response()->json($respuesta);
    }

    public function buscarRUC($ruc)
    {
        $respuesta = array();

        $client = new Client([
            'verify' => false,
        ]);
        $res = $client->get('https://comprobante-e.com/facturacion/buscaCliente/BuscaClienteRuc.php?fe=N&token=qusEj_w7aHEpX&' . 'ruc=' . $ruc);
        if ($res->getStatusCode() == 200) { // 200 OK
            $response_data = $res->getBody()->getContents();
            $respuesta = json_decode($response_data);
        }
        return response()->json($respuesta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $valoresSeleccionados = $request->input('roles', []);
        $banderaUsuario = 0;
        if ($valoresSeleccionados) {
            if ($valoresSeleccionados[0] == '1') {
                $banderaUsuario = 1;
            }
        }
        $validator = null;

        $rules = [];
        $messages = [];

        if ($banderaUsuario == 1) {
            $rules = array_merge($rules, [
                'nombreUsuario' => 'required|unique:users,username',
            ]);
            $messages = array_merge($messages, [
                'nombreUsuario.required' => 'El campo USERNAME es obligatorio',
                'nombreUsuario.unique' => 'El USERNAME ya está en uso por otro usuario.',
            ]);
        }

        if ($request->input('selectDNI-RUC') == 'DNI') {
            $rules = array_merge($rules, [
                'dni' => 'required|unique:personas,dni',
            ]);
            $messages = array_merge($messages, [
                'dni.required' => 'El campo DNI es obligatorio',
                'dni.unique' => 'El DNI ya está en uso por otro usuario.',
            ]);
        } else {
            $rules = array_merge($rules, [
                'dni' => 'required|unique:personas,ruc',
            ]);
            $messages = array_merge($messages, [
                'dni.required' => 'El campo RUC es obligatorio',
                'dni.unique' => 'El RUC ya está en uso por otro usuario.',
            ]);
        }
        if ($request->input('telefono')) {
            $rules = array_merge($rules, [
                'telefono' => 'numeric|digits:9',
            ]);
            $messages = array_merge($messages, [
                'telefono.numeric' => 'El teléfono debe contener sólo números',
                'telefono.digits' => 'El teléfono debe tener 9 dígitos',
            ]);
        }

        if ($request->input('email')) {
            $rules = array_merge($rules, [
                'email' => 'email|unique:personas,email',
            ]);
            $messages = array_merge($messages, [
                'email.required' => 'El campo email es obligatorio',
                'email.unique' => 'El email ya está en uso por otro usuario.',
                'email.email' => 'El correo electrónico debe ser una dirección de correo electrónico válida.',
            ]);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            if ($request->input('selectDNI-RUC') == 'DNI') {
                //PERSONA - DNI
                $persona = Persona::create([
                    'nombres' => $request->input('nombre'),
                    'apellidopaterno' => $request->input('apellPaterno'),
                    'apellidomaterno' => $request->input('apellMaterno'),
                    'dni' => $request->input('dni'),
                    'telefono' => $request->input('telefono'),
                    'email' => $request->input('email'),
                ]);
            } else {
                //PERSONA - RUC
                $persona = Persona::create([
                    'razonsocial' => $request->input('razonsocial'),
                    'direccion' => $request->input('direccion'),
                    'ruc' => $request->input('dni'),
                    'telefono' => $request->input('telefono'),
                    'email' => $request->input('email'),
                ]);
            }

            //ROLES

            foreach ($valoresSeleccionados as $rol) {
                rol_persona::create([
                    'rol_id' => $rol,
                    'persona_id' => $persona->id,
                ]);
            }

            //USUARIO
            if ($banderaUsuario == 1) {
                $nombreUsuarioPassword = Hash::make($request->input('nombreUsuario'));
                $Person = User::create([
                    'username' => $request->input('nombreUsuario'),
                    'name' => $request->input('nombre'),
                    'password' => $nombreUsuarioPassword,
                    'persona_id' => $persona->id,
                    'tipoUsuario' => $request->input('tipoUsuario'),
                ]);
                $Person->assignRole($request->input('tipoUsuario'));
            }

            return response($persona);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ROL)
    {
        $proveedores = DB::select('call ObtenerPersonasPorRol(?)', array($ROL));
        $movimientosCompraCount = DB::select("SELECT COUNT(*) AS count FROM movimientos ");
        $user = User::where('id', Auth::user()->id)->first();
        $datosUsuarios = [
            'responsable' => Persona::find($user->persona_id),
            'fecha' => Carbon::now()->format('Y F d, h:i A'),
            'proveedores' => $proveedores,
            'formatoNumeroCompra' => "M003-" . str_pad(($movimientosCompraCount[0]->count + 1), 8, "0", STR_PAD_LEFT),
        ];

        return response()->json($datosUsuarios);
    }

    public function showId($id)
    {

        $persona = Persona::find($id);
        if ($persona->nombres != 'VARIOS') {
            $nombrePersona = $persona->dni == null ? $persona->razonsocial : $persona->nombres . " " . $persona->apellidopaterno;

        } else {
            $nombrePersona = 'VARIOS';
        }

        $datosUsuarios = [
            'persona' => $nombrePersona,
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

        $persona = Persona::where('id', $id)->first();

        $idPersona = $id;
        $tipoUsuarios = Role::where('estado', 1)->orWhere('id', $idPersona)->get();

        $misRolesPersona = Rol_persona::get()->where('persona_id', $idPersona);
        $RolesPersona = Rol::where('estado', 1)->orWhere('id', $idPersona)->get();

        $user = User::where('persona_id', $id)->first();

        if ($user) {
            $tipoUser = $user->tipoUsuario;
            $nameUser = $user->username;
        } else {
            $tipoUser = 'null';
            $nameUser = '';
        }

        $datosUsuarios = [
            'persona' => $persona,
            'tipoUsuarios' => $tipoUsuarios,
            'miTipoUsuario' => $tipoUser,
            'misRolesPersona' => $misRolesPersona,
            'RolesPersona' => $RolesPersona,
            'nameUser' => $nameUser,
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
        $user = User::where('persona_id', $id)->first();
        $valoresSeleccionados = $request->input('rolesE', []);
        $banderaUsuario = 0;
        if ($valoresSeleccionados) {
            if ($valoresSeleccionados[0] == '1') {
                $banderaUsuario = 1; //PRESIONADO USUARIO
            }
        }
        $validator = null;
        $rules = [];
        $messages = [];
        if ($user) {

            if ($banderaUsuario == 1) {
                $rules = array_merge($rules, [
                    'nombreUsuarioE' => 'required|unique:users,username,' . $user->id,
                ]);
                $messages = array_merge($messages, [
                    'nombreUsuarioE.required' => 'El campo USERNAME es obligatorio',
                    'nombreUsuarioE.unique' => 'El USERNAME ya está en uso por otro usuario.',
                ]);
            }
        }

        if ($request->input('selectDNI-RUC-E') == 'DNI') {
            $rules = array_merge($rules, [
                'dniE' => 'required|numeric|digits:8|unique:personas,dni,' . $id,
            ]);
            $messages = array_merge($messages, [
                'dniE.digits' => 'El DNI debe tener 8 dígitos.',
                'dniE.unique' => 'El DNI ya está en uso por otro usuario.',
                'dniE.numeric' => 'El DNI debe ser un número.',
            ]);
        } else {
            $rules = array_merge($rules, [
                'dniE' => 'required|unique:personas,ruc,' . $id,
            ]);
            $messages = array_merge($messages, [
                'dniE.required' => 'El campo RUC es obligatorio',
                'dniE.unique' => 'El RUC ya está en uso por otro usuario.',
            ]);
        }

        if ($request->input('telefonoE')) {
            $rules = array_merge($rules, [
                'telefonoE' => 'numeric|digits:9',
            ]);
            $messages = array_merge($messages, [
                'telefonoE.numeric' => 'El teléfono debe contener sólo números',
                'telefonoE.digits' => 'El teléfono debe tener 9 dígitos',
            ]);
        }

        if ($request->input('emailE')) {
            $rules = array_merge($rules, [
                'emailE' => 'email|unique:personas,email,' . $id,
            ]);
            $messages = array_merge($messages, [
                'emailE.required' => 'El campo email es obligatorio',
                'emailE.unique' => 'El email ya está en uso por otro usuario.',
                'emailE.email' => 'El correo electrónico debe ser una dirección de correo electrónico válida.',
            ]);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $persona = Persona::where('id', $id)->first();
            if ($request->input('selectDNI-RUC-E') == 'DNI') {
                $persona->nombres = $request->input('nombreE');
                $persona->apellidopaterno = $request->input('apellPaternoE');
                $persona->apellidomaterno = $request->input('apellMaternoE');
                $persona->dni = $request->input('dniE');
                if ($user) {
                    $user->name = $request->input('nombreE');
                    $user->save();
                }
            } else {
                //PERSONA - RUC
                $persona->razonsocial = $request->input('razonsocialE');
                $persona->direccion = $request->input('direccionE');
                $persona->ruc = $request->input('dniE');
                if ($user) {
                    $user->name = $request->input('razonsocialE');
                    $user->save();
                }
            }

            $persona->telefono = $request->input('telefonoE');
            $persona->email = $request->input('emailE');
            $persona->save();

            //ROLES
            if ($rolesPersonaAntiguo = Rol_persona::where('persona_id', $id)->get()) {;
                foreach ($rolesPersonaAntiguo as $rol) {
                    $rol->delete();
                }
            }

            foreach ($valoresSeleccionados as $rol) {
                rol_persona::create([
                    'rol_id' => $rol,
                    'persona_id' => $id,
                ]);
            }

            //USUARIO
            if ($banderaUsuario == 1) {
                if ($user) {
                    if ($user->estado == 0) {
                        $user->estado = 1;
                    }
                    $input = $request->all();
                    if (!empty($input['password'])) {
                        $user->password = Hash::make($input['password']);
                    } else {
                        $input = Arr::except($input, array('password'));
                    }
                    $user->assignRole($request->input('tipoUsuarioE'));
                    $user->username = $request->input('nombreUsuarioE');
                    $user->tipoUsuario = $request->input('tipoUsuarioE');
                    $user->save();
                    DB::table('model_has_roles')->where('model_id', $id)->delete();
                    $user->assignRole($request->input('tipoUsuarioE'));
                    $user->save();
                } else {
                    $nombreUsuarioPassword = Hash::make($request->input('nombreUsuarioE'));
                    $User = User::create([
                        'username' => $request->input('nombreUsuarioE'),
                        'password' => $nombreUsuarioPassword,
                        'persona_id' => $id,
                        'tipoUsuario' => $request->input('tipoUsuarioE'),
                    ]);
                    if ($request->input('selectDNI-RUC-E') == 'DNI') {
                        $User->name = $request->input('nombreE');
                        $User->save();
                    } else {
                        $User->name = $request->input('razonsocialE');
                        $User->save();
                    }

                    $User->assignRole($request->input('tipoUsuarioE'));
                }
            } else {

                if ($user) {
                    $user = User::where('persona_id', $id)->first();
                    $user->estado = 0;
                    $user->save();
                }
            }
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
        $persona = Persona::find($id);
        $cadena = ($persona->estado == 0) ? 'Activo' : 'Inactivo';
        $persona->estado = ($persona->estado == 0) ? 1 : 0;
        $persona->save();
        return response($cadena);
    }
}
