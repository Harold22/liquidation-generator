<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class File extends Model
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
        'file_name', 
        'cash_advance_allocation_id',
        'total_amount',
        'total_beneficiary',
        'location'
    ];


    public function cashAdvanceAllocation()
    {
        return $this->belongsTo(CashAdvanceAllocation::class, 'cash_advance_allocation_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();

    }

    public function file_data()
    {
        return $this->hasMany(FileData::class, 'file_id');
    }
}
