<?php

namespace App\Traits;

use App\Models\Employee;
use Facade\FlareClient\Http\Exceptions\NotFound;

trait EmployeeTraits {

    public function getEmployeeById($id, bool $throw_error = true): Employee {
        $employee = Employee::find($id);

        if (empty($employee) && $throw_error){
            throw new NotFound("Employee not found");
        }

        return $employee;
    }
}
