<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = ['icon','eng','rus','image','description','active'];

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }
}
