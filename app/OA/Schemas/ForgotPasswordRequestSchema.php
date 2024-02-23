<?php

namespace App\OA\Schemas;

use OpenApi\Attributes as OA;

class ForgotPasswordRequestSchema
{
    #[OA\Schema(
        schema: "ForgotPasswordRequest",
        type: "object",
        required: ["email"],
        properties: [
            new OA\Property(
                property: "email",
                type: "string",
                format: "email",
                description: "The email of the admin",
                example: "sheja@eddy.com"
            )
        ],
    )]
    public function __construct()
    {
    }
}
