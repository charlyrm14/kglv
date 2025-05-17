<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ChatIARequest;
use App\Services\APIService;

class IAController extends Controller
{
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
    public function chatIA(ChatIARequest $request)
    {
        try {

            $user = User::with(['biography', 'featured'])->byId($request->user_id)->first();
            
            if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

            $teachers = User::with('biography')->where('role_id', 2)->get();
            $students = User::with('featured')->where('role_id', 3)->get();

            $context = [
                'user' => $user,
                'teachers' => $teachers,
                'students' => $students
            ];

            $prompt = "Eres un asistente experto en deportes, especialmente en natación. 
            Tu tarea es responder preguntas sobre deportes, priorizando natación, y también puedes dar información sobre maestros 
            alumnos, y el usuario destacados de la escuela de natación, cualquier otro tema ajeno responde que no puedes brindar información al respecto.\n
            Contexto:\n" . json_encode($context, JSON_PRETTY_PRINT) . "\n
            Pregunta: $request->question\n
            Respuesta:";

            $query_api = new APIService();
            $response = $query_api->ia($prompt);

        } catch (\Exception $e) {
            
            return response()->json(["error" => 'Error del servidor'], 500);
        }

        return response()->json([
            'data' => $response
        ], 200);
    }
}
