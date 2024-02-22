<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "Admin",
    description: "Admin user schema",
    required: ["name", "email", "password"],
    example: [
        "name" => "Sheja Eddy",
        "email" => "admin@example.com",
        "updated_at" => "2024-10-10T12:00:00+00:00",
        "created_at" => "2024-10-10T12:00:00+00:00"
    ],
    properties: [
        new OA\Property(
            property: "id",
            type: "integer",
            description: "The id of the admin",
        ),
        new OA\Property(
            property: "name",
            type: "string",
            description: "The name of the admin",
        ),
        new OA\Property(
            property: "email",
            type: "string",
            format: "email",
            description: "The email of the admin",
        ),
        new OA\Property(
            property: "created_at",
            type: "string",
            format: "date-time",
            description: "The date and time the admin was created",
        ),
        new OA\Property(
            property: "updated_at",
            type: "string",
            format: "date-time",
            description: "The date and time the admin was updated",
        ),
    ]
)]
class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ["name", "email", "password"];

    protected $hidden = ["password", "remember_token"];
}
