<?php

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Admin;
use App\Traits\BaseTraits;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use BaseTraits;

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
            ]);

        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
