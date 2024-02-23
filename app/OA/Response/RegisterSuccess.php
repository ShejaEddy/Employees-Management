<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class RegisterSuccess
{
    #[OA\Response(
        response: "RegisterSuccess",
        description: "Success",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        example: 201
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Success"
                    ),
                    new OA\Property(
                        property: "data",
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "user",
                                type: "object",
                                description: "The registered admin",
                                ref: "#/components/schemas/Admin"
                            ),
                            new OA\Property(
                                property: "token",
                                type: "string",
                                description: "The token of the registered admin"
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
