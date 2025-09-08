<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\ReservationIndexRequest;
use App\Http\Resources\ReservationResource;
use App\Enums\ReservationState;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{

public function index(ReservationIndexRequest $request)
{
    $user   = $request->user();
    $search = trim((string) $request->query('q'));

    $reservations = Reservation::query()
        ->with(['visitor','package'])
        ->forUser($user)
        ->search($search)
        ->latest('id')
        ->paginate(6);
        //$reservations =Reservation::with(['package', 'visitor'])->get();

    return ReservationResource::collection($reservations);
}


    public function create()
    {
        //
    }


    public function store(CreateReservationRequest $request)
    {
        $data = $request->createReservation();
        $reservation = Reservation::create($data);
        return ReservationResource::make($reservation);
    }

    public function show(Reservation $reservation)
    {
        return ReservationResource::make($reservation->load(['visitor', 'package']));
    }



    public function editStatus(Reservation $reservation, Request $request)
    {
        $request->validate([
            'reservation_state' => ['required', Rule::enum(ReservationState::class)],
            'created_by' => ['nullable', 'exists:users,id'],
        ]);
        $reservation->reservation_state = $request->reservation_state;
        $reservation->created_by = $request->created_by;
        $reservation->save();
        return ReservationResource::make($reservation->load(['visitor', 'package']));
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'reservation_ids' => 'required|array',
            'reservation_state' => ['required', Rule::enum(ReservationState::class)],
        ]);

    //     $updatedReservations = [];
    //     foreach ($request->reservation_ids as $id) {
    //         $reservation = Reservation::find($id);
    //         if ($reservation) {
    //             $reservation->reservation_state = $request->reservation_state;
    //             $reservation->save();
    //             $updatedReservations[] = $reservation;
    //         }
    //     }

    //     return ReservationResource::collection(collect($updatedReservations)->load(['visitor', 'package']));
        $updatedReservations = Reservation::whereIn('id', $request->reservation_ids)->get();

        $updatedReservations->each(function ($reservation) use ($request) {
            $reservation->reservation_state = $request->reservation_state;
            $reservation->save();
        });

        $updatedReservations->load(['visitor', 'package']);

        return ReservationResource::collection($updatedReservations);
    }

    public function cancelReservation(Reservation $reservation)
    {
        $status = $reservation->reservation_state;
        if ($status === 'completed' || $status === 'confirmed' || $status === 'cancelled') {
            return response()->json(['message' => 'Cannot cancel a completed or already canceled reservation.'], 400);
        }
        $reservation->reservation_state = 'cancelled';
        $reservation->save();
        return ReservationResource::make($reservation->load(['visitor', 'package']));
    }

    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->noContent();
    }


}
