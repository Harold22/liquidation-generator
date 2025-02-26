<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class File extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) { 
                $model->id = (string) Str::uuid();
            }
        });
    }
    protected $fillable = [
        'file_name', 
        'cash_advance_id',
        'total_amount',
        'total_beneficiary',
        'location'
    ];


    public function cashAdvance()
    {
        return $this->belongsTo(CashAdvance::class, 'cash_advance_id');
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
