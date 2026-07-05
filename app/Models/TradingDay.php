<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradingDay extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'date',
        'status',
        'partition_name',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
