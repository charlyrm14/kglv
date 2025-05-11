<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function create(StoreEventRequest $request) : JsonResponse
    {
        try {

            $event = Event::create($request->validated());

            NotificationEvent::dispatch($event);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'Evento creado con éxito',
            'data' => $event
        ], 201);
    }
}
