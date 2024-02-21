<?php

namespace App\Http\Controllers\Api\Auth;

use AdminTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Traits\BaseTraits;
use AuthTraits;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ResetPasswordController extends Controller
{
    use AuthTraits, BaseTraits, AdminTrait;

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $email = $request->email;
            $password = $request->password;
            $token_key = $request->token;

            $token = $this->getResetTokenByEmail($email, $token_key);

            if (!$token) {
                throw new BadRequestException('Invalid token');
            }

            $is_expired = $this->checkTokenExpiry($token);

            if ($is_expired) {
                throw new BadRequestException('Token has expired, request a new one');
            }

            $admin = $this->getAdminByEmail($email);

            $this->updatePassword($admin, $password);

            $this->deleteResetToken($token);

            // TODO: Send email to user that password has been reset

            return $this->respondSuccess([], 'Password reset successfully');
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
