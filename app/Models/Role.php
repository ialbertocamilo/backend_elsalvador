<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const agent=1;
    const supervisor=2;
    const admin=3;

    use HasFactory;

    protected $guarded = [
    ];

    public function users(){
        return $this->hasMany(User::class);
    }

//    public function checkRole(string $resourceCode){
//
//        if ( auth()->user()->role->code == $resourceCode)
//    }
}
