<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class RecordAttendanceSuccess
{
    #[OA\Response(
        response: "RecordAttendanceSuccess",
        description: "Attendance recorded successfully",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        example: 201
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Attendance recorded successfully"
                    ),
                    new OA\Property(
                        property: "data",
                        type: "object",
                        description: "The data of the response",
                        ref: "#/components/schemas/Attendance",
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
