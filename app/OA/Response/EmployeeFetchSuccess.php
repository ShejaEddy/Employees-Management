<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;


class EmployeeFetchSuccess
{
    #[OA\Response(
        response: "EmployeeFetchSuccess",
        description: "Employee details fetched successfully",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        description: "The status of the response",
                        example: 200
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        description: "The message of the response",
                        example: "Employee details fetched successfully"
                    ),
                    new OA\Property(
                        property: "data",
                        type: "object",
                        description: "The data of the response",
                        ref: "#/components/schemas/Employee",
                    )
                ],
                required: ["status", "message", "data"],
            )
        )
    )]
    public function __construct()
    {
    }
}
