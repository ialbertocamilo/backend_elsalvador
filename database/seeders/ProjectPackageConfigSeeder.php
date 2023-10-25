<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class ProjectPackageConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borra todos los registros existentes en la tabla de usuarios
        \DB::table('project_package_config')->truncate();

        // Genera algunos usuarios de ejemplo
        $payload = [
            'name' => 'Paquete 1',
            'key_name' => 'package_one',
            'config' => json_encode([
                'proportion_wall_window' => 0.4,
                'walls_u_value' => 3.499,
                'walls_reflectance' => 0.6,
                'roofs_u_value' => 0.8,
                'roofs_reflectance' => 0.8,
                'windows_u_value' => 2.6,
                'shading_coefficient' => 0.39,
                'shades' => 0,
                'hvac' => 4.49,
                'final_energy_reduction' => 0.3,
            ])
        ];

        // Inserta los usuarios en la tabla de usuarios
        \DB::table('project_package_config')->insert($payload);
    }
}
