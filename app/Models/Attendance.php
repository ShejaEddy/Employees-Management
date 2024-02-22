<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "Attendance",
    description: "Attendance model schema",
    properties: [
        new OA\Property(
            property: "id",
            type: "integer",
            description: "The id of the attendance",
        ),
        new OA\Property(
            property: "employee_id",
            type: "integer",
            description: "The id of the employee",
        ),
        new OA\Property(
            property: "arrival_time",
            type: "string",
            format: "date-time",
            description: "The arrival time of the employee",
        ),
        new OA\Property(
            property: "departure_time",
            type: "string",
            format: "date-time",
            description: "The departure time of the employee",
        ),
        new OA\Property(
            property: "created_at",
            type: "string",
            format: "date-time",
            description: "The date and time the attendance was created",
        ),
        new OA\Property(
            property: "updated_at",
            type: "string",
            format: "date-time",
            description: "The date and time the attendance was updated",
        ),
    ],
)]
class Attendance extends Model
{
    use HasFactory;

    public const ATTENDANCE_DEPARTURE_TYPE = 'Departure';
    public const ATTENDANCE_ARRIVAL_TYPE = 'Arrival';

    protected $fillable = [
        'employee_id',
        'arrival_time',
        'departure_time',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
