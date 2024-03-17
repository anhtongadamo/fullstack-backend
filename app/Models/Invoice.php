<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;
    protected $table = "tour_invoice";

    public function invoice(): HasOne
    {
        return $this->hasOne(Booking::class, 'booking_id');
    }
}
