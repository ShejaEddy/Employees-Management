<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class InternalServerErrorResponse
{
    #[OA\Response(
        response: "InternalServerError",
        description: "Internal server error",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        description: "The status of the response",
                        example: 500
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        description: "The message of the response",
                        example: "Internal server error"
                    ),
                ],
                required: ["status", "message"],
            )
        )
    )]
    public function __construct()
    {
    }
}
