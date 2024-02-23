<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Traits\AdminTraits;
use App\Traits\AuthTraits;
use App\Traits\BaseTraits;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use OpenApi\Attributes as OA;

class ResetPasswordController extends Controller
{
    use AuthTraits, BaseTraits, AdminTraits;

    #[OA\Post(
        tags: ["Authentication"],
        path: "/api/admins/reset-password",
        description: "Reset admin password",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Admin email, password and token",
            content: new OA\JsonContent(ref: "#/components/schemas/ResetPasswordRequest")
        ),
        responses: [
            new OA\Response(
                response: "200",
                description: "Success",
                ref: "#/components/responses/ResetPasswordSuccess",
            ),
            new OA\Response(
                response: 403,
                description: "Request rejected",
                ref: "#/components/responses/InvalidTokenOrExpiredError",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
        ]
    )]
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $email = $request->email;
            $password = $request->password;
            $token_key = $request->token;

            $token = $this->getResetTokenByEmail($email, $token_key);

            if (!$token) {
                throw new BadRequestException('Invalid token', 403);
            }

            $is_expired = $this->checkTokenExpiry($token);

            if ($is_expired) {
                $this->deleteResetToken($token);
                throw new BadRequestException('Token has expired, request a new one', 403);
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
