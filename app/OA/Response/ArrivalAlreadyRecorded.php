<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class ArrivalAlreadyRecorded
{
    #[OA\Response(
        response: "ArrivalAlreadyRecorded",
        description: "If the attendance for arrival is already recorded for the employee today",
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
                        example: "Arrival already recorded for the employee today"
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
