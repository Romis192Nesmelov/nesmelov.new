<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'contact_person',
        'value',
        'paid_off',
        'percents',
//        'my_percent',
        'paid_percents',
        'use_duty',
        'status',
        'start_time',
        'completion_time',
        'payment_time',
        'description',

        'convention_number',
        'convention_date',
        'convention',
        'tax_type',

        'send_email',
        'customer_id',
        'user_id',
        'owner_id'
    ];


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class,'owner_id');
    }

    public function subTasks(): HasMany
    {
        return $this->hasMany(SubTask::class,'task_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(Statistic::class);
    }
}
