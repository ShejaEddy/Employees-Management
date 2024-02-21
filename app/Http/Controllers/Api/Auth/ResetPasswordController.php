<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Traits\AdminTraits;
use App\Traits\AuthTraits;
use App\Traits\BaseTraits;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ResetPasswordController extends Controller
{
    use AuthTraits, BaseTraits, AdminTraits;

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

            $this->sendEmail(ResetPasswordMail::class, $email);

            return $this->respondSuccess([], 'Password reset successfully');
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
