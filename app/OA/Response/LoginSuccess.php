<?php

namespace App\OA\Response;

use OpenApi\Attributes as OA;

class LoginSuccess
{
    #[
        OA\Response(
            response: "LoginSuccess",
            description: "Admin logged in successfully",
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "status",
                            type: "integer",
                            example: 200
                        ),
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Admin logged in successfully"
                        ),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "user",
                                    type: "object",
                                    ref: "#/components/schemas/Admin"
                                ),
                                new OA\Property(
                                    property: "token",
                                    type: "string",
                                    example: "1|fXhVxEUyT3oTqjFDTfVMjn8D1Qy3Obq1VLJCMn7K"
                                )
                            ]
                        )
                    ]
                )
            )
        )
    ]
    public function __construct()
    {
    }
}
