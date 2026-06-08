<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Traits\HasRolesAndPermissions;
use App\Models\GrupoMenu;
use App\Models\User;
use Spatie\Permission\Contracts\Permission;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    public function MenuDinamico($id)
    {
        $subMenu = [];
        $Grupos =  GrupoMenu::orderBy('created_at', 'asc')->get();

        $User = User::where('id', $id)->get();
        $rol = Role::where('id', $User[0]->tipoUsuario)->first();

        config(['adminlte.logo' => "<b>" . strtoupper($rol->name) . "</b>"]);


        switch ($rol->name) {
            case 'Administrador':
                config(['adminlte.classes_sidebar' => 'sidebar-dark-info elevation-4 fixed-sidebar']);
                config(['adminlte.logo_img' => '/imagesComunes/perfil.png']);
                break;
            case 'Trabajador':
                config(['adminlte.classes_sidebar' => 'sidebar-dark-danger elevation-4 fixed-sidebar']);
                config(['adminlte.logo_img' => '/imagesComunes/programador.png']);
                break;
            case 'Cliente':
                config(['adminlte.logo_img' => '/imagesComunes/client2.png']);
                config(['adminlte.classes_sidebar' => 'sidebar-dark-primary elevation-4 fixed-sidebar']);
                break;
            default:
                config(['adminlte.logo_img' => '/imagesComunes/worker.png']);
                config(['adminlte.classes_sidebar' => 'sidebar-dark-warning elevation-4 fixed-sidebar']);
                break;
        }


        $Permissions =  DB::select('CALL permmisosPorRol(?)', array($User[0]->tipoUsuario));
        $permis = [];
        $i = 0;
        foreach ($Permissions as $Permissio) {
            $permis[$i++] = $Permissio->Permiso;
        }

        foreach ($Grupos as $grupo) {
            $Permisos =  DB::select('CALL permmisosPorGrupo(?)', array($grupo->id));

            if ($grupo->estado && count($Permisos) > 0) {
                $menu2 = config('adminlte.menu');

                if ($Permisos != []) {

                    foreach ($Permisos as $permiso) {

                        if (in_Array($permiso->name, $permis)) {
                            $subMenu[] = [
                                'text' => $permiso->name,
                                'url' => $permiso->ruta,
                                'icon' => $permiso->icono,
                                'can' => $permiso->name,
                            ];
                        }
                    }

                    if (count($subMenu) > 0) {
                        $menu2[] = [
                            'text' => $grupo->nombre,
                            'icon' => $grupo->icono,
                            'submenu' => $subMenu,
                        ];
                    }

                    $subMenu = [];
                } else {
                }
                config(['adminlte.menu' => $menu2]);
            }
        }
    }
}
