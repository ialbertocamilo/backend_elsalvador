<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const numberOfDepartments = 12;
    public const IN_PROGRESS         = 0;
    public const IN_REVISION         = 1;
    public const APPROVED            = 2;
    public const DENIED              = 3;


    protected $guarded = [];
    protected $with    = ['data'];

    static function getStatus(int $status)
    {
        switch ($status) {
            case "0":
                return 'En progreso';
            case "1":
                return 'En revision';
            case "2":
                return 'Aprobado';
            case "3":
                return 'Rechazado';
        }
    }

    static function getBuildingClassificationString($type)
    {
        switch ($type) {

            case 0:
                return 'Vivienda';
            case 1:
                return 'Oficina';
            case 2:
                return 'Terciarios';
        }
    }

    static function getBuildingTypeString($type)
    {
        switch ($type) {

            case 0:
                return 'Simple';
            case 1:
                return 'Duplex';
            case 2:
                return 'Departamento';
            case 3:
                return 'Público';
            case 4:
                return 'Privado';
        }
    }

    function data()
    {
        return $this->hasMany(Data::class);
    }

    function scopeSearch($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('project_name', 'like', "%$keyword%")
                ->orWhere('owner_name', 'like', "%$keyword%")
                ->orWhere('designer_name', 'like', "%$keyword%")
                ->orWhere('project_director', 'like', "%$keyword%")
                ->orWhere('address', 'like', "%$keyword%");
        });
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
