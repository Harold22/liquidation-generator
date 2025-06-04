<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class SDO extends Model
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
        'firstname',
        'middlename',
        'lastname',
        'extension_name',
        'position',
        'designation',
        'station',
        'status',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
    
    public function cashAdvances()
    {
        return $this->hasMany(CashAdvance::class, 'sdos_id');
    }

}
