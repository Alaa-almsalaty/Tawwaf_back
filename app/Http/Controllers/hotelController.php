<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\HotelResource;

class hotelController extends Controller
{

    public function index()
    {
       $hotels = Hotel::get();
        return HotelResource::collection($hotels);
    }


    public function store(CreateHotelRequest $request)
    {
        $hotelData = $request->validated();
        $hotel = Hotel::create($hotelData);
        return new HotelResource($hotel);
    }


    public function show(Hotel $hotel)
    {
        return new HotelResource($hotel);
    }



    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $hotelData = $request->updateHotel();
        $hotel->update($hotelData);
        return new HotelResource($hotel);
    }


    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return response()->json(['message' => 'Hotel deleted successfully']);
    }
}
