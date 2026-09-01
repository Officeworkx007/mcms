<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediationCase extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'case_no',
        'parties_name',
        'case_category_id',
        'mediator_id',
        'first_mediation_date',
        'final_result_date',
        'received_date',
        'assigned_date',
        'sitting_dates',
        'status',
        'amount',
    ];

    protected $casts = [
        'first_mediation_date' => 'date',
        'final_result_date' => 'date',
        'received_date' => 'date',
        'assigned_date' => 'date',
        'sitting_dates' => 'array',
        'amount' => 'decimal:2',
    ];

    public function mediator(): BelongsTo
    {
        return $this->belongsTo(Mediator::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CaseCategory::class, 'case_category_id');
    }
}
