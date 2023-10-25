<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Data extends Model
{
    use HasFactory;

    protected $guarded = [
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });
    }

    function projects()
    {
        return $this->belongsTo(Project::class);
    }


    static function savePackageConfiguration($key, $payload)
    {
        if (isset($key) && $key == 'package-configuration') {
            $response = Data::where(['key' => $key])->first();
            if (!$response)
                $response = Data::create(['key' => $key]);

            $response->payload=json_encode($payload);
            return $response->save();
        }

        return false;
    }
}
