<?php

namespace App\Http\Controllers;

use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Models\TourDate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TourController extends BaseController
{
    public function index(Tour $tour)
    {
        $tours = $tour->getListTours(Tour::PUBLIC);

        return $this->successResponse('Success', __('api.action_success', ['action' => 'Get list']), TourResource::collection($tours), 200);
    }

    public function store(Request $request, Tour $tour, TourDate $tourDate)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:256|unique:tours,name',
                'itinerary' => 'required|string|max:500',
                'price' => 'required|integer',
                'tour_date'    => 'required|array',
                'tour_date.*'  => 'required|string|distinct',
                'status' => ['required', Rule::in(['draft', 'public'])]
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(
                    "Error",
                    __('api.validation_error'),
                    $validator->errors(),
                    422
                );
            }

            $newTour = $tour->createTour([
                'name' => $request->name,
                'itinerary' => $request->itinerary,
                'price' => $request->price,
                'status' => $request->status == 'public' ? Tour::PUBLIC : Tour::DRAFT
            ]);

            $tourDate->createTourDates($newTour->id, $request->tour_date);

            return $this->successResponse("Success", __('api.action_success', ['action' => 'Create tour']), new TourResource($newTour), 200);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 'Server error', [], 500);
        }
    }

    public function show(Request $request, Tour $tour)
    {
        $tourItem = $tour->getTourById($request->id);
        if (!$tourItem) {
            return $this->errorResponse(
                __('api.not_found', ['item' => 'Tour']),
                __('api.notfound_error'),
                [],
                404
            );
        }
        return $this->successResponse(
            "Success",
            __('api.action_success', ['action' => 'Updated tour']),
            new TourResource($tourItem),
            200
        );
    }
    public function update(Request $request, Tour $tour, TourDate $tourDate)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:256',
                'itinerary' => 'required|string|max:500',
                'price' => 'required|integer',
                'tour_date'    => 'nullable',
                'tour_date.*'  => 'nullable',
                'status' => ['required', Rule::in(['draft', 'public'])]
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(
                    "Error",
                    __('api.validation_error'),
                    $validator->errors(),
                    422
                );
            }


            $tourItem = $tour->getTourById($request->id);
            if (!$tourItem) {
                return $this->errorResponse(
                    __('api.not_found', ['item' => 'Tour']),
                    __('api.notfound_error'),
                    [],
                    404
                );
            }
            $isUpdated = $tour->updateTour($request->id, [
                'name' => $request->name,
                'itinerary' => $request->itinerary,
                'status' => $request->status == 'public' ? Tour::PUBLIC : Tour::DRAFT,
                'price' => $request->price
            ]);
            if ($request->tour_date) {
                $tourDate->createTourDates($request->id, $request->tour_date);
            }

            $updateTour = $tour->getTourById($request->id);

            return $this->successResponse(
                "Success",
                __('api.action_success', ['action' => 'Updated tour']),
                new TourResource($updateTour),
                200
            );
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 'Server error', [], 500);
        }
    }

    public function toggleDate(Request $request, Tour $tour, TourDate $tourDate)
    {

        $validator = Validator::make($request->all(), [
            'id_date' => 'required|integer|exists:tour_date,id',
            'status' => ['required', 'between:1,2']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                "Error",
                __('api.validation_error'),
                $validator->errors(),
                422
            );
        }

        $tourItem = $tour->getTourById($request->id);

        if (!$tourItem) {
            return $this->errorResponse(
                __('api.not_found', ['item' => 'Tour']),
                __('api.notfound_error'),
                [],
                404
            );
        }

        $tourDate->updateTourDate($request->id, $request->id_date, $request->status);

        return $this->successResponse("Success", __('api.action_success', ['action' => 'Updated tour']), new TourResource($tourItem), 200);
    }
}
