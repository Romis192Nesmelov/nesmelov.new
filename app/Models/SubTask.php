<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubTask extends Model
{
    protected $fillable = [
        'name',
        'value',
        'percents',
        'paid_percents',
        'status',
        'start_time',
        'completion_time',
        'description',
        'send_email',
        'task_id',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
