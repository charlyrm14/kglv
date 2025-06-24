<?php

namespace App\Services;

class PromptBuilderService
{
    /**
     * Builds a contextual prompt for a sports-focused virtual assistant with emphasis on swimming.
     *
     * This method constructs a structured prompt in Spanish for an AI assistant, providing context
     * and the user's message to guide the assistant's response. The assistant is instructed to
     * specialize in swimming-related topics, provide safe and relevant responses, and avoid sharing
     * sensitive information.
     *
     * @param array $context An associative array containing contextual information (e.g., user, class, or sports data).
     * @param string $message The user's question to be answered by the assistant.
     * 
     * @return string A formatted prompt string including context and the user's message.
     */
    public static function buildChatPrompt(array $context, string $message): string
    {
        $sanitizedContext = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return "Eres un asistente especializado en deportes, con un enfoque profundo en natación. 
        Responde preguntas sobre natación, entrenamiento, técnicas, campeonatos, logros y biografías deportivas.
        rinda información sobre personas de la escuela de natación (maestros, alumnos, usuario actual), pero **no reveles datos sensibles** como contraseñas, correos electrónicos, teléfonos, direcciones o información privada.
        Si la pregunta no está relacionada con natación, deportes o la comunidad escolar, responde amablemente que no puedes ayudar con ese tema.
        Mantén un estilo claro, profesional, amigable y enfocado.
        No inventes información; responde solo con lo que está en el contexto\n
        Contexto:\n" . $sanitizedContext. "\n
        Pregunta: $message\n
        Respuesta:";
    }
}