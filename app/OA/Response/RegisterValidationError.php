<?php
namespace App\OA\Response;

use OpenApi\Attributes as OA;

class RegisterValidationError
{
    #[OA\Response(
        response: "RegisterValidationError",
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
                                property: "name",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "The name field is required"
                                )
                            ),
                            new OA\Property(
                                property: "email",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "The email address is already in use"
                                )
                            ),
                            new OA\Property(
                                property: "password",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "Passwords do not match"
                                )
                            ),
                            new OA\Property(
                                property: "password_confirmation",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "The password confirmation field is required"
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
