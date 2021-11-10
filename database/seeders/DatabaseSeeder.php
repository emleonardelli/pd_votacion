<?php

namespace Database\Seeders;

use App\Models\User;
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
        // \App\Models\User::factory(10)->create();
        User::insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '$2y$10$1/dtGCGFottJcU3BSYijietpTP1TkrX2msFoC4vwJOG29Q8VYGcVS',
        ]);
        $this->call([CandidateSeeder::class]);
        $this->call([CircuitSeeder::class]);
    }
}
