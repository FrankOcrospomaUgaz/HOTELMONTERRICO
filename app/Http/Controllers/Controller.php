<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\Habitacion;
use App\Models\Producto;
use App\Models\stockHabitacion;
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

    protected function obtenerStockHabitacionProducto(int $productoId, int $habitacionId): float
    {
        return (float) stockHabitacion::where('producto_id', $productoId)
            ->where('habitacion_id', $habitacionId)
            ->where('estado', 1)
            ->sum('cantidad');
    }

    protected function obtenerStockHabitacionesProducto(int $productoId): float
    {
        return (float) stockHabitacion::where('producto_id', $productoId)
            ->where('estado', 1)
            ->sum('cantidad');
    }

    protected function obtenerStockTotalProducto($producto): float
    {
        return (float) $producto->stock + $this->obtenerStockHabitacionesProducto((int) $producto->id);
    }

    protected function incrementarStockHabitacion(int $productoId, int $habitacionId, float $cantidad): void
    {
        $registro = stockHabitacion::where('producto_id', $productoId)
            ->where('habitacion_id', $habitacionId)
            ->where('estado', 1)
            ->first();

        if ($registro) {
            $registro->cantidad = (float) $registro->cantidad + $cantidad;
            $registro->save();
            return;
        }

        stockHabitacion::create([
            'producto_id' => $productoId,
            'habitacion_id' => $habitacionId,
            'cantidad' => $cantidad,
            'estado' => 1,
        ]);
    }

    protected function decrementarStockHabitacion(int $productoId, int $habitacionId, float $cantidad): void
    {
        $registro = stockHabitacion::where('producto_id', $productoId)
            ->where('habitacion_id', $habitacionId)
            ->where('estado', 1)
            ->first();

        if (!$registro || (float) $registro->cantidad < $cantidad) {
            throw new \RuntimeException('No existe stock suficiente en la habitación.');
        }

        $nuevoStock = (float) $registro->cantidad - $cantidad;
        if ($nuevoStock <= 0) {
            $registro->delete();
            return;
        }

        $registro->cantidad = $nuevoStock;
        $registro->save();
    }

    protected function liberarStockHabitacion(int $habitacionId): array
    {
        $registros = stockHabitacion::where('habitacion_id', $habitacionId)
            ->where('estado', 1)
            ->get();

        $detalle = [];

        foreach ($registros as $registro) {
            $producto = Producto::find($registro->producto_id);

            if (!$producto) {
                $registro->delete();
                continue;
            }

            $producto->stock = (float) $producto->stock + (float) $registro->cantidad;
            $producto->save();

            $detalle[] = [
                'producto_id' => $producto->id,
                'producto' => $producto->nombre,
                'cantidad' => (float) $registro->cantidad,
            ];

            $registro->delete();
        }

        return $detalle;
    }

    protected function enriquecerProductosConStockHabitacion($productos)
    {
        $stocksHabitaciones = stockHabitacion::select('producto_id', DB::raw('SUM(cantidad) as cantidad'))
            ->where('estado', 1)
            ->groupBy('producto_id')
            ->pluck('cantidad', 'producto_id');

        return collect($productos)->map(function ($producto) use ($stocksHabitaciones) {
            $stockHabitacion = (float) ($stocksHabitaciones[$producto->id] ?? 0);
            $producto->stock_general = (float) $producto->stock;
            $producto->stock_habitacion = $stockHabitacion;
            $producto->stock_total = (float) $producto->stock + $stockHabitacion;

            return $producto;
        });
    }

    protected function obtenerHabitacionesActivas()
    {
        return Habitacion::where('estado', 1)->orderBy('numero', 'asc')->get();
    }
}
