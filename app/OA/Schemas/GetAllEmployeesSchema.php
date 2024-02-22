<?php

namespace App\OA\Schemas;

use OpenApi\Attributes as OA;


class GetAllEmployeesSchema
{
    #[OA\Schema(
        schema: "GetAllEmployeesResponse",
        properties: [
            new OA\Property(
                property: "status",
                type: "integer",
                example: 200
            ),
            new OA\Property(
                property: "message",
                type: "string",
                example: "Employees fetched successfully"
            ),
            new OA\Property(
                property: "data",
                type: "object",
                properties: [
                    new OA\Property(
                        property: "current_page",
                        type: "integer",
                        example: 1
                    ),
                    new OA\Property(
                        property: "data",
                        type: "array",
                        items: new OA\Items(ref: "#/components/schemas/Employee")
                    ),
                    new OA\Property(
                        property: "first_page_url",
                        type: "string",
                        example: "http://localhost:8000/api/employees?page=1"
                    ),
                    new OA\Property(
                        property: "from",
                        type: "integer",
                        example: 1
                    ),
                    new OA\Property(
                        property: "last_page",
                        type: "integer",
                        example: 2
                    ),
                    new OA\Property(
                        property: "last_page_url",
                        type: "string",
                        example: "http://localhost:8000/api/employees?page=2"
                    ),
                    new OA\Property(
                        property: "next_page_url",
                        type: "string",
                        example: "http://localhost:8000/api/employees?page=2"
                    ),
                    new OA\Property(
                        property: "path",
                        type: "string",
                        example: "http://localhost:8000/api/employees"
                    ),
                    new OA\Property(
                        property: "per_page",
                        type: "integer",
                        example: 5
                    ),
                    new OA\Property(
                        property: "prev_page_url",
                        type: "string",
                        example: "http://localhost:8000/api/employees?page=1"
                    ),
                    new OA\Property(
                        property: "to",
                        type: "integer",
                        example: 5
                    ),
                    new OA\Property(
                        property: "total",
                        type: "integer",
                        example: 6
                    )
                ]
            )
        ]
    )]
    public function __construct()
    {
    }
}
