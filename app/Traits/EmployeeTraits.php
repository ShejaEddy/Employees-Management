<?php

namespace App\Traits;

use App\Models\Employee;
use Symfony\Component\HttpFoundation\Response;
use Exception;

trait EmployeeTraits {

    public function getEmployeeById(string $id, bool $throw_error = true): ?Employee {
        $employee = Employee::find($id);

        if (empty($employee) && $throw_error){
            throw new Exception("Employee not found", Response::HTTP_NOT_FOUND);
        }

        return $employee;
    }

    public function getFirstName(string $names): string
    {
        if (empty($names)) return "Unknown";

        $names_arr = explode(" ", trim($names));

        return $names_arr[0];
    }
}
