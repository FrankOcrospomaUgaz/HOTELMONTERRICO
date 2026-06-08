<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class userConfigController extends Controller
{
    public const idVista = 89;

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
        $permisosCrear = Permission::where('padreCrud', self::idVista)->get()[0];
        $permisosEditar = Permission::where('padreCrud', self::idVista)->get()[1];
        $permisosEliminar = Permission::where('padreCrud', self::idVista)->get()[2];
        $this->MenuDinamico(Auth::user()->id);

        if ($request->ajax()) {
            $Usuario = DB::select('call showUserId(?)', array(Auth::user()->id));

            return response()->json($Usuario[0]);
        }

        return view('Modulos.Configuracion.index', compact('permisosCrear', 'permisosEditar', 'permisosEliminar'));
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
        $user = User::where('persona_id', $id)->first();
        $validator = Validator::make($request->all(), [
            'nombresE' => 'required',
            'apPaternoE' => 'required',
            'apMaternoE' => 'required',
            'dniE' => 'required|numeric|digits:8|unique:personas,dni,' . $id,
            'nombreUsuarioE' => 'required|unique:users,username,' . $user->id,


        ], [
            'nombresE.required' => 'El campo Nombre es obligatorio',
            'apPaternoE.required' => 'El campo Apellido Paterno es obligatorio',
            'apMaternoE.required' => 'El campo Apellido Materno es obligatorio',
            'dniE.required' => 'El campo DNI es obligatorio',
            'telefonoE.required' => 'El campo telefono es obligatorio',

            // 'correoE.required' => 'El campo email es obligatorio',
            // 'correoE.unique' => 'El email ya está en uso por otro usuario.',
            // 'correoE.email' => 'El correo electrónico debe ser una dirección de correo electrónico válida.',

            'nombreUsuarioE.required' => 'El campo Nombre Usuario es obligatorio',
            'nombreUsuarioE.unique' => 'El Nombre Usuario ya está en uso por otro usuario.',

            'dniE.digits' => 'El DNI debe tener 8 dígitos.',
            'dniE.unique' => 'El DNI ya está en uso por otro usuario.',
            'dniE.numeric' => 'El DNI debe ser un número.',

            // 'telefonoE.numeric' => 'El teléfono debe contener sólo números',
            // 'telefonoE.digits' => 'El teléfono debe tener 9 dígitos',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $persona = Persona::where('id', $id)->first();

            $persona->nombres = $request->input('nombresE');
            $persona->apellidopaterno = $request->input('apPaternoE');
            $persona->apellidomaterno = $request->input('apMaternoE');
            $persona->dni = $request->input('dniE');

            $user = User::where('persona_id', $id)->first();
            $user->username = $request->input('nombreUsuarioE');
            $persona->email = $request->input('correoE');
            $persona->telefono = $request->input('telefonoE');

            $persona->save();

            $user->name = $request->input('nombresE');
            $user->email = $request->input('correoE');
            $user->save();

            $Usuario = DB::select('call showUserId(?)', array(Auth::user()->id));
            // $datosUsuarios = [
            //     'Usuario' => $Usuario[0],
            // ];
            return response()->json($Usuario[0]);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        $currentPassword = $user->password;

        $validator = Validator::make($request->all(), [
            'anteriorContraseña' => ['required', function ($attribute, $value, $fail) use ($currentPassword) {
                if (!Hash::check($value, $currentPassword)) {
                    $fail('La contraseña anterior es incorrecta.');
                }
            }],
            'password-confirm' => 'required|same:nuevaContraseña',
            // 'nuevaContraseña' => 'required|min:8',
        ], [
            'nuevaContraseña.required' => 'El campo Contaseña Nueva es obligatorio',
            // 'nuevaContraseña.min' => 'El campo Contaseña debe tener Minimo 8 Caracteres',
            'password-confirm.same' => 'Las contraseñas no coinciden.',
            'password-confirm.required' => 'El campo Confirmar Contaseña es obligatorio',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }{
            $input = $request->all();
            $user = User::where('id', $id)->first();
            $user->password = Hash::make($input['nuevaContraseña']);
            $user->save();
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
        //
    }
}
