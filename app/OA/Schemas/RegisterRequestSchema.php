<?php

namespace App\OA\Schemas;

use OpenApi\Attributes as OA;

class RegisterRequestSchema
{
    #[OA\Schema(
        schema: "RegisterRequest",
        type: "object",
        required: ["name", "email", "password"],
        properties: [
            new OA\Property(
                property: "name",
                type: "string",
                description: "The name of the admin",
                example: "Sheja Eddy"
            ),
            new OA\Property(
                property: "email",
                type: "string",
                format: "email",
                description: "The email of the admin",
                example: "admin@example.com"
            ),
            new OA\Property(
                property: "password",
                type: "string",
                format: "password",
                description: "The password of the admin",
                example: "password"
            ),
            new OA\Property(
                property: "password_confirmation",
                type: "string",
                format: "password",
                description: "Confirm password",
                example: "password"
            ),
        ]
    )]
    public function __construct()
    {
    }
}
