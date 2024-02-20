<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Traits\BaseTraits;
use App\Traits\EmployeeTraits;

class EmployeeController extends Controller
{
    use BaseTraits, EmployeeTraits;

    public function fetchAll()
    {
        try {
            $employees = Employee::paginate();

            return $this->respondSuccess($employees, 'Employees fetched successfully');
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    public function save(StoreEmployeeRequest $request)
    {
        try {
            $employee = new Employee();
            $employee->names = $request->names;
            $employee->email = $request->email;
            $employee->badge_id = $request->badge_id;
            $employee->phone_number = $request->phone_number;
            $employee->save();

            return $this->respondSuccess($employee, "Employee created successfully", 201);
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    public function fetchOne($id)
    {
        try {
            $employee = $this->getEmployeeById($id);

            return $this->respondSuccess($employee, "Employee details fetched successfully");
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            $data = $request->all();

            $employee = $this->getEmployeeById($id);

            $employee->update($data);

            return $this->respondSuccess($employee, "Employee details updated successfully");
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    public function delete($id)
    {
        try {
            $employee = $this->getEmployeeById($id);

            $deleted_suffix = env("DELETED_EMPLOYEE_SUFFIX", "_ERASED");

            $employee->email .= $deleted_suffix;
            $employee->badge_id .= $deleted_suffix;

            $employee->save();
            $employee->softDelete();

            return $this->respondSuccess($employee, "Employee deleted successfully");
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
