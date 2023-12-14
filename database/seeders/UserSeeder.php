<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borra todos los registros existentes en la tabla de usuarios
        DB::table('users')->truncate();

        User::create(['name' => 'Administrador', 'lastname' => 'Plataforma', 'email' => 'svg.z32@gmail.com', 'password' => Hash::make('12345678'), 'role_id' => Role::supervisor, 'active' => true]);
        User::create(['name' => 'Jane', 'lastname' => 'Smith', 'email' => 'janesmith@example.com', 'password' => Hash::make('password456'), 'role_id' => Role::agent, 'active' => true]);
    }
}
