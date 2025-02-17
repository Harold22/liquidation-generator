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

    protected $table = 'file_data';
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

    protected static function booted()
    {
        // When a new record is created
        static::created(function ($fileData) {
            $fileData->updateTotals();
        });

        // When a record is updated (e.g., amount is changed)
        static::updated(function ($fileData) {
            $fileData->updateTotals();
        });

        // When a record is deleted
        static::deleted(function ($fileData) {
            $fileData->updateTotals();
        });

        // If restoring a soft-deleted record
        static::restored(function ($fileData) {
            $fileData->updateTotals();
        });
    }

    public function updateTotals()
    {
        if (!$this->file_id) {
            return; 
        }

        $file = $this->file; // Get the related File model

        if ($file) {
            $totalAmount = $file->file_data()->sum('amount') ?? 0;
            $totalBeneficiaries = $file->file_data()->count();

            $file->update([
                'total_amount' => $totalAmount,
                'total_beneficiary' => $totalBeneficiaries,
            ]);
        }
    }
}
