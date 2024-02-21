<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $arrival_time = $this->faker->dateTime();
        $employee_id = Employee::inRandomOrder()->first()->id;
        return [
            'employee_id' => $employee_id,
            'arrival_time' => $arrival_time,
            'departure_time' => $this->faker->dateTimeBetween($arrival_time, '+8 hours'),
        ];
    }
}
