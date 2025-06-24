<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CashAdvanceAllocation extends Model
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
        'cash_advance_id',
        'office_id',
        'amount',
        'status',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function cash_advance()
    {
        return $this->belongsTo(CashAdvance::class, 'cash_advance_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

}
