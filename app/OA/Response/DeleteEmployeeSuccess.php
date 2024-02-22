<?php
namespace App\OA\Response;

use OpenApi\Attributes as OA;

class DeleteEmployeeSuccess
{
    #[OA\Response(
        response: "DeleteEmployeeSuccess",
        description: "Employee deleted successfully",
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        description: "The status of the response",
                        example: 200
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        description: "The message of the response",
                        example: "Employee deleted successfully"
                    ),
                ],
                required: ["status", "message"],
            )
        )
    )]
    public function __construct()
    {
    }
}
