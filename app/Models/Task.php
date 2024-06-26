<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $with = [
        'images'
    ];

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
        if (!$this->deadline) return null;
        if (Carbon::now()->gte($this->deadline)) return 'Дедлайн!';
        return Carbon::now()->locale('ru')->diffForHumans($this->deadline) . ' дедлайна';
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
