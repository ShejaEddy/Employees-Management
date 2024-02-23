<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class LoginValidationErrorResponse
{
    #[OA\Response(
        response: "LoginValidationErrorResponse",
        description: "Validation failed",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        example: 422
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Validation failed"
                    ),
                    new OA\Property(
                        property: "errors",
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "email",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "The email address field is required"
                                )
                            ),
                            new OA\Property(
                                property: "password",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "The password field is required"
                                )
                            )
                        ]
                    )
                ]
            )
        )
    )]
    public function __construct()
    {
    }
}
