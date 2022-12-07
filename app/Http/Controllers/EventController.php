<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPlacesRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class EventController extends Controller
{

    public function getPlaces(Event $event, GetPlacesRequest $request): JsonResponse
    {
        $places = array_fill(0, $event->count_places, true);
        $reservations = $event->reservations()
            ->where('date', new \DateTime($request->get('date')))
            ->where('time', new \DateTime($request->get('time')))
            ->pluck('place_number');
        foreach ($reservations as $reservation) {
            $places[$reservation] = false;
        }

        $dateTime = new \DateTime($request->get('date') . ' ' . $request->get('time'));
        $readonly = !(microtime(true) > $event->date_start->getTimestamp()) ||
            $dateTime->getTimestamp() < microtime(true);

        return new JsonResponse(['status' => 'success', 'places' => $places, 'readonly' => $readonly]);
    }

    public function getItems(): JsonResponse
    {
        $sessions = Event::query()
            ->where('date_start', '>=', (new \DateTime())->modify('-1 week'))
            ->where('date_start', '<=', (new \DateTime())->modify('+1 week'))
            ->get();

        return new JsonResponse(['status' => 'success', 'places' => $sessions->toArray()]);
    }
}
