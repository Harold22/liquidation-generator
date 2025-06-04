<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CashAdvance extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    protected $fillable = [
        'sdos_id',
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
     public function sdo()
    {
        return $this->belongsTo(SDO::class, 'sdos_id');
    }

    protected $appends = ['special_disbursing_officer'];
    protected $hidden = ['sdo'];

    public function getSpecialDisbursingOfficerAttribute()
    {
        if (!$this->relationLoaded('sdo') || !$this->sdo) {
            return null;
        }

        $parts = [
            $this->sdo->firstname,
            $this->sdo->middlename,
            $this->sdo->lastname,
            $this->sdo->extension_name,
        ];

        return trim(implode(' ', array_filter($parts)));
    }

}
