<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdminActivityLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'admin_id',
        'admin_name',
        'admin_email',
        'admin_role',
        'category',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    /**
     * Administrator who executed the action.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Polymorphic subject of the activity log.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for category filter.
     */
    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        return $category ? $query->where('category', $category) : $query;
    }

    /**
     * Scope for admin actor filter.
     */
    public function scopeByAdmin(Builder $query, ?int $adminId): Builder
    {
        return $adminId ? $query->where('admin_id', $adminId) : $query;
    }

    /**
     * Scope for fulltext keyword search.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
                ->orWhere('admin_name', 'like', "%{$search}%")
                ->orWhere('admin_email', 'like', "%{$search}%")
                ->orWhere('action', 'like', "%{$search}%")
                ->orWhere('ip_address', 'like', "%{$search}%");
        });
    }

    /**
     * Get badge styling details for the activity category.
     *
     * @return array{label: string, bg: string, color: string, border: string}
     */
    public function getCategoryBadgeAttribute(): array
    {
        return match ($this->category) {
            'auth' => ['label' => 'Authentication', 'bg' => '#eff6ff', 'color' => '#1d4ed8', 'border' => '#bfdbfe'],
            'staff' => ['label' => 'Staff & Roles', 'bg' => '#faf5ff', 'color' => '#7e22ce', 'border' => '#e9d5ff'],
            'bookings' => ['label' => 'Work Orders', 'bg' => '#fffbeb', 'color' => '#b45309', 'border' => '#fde68a'],
            'tickets' => ['label' => 'Support Desk', 'bg' => '#f0fdf4', 'color' => '#15803d', 'border' => '#bbf7d0'],
            'users' => ['label' => 'Client Users', 'bg' => '#ecfeff', 'color' => '#0e7490', 'border' => '#a5f3fc'],
            'services' => ['label' => 'Services Catalog', 'bg' => '#f0fdfa', 'color' => '#0f766e', 'border' => '#99f6e4'],
            'settings' => ['label' => 'Site & SMTP', 'bg' => '#f8fafc', 'color' => '#475569', 'border' => '#cbd5e1'],
            'content' => ['label' => 'Web Content', 'bg' => '#fef2f2', 'color' => '#b91c1c', 'border' => '#fecaca'],
            default => ['label' => ucfirst($this->category), 'bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0'],
        };
    }
}
