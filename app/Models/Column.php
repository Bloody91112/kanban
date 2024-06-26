<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Column extends Model
{
    use HasFactory;
    protected $guarded = [];

    public const BACKLOG_COLUMN = 'Бэклог';

    public function tasks(): hasMany
    {
        return $this->hasMany(Task::class, 'column_id', 'id')->orderBy('position');
    }

    public function project(): belongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
