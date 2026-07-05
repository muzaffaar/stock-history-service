<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $fillable = [
        'ticker',
        'name',
        'exchange',
    ];

    public function minuteAggregates(): HasMany
    {
        return $this->hasMany(MinuteAggregate::class);
    }
}
