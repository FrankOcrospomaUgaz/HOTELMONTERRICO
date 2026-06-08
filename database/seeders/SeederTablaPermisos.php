<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// spatie
use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $opcionmenus = [
        //     ["Usuario", "usuario.index", 1, 'fa-regular fa-user', 5],
        //     ["Crear-Usuario", "usuario.create", 2, 'fa-regular fa-user-plus', 5],
        //     ["Editar-Usuario", "usuario.edit", 3, 'fa-solid fa-user-pen', 5],
        //     ["Eliminar-Usuario", "usuario.destroy", 4, 'fa-solid fa-user-xmark', 5],
        // ];

        // $opcionmenus = [
        //     ["Rol", "rol.index", 1, 'fa-sharp fa-solid fa-address-book', 5],
        //     ["Crear-Rol", "rol.create", 2, 'fa-solid fa-square-plus', 5],
        //     ["Editar-Rol", "rol.edit", 3, 'fa-solid fa-pen-to-square', 5],
        //     ["Eliminar-Rol", "rol.destroy", 4, 'fa-solid fa-circle-xmark', 5],
        // ];

        $opcionmenus = [
            ["Opciones", "opciones", 1, 'fa-sharp fa-solid fa-address-book', 5],
            ["Crear-Opciones", "opciones.create", 2, 'fa-solid fa-square-plus', 5],
            ["Editar-Opciones", "opciones.edit", 3, 'fa-solid fa-pen-to-square', 5],
            ["Eliminar-Opciones", "opciones.destroy", 4, 'fa-solid fa-circle-xmark', 5],
        ];

        foreach ($opcionmenus as $propiedad) {

            Permission::create([
                'name' => $propiedad[0],
                'ruta' => $propiedad[1],
                'orden' => $propiedad[2],
                'icono' => $propiedad[3],
                'grupomenu_id' => $propiedad[4],
            ]);
        }
    }
}
