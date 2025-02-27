<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CashAdvance extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'special_disbursing_officer',
        'position',
        'station',
        'check_number',
        'cash_advance_amount',
        'cash_advance_date',
        'dv_number',
        'ors_burs_number',
        'responsibility_code',
        'uacs_code',
        'status',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();

    }
    public function setCashAdvanceAmountAttribute($value)
    {
        $this->attributes['cash_advance_amount'] = (int) str_replace(',', '', $value);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
