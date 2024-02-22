<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\BaseTraits;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ApiController extends Controller
{
    use BaseTraits;

    #[OA\Get(
        path: "/",
        description: "Welcome message",
        tags: ["Home"],
        responses: [
            new OA\Response(
                response: "200",
                description: "Welcome message",
                ref: "#/components/responses/WelcomeResponse"
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->respondSuccess([], "Welcome to our Employee Management System API");
    }
}
