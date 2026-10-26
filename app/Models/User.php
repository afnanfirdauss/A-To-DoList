<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Projects owned by the user.
     */
    public function ownedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    /**
     * Projects where the user is a member.
     */
    public function memberProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Categories created by the user.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Tasks created by the user.
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Tasks assigned to the user.
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Comments made by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * In-app notifications for the user.
     */
    public function inAppNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Count of unread in-app notifications.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->inAppNotifications()->whereNull('read_at')->count();
    }

    /**
     * Activities recorded for the user.
     */
    public function taskActivities(): HasMany
    {
        return $this->hasMany(TaskActivity::class);
    }

    /**
     * Helper to get IDs of all projects accessible by the user (owner or member).
     *
     * @return array<int>
     */
    public function accessibleProjectIds(): array
    {
        $owned = $this->ownedProjects()->pluck('id')->toArray();
        $member = $this->memberProjects()->pluck('projects.id')->toArray();

        return array_values(array_unique(array_merge($owned, $member)));
    }
}
