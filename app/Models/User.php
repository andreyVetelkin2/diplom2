<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
<<<<<<< Updated upstream
        'department_id'
=======
        'department_id',
        'citations',
        'hirsh',
        'position_id',
        'limit_ballov_na_kvartal',
>>>>>>> Stashed changes
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
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

<<<<<<< Updated upstream
=======
     public function author()
    {
        return $this->hasOne(Author::class);
    }
    public function penaltyPoints()
    {
        return $this->hasMany(PenaltyPoints::class);
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }
// В модели User (app/Models/User.php)

public function hasPermissionTo($permission)
{
    // Если permission передано как строка (slug)
    if (is_string($permission)) {
        $permission = Permission::where('slug', $permission)->first();
    }

    // Если permission не найдено
    if (!$permission) {
        return false;
    }

    return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission->slug);
}

public function hasPermissionThroughRole($permission)
{
    // Проверяем, что у пользователя есть роли
    if (!$this->roles || !$permission->roles) {
        return false;
    }

    // Проверяем пересечение ролей
    return $this->roles->pluck('id')->intersect($permission->roles->pluck('id'))->isNotEmpty();
}

public function hasPermission($slug)
{
    return $this->permissions()->where('slug', $slug)->exists();
}
>>>>>>> Stashed changes
}
