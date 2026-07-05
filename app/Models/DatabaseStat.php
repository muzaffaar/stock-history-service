<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseStat extends Model
{
    public $timestamps = false;

    protected $fillable = [

        'date',

        'rows_today',

        'rows_total',

        'ticker_count',

        'database_size_mb',

        'partition',

        'messages_received',

        'aggregates_received',

        'rows_inserted',

        'reconnects',

        'peak_memory_mb',

        'runtime_seconds',

        'backup_completed',

        'backup_size_mb',

    ];

    protected $casts = [
        'date' => 'date',
    ];
}
