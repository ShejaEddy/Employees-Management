<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\ForgotPasswordMail;
use App\Traits\AuthTraits;
use App\Traits\BaseTraits;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use OpenApi\Attributes as OA;

class ForgotPasswordController extends Controller
{
    use AuthTraits, BaseTraits;

    #[OA\Post(
        tags: ["Authentication"],
        path: "/api/admins/forgot-password",
        description: "Request a password reset",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Admin email",
            content: new OA\JsonContent(ref: "#/components/schemas/ForgotPasswordRequest")
        ),
        responses: [
            new OA\Response(
                response: "200",
                description: "Success",
                ref: "#/components/responses/ForgotPasswordSuccess",
            ),
            new OA\Response(
                response: "400",
                description: "Request rejected",
                ref: "#/components/responses/ForgotPasswordReject",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
        ]
    )]
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $email = $request->email;
            $token = $this->getResetTokenByEmail($email);

            if ($token) {
                $result = $this->checkResendInterval($token);

                if ($result['reject']) {
                    $time_left = $result['time_left'];
                    $message = "You can only request a new password reset after $time_left seconds";

                    throw new BadRequestException($message, 400);
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
