<?php

namespace App\Http\Resources;

use App\Enums\StatusType;
use App\Models\Data;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingParametersReportResource extends JsonResource
{
    public function toArray($request)
    {
        $reviewer=User::find($this->reviewer_id);
        return [
            'Identificador' => $this->id,
            'Nombre Proyecto' => $this->project_name,
            'Municipio' => Data::getMunicipalityString($this->department,$this->municipality),
            'Departamento' => Data::getDepartmentString($this->department),
            'Nombre de operador' => $this->user->name . ' ' . $this->user->lastname,
            'Revisor' => $reviewer?$reviewer->name.' '.$reviewer->lastname:'-',
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
            'Origen Valoracion - Proporción Muro-Ventana' => Data::getOriginString($this->fetchOriginJSON('wall_window_proportion')),
            'Origen Valoracion - Valor U de Muro' => Data::getOriginString($this->fetchOriginJSON('wall_u_value')),
            'Origen Valoracion - Reflectancia Muros' => Data::getOriginString($this->fetchOriginJSON('wall_reflectance')),
            'Origen Valoracion - Valor U de Techo' => Data::getOriginString($this->fetchOriginJSON('roof_u_value')),
            'Origen Valoracion - Reflectancia Techos' => Data::getOriginString($this->fetchOriginJSON('roof_reflectance')),
            'Origen Valoracion - Valor U de Ventana' => Data::getOriginString($this->fetchOriginJSON('window_u_value')),
            'Origen Valoracion - Valor g de Ventana' => Data::getOriginString($this->fetchOriginJSON('window_g_value')),
            'Origen Valoracion - Sombras Ventanas Exteriores' => Data::getOriginString($this->fetchOriginJSON('shades')),
            'Origen Valoracion - Aire Acondicionado COP' => Data::getOriginString($this->fetchOriginJSON('cop')),
            'Cumple - Proporción Muro-Ventana' => $this->fetchMeetsJSON('wall_window_proportion'),
            'Cumple - Valor U de muro (W/m2K)' => $this->fetchMeetsJSON('wall_u_value'),
            'Cumple - Reflectancia Muros (%) coeficiente absortividad' => $this->fetchMeetsJSON('wall_reflectance'),
            'Cumple - Valor U de Techo' => $this->fetchMeetsJSON('roof_u_value'),
            'Cumple - Reflectancia Techos (%) coeficiente absortividad' => $this->fetchMeetsJSON('roof_reflectance'),
            'Cumple - Valor U de Ventana' => $this->fetchMeetsJSON('window_u_value'),
            'Cumple - Valor G de Ventana' => $this->fetchMeetsJSON('window_g_value'),
            'Cumple - Sombras Ventanas Exteriores' => $this->fetchMeetsJSON('shades'),
            'Cumple - Aire acondicionado COP' => $this->fetchMeetsJSON('cop'),
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
