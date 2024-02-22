<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class UnauthenticatedErrorResponse
{
    #[OA\Response(
        response: "Unauthenticated",
        description: "Unauthorized access",
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
                        example: "Unauthorized access, please login."
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
