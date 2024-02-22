<?php

namespace App\OA\Schemas;

use OpenApi\Attributes as OA;

class LoginRequestSchema
{
    #[OA\Schema(
        schema: "LoginRequest",
        type: "object",
        required: ["email", "password"],
        properties: [
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
        ]
    )]
    public function __construct()
    {
    }
}
