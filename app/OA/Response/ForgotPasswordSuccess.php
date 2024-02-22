<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class ForgotPasswordSuccess
{
    #[OA\Response(
        response: "ForgotPasswordSuccess",
        description: "Success",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        description: "The status of the response",
                        example: 200
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        description: "The message of the response",
                        example: "Password reset link has been sent to your email. Check your email to reset your password."
                    ),
                    new OA\Property(
                        property: "data",
                        type: "object",
                        description: "The data of the response"
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
