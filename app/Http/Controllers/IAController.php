<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ChatIARequest;
use App\Models\ChatIA;
use App\Services\APIService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class IAController extends Controller
{
    /**
     * This PHP function retrieves conversation history for a user and returns it as a JSON response.
     * 
     * @param int user_id The `conversationByUser` function takes a parameter `user_id` of type
     * integer. This function retrieves the user information based on the provided `user_id`, then
     * fetches the conversation history for that user using a method called `conversationHistoryByUser`
     * from the `ChatIA` class.
     * 
     * @return JsonResponse a JSON response. If the user is not found, it returns a JSON response with
     * a message "Usuario no encontrado" and status code 404. If there is no conversation history for
     * the user, it returns a JSON response with a message "Sin historial de conversación" and status
     * code 404. If there is an error during the process, it returns a JSON response
     */
    public function conversationByUser(): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $conversation_hsitory = ChatIA::conversationHistoryByUser($user->id)->get();

            if($conversation_hsitory->isEmpty()) return response()->json(['message' => 'Sin historial de conversación'], 404);

        } catch (\Exception $e) {
            
            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => $conversation_hsitory
        ], 200);
    }

    /**
     * The PHP function `chatIA` processes a chat request related to sports, particularly swimming, by
     * fetching relevant users and sending a prompt to an API service for generating a response.
     * 
     * @param ChatIARequest request The `chatIA` function seems to be handling a chatbot interaction
     * where it responds to questions related to sports, with a focus on swimming. It fetches teachers
     * and students based on their roles and includes them in the context for the chatbot response.
     * 
     * @return The function `chatIA` is designed to handle a chat request related to sports,
     * particularly swimming. It retrieves a list of teachers and students from the database based on
     * their role IDs. It then constructs a context object containing this information. The function
     * prepares a prompt based on the received question and the context data. It then uses an
     * `APIService` to query an AI service with the constructed prompt and
     */
    public function chatIA(ChatIARequest $request): JsonResponse
    {
        try {

            $user = User::with(['profile'])->byId($request->user_id)->first();
            
            if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

            $users = User::with('profile')->get();

            $context = [
                'user' => $user,
                'users' => $users
            ];

            $sanitizedContext = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $prompt = "Eres un asistente especializado en deportes, con un enfoque profundo en natación. 
            Responde preguntas sobre natación, entrenamiento, técnicas, campeonatos, logros y biografías deportivas.
            rinda información sobre personas de la escuela de natación (maestros, alumnos, usuario actual), pero **no reveles datos sensibles** como contraseñas, correos electrónicos, teléfonos, direcciones o información privada.
            Si la pregunta no está relacionada con natación, deportes o la comunidad escolar, responde amablemente que no puedes ayudar con ese tema.
            Mantén un estilo claro, profesional, amigable y enfocado.
            No inventes información; responde solo con lo que está en el contexto\n
            Contexto:\n" . $sanitizedContext. "\n
            Pregunta: $request->message\n
            Respuesta:";

            $query_api = new APIService();
            $response_ia = $query_api->ia($prompt);

            if (is_null($response_ia)) response()->json(["error" => 'Estamos en mantenimiento intentalo de nuevo más tarde'], 500);

            ChatIA::saveLog('user', $request->message, $user->id);
            ChatIA::saveLog('ia', $response_ia, $user->id);

        } catch (\Exception $e) {
            
            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => $response_ia
        ], 201);
    }
}
