<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class MinuteAggregate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'stock_id',
        'minute',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'av',
        'vwap',
        'transactions',
    ];

    protected $casts = [
        'minute' => 'datetime',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function scopeTicker(Builder $query, int $stockId): Builder
    {
        return $query->where('stock_id', $stockId);
    }

    public function scopeDay(Builder $query, string $date): Builder
    {
        return $query->whereDate('minute', $date);
    }

    public function scopeBetween(
        Builder $query,
        Carbon $from,
        Carbon $to
    ): Builder {
        return $query->whereBetween('minute', [$from, $to]);
    }

    public function scopeChronological(Builder $query): Builder
    {
        return $query->orderBy('minute');
    }
}
