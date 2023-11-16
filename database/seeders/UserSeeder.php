<?php

namespace Database\Seeders;
use App\Helpers\RoleCode;
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
        \DB::table('users')->truncate();

        // Genera algunos usuarios de ejemplo
        $users = [
            [
                'name' => 'admin',
                'email' => 'svg.z32@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id'=> 2
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@example.com',
                'password' => Hash::make('password456'),
                'role_id'=> 1
            ],
            // Agrega más usuarios según tus necesidades
        ];

        // Inserta los usuarios en la tabla de usuarios
        \DB::table('users')->insert($users);
    }
}
