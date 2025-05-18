<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class APIService {

    /**
     * The function `iaApi` sends a prompt to an AI chat completion API and returns the response
     * content or a default message if there are any issues.
     * 
     * @param string prompt The `iaApi` function you provided seems to be making a POST request to the
     * OpenRouter AI API to get completions for a given prompt. It sends the prompt to the API and
     * retrieves the response content.
     * 
     * @return string The `iaApi` function is making a POST request to the OpenRouter AI API with a
     * given prompt. It sends the prompt to the API and retrieves a response. The function then
     * extracts the content of the response and returns it. If the response contains a message content,
     * that content is returned. If not, a default message "Estamos en mantenimiento intentalo de nuevo
     * más tarde"
     */
    public function ia(string $prompt) : string
    {
        $api_key = config('app.openrouter_key');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'meta-llama/llama-3.3-70b-instruct:free',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        $body = $response->collect($key = null);

        $content = $body['choices'][0]['message']['content'] ?? null;

        return $content;
    } 
}