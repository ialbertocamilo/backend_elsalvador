<?php

namespace App\Models;

use App\Exceptions\PermissionException;
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

    public function users()
    {
        return $this->hasMany(User::class);
    }

//    public function checkRole(string $resourceCode){
//
//        if ( auth()->user()->role->code == $resourceCode)
//    }
    static function enablePermission(int $role)
    {
        $user = auth()->user();
        if ($role != $user->role_id)
            throw new PermissionException('No cuentas con los permisos suficientes para realizar esta operación.');
    }

    static function canModify(bool $condition){
        if (!$condition)
            throw new PermissionException('No cuentas con los permisos suficientes para realizar esta operación.');
        return true;
    }
}
