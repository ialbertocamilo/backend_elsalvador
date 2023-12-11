<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//    ];

    protected $guarded = [
        'password'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    function role()
    {
        return $this->belongsTo(Role::class);
    }

    function scopeSearch($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('address', 'like', "%$keyword%")
                ->orWhere('phone', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('name', 'like', "%$keyword%")
                ->orWhere('lastname', 'like', "%$keyword%")
                ->orWhere('profession', 'like', "%$keyword%")
                ->orWhere('nationality', 'like', "%$keyword%")
                ->orWhere('department', 'like', "%$keyword%")
                ->orWhere('municipality', 'like', "%$keyword%");
        });
    }
}
