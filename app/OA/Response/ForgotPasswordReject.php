<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class ForgotPasswordReject
{
    #[OA\Response(
        response: "ForgotPasswordReject",
        description: "Bad request",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        description: "The status of the response",
                        example: 400
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        description: "The message of the response",
                        example: "You can only request a new password reset after 120 seconds"
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
