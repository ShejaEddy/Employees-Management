<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Traits\BaseTraits;
use App\Traits\EmployeeTraits;
use OpenApi\Attributes as OA;

class EmployeeController extends Controller
{
    use BaseTraits, EmployeeTraits;
    #[OA\Get(
        path: "/api/employees",
        description: "Fetch all employees",
        tags: ["Employees"],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/PageNumber"
            ),
            new OA\Parameter(
                ref: "#/components/parameters/PageLimit"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Employees fetched successfully.",
                content: new OA\JsonContent(ref: "#/components/schemas/GetAllEmployeesResponse")
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
        ]
    )]
    public function fetchAll()
    {
        try {
            $employees = Employee::paginate();

            return $this->respondSuccess($employees, 'Employees fetched successfully');
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    #[OA\Post(
        path: "/api/employees",
        description: "Create an employee",
        tags: ["Employees"],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Employee details",
            content: new OA\JsonContent(ref: "#/components/schemas/StoreEmployeeRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Employee created successfully",
                ref: "#/components/responses/StoreEmployeeSuccess",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized access",
                ref: "#/components/responses/Unauthenticated",
            ),
            new OA\Response(
                response: 422,
                description: "Validation failed",
                ref: "#/components/responses/StoreEmployeeRequestValidation"
            )
        ]
    )]
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

    #[OA\Get(
        path: "/api/employees/{id}",
        description: "Fetch an employee",
        tags: ["Employees"],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/EmployeeId"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Employee details fetched successfully",
                ref: "#/components/responses/EmployeeFetchSuccess"
            ),
            new OA\Response(
                response: 404,
                description: "Employee not found",
                ref: "#/components/responses/NotFound",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized access",
                ref: "#/components/responses/Unauthenticated",
            ),
        ]
    )]
    public function fetchOne($id)
    {
        try {
            $employee = $this->getEmployeeById($id);

            return $this->respondSuccess($employee, "Employee details fetched successfully");
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    #[OA\Put(
        path: "/api/employees/{id}",
        summary: "Update an (PUT/PATCH)",
        description: "Update an employee by ID. This endpoint supports both PUT and PATCH requests.",
        tags: ["Employees"],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/EmployeeId"
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Employee details",
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateEmployeeRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Employee details updated successfully",
                ref: "#/components/responses/EmployeeFetchSuccess",
            ),
            new OA\Response(
                response: 404,
                description: "Employee not found",
                ref: "#/components/responses/NotFound",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized access",
                ref: "#/components/responses/Unauthenticated",
            ),
            new OA\Response(
                response: 422,
                description: "Validation failed",
                ref: "#/components/responses/StoreEmployeeRequestValidation"
            )
        ]
    )]
    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $employee = $this->getEmployeeById($id);

            $employee->update($data);

            return $this->respondSuccess($employee, "Employee details updated successfully");
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    #[OA\Delete(
        path: "/api/employees/{id}",
        description: "Delete an employee",
        tags: ["Employees"],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/EmployeeId"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Employee deleted successfully",
                ref: "#/components/responses/DeleteEmployeeSuccess",
            ),
            new OA\Response(
                response: 404,
                description: "Employee not found",
                ref: "#/components/responses/NotFound",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized access",
                ref: "#/components/responses/Unauthenticated",
            ),
        ]
    )]
    public function delete($id)
    {
        try {
            $employee = $this->getEmployeeById($id);

            $deleted_suffix = env("DELETED_EMPLOYEE_SUFFIX", "_ERASED");

            $employee->email .= $deleted_suffix;
            $employee->badge_id .= $deleted_suffix;
            $employee->phone_number .= $deleted_suffix;

            $employee->save();
            $employee->delete();

            return $this->respondSuccess([], "Employee deleted successfully");
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
