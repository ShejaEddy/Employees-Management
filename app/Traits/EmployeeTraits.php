<?php

namespace App\Traits;

use App\Models\Employee;
use Facade\FlareClient\Http\Exceptions\NotFound;

trait EmployeeTraits {

    public function getEmployeeById(string $id, bool $throw_error = true): Employee {
        $employee = Employee::find($id);

        if (empty($employee) && $throw_error){
            throw new NotFound("Employee not found", 404);
        }

        return $employee;
    }

    public function getFirstName(string $names): string
    {
        if (empty($names)) return "Unknown";

        $names_arr = explode(" ", $names);

        return $names_arr[0];
    }
}
