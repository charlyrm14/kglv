<?php

namespace App\Http\Controllers;

use App\Events\NotificationNewContent;
use App\Models\Content;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * The function creates a new event using validated data from a request, dispatches a notification
     * event, and returns a JSON response with success message and event data.
     * 
     * @param StoreEventRequest request The `create` function you provided seems to be a controller
     * method for creating a new event. It takes a `StoreEventRequest` object as a parameter, which
     * likely contains the validated data for creating the event.
     * 
     * @return JsonResponse A JSON response is being returned. If the creation of the event is
     * successful, a JSON response with a success message and the event data is returned with a status
     * code of 201 (Created). If an exception occurs during the process, a JSON response with an error
     * message is returned with a status code of 500 (Internal Server Error).
     */
    public function create(StoreEventRequest $request) : JsonResponse
    {
        try {

            $data = $request->validated() + ['content_type_id' => 2];
            $event = Content::create($data);

            if($event->active == 1) NotificationNewContent::dispatch($event);

            $event->load('contentCategory');

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'Evento creado con éxito',
            'data' => $event
        ], 201);
    }
}
