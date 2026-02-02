<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission)
    {
        if (!$this->role) {
            return false;
        }
        
        // Super Admin has all permissions (assuming 'super-admin' slug)
        if ($this->role->slug === 'super-admin') {
            return true;
        }

        $permissions = $this->role->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public function hasRole($roleSlug)
    {
        return $this->role && $this->role->slug === $roleSlug;
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->avatar) {
            return \Illuminate\Support\Str::startsWith($this->avatar, ['http', 'https']) 
                ? $this->avatar 
                : asset('storage/' . $this->avatar);
        }

        $name = urlencode($this->name ?? 'Admin');
        return "https://ui-avatars.com/api/?name={$name}&background=4F46E5&color=ffffff&rounded=true&bold=true&font-size=0.33";
    }
}
