<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * The function handles user login authentication using email or user code and returns a JWT token
     * upon successful authentication.
     * 
     * @param LoginRequest request The `login` function you provided is a PHP function that handles
     * user authentication based on the provided login credentials. It uses JWT (JSON Web Token) for
     * authentication.
     * 
     * @return JsonResponse The `login` function returns a JSON response with a success message and
     * access token if the login credentials are correct. If the credentials are incorrect, it returns
     * a JSON response with an error message. If an exception occurs during the login process, it
     * returns a JSON response with the error message from the exception.
     */
    public function login(LoginRequest $request) : JsonResponse
    {
        try {

            /** Determina si el valor es email o no */
            $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_code';

            $credentials = [ $field => $request->email, 'password' => $request->password];

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales incorrectas'], 400);
            }

            /** Obtiene usuario autenticado */
            $user = auth()->user();

            if(!is_null($user->token)) return response()->json(['message' => 'Verifica tu cuenta'], 400);

            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json([
                'message' => 'success',
                'data' => [
                    'access_token' => $token,
                    'token_type'   => 'bearer',
                ]
            ], 200);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }   
    }

    /**
     * The function `getUserInfo` retrieves user information using JWT authentication and returns it as
     * a JSON response.
     * 
     * @return JsonResponse A JSON response containing the user data is being returned. If the user is
     * not found or there is an issue with the token, appropriate error messages are returned along
     * with the corresponding HTTP status codes.
     */
    public function getUserInfo() : JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

        } catch (JWTException $e) {

            return response()->json(['error' => 'Token invalido'], 400);
        }

        
        return response()->json([
            'data' => $user->load('role')
        ], 200);
    }

    /**
     * The above function logs out the user by invalidating the JWT token and returning a JSON response
     * indicating successful session closure.
     * 
     * @return A JSON response with the message 'Session closed successfully' and a status code of 200.
     */
    public function logout() : JsonResponse
    {
        try {

            auth()->logout();

            return response()->json(['message' => 'Sesión cerrada con éxito'], 200);

        } catch (\Exception $e) {

            return response()->json(["error" => $e->getMessage()], 500);
        }   
    }
}
