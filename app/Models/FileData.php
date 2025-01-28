<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FileData extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'file_id',
        'control_number',
        'lastname',
        'firstname',
        'middlename',
        'extension_name',
        'birthdate',
        'status',
        'date_time_claimed',
        'remarks',
        'amount',
        'assistance_type',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();

    }
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
