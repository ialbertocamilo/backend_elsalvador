<?php

namespace App\Http\Resources;

use App\Enums\StatusType;
use App\Models\Data;
use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingsByUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Identificador' => $this->id,
            'Nombre Proyecto' => $this->project_name,
            'Nombre de propietario' => $this->user->name,
            'Apellido de propietario' => $this->user->lastname,
            'Profesión - Oficio' => $this->user->profession,
            'Nacionalidad' => $this->user->nationality,
            'Email' => $this->user->email,
            'Diseñador' => $this->user->designer_name,
            'Director responsable' => $this->user->project_director,
            'Dirección' => $this->address,
            'Municipio' => Data::getMunicipalityString($this->department,$this->municipality),
            'Departamento' => Data::getDepartmentString($this->department),
            'Asesor energético' => $this->energy_advisor,
            'Clasificación de edificación' => Project::getBuildingClassificationString($this->building_classification) ,
            'Tipo de Edificación' => Project::getBuildingTypeString($this->building_type) ,
            'Nro. de niveles' => $this->levels,
            'Nro. Oficinas por nivel' => $this->offices,
            'Superficie construida (m2)' => $this->surface,
            'Fecha de Registro' => $this->created_at->format("m/d/Y")
        ];
    }
    protected function fetchReportedJSON(string $keyVal)
    {
        $data = $this->data->where('key', 'package')->first();

        if ($data) {
            $payload = json_decode($data->payload, true);
            return $payload['reportedValue'][$keyVal] ?? '-';
        }

        return  '-';
    }
    protected function fetchMeetsJSON(string $keyVal)
    {
        $data = $this->data->where('key', 'package')->first();

        if ($data) {
            $payload = json_decode($data->payload, true);
            return $payload['meets'][$keyVal] ?'Si':'No';
        }

        return 'No';
    }
    protected function fetchOriginJSON(string $keyVal)
    {
        $data = $this->data->where('key', 'package')->first();

        if ($data) {
            $payload = json_decode($data->payload, true);
            return $payload['valueOrigin'][$keyVal] ??null;
        }

        return null;
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
}
