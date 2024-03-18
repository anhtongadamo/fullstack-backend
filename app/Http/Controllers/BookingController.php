<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Http\Resources\PassengerResource;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Invoice;
use App\Models\Passenger;
use App\Models\Tour;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookingController extends BaseController
{
    public function index(Booking $booking)
    {
        $bookings = $booking->getListBookings();

        return $this->successResponse('Success', __('api.action_success', ['action' => 'Get list booking']), BookingResource::collection($bookings), 200);
    }

    public function show(Request $request, Booking $booking)
    {
        $booking = $booking->getBookingById($request->id);
        if (!$booking) {
            return $this->errorResponse(
                __('api.not_found', ['item' => 'Booking']),
                __('api.notfound_error'),
                [],
                404
            );
        }
        return $this->successResponse(
            "Success",
            __('api.action_success', ['action' => 'Get detail booking']),
            new BookingResource($booking),
            200
        );
    }

    public function store(Request $request, Passenger $passenger, BookingPassenger $bookingPassenger)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'tour_date' => 'required|string|date',
                'passengers' => 'required|array',
                'passengers.*.given_name' => 'required|string|max:128',
                'passengers.*.surname' => 'required|string|max:64',
                'passengers.*.email' => 'required|string|email|max:128',
                'passengers.*.mobile' => 'required|string|max:16',
                'passengers.*.passport' => 'required|string|max:16',
                'passengers.*.birth_date' => 'required|string|max:16',
                'passengers.*.special_request' => 'nullable|string|max:128'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(
                    "Validation error",
                    "Validation error.",
                    $validator->errors(),
                    422
                );
            }
            $tour = Tour::find($request->id);

            if (!$tour) {
                return $this->errorResponse(
                    __('api.not_found', ['item' => 'Tour']),
                    __('api.notfound_error'),
                    [],
                    404
                );
            }


            $newBooking = Booking::create([
                'tour_id' => $request->id,
                'tour_date' => $request->tour_date,
                'status' => Booking::SUBMITTED
            ]);
            $newInvoice = Invoice::create([
                'booking_id' => $newBooking->id,
                'amount' => $tour->price,
                'status' => Invoice::UNPAID
            ]);

            $passengerIds = $passenger->updateOrCreatePassengers($request->passengers);

            $bookingPassenger->updateOrCreateBookingPassenger($newBooking->id, $passengerIds);

            DB::commit();

            return $this->successResponse("Success", __('api.action_success', ['action' => 'Create booking']), new BookingResource($newBooking), 200);
        } catch (Exception $ex) {
            DB::rollback();

            return $this->errorResponse($ex->getMessage(), 'Server error', [], 500);
        }
    }

    public function update(Request $request, Passenger $passenger, BookingPassenger $bookingPassenger)
    {

        DB::beginTransaction();

        try {
            $bookingId = $request->id;

            $validator = Validator::make($request->all(), [
                'tour_date' => 'required|string|date',
                'passengers' => 'required|array',
                'passengers.*.given_name' => 'required|string|max:128',
                'passengers.*.surname' => 'required|string|max:64',
                'passengers.*.email' => 'required|string|email|max:128',
                'passengers.*.mobile' => 'required|string|max:16',
                'passengers.*.passport' => 'required|string|max:16',
                'passengers.*.birth_date' => 'required|string|max:16',
                'passengers.*.special_request' => 'nullable|string|max:128',
                'status' => ['required', Rule::in(['1', '3'])]
            ]);
            if ($validator->fails()) {
                return $this->errorResponse(
                    "Validation error",
                    "Validation error.",
                    $validator->errors(),
                    422
                );
            }
            $booking = Booking::find($bookingId);

            if (!$booking) {
                return $this->errorResponse(
                    __('api.not_found', ['item' => 'Booking']),
                    __('api.notfound_error'),
                    [],
                    404
                );
            }
            //Update tour booking
            $booking->tour_date = $request->tour_date;
            $booking->status = $request->status;
            $booking->save();

            $passengerIds = $passenger->updateOrCreatePassengers($request->passengers);

            $bookingPassenger->updateOrCreateBookingPassenger($bookingId, $passengerIds);

            DB::commit();

            return $this->successResponse("Success", __('api.action_success', ['action' => 'Update booking']), new BookingResource($booking), 200);
        } catch (Exception $ex) {
            DB::rollback();

            return $this->errorResponse($ex->getMessage(), 'Server error', [], 500);
        }
    }
}
