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
        DB::table('users')
            ->insert([
                'name' => 'User',
                'email' => 'user@gmail.com',
                'email_verified_at' => time(),
                'password' => Hash::make('user'),
                'created_at' => time()
            ]);
    }
}
