<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class BookingPassenger extends Model
{
    use HasFactory;
    protected $table = "tour_booking_passenger";
    protected $fillable = ['booking_id', 'passenger_id', 'special_request'];


    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class, 'passenger_id');
    }


    /**
     * Define all business logic
     */

    public function updateOrCreateBookingPassenger(int $bookingId, array $passengerIds)
    {
        $bookingPassengers = [];
        foreach ($passengerIds as $key => $passengerId) {
            $bookingPassengers[$key] = [
                'booking_id' => $bookingId,
                'passenger_id' => $passengerId['id'],
                'special_request' => $passengerId['special_request'],
            ];
        }
        return $this->upsert(
            $bookingPassengers,
            ['booking_id', 'passenger_id'],
            ['special_request']
        );
    }
}
