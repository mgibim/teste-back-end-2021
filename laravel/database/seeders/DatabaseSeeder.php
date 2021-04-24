<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('users')->insert([
            'name'     => 'DIN DIGITAL',
            'email'    => 'suporte@dindigital.com',
            'password' => Hash::make('secret'),
        ]);
        
        DB::table('products')->insert([
            'name'       => 'mouse optico',
            'price'      => 60.00,
            'weight'     => 0.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
