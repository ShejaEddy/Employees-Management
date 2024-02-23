<?php

use App\Models\Employee;
use Faker\Factory as Faker;

dataset('employees', runConcurrentTests(fn () => generateEmployee(10)));

dataset('employee', runConcurrentTests(fn () => generateEmployee()));

dataset(
    'create_employees',
    runConcurrentTests(fn () => [
        'names' => faker()->name(),
        'email' => faker()->unique()->safeEmail(),
        'phone_number' => faker()->phoneNumber(),
        'badge_id' => (string) faker()->randomNumber(5, true)
    ])
);

dataset(
    'update_employees',
    array_map(
        function () {
            return [
                fn () => generateEmployee(),
                [
                    'names' => faker()->name(),
                    'email' => faker()->safeEmail(),
                    'phone_number' => faker()->phoneNumber(),
                    'badge_id' => (string) faker()->randomNumber(5, true)
                ]
            ];
        },
        range(1, 3)
    )
);

dataset(
    'two_employees',
    runConcurrentTests(fn () => [
        array_map(fn () => generateEmployee(), range(1, 2))
    ])
);

function faker()
{
    return Faker::create();
}

function runConcurrentTests($function, $count = 3)
{
    return [array_map(fn () => fn () => $function(), range(1, $count))];
}

function generateEmployee($count = null)
{
    return Employee::factory($count)->create();
}
