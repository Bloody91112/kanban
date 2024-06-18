<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function column(): belongsTo
    {
        return $this->belongsTo(Column::class, 'column_id', 'id');
    }
}
