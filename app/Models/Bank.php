<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $fillable = ['name','bank_id'];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
