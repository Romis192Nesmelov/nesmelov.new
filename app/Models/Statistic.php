<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistic extends Model
{
    protected $fillable = ['status','task_id'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
