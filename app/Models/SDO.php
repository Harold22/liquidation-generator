<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SDO extends Model
{
     use HasFactory, SoftDeletes, LogsActivity;

    protected $keyType = 'string';


    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'extension_name',
        'position',
        'station',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
    
    public function cashAdvances()
    {
        return $this->hasMany(CashAdvance::class, 'cash_advance_id');
    }

}
