<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class PromptBuilderService
{
    /**
     * Returns a description of the school's mission and values.
     *
     * This method provides a detailed explanation of King Dreams' philosophy towards swimming education.
     * It emphasizes the importance of learning swimming as a life-saving skill and highlights how the school
     * fosters physical and emotional health, discipline, and personal motivation in its students.
     *
     * The returned string includes a multi-line narrative about how each class is designed not only to teach
     * technical swimming skills, but also to promote self-confidence, respect for the body, and personal growth.
     *
     * @return string A description of the school's goals and values related to swimming education.
     */
    public static function aboutSchool(): string
    {
        return 'En King Dreams nos enfocamos en brindar mucho más que clases de natación: promovemos el aprendizaje de esta habilidad como un verdadero seguro de vida. Nuestro principal objetivo es formar nadadores seguros, capaces y conscientes de la importancia del autocuidado en el agua. "'.PHP_EOL.'"
        Creemos firmemente que la natación no solo salva vidas, sino que también fomenta la salud física y emocional, fortalece la disciplina y alimenta la motivación personal de nuestros alumnos. Cada clase está diseñada para acompañar a nuestros estudiantes en un proceso integral, donde se combina el aprendizaje técnico con el desarrollo personal, el respeto por el cuerpo y la confianza en uno mismo. "'.PHP_EOL.'"
        Ya sea que estén dando sus primeras brazadas o perfeccionando su estilo, nuestro compromiso es que cada alumno viva la experiencia de aprender a nadar como una herramienta para una vida más segura, saludable y motivada.';
    }

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
    public static function buildChatPrompt(User $user, Collection $users, string $message): string
    {
        $context = [
            'user' => $user,
            'users' => $users,
            'king_dreams' => self::aboutSchool()
        ];

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