<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * The function retrieves events ordered by start date and returns them as JSON response, handling
     * potential errors along the way.
     * 
     * @return JsonResponse The `index` function is returning a JSON response. If the ``
     * collection is not empty, it will return a JSON response with the data containing the events in
     * the collection and a status code of 201. If the `` collection is empty, it will return a
     * JSON response with a message indicating that no results were found and a status code of 404. If
     * an exception occurs
     */
    public function index () : JsonResponse
    {
        try {
            
            $events = Event::orderByDesc('start_date')->get();

            if($events->isEmpty()) return response()->json(["message" => 'No se encontraron resultados'], 404);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => $events
        ], 201); 
    }

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

    /**
     * This PHP function retrieves an event by its ID and returns a JSON response with the event data
     * or appropriate error messages.
     * 
     * @param int id The `show` function you provided is a method that retrieves an event based on the
     * given `id` parameter and returns a JSON response. If the event is found, it returns a success
     * response with the event data. If the event is not found, it returns a 404 response indicating
     * that the
     * 
     * @return JsonResponse A JSON response is being returned. If the event with the specified ID is
     * found, a success message along with the event data will be returned with a status code of 200.
     * If the event is not found, a message indicating that the resource was not found will be returned
     * with a status code of 404. If an exception occurs during the process, an error message will be
     * returned with a status
     */
    public function show(int $id) : JsonResponse
    {
        try {

            $event = Event::Id($id)->first();

            if(!$event) return response()->json(['message' => 'Recurso no encontrado'], 404);

        } catch (\Exception $e) {
            
            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'success',
            'data' => $event
        ], 200);
    }
}
