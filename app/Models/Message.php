<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'message',
        'status',
        'active_to_owner',
        'active_to_user',
        'owner_id',
        'user_id',
        'task_id',
        'sub_task_id',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class,'owner_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function subTask(): BelongsTo
    {
        return $this->belongsTo(SubTask::class);
    }
}
