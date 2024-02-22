<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class InvalidTokenOrExpiredError
{
    #[OA\Response(
        response: "InvalidTokenOrExpiredError",
        description: "Invalid token or token has expired",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        example: 403
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Invalid token or token has expired"
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
