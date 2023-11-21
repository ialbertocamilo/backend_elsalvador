<?php

namespace Database\Seeders;
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
        // Borra todos los registros existentes en la tabla de usuarios
        \DB::table('data')->truncate();

        // Genera algunos usuarios de ejemplo
        $payload = [
            [
                'key'=>'package-configuration',
                'payload'=>'{"config": [{"hvac": 2, "shades": 2, "roofs_u_value": 2, "walls_u_value": 2, "windows_u_value": 2, "roofs_reflectance": 2, "walls_reflectance": 2, "shading_coefficient": 2, "final_energy_reduction": 2, "proportion_wall_window": 2}],
                 "questions": [
  {
    "title": "Los planos indican claramente la composición y valores de los muros exteriores",
    "id": 0,
    "deactivated": false
  },
  {
    "title": "Los planos indican claramente los elementos de sombreado en ventanas Sur",
    "id": 1,
    "deactivated": false
  },
  {
    "title": "Los planos indican claramente los elementos de sombreado en ventanas Oeste",
    "id": 2,
    "deactivated": false
  },
  {
    "title": "Los planos indican claramente el área de elementos opacos y transparentes",
    "id": 3,
    "deactivated": false
  },
  {
    "title": "Los planos indican claramente la composición y valores del techo",
    "id": 4,
    "deactivated": false
  },
  {
    "title": "Los planos indican claramente la instalación del equipo de HVAC",
    "id": 5,
    "deactivated": false
  },
  {
    "title": "Se entregan certificados y/o fichas técnicas de los materiales que componen los muros",
    "id": 6,
    "deactivated": false
  },
  {
    "title": "Se entregan certificados y/o fichas técnicas de los materiales que componen las ventanas",
    "id": 7,
    "deactivated": false
  },
  {
    "title": "Se entregan certificados y/o fichas técnicas de los materiales que componen el techo",
    "id": 8,
    "deactivated": false
  },
  {
    "title": "Se entregan certificados y/o fichas técnicas del sistema de aire acondicionado",
    "id": 9,
    "deactivated": false
  }
]}',
            ],
            // Agrega más usuarios según tus necesidades
        ];

        // Inserta los usuarios en la tabla de usuarios
        \DB::table('data')->insert($payload);
    }
}
