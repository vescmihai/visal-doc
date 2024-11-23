<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear un usuario con los datos proporcionados
        DB::table('users')->insert([
            'name' => 'Mihai',
            'email' => 'mihai@gmail.com',
            'password' => Hash::make('12345678'), // Hashear la contraseña
            'role' => 'cliente', // Asignar el rol 'cliente' por defecto
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
