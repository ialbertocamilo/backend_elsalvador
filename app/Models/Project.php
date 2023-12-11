<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const numberOfDepartments=12;
    public const IN_PROGRESS = 0;
    public const IN_REVISION = 1;
    public const APPROVED    = 2;
    public const DENIED      = 3;


    protected $guarded = [];
    protected $with    = ['data'];

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
