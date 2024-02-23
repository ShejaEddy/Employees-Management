<?php
namespace App\OA\Response;

use OpenApi\Attributes as OA;

class InvalidDateRangeResponse
{
    #[OA\Response(
        response: "InvalidDateRangeResponse",
        description: "Invalid date if 'from' or 'to' is in query but not in the right format",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        example: 400
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Invalid date",
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
