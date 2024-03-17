<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;
    protected $table = "tours";
    protected $fillable = ['name', 'itinerary', 'status', 'price'];

    const DRAFT = 1;
    const PUBLIC = 2;


    /**
     * Define all relationship
     */
    public function tourDates(): HasMany
    {
        return $this->hasMany(TourDate::class, 'tour_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'tour_id');
    }

    /**
     * Define all business logic
     */

    public function getTourById(int $id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function getListTours(int $status)
    {
        return $this->where('status', $status)->orderBy('created_at', 'desc')->get();
    }

    public function createTour(array $data)
    {
        return $this->create($data);
    }

    public function updateTour(int $id, array $data)
    {
        return $this->where($this->primaryKey, $id)
                    ->update($data);
    }
}
