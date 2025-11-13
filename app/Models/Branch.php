<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = ['icon','en','ru','image','active'];

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }
}
