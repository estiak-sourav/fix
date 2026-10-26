<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'avatar',
        'is_active',
        'must_change_password',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'permissions' => 'array',
        ];
    }

    /**
     * Check if administrator is an admin account.
     */
    public function isAdmin(): bool
    {
        return true;
    }

    /**
     * Check if administrator has super-admin privileges.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if administrator has operations manager role.
     */
    public function isOperationsManager(): bool
    {
        return in_array($this->role, ['super_admin', 'operations_manager']);
    }

    /**
     * Check if admin user has specific permission.
     * Super Admin always has full access to all features.
     * If user has .edit permission for a module, .view is automatically granted.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $granted = $this->permissions ?? [];
        if (! is_array($granted)) {
            return false;
        }

        if (in_array($permission, $granted, true)) {
            return true;
        }

        // Implicit grant: .edit permission automatically implies .view permission
        if (str_ends_with($permission, '.view')) {
            $editPermission = str_replace('.view', '.edit', $permission);
            if (in_array($editPermission, $granted, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if admin can view a module.
     */
    public function canView(string $module): bool
    {
        return $this->hasPermission("{$module}.view");
    }

    /**
     * Check if admin can edit/manage a module.
     */
    public function canEdit(string $module): bool
    {
        return $this->hasPermission("{$module}.edit");
    }

    /**
     * Get avatar full URL if present.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('storage/'.$this->avatar);
    }
}
