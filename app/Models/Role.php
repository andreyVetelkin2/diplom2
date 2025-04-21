<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }


    public function givePermissions(...$permissions)
    {
        $permissions = Permission::whereIn('slug', $permissions)->get();
        $this->permissions()->saveMany($permissions);
    }

    public function syncPermissions(array $permissionIds)
    {
        $this->permissions()->sync($permissionIds);
    }
}
