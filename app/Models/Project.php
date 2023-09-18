<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $guarded=[];

    function user(){
        return $this->belongsTo(User::class);
    }

    function data(){
        return $this->hasMany(Data::class);
    }

    function scopeSearch($query,$keyword){
        return $query->where(function($query) use ($keyword){
            $query->where('project_name','like',"%$keyword%")
                ->orWhere('owner_name','like',"%$keyword%")
                ->orWhere('designer_name','like',"%$keyword%")
                ->orWhere('project_director','like',"%$keyword%")
                ->orWhere('address','like',"%$keyword%");
        });
    }
}
