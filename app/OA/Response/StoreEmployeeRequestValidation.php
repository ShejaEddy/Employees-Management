<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;


class StoreEmployeeRequestValidation
{

    #[OA\Response(
        response: "StoreEmployeeRequestValidation",
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
                                property: "names",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "The names field is required"
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
                                property: "badge_id",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "The badge ID is already in use"
                                )
                            ),
                            new OA\Property(
                                property: "phone_number",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "The phone number field is already in use"
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
