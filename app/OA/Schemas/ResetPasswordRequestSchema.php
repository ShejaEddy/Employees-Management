<?php

namespace App\OA\Schemas;

use OpenApi\Attributes as OA;

class ResetPasswordRequestSchema
{
    #[OA\Schema(
        schema: "ResetPasswordRequest",
        type: "object",
        required: ["email", "password", "token"],
        properties: [
            new OA\Property(
                property: "email",
                type: "string",
                format: "email",
                description: "The email of the admin",
                example: "sheja@eddy.com"
            ),
            new OA\Property(
                property: "password",
                type: "string",
                format: "password",
                description: "The new password of the admin",
                example: "password"
            ),
            new OA\Property(
                property: "password_confirmation",
                type: "string",
                format: "password",
                description: "Confirm Password",
                example: "password"
            ),
            new OA\Property(
                property: "token",
                type: "string",
                description: "The reset token sent to the admin's email",
                example: "d3b3c4d3-4b3d-4c3d-4b3d-4c3d4b3d4c3d"
            )
        ],
    )]
    public function __construct()
    {
    }
}
