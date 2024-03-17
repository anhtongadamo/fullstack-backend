<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passenger extends Model
{
    use HasFactory;
    protected $table = "passengers";


    const ENABLED = 1;
    const DISABLED = 2;

    protected $fillable = [
        'given_name',
        'surname',
        'mobile',
        'passport',
        'birth_date',
        'status',
        'email',
    ];

    /**
     * Define relationship
     */

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingPassenger::class, 'passenger_id');
    }


    /**
     * Define all business logic
     */

    public function updateOrCreatePassengers(array $passengers)
    {
        $passengersId = [];
        foreach ($passengers as $key => $passenger) {
            $passengerId = $passenger['id'] ?? null;

            $passengerData = [
                'given_name' => $passenger['given_name'],
                'surname' => $passenger['surname'],
                'mobile' => $passenger['mobile'],
                'passport' => $passenger['passport'],
                'birth_date' => $passenger['birth_date'],
                'email' => $passenger['email'],
                'status' => Passenger::ENABLED,
            ];
            if ($passengerId) {
                $passengerData['updated_at'] = now();
                $this->where($this->primaryKey, $passengerId)->update($passengerData);
            } else {
                $newPassenger = $this->create([
                    'given_name' => $passenger['given_name'],
                    'surname' => $passenger['surname'],
                    'mobile' => $passenger['mobile'],
                    'passport' => $passenger['passport'],
                    'birth_date' => $passenger['birth_date'],
                    'email' => $passenger['email'],
                    'status' => Passenger::ENABLED,
                ]);

                $passengerId = $newPassenger->id;
            }

            $passengersId[] = [
                'id' => $passengerId,
                'special_request' => $passenger['special_request']
            ];
        }
        return $passengersId;
    }
}
