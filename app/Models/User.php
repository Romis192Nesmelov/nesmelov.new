<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class,'user_id')->orderBy('id','desc');
    }

    public function ownTasks(): HasMany
    {
        return $this->hasMany(Task::class,'owner_id')->orderBy('id','desc');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('id','desc');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class)->orderBy('id','desc');
    }

    public function sentEmail(): HasMany
    {
        return $this->hasMany(SentEmail::class)->orderBy('id','desc');
    }
}
