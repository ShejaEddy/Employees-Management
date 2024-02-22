<?php

namespace App\OA\Schemas;

use OpenApi\Attributes as OA;


class UpdateEmployeeRequestSchema
{
    #[OA\Schema(
        schema: "UpdateEmployeeRequest",
        type: "object",
        required: [],
        properties: [
            new OA\Property(
                property: "names",
                type: "string",
                description: "The names of the employee",
                example: "Sheja Eddy"
            ),
            new OA\Property(
                property: "email",
                type: "string",
                format: "email",
                description: "The email of the employee",
                example: "sheja@eddy.com"
            ),
            new OA\Property(
                property: "badge_id",
                type: "string",
                description: "The badge id of the employee",
                example: "SH123456"
            ),
            new OA\Property(
                property: "phone_number",
                type: "string",
                description: "The phone number of the employee",
                example: "250784141587"
            ),
        ],
    )]
    public function __construct()
    {
    }
}
