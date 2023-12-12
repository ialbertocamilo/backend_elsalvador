<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cop                    = 0;
        $wall_window_proportion = 0;
        $shades                 = 0;
        $wall_u_value           = 0;
        $window_u_value         = 0;
        $window_g_value         = 0;
        $wall_reflectance       = 0;
        $roof_u_value           = 0;
        $roof_reflectance       = 0;
        $packageArray           = $this->data->map(function ($item) {
            return collect(json_decode($item->payload, true))->get('reportedValue');
        });
        $packageArray->each(function ($item) use (
            &$cop, &$shades, &$wall_window_proportion, &$wall_u_value,
            &$window_u_value, &$window_g_value, &$wall_reflectance, &$roof_u_value, &$roof_reflectance
        ) {
            if ($item['wall_window_proportion']) {
                $wall_window_proportion += $item['wall_window_proportion'];
            }
            if ($item['wall_u_value']) {
                $wall_u_value += $item['wall_u_value'];
            }
            if ($item['cop']) {
                $cop += $item['cop'];
            }
            if ($item['shades']) {
                $shades += $item['shades'];
            }
            if ($item['window_u_value']) {
                $window_u_value += $item['window_u_value'];
            }
            if ($item['window_g_value']) {
                $window_g_value += $item['window_g_value'];
            }
            if ($item['wall_reflectance']) {
                $wall_reflectance += $item['wall_reflectance'];
            }
            if ($item['roof_u_value']) {
                $roof_u_value += $item['roof_u_value'];
            }
            if ($item['roof_reflectance']) {
                $roof_reflectance += $item['roof_reflectance'];
            }
        });
        return [$wall_window_proportion, $wall_u_value, $window_u_value, $window_g_value,
            $cop, $wall_reflectance, $roof_u_value, $roof_reflectance, $shades];
    }
}
