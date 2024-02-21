<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Traits\BaseTraits;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class LoginController extends Controller
{
    use BaseTraits;

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
                ]);
            }

            throw new BadRequestException("Invalid credentials, try again");
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
