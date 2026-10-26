<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'category_id',
        'created_by',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'start_date',
        'due_date',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(TaskActivity::class)->latest();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Determine if task is overdue.
     */
    public function isOverdue(): bool
    {
        if ($this->status === 'done' || ! $this->due_date) {
            return false;
        }

        return $this->due_date->lt(Carbon::today());
    }

    /**
     * Determine if task is due soon (today or within 24-48 hours).
     */
    public function isDueSoon(): bool
    {
        if ($this->status === 'done' || ! $this->due_date) {
            return false;
        }

        return ! $this->isOverdue() && $this->due_date->lte(Carbon::today()->addDay());
    }

    /**
     * Scope tasks accessible by a specific user.
     */
    public function scopeAccessibleBy(Builder $query, User $user): Builder
    {
        $projectIds = $user->accessibleProjectIds();

        return $query->where(function (Builder $q) use ($user, $projectIds) {
            $q->where('created_by', $user->id)
                ->orWhere('assigned_to', $user->id)
                ->orWhereIn('project_id', $projectIds);
        });
    }

    /**
     * Scope tasks that are overdue.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', Carbon::today());
    }

    /**
     * Scope tasks that are due soon (today or tomorrow).
     */
    public function scopeDueSoon(Builder $query): Builder
    {
        return $query->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', Carbon::today())
            ->whereDate('due_date', '<=', Carbon::tomorrow());
    }
}
