<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\ForgotPasswordMail;
use App\Traits\BaseTraits;
use AuthTraits;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ForgotPasswordController extends Controller
{
    use AuthTraits, BaseTraits;

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $email = $request->email;
            $token = $this->getResetTokenByEmail($email);

            if ($token) {
                $result = $this->checkResendInterval($token);

                if ($result['reject']) {
                    $time_left = $result['time_left'];
                    $message = "You can only request a password reset after $time_left seconds";

                    throw new BadRequestException($message);
                } else {
                    $this->deleteResetToken($token);
                }
            }

            $randomToken = $this->generateRandomToken();

            $this->createResetToken($email, $randomToken);

            $this->sendEmail(ForgotPasswordMail::class, $email, [$randomToken, $email]);

            return $this->respondSuccess([], "Password reset link has been sent to your $email. Check your email to reset your password.");
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
