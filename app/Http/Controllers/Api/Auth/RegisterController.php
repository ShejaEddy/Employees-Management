<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Admin;
use App\Traits\BaseTraits;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class RegisterController extends Controller
{
    use BaseTraits;

    #[OA\Post(
        tags: ["Authentication"],
        path: "/api/admins/register",
        description: "Register an admin",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Admin credentials",
            content: new OA\JsonContent(ref: "#/components/schemas/RegisterRequest")
        ),
        responses: [
            new OA\Response(
                response: 422,
                description: "Validation failed",
                ref: "#/components/responses/RegisterValidationError",
            ),
            new OA\Response(
                response: 201,
                description: "Success",
                ref: "#/components/responses/RegisterSuccess",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
        ]
    )]

    public function register(RegisterRequest $request)
    {
        try {
            $email = $request->email;
            $name = $request->name;
            $password = $request->password;

            $admin = Admin::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $token = $admin->createToken(env('API_AUTH_TOKEN_NAME', 'auth_token'))->plainTextToken;

            return $this->respondSuccess([
                "user" => $admin,
                "token" => $token
            ], 'Admin registered successfully', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
