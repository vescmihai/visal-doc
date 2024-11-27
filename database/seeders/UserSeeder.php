<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insertar el usuario principal
        DB::table('users')->insert([
            'name' => 'Mihai',
            'email' => 'mihai@gmail.com',
            'password' => Hash::make('12345678'), 
            'role' => 'admin', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar 50 usuarios aleatorios
        $randomUsers = [];
        for ($i = 0; $i < 50; $i++) {
            $randomUsers[] = [
                'name' => 'Usuario_' . Str::random(5),
                'email' => 'user_' . $i . '@example.com',
                'password' => Hash::make('password'), // Contraseña genérica
                'role' => ['cliente', 'gestor', 'admin'][array_rand(['cliente', 'gestor', 'admin'])],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($randomUsers);
    }
}
