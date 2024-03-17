<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use PhpParser\Node\Expr\FuncCall;

class Booking extends Model
{
    use HasFactory;
    protected $table = "tour_booking";
    protected $fillable = ['tour_id', 'tour_date', 'status'];

    const SUBMITTED = 1;
    const CONFIRMED = 2;
    const CANCELLED = 3;


    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class, 'booking_id');
    }
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'booking_id');
    }

    /**
     * Define all business logic
     */
    public function getListBookings()
    {
        return $this->orderBy('created_at', 'desc')->get();
    }

    public function getBookingById(int $id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
}
