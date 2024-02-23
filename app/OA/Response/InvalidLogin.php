<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class InvalidLogin
{
    #[OA\Response(
        response: "InvalidLogin",
        description: "Invalid credentials, try again",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        example: 401
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Invalid credentials, try again"
                    ),
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
