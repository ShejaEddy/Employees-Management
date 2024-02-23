<?php

namespace App\OA\Components;

use OpenApi\Attributes as OA;


class PaginationQueryParameters
{
    #[OA\Parameter(
        name: "PageLimit",
        in: "query",
        description: "The number of items per page",
        required: false,
        schema: new OA\Schema(
            type: "integer",
            default: 10
        )
    )]
    #[OA\Parameter(
        name: "PageNumber",
        in: "query",
        description: "The page number",
        required: false,
        schema: new OA\Schema(
            type: "integer",
            default: 1
        )
    )]
    #[OA\Parameter(
        name: "from",
        in: "query",
        required: false,
        description: "The start date of the report",
        schema: new OA\Schema(
            type: "string",
            format: "date",
            example: "2021-01-01"
        )
    )]

    #[OA\Parameter(
        name: "to",
        in: "query",
        required: false,
        description: "The end date of the report",
        schema: new OA\Schema(
            type: "string",
            format: "date",
            example: "2021-12-31"
        )
    )]

    #[OA\Parameter(
        name: "limit",
        in: "query",
        required: false,
        description: "The limit of the report, by default 20",
        schema: new OA\Schema(
            type: "integer",
            example: 20
        )
    )]

    #[OA\Parameter(
        name: "EmployeeId",
        description: "Employee id parameter",
        required: true,
        in: "path",
        schema: new OA\Schema(
            type: "integer",
            example: 1
        )
    )]
    public function __construct()
    {
    }
}
