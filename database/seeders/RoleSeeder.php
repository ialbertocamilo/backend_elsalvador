<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borra todos los registros existentes en la tabla de usuarios
        \DB::table('roles')->truncate();

        // Genera algunos usuarios de ejemplo
        $payload = [
            [
                'code' => 'agent',
                'name' => 'Operador(a)',
            ],
            [
                'code' => 'supervisor',
                'name' => 'Administrador(a)',
            ],
            [
                'code' => 'admin',
                'name' => 'Superadmin',
            ],
            // Agrega más usuarios según tus necesidades
        ];

        // Inserta los usuarios en la tabla de usuarios
        \DB::table('roles')->insert($payload);
    }
}
