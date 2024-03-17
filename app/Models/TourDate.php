<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourDate extends Model
{
    use HasFactory;
    protected $table = "tour_date";

    const ENABLED = 1;
    const DISABLED = 2;

    /**
     * Define all relationship
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    /**
     * Define all business logic
     */

     public function createTourDates(int $tourId, array $dates)
    {
        $tourDates = [];
        foreach ($dates as $key => $itemDate) {
            $tourDates[$key] = [
                'tour_id' => $tourId,
                'date' => $itemDate,
                'status' => self::ENABLED,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        return $this->insert($tourDates);
    }

    public function updateTourDate(int $tourId, int $id, int $status) {
        return $this->where($this->primaryKey, $id)
                    ->where('tour_id', $tourId)
                    ->update([
                        'status' => $status
                    ]);
    }

}
