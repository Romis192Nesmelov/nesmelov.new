<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use Sluggable;

    protected $fillable = ['icon','slug','en','ru','image','description_ru','description_en','active'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'en'
            ]
        ];
    }

    public function activeWorks(): HasMany
    {
        return $this->hasMany(Work::class)->where('active',1);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }
}
