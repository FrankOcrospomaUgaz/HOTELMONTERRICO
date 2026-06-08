<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;

class UsuariorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $opcionmenus = [
            ["Miguel", "Guevara","Cajusol", '12345678', '54123641','asdf@gmail.com'],
            ["MiguelA", "GuevaraA","CajusolA", '12345978', '54123661','aGHdf@gmail.com'],
        ];

        foreach ($opcionmenus as $propiedad) {

            Persona::create([
                'nombres' => $propiedad[0],
                'apellidopaterno' => $propiedad[1],
                'apellidomaterno' => $propiedad[2],
                'dni' => $propiedad[3],
                'telefono' => $propiedad[4],
                'email' => $propiedad[5],
            ]);
        }
    }
}
