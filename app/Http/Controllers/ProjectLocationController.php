<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectLocationController extends Controller
{


    public function generateGeoJson()
    {

        $result   = auth()->user()->projects()->withWhereHas('data', function ($query) {

            $query->where('key', 'geoinformation');
        })->get(['id', 'project_name']);
        $features = $result->map(function ($value) {
            return [
                'type' => 'Feature',
                'properties' => [
                    'name' => $value->project_name,
                    'id'=>$value->id
                ],
                'geometry' => ['type' => 'Point', 'coordinates' => json_decode($value->data[0]->payload)->Place->Geometry->Point]
            ];
        });
        $geoJSON  =[
                'type' => 'FeatureCollection',
                'features' => $features,
            ];

        return response()->json($geoJSON);
    }
}
