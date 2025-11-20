<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use Sluggable;

    protected $fillable = [
        'slug',
        'name',
        'email',
        'phone',
        'contact_person',
        'type',
        'description',

        'save_contract',
        'contract',

        'contract_number',
        'contract_date',
        'ltd',
        'director',
        'director_case',
        'address',
        'ogrn',
        'okpo',
        'okved',
        'oktmo',
        'inn',
        'kpp',
        'payment_account',
        'correspondent_account',
        'bank_id'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public $timestamps = false;

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function userTasks(): HasMany
    {
        return $this->hasMany(Task::class)->where('user_id', Auth::id())->orWhere('owner_id', Auth::id());
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
