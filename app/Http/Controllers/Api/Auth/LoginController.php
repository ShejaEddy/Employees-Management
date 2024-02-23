<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Traits\BaseTraits;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use OpenApi\Attributes as OA;

class LoginController extends Controller
{
    use BaseTraits;

    #[OA\Post(
        tags: ["Authentication"],
        path: "/api/admins/login",
        description: "Login an admin",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Admin credentials",
            content: new OA\JsonContent(ref: "#/components/schemas/LoginRequest")
        ),
        responses: [
            new OA\Response(
                response: 422,
                description: "Validation failed",
                ref: "#/components/responses/LoginValidationErrorResponse",
            ),
            new OA\Response(
                response: 401,
                description: "Invalid credentials, try again",
                ref: "#/components/responses/InvalidLogin",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
            new OA\Response(
                response: 200,
                description: "Admin logged in successfully",
                ref: "#/components/responses/LoginSuccess"
            ),

        ]
    )]
    public function login(LoginRequest $request)
    {
        try {
            $fields = $request->only('email', 'password');

            $authenticate = Auth::guard('admin')->attempt($fields);

            if ($authenticate) {
                $user = Auth::guard('admin')->user();
                $token = $user->createToken(env('API_AUTH_TOKEN_NAME', 'auth_token'), [])->plainTextToken;

                return $this->respondSuccess([
                    "user" => $user,
                    "token" => $token
                ], "Admin logged in successfully");
            }

            throw new BadRequestException("Invalid credentials, try again", 401);
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
