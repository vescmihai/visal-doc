<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UserSeeder::class, 
            TramiteSeeder::class,
        ]);
        $this->call(DocumentSeeder::class);
        $this->call(PlacaSeeder::class);


    }
    
}
