<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUserPasswordRequest;
use App\Http\Requests\GenerateTokenPasswordRequest;
use App\Mail\ResetPasswordToken;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Services\HelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    /**
     * Generates a password reset token and sends it via email.
     *
     * This method handles the password reset request by:
     * - Validating the email address (via a custom request class)
     * - Looking up the user by email
     * - Deleting any previously issued password reset tokens for that user
     * - Generating a new secure token
     * - Storing the new token in the password_reset_tokens table
     * - Sending an email to the user with the reset instructions and token
     *
     * @param GenerateTokenPasswordRequest $request
     *     The incoming HTTP request, which contains the validated user email.
     *
     * @return JsonResponse
     *     A JSON response indicating success or failure.
     */
    public function generateToken(GenerateTokenPasswordRequest $request): JsonResponse
    {
        $user = User::byEmail($request->email)->first();

        if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        try {

            PasswordResetToken::deleteOlderTokens($request->email);

            $token = HelperService::generateToken();
            PasswordResetToken::create(['email' => $request->email, 'token' => $token]);

            Mail::to($request->email)->send(new ResetPasswordToken($user, $token));

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Hemos enviado un correo con las instrucciones para cambiar tu contraseña'
        ], 201);
    }

    /**
     * Validate a password reset token.
     *
     * This endpoint verifies that the provided token:
     * - Is present and in a valid UUID format
     * - Exists in the password reset tokens table
     * - Has not expired or been deleted
     *
     * @param string $token  The reset token provided via URL or query string
     * @return JsonResponse  A response indicating token validity and associated email, or an error message
     */
    public function validateToken(string $token): JsonResponse
    {
        try {

            $validator = Validator::make(['token' => $token], [
                'token' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Formato de token invalido'], 400);
            }

            $tokenRecord = PasswordResetToken::verifyToken($token);
            
            if(!$tokenRecord) return response()->json(['message' => 'Token invalido o expirado'], 401);

            return response()->json([
                'valid' => true,
                'email' => $tokenRecord->email,
            ], 200);
        
        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Change the password of a user based on their email address.
     *
     * This method receives a validated request containing the user's email and new password.
     * It attempts to find the user by email, and if found, updates the password using the
     * model's mutator (which handles password hashing). If the user is not found, it returns
     * a 404 response. Any unexpected exception returns a 500 error.
     *
     * @param  ChangeUserPasswordRequest  $request  The incoming request containing email and new password.
     * @return JsonResponse
     *
     * Responses:
     * - 200: Password updated successfully.
     * - 404: User not found with the provided email.
     * - 500: Internal server error during password update process.
     */
    public function changePassword(ChangeUserPasswordRequest $request): JsonResponse
    {
        try {

            $user = User::byEmail($request->email)->first();

            if(!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

            $user->password = $request->password;
            $user->save();

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => "Contraseña actualizada con éxito, ya puedes iniciar sesión"
        ], 200);
    }
    
}
