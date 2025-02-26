<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Refund extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'cash_advance_id',
        'amount_refunded',
        'date_refunded',
        'official_receipt',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();

    }
    public function cashAdvance()
    {
        return $this->belongsTo(CashAdvance::class);
    }

    public function setAmountRefundedAttribute($value)
    {
        $this->attributes['amount_refunded'] = (int) str_replace(',', '', $value);
    }
}
