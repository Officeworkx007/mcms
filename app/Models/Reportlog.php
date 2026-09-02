<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
    protected $fillable = [
        'report_key',
        'title',
        'label',
        'from_date',
        'to_date',
        'url',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];
}
