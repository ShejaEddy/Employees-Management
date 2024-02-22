<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class NotFoundErrorResponse
{
    #[OA\Response(
        response: "NotFound",
        description: "Resource not found",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        description: "The status of the response",
                        example: 404
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        description: "The message of the response",
                        example: "Resource not found"
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
