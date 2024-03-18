<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;
    protected $table = "tour_invoice";
    protected $fillable = ['booking_id', 'amount', 'status'];

    const UNPAID = 1;
    const PAID = 2;
    const CANCELLED = 3;

    public function invoice(): HasOne
    {
        return $this->hasOne(Booking::class, 'booking_id');
    }
}
