<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use App\Helpers\RoleCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('data')->truncate();

        $payload = [
            'key' => 'package-configuration',
            'payload' => json_encode(['config' => [[
                "hvac" => 2,
                "shades" => 2,
                "roofs_u_value" => 2,
                "walls_u_value" => 2,
                "windows_u_value" => 2,
                "roofs_reflectance" => 2,
                "walls_reflectance" => 2,
                "shading_coefficient" => 2,
                "final_energy_reduction" => 2,
                "proportion_wall_window" => 2,
                "package_name" => "Paquete ejemplo",
                "package_id" => Constants::packagePrefix.'1',
                "package_status" => true
            ]], 'questions' => [
                [
                    "id" => 0,
                    "title" => "Los planos indican claramente la composición y valores de los muros exteriores",
                    "deactivated" => false
                ],
                [
                    "id" => 1,
                    "title" => "Los planos indican claramente los elementos de sombreado en ventanas Sur",
                    "deactivated" => false
                ],
                [
                    "id" => 2,
                    "title" => "Los planos indican claramente los elementos de sombreado en ventanas Oeste",
                    "deactivated" => false
                ],
                [
                    "id" => 3,
                    "title" => "Los planos indican claramente el área de elementos opacos y transparentes",
                    "deactivated" => false
                ],
                [
                    "id" => 4,
                    "title" => "Los planos indican claramente la composición y valores del techo",
                    "deactivated" => false
                ],
                [
                    "id" => 5,
                    "title" => "Los planos indican claramente la instalación del equipo de HVAC",
                    "deactivated" => false
                ],
                [
                    "id" => 6,
                    "title" => "Se entregan certificados y/o fichas técnicas de los materiales que componen los muros",
                    "deactivated" => false
                ],
                [
                    "id" => 7,
                    "title" => "Se entregan certificados y/o fichas técnicas de los materiales que componen las ventanas",
                    "deactivated" => false
                ],
                [
                    "id" => 8,
                    "title" => "Se entregan certificados y/o fichas técnicas de los materiales que componen el techo",
                    "deactivated" => false
                ],
                [
                    "id" => 9,
                    "title" => "Se entregan certificados y/o fichas técnicas del sistema de aire acondicionado",
                    "deactivated" => false
                ]]])
        ];

        // Inserta los usuarios en la tabla de usuarios
        \DB::table('data')->insert($payload);
    }
}
