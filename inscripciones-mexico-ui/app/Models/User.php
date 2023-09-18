<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    protected $fillable = [
        'id',
        'encoded_id',
        'email',
        'password',
        'name',
        'avatar',
        'user_status_id',
        'status_comments',
        'created_on',
        'updated_on',
        'updated_by',
        'roles',
        'permissions',
        'authentication_sources'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        ''
    ];

    public function isAdmin() {
        return $this->roleListHasRole('admin') || $this->permissionListHasPermission('all-permissions');
    }

    public function hasRole($role) {
        if ($this->roleListHasRole('admin')) {
            return true;
        }
        return $this->roleListHasRole($role);
    }

    public function hasPermission($permission) {
        if ($this->isAdmin()) {
            return true;
        }
        return $this->permissionListHasPermission($permission);
    }

    public function hasAtLeastOnePermission($permissions) {
        if ($this->isAdmin()) {
            return true;
        }
        foreach ($permissions as $permission) {
            if ($this->permissionListHasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function getAllPermissions() {
        if (!isset($this->permissions) || $this->permissions == null) {
            return [];
        }
        return explode(',', $this->permissions);
    }

    public function getAllRoles() {
        if (!isset($this->roles) || $this->roles == null) {
            return [];
        }
        return explode(',', $this->roles);
    }

    public function getAllAuthenticationSources() {
        if (!isset($this->authentication_sources) || $this->authentication_sources == null) {
            return [];
        }
        return $this->authentication_sources;
    }

    private function roleListHasRole($searchRole) {
        if (count($this->getAllRoles()) < 1) {
            return false;
        }
        foreach ($this->getAllRoles() as $role) {
            if ($role != null && $role != "" && $role == $searchRole) {
                return true;
            }
        }
        return false;
    }

    private function permissionListHasPermission($searchPermission) {
        if (count($this->getAllPermissions()) < 1) {
            return false;
        }
        foreach ($this->getAllPermissions() as $permission) {
            if ($permission != null && $permission != "" && $permission == $searchPermission) {
                return true;
            }
        }
        return false;
    }

}
