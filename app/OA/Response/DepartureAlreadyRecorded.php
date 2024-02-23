<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class DepartureAlreadyRecorded
{
    #[OA\Response(
        response: "DepartureAlreadyRecorded",
        description: "If the departure for the employee is already recorded today or no arrival recorded for the employee",
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
                        example: "Departure already recorded for the employee today or no arrival recorded for the employee"
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
