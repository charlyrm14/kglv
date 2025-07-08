<?php

namespace App\Http\Controllers;

use App\Events\NotificationNewContent;
use App\Models\Content;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateEventRequest;
use App\Services\ContentService;
use App\Services\FileService;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventController extends ContentController
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
    public function create(StoreEventRequest $request): JsonResponse
    {
        try {

            $data = $request->validated() + ['content_type_id' => 2];
            $event = Content::create($data);

            if($event->active == 1) {
                NotificationNewContent::dispatch($event);
            }

            $event->load('contentType');

            return response()->json([
                'message' => 'Evento creado con éxito',
                'data' => $event
            ], 201);

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }
    }

    /**
     * This PHP function updates an event based on the provided request data and slug, handling various
     * scenarios such as resource not found or forbidden access.
     * @param UpdateEventRequest request The `update` function you provided seems to be updating an
     * event based on the request data and a slug. The function first retrieves the event by its slug,
     * then checks if the event exists and if it has the correct content type before proceeding with
     * the update.
     * @param string slug The `slug` parameter in the `update` function is a string that represents a
     * unique identifier for the content you are trying to update. It is used to fetch the specific
     * content from the database based on its slug value. In this case, the `slug` parameter is used to
     * retrieve the event
     * @return a JSON response with a success message 'Evento actualizado con éxito' and the updated
     * event data if the update operation is successful. If the event is not found, it returns a 404
     * status with a message 'Recurso no encontrado'. If the content type of the event is not 2, it
     * returns a 403 status with a message 'Forbidden'. If there is an
     */
    public function update(UpdateEventRequest $request, string $slug): JsonResponse
    {
        $event = Content::getContentBySlug($slug);

        try {
            
            if(!$event) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }

            ContentService::checkContentType($event->content_type_id, 2);

            if($event->cover_image !== $this->default_cover_image) {
                FileService::deleteFile($event->cover_image);
            }
            
            $event->update($request->validated());

            return response()->json([
                'message' => 'Evento actualizado con éxito',
                'data' => $event
            ], 200);

        } catch (HttpResponseException $e) {
            // Re-lanzamos para que Laravel maneje correctamente el JSON que ya viene en la excepción
            throw $e;

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }
    }
}
