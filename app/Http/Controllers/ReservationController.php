<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReserveRequest;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class ReservationController extends Controller
{
    //

    public function reserve(ReserveRequest $request): JsonResponse
    {
        $item = Reservation::query()
            ->where('place_number', $request->get('booking_item_id'))
            ->where('time', $request->get('time'))
            ->where('date', $request->get('date'))
            ->where('event_id', $request->get('item_id'));
        if ($item->exists()) {
            abort(404);
        }

        $reservation = new Reservation();
        $reservation->user_ip = $request->ip();
        $reservation->place_number = $request->get('booking_item_id');
        $reservation->time = $request->get('time');
        $reservation->date = $request->get('date');
        $reservation->event()->associate($request->get('item_id'));
        $reservation->save();

        return new JsonResponse(['status' => 'success']);
    }
}
