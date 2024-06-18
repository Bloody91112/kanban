<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const ROLES_LIST = [
        'ADMIN' => 'admin',
        'MANAGER' => 'manager',
        'DIRECTOR' => 'director',
        'GUEST' => 'guest',
        'EMPLOYEE' => 'employee',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'roles_users', 'role_id', 'user_id');
    }

    public static function ADMIN_ROLE(): Role
    {
        return Role::where('name', '=', self::ROLES_LIST['ADMIN'])->first();
    }

    public static function FULL_ACCESS_ROLES(): Collection
    {
        return Role::whereIn('name', [
            self::ROLES_LIST['ADMIN'],
            self::ROLES_LIST['DIRECTOR']
        ])->get();
    }

}
