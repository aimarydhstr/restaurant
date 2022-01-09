<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Run to seed Role Admin

        // email : admin@gmail.com
        // password : password
        
        \App\Models\User::factory(1)->create();
    }
}
