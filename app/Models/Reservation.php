<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_ip',
        'place_number',
        'time',
        'date'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
