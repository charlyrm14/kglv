<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreNoticeRequest;
use App\Events\NotificationNewContent;
use App\Models\Content;

class NoticeController extends Controller
{
    /**
     * The function creates a new event using validated data from a request, dispatches a notification
     * event, and returns a JSON response with success message and event data.
     * 
     * @param StoreNoticeRequest request The `create` function you provided seems to be a controller
     * method for creating a new notice. It takes a `StoreNoticeRequest` object as a parameter, which
     * likely contains the validated data for creating the event.
     * 
     * @return JsonResponse A JSON response is being returned. If the creation of the notice is
     * successful, a JSON response with a success message and the event data is returned with a status
     * code of 201 (Created). If an exception occurs during the process, a JSON response with an error
     * message is returned with a status code of 500 (Internal Server Error).
     */
    public function create(StoreNoticeRequest $request) : JsonResponse
    {
        try {

            $data = $request->validated() + ['content_category_id' => 1];
            $notice = Content::create($data);

            if($notice->active == 1) NotificationNewContent::dispatch($notice);

            $notice->load('contentCategory');

        } catch (\Exception $e) {

            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'message' => 'Aviso creado con éxito',
            'data' => $notice
        ], 201);
    }
}
