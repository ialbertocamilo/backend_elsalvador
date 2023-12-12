<?php

namespace App\Models;

use App\Enums\BuildingClassification;
use App\Http\Resources\DashboardResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

    }

    static function savePackageConfiguration($key, $payload)
    {
        if (isset($key) && $key == 'package-configuration') {
            Role::enablePermission(Role::supervisor);
            $response = Data::where(['key' => $key])->first();
            if (!$response) $response = Data::create(['key' => $key]);

            $config    = $payload['config'] ?? json_decode($response->payload)->config;
            $questions = $payload['questions'] ?? json_decode($response->payload)->questions;

            $newPayload        = ['config' => $config, 'questions' => $questions];
            $response->payload = json_encode($newPayload);
            return $response->save();
        }

        return false;
    }

    static function calculateDataByAverageType(BuildingClassification $type, \Illuminate\Http\Request $request): array
    {
        $result = Project::where('building_classification', $type)->whereYear('created_at',$request->year)->where('status', Project::APPROVED)->with('data', function ($query) {
            return $query->where('key', 'package');
        })->get();
        $result = (array)DashboardResource::collection($result)->toArray($request);

        $numberOfArrays   = count($result);
        if (isset($result[0])){
            $numberOfElements = count($result[0]); // Suponiendo que todos los arrays tienen la misma longitud
            $averages = [];
            for ($i = 0; $i < $numberOfElements; $i++) {
                $sum = 0;
                foreach ($result as $subArray) {
                    $sum += $subArray[$i];
                }
                $averages[$i] = number_format($sum / $numberOfArrays, 2);
            }
            return $averages;
        }
        return [];
    }

    function projects()
    {
        return $this->belongsTo(Project::class);
    }
}
