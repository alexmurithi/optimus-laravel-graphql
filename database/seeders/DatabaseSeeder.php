<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()     {
        DB::table("users")->insert([
            "username"=>"Johndoe",
            "email"=>"johndoe@yahoo.com",
            'email_verified_at' => now(),
            "password"=>bcrypt("freeuser@123"),
            "name"=>"John Doe",
            'remember_token' => Str::random(10),

        ]);

        \App\Models\User::factory(10)->create();
        \App\Models\Vehicle::factory(10)->create();


    }
}
