<?php

namespace App\Http\Resources;

use App\Models\TourDate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tour' => $this->tour()->with(['tourDates' => function ($query) {
                $query->where('status', TourDate::ENABLED);
            }])->first(),
            'tour_date' => $this->tour_date,
            'number_passengers' => $this->passengers()->count(),
            'passengers' => $this->passengers()->with('passenger')->get(),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
