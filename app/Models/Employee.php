<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "Employee",
    description: "Employee schema",
    required: ["names", "email", "phone_number", "badge_id"],
    example: [
        "names" => "Sheja Eddy",
        "email" => "sheja@eddy.com",
        "phone_number" => "250784141587",
        "badge_id" => "SH123456"
    ],
    properties: [
        new OA\Property(
            property: "id",
            type: "integer",
            description: "The id of the employee",
        ),
        new OA\Property(
            property: "names",
            type: "string",
            description: "The names of the employee",
        ),
        new OA\Property(
            property: "email",
            type: "string",
            format: "email",
            description: "The email of the employee",
        ),
        new OA\Property(
            property: "phone_number",
            type: "string",
            description: "The phone number of the employee",
        ),
        new OA\Property(
            property: "badge_id",
            type: "string",
            description: "The badge id of the employee",
        ),
        new OA\Property(
            property: "created_at",
            type: "string",
            format: "date-time",
            description: "The date and time the employee was created",
        ),
        new OA\Property(
            property: "updated_at",
            type: "string",
            format: "date-time",
            description: "The date and time the employee was updated",
        ),
        new OA\Property(
            property: "deleted_at",
            type: "string",
            format: "date-time",
            description: "The date and time the employee was deleted",
        ),
    ]
)]
class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["names", "email", "phone_number", "badge_id"];
}
