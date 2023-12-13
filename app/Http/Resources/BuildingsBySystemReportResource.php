<?php

namespace App\Http\Resources;

use App\Enums\StatusType;
use App\Models\Data;
use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingsBySystemReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Identificador' => $this->id,
            'Nombre Proyecto' => $this->project_name,
            'Nombre de operador' => $this->user->name . ' ' . $this->user->lastname,
            'Nombre del diseñador' => $this->designer_name,
            'Director responsable' => $this->project_director,
            'Dirección' => $this->address,
            'Municipio' => Data::getMunicipalityString($this->department,$this->municipality),
            'Departamento' => Data::getDepartmentString($this->department),
            'Asesor energético' => $this->energy_advisor,
            'Clasificación de edificación' => Project::getBuildingClassificationString($this->building_classification),
            'Tipo de Edificación' => Project::getBuildingTypeString($this->building_type),
            'Nro. de niveles' => $this->levels,
            'Nro. Oficinas por nivel' => $this->offices,
            'Superficie construida (m2)' => $this->surface,
            'Paquete de Evaluación' => $this->fetchPackageNameJSON(),
            'Proporción muro-ventana (%)' => $this->fetchReportedJSON('wall_window_proportion'),
            'Valor U de muro (W/m2K)' => $this->fetchReportedJSON('wall_u_value'),
            'Reflectancia Muros (%) coeficiente absortividad' => $this->fetchReportedJSON('wall_reflectance'),
            'Valor U de Techo (W/m2K)' => $this->fetchReportedJSON('roof_u_value'),
            'Reflectancia Techos (%) coeficiente absortividad' => $this->fetchReportedJSON('roof_reflectance'),
            'Valor U de ventana (W/m2K)' => $this->fetchReportedJSON('window_u_value'),
            'Valor G de Ventana' => $this->fetchReportedJSON('window_g_value'),
            'Sombras Ventanas Exteriores' => $this->fetchReportedJSON('shades'),
            'Aire Acondicionado COP' => $this->fetchReportedJSON('cop'),
            'Estado' => Project::getStatus($this->status),
            'Fecha de Registro' => $this->created_at->format("m/d/Y")
        ];
    }

    protected function fetchPackageNameJSON()
    {
        $data = $this->data->where('key', 'package')->first();

        if ($data) {
            $payload = json_decode($data->payload, true);
            return $payload['packageName'] ?? '-';
        }

        return '-';
    }

    protected function fetchReportedJSON(string $keyVal)
    {
        $data = $this->data->where('key', 'package')->first();

        if ($data) {
            $payload = json_decode($data->payload, true);
            return $payload['reportedValue'][$keyVal] ?? '-';
        }

        return '-';
    }

    protected function fetchOriginJSON(string $keyVal)
    {
        $data = $this->data->where('key', 'package')->first();

        if ($data) {
            $payload = json_decode($data->payload, true);
            return $payload['valueOrigin'][$keyVal] ?? null;
        }

        return null;
    }

    protected function fetchMeetsJSON(string $keyVal)
    {
        $data = $this->data->where('key', 'package')->first();

        if ($data) {
            $payload = json_decode($data->payload, true);
            return $payload['meets'][$keyVal] ? 'Si' : 'No';
        }

        return 'No';
    }
}
