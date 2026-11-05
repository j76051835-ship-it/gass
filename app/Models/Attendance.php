<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'check_in_lat',
        'check_in_lng',
        'check_out_lat',
        'check_out_lng',
        'check_in_photo',
        'check_out_photo',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in_lat' => 'float',
            'check_in_lng' => 'float',
            'check_out_lat' => 'float',
            'check_out_lng' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getShiftLabelAttribute(): string
    {
        if (! $this->check_in_time) {
            return '-';
        }

        return substr($this->check_in_time, 0, 5) < '13:30' ? 'Shift Pagi' : 'Shift Sore';
    }

    public function getWorkMinutesAttribute(): int
    {
        if (! $this->check_in_time || ! $this->check_out_time) {
            return 0;
        }

        $inParts = array_map('intval', explode(':', $this->check_in_time));
        $outParts = array_map('intval', explode(':', $this->check_out_time));

        if (count($inParts) < 2 || count($outParts) < 2) {
            return 0;
        }

        $inTotal = $inParts[0] * 60 + $inParts[1];
        $outTotal = $outParts[0] * 60 + $outParts[1];

        $minutes = $outTotal < $inTotal
            ? (1440 - $inTotal) + $outTotal
            : $outTotal - $inTotal;

        return max(0, $minutes);
    }

    public function getWorkDurationAttribute(): string
    {
        if (! $this->check_in_time || ! $this->check_out_time) {
            return '--:--';
        }

        $minutes = $this->work_minutes;
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return "{$hours}j {$remainingMinutes}m";
    }
}
