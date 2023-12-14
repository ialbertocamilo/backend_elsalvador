<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use DB;
use Illuminate\Database\Seeder;


class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('data')->truncate();

        $payload = ['key' => 'package-configuration', 'payload' => '{"config": [{"hvac": 2, "shades": 2, "package_id": "PACK1", "package_name": "Paquete ejemplo", "roofs_u_value": 2, "walls_u_value": 2, "package_status": true, "windows_u_value": 2, "roofs_reflectance": 2, "walls_reflectance": 2, "shading_coefficient": 2, "final_energy_reduction": 2, "proportion_wall_window": 2, "building_classification": "0"}, {"hvac": 98, "shades": 60, "package_id": "PACK2", "package_name": "Warren Ward", "roofs_u_value": 38, "walls_u_value": 15, "package_status": true, "windows_u_value": 53, "roofs_reflectance": 36, "walls_reflectance": 44, "shading_coefficient": 60, "final_energy_reduction": 90, "proportion_wall_window": 64, "building_classification": "1"}, {"hvac": 85, "shades": 45, "package_id": "PACK3", "package_name": "Ann Rocha", "roofs_u_value": 98, "walls_u_value": 26, "package_status": true, "windows_u_value": 38, "roofs_reflectance": 41, "walls_reflectance": 7, "shading_coefficient": 87, "final_energy_reduction": 1, "proportion_wall_window": 11, "building_classification": "2"}, {"hvac": 1, "shades": 32, "package_id": "PACK4", "package_name": "Igor Orr", "roofs_u_value": 94, "walls_u_value": 55, "package_status": true, "windows_u_value": 77, "roofs_reflectance": 94, "walls_reflectance": 93, "shading_coefficient": 21, "final_energy_reduction": 89, "proportion_wall_window": 89, "building_classification": "0"}], "questions": [{"id": 0, "title": "Los planos indican claramente la composición y valores de los muros exteriores", "deactivated": false}, {"id": 1, "title": "Los planos indican claramente los elementos de sombreado en ventanas Sur", "deactivated": false}, {"id": 2, "title": "Los planos indican claramente los elementos de sombreado en ventanas Oeste", "deactivated": false}, {"id": 3, "title": "Los planos indican claramente el área de elementos opacos y transparentes", "deactivated": false}, {"id": 4, "title": "Los planos indican claramente la composición y valores del techo", "deactivated": false}, {"id": 5, "title": "Los planos indican claramente la instalación del equipo de HVAC", "deactivated": false}, {"id": 6, "title": "Se entregan certificados y/o fichas técnicas de los materiales que componen los muros", "deactivated": false}, {"id": 7, "title": "Se entregan certificados y/o fichas técnicas de los materiales que componen las ventanas", "deactivated": false}, {"id": 8, "title": "Se entregan certificados y/o fichas técnicas de los materiales que componen el techo", "deactivated": false}, {"id": 9, "title": "Se entregan certificados y/o fichas técnicas del sistema de aire acondicionado", "deactivated": false}]}'];

        $originList = ['key' => 'origin-list', 'payload' => '[
    {
        "value": 1,
        "text": "Calculado por asesor"
    },
    {
        "value": 2,
        "text": "Certificado producto (\u03BB)"
    },
    {
        "value": 3,
        "text": "Certificado producto (U)"
    },
    {
        "value": 4,
        "text": "Certificado producto (%)"
    },
    {
        "value": 5,
        "text": "Certificado producto (g)"
    },
    {
        "value": 6,
        "text": "Certificado producto (CS)"
    },
    {
        "value": 7,
        "text": "Certificado producto (CS)"
    },
    {
        "value": 8,
        "text": "Ficha t\u00e9cnica"
    }]'];

        $departmentsAndMunicipalities=[
            'key'=>'department-list',
            'payload'=>'[
  {
    "department": "Ahuachapán",
    "code": "AHUA",
    "municipality": [
      "Ahuachapán",
      "Apaneca",
      "Atiquizaya",
      "Concepción de Ataco",
      "El Refugio",
      "Guaymango",
      "Jujutla",
      "San Francisco Menéndez",
      "San Lorenzo",
      "San Pedro Puxtla",
      "Tacuba",
      "Turín"
    ],
    "id": 0
  },
  {
    "department": "Cabañas",
    "code": "CAB",
    "municipality": [
      "Cinquera",
      "Dolores",
      "Guacotecti",
      "Ilobasco",
      "Jutiapa",
      "San Isidro",
      "Sensuntepeque",
      "Tejutepeque",
      "Victoria"
    ],
    "id": 1
  },
  {
    "department": "Chalatenango",
    "code": "CHAL",
    "municipality": [
      "Agua Caliente",
      "Arcatao",
      "Azacualpa",
      "Chalatenango",
      "Citalá",
      "Comalapa",
      "Concepción Quezaltepeque",
      "Dulce Nombre de María",
      "El Carrizal",
      "El Paraíso",
      "La Laguna",
      "La Palma",
      "La Reina",
      "Las Vueltas",
      "Nombre de Jesús",
      "Nueva Concepción",
      "Nueva Trinidad",
      "Ojos de Agua",
      "Potonico",
      "San Antonio de la Cruz",
      "San Antonio Los Ranchos",
      "San Fernando",
      "San Francisco Lempa",
      "San Francisco Morazán",
      "San Ignacio",
      "San Isidro Labrador",
      "San José Cancasque",
      "San José Las Flores",
      "San Luis del Carmen",
      "San Miguel de Mercedes",
      "San Rafael",
      "Santa Rita",
      "Tejutla"
    ],
    "id": 2
  },
  {
    "department": "Cuscatlán",
    "code": "CUS",
    "municipality": [
      "Candelaria",
      "Cojutepeque",
      "El Carmen",
      "El Rosario",
      "Monte San Juan",
      "Oratorio de Concepción",
      "San Bartolomé Perulapía",
      "San Cristóbal",
      "San José Guayabal",
      "San Pedro Perulapán",
      "San Rafael Cedros",
      "San Ramón",
      "Santa Cruz Analquito",
      "Santa Cruz Michapa",
      "Suchitoto",
      "Tenancingo"
    ],
    "id": 3
  },
  {
    "department": "La Libertad",
    "code": "LIB",
    "municipality": [
      "Antiguo Cuscatlán",
      "Chiltiupán",
      "Ciudad Arce",
      "Colón",
      "Comasagua",
      "Huizúcar",
      "Jayaque",
      "Jicalapa",
      "La Libertad",
      "Nueva San Salvador",
      "Nuevo Cuscatlán",
      "Quezaltepeque",
      "San Juan Opico",
      "San Matías",
      "San Pablo Tacachico",
      "Santa Tecla",
      "Talnique",
      "Tamanique",
      "Teotepeque",
      "Tepecoyo",
      "Zaragoza"
    ],
    "id": 4
  },
  {
    "department": "La Paz",
    "code": "PAZ",
    "municipality": [
      "Cuyultitán",
      "El Rosario",
      "Jerusalén",
      "Mercedes La Ceiba",
      "Olocuilta",
      "Paraíso de Osorio",
      "San Antonio Masahuat",
      "San Emigdio",
      "San Francisco Chinameca",
      "San Juan Nonualco",
      "San Juan Talpa",
      "San Juan Tepezontes",
      "San Luis La Herradura",
      "San Luis Talpa",
      "San Miguel Tepezontes",
      "San Pedro Masahuat",
      "San Pedro Nonualco",
      "San Rafael Obrajuelo",
      "Santa María Ostuma",
      "Santiago Nonualco",
      "Tapalhuaca",
      "Zacatecoluca"
    ],
    "id": 5
  },
  {
    "department": "La Unión",
    "code": "UNIO",
    "municipality": [
      "Anamorós",
      "Bolívar",
      "Concepción de Oriente",
      "Conchagua",
      "El Carmen",
      "El Sauce",
      "Intipucá",
      "La Unión",
      "Lislique",
      "Meanguera del Golfo",
      "Nueva Esparta",
      "Pasaquina",
      "Polorós",
      "San Alejo",
      "San José",
      "Santa Rosa de Lima",
      "Yayantique",
      "Yucuaiquín"
    ],
    "id": 6
  },
  {
    "department": "Morazán",
    "code": "MOR",
    "municipality": [
      "Arambala",
      "Cacaopera",
      "Chilanga",
      "Corinto",
      "Delicias de Concepción",
      "El Divisadero",
      "El Rosario",
      "Gualococti",
      "Guatajiagua",
      "Joateca",
      "Jocoaitique",
      "Jocoro",
      "Lolotiquillo",
      "Meanguera",
      "Osicala",
      "Perquín",
      "San Carlos",
      "San Fernando",
      "San Francisco Gotera",
      "San Isidro",
      "San Simón",
      "Sensembra",
      "Sociedad",
      "Torola",
      "Yamabal",
      "Yoloaiquín"
    ],
    "id": 7
  },
  {
    "department": "San Miguel",
    "code": "MIG",
    "municipality": [
      "Carolina",
      "Chapeltique",
      "Chinameca",
      "Chirilagua",
      "Ciudad Barrios",
      "Comacarán",
      "El Tránsito",
      "Lolotique",
      "Moncagua",
      "Nueva Guadalupe",
      "Nuevo Edén de San Juan",
      "Quelepa",
      "San Antonio del Mosco",
      "San Gerardo",
      "San Jorge",
      "San Luis de la Reina",
      "San Miguel",
      "San Rafael Oriente",
      "Sesori",
      "Uluazapa"
    ],
    "id": 8
  },
  {
    "department": "San Salvador",
    "code": "SAL",
    "municipality": [
      "Aguilares",
      "Apopa",
      "Ayutuxtepeque",
      "Cuscatancingo",
      "Delgado",
      "El Paisnal",
      "Guazapa",
      "Ilopango",
      "Mejicanos",
      "Nejapa",
      "Panchimalco",
      "Rosario de Mora",
      "San Marcos",
      "San Martín",
      "San Salvador",
      "Santiago Texacuangos",
      "Santo Tomás",
      "Soyapango",
      "Tonacatepeque"
    ],
    "id": 9
  },
  {
    "department": "San Vicente",
    "code": "VIC",
    "municipality": [
      "Apastepeque",
      "Guadalupe",
      "San Cayetano Istepeque",
      "San Esteban Catarina",
      "San Ildefonso",
      "San Lorenzo",
      "San Sebastián",
      "San Vicente",
      "Santa Clara",
      "Santo Domingo",
      "Tecoluca",
      "Verapaz"
    ],
    "id": 10
  },
  {
    "department": "Santa Ana",
    "code": "ANA",
    "municipality": [
      "Candelaria de la Frontera",
      "Chalchuapa",
      "Coatepeque",
      "El Congo",
      "El Porvenir",
      "Masahuat",
      "Metapán",
      "San Antonio Pajonal",
      "San Sebastián Salitrillo",
      "Santa Ana",
      "Santa Rosa Guachipilín",
      "Santiago de la Frontera",
      "Texistepeque"
    ],
    "id": 11
  },
  {
    "department": "Sonsonate",
    "code": "SON",
    "municipality": [
      "Acajutla",
      "Armenia",
      "Caluco",
      "Cuisnahuat",
      "Izalco",
      "Juayúa",
      "Nahuizalco",
      "Nahulingo",
      "Salcoatitán",
      "San Antonio del Monte",
      "San Julián",
      "Santa Catarina Masahuat",
      "Santa Isabel Ishuatán",
      "Santo Domingo de Guzmán",
      "Sonsonate",
      "Sonzacate"
    ],
    "id": 12
  },
  {
    "department": "Usulután",
    "code": "USU",
    "municipality": [
      "Alegría",
      "Berlín",
      "California",
      "Concepción Batres",
      "El Triunfo",
      "Ereguayquín",
      "Estanzuelas",
      "Jiquilisco",
      "Jucuapa",
      "Jucuarán",
      "Mercedes Umaña",
      "Nueva Granada",
      "Ozatlán",
      "Puerto El Triunfo",
      "San Agustín",
      "San Buenaventura",
      "San Dionisio",
      "San Francisco Javier",
      "Santa Elena",
      "Santa María",
      "Santiago de María",
      "Tecapán",
      "Usulután"
    ],
    "id": 13
  }
]'];
        // Inserta los usuarios en la tabla de usuarios
        DB::table('data')->insert($payload);
        DB::table('data')->insert($originList);
        DB::table('data')->insert($departmentsAndMunicipalities);
    }

}
