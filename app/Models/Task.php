<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    protected $appends = ['deadline_time_left'];

    public function column(): belongsTo
    {
        return $this->belongsTo(Column::class, 'column_id', 'id');
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getDeadlineTimeLeftAttribute(): ?string
    {
        if (!$this->deadline || Carbon::now()->gte($this->deadline)) return null;
        return Carbon::now()->locale('ru')->diffForHumans($this->deadline) . ' дедлайна';
    }
}
