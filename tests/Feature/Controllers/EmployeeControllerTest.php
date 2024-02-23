<?php

use App\Models\Admin;
use App\Models\Employee;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('should return 401 for unauthenticated access to employee', function () {
    $response = get('/api/employees', []);

    $response->assertStatus(401)
        ->assertJson([
            'status' => 401,
            'message' => 'Unauthorized access, please login.',
        ]);

    $response = post('/api/employees', []);

    $response->assertStatus(401)
        ->assertJson([
            'status' => 401,
            'message' => 'Unauthorized access, please login.',
        ]);

    $employee = Employee::factory()->create();

    $response = put('/api/employees/' . $employee->id, []);

    $response->assertStatus(401)
        ->assertJson([
            'status' => 401,
            'message' => 'Unauthorized access, please login.',
        ]);

    $response = delete('/api/employees/' . $employee->id, []);

    $response->assertStatus(401)
        ->assertJson([
            'status' => 401,
            'message' => 'Unauthorized access, please login.',
        ]);

    beforeEach(function () {
        $this->admin = Admin::factory()->create();
        $response = post('/api/admins/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $this->token = $response['data']['token'];

        $this->withHeader('Authorization', 'Bearer ' . $this->token);
    });
});

it('should list all employees', function () {
    $employees = Employee::factory(3)->create();

    $response = get('/api/employees', []);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'Employees fetched successfully',
            'data' => [
                'first_page_url' => env('APP_URL') . '/api/employees?page=1',
                'last_page_url' => env('APP_URL') . '/api/employees?page=1',
                'prev_page_url' => null,
                'next_page_url' => null,
                'current_page' => 1,
                'data' => $employees->toArray(),
                'per_page' => 15,
                'total' => 3,
                'to' => 3,
                'from' => 1,
                'path' => env('APP_URL') . '/api/employees',
            ]
        ]);

    assertDatabaseCount('employees', 3);
    assertDatabaseHas('employees', $employees->first()->toArray());
});

it('should validate the email, password and badge field as unique', function () {
    $existingEmployee = Employee::factory()->create();


    $response = post('/api/employees', [
        'names' => 'Sheja Eddy',
        'email' => $existingEmployee->email,
        'phone_number' => $existingEmployee->phone_number,
        'badge_id' => $existingEmployee->badge_id,
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'email' => ['The email address is already in use'],
                'phone_number' => ['The phone number is already in use'],
                'badge_id' => ['The badge ID is already in use'],
            ],
        ]);
});

it('should create an employee', function () {
    $data = ['names' => 'Sheja Eddy', 'email' => 'sheja@eddy.com', 'phone_number' => '250784141587', 'badge_id' => 'SH123456'];

    $response = post('/api/employees', $data);

    $response->assertStatus(201)
        ->assertJson([
            'status' => 201,
            'message' => 'Employee created successfully',
            'data' => $data,
        ]);

    assertDatabaseCount('employees', 1);
    assertDatabaseHas('employees', $data);
});

it('should fail to update an employee with an existing email, phone number or badge ID', function () {
    $employee1 = Employee::factory()->create();
    $employee2 = Employee::factory()->create();

    $response = put('/api/employees/' . $employee1->id, [
        'email' => $employee2->email,
        'phone_number' => $employee2->phone_number,
        'badge_id' => $employee2->badge_id,
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'email' => ['The email address is already in use'],
                'phone_number' => ['The phone number is already in use'],
                'badge_id' => ['The badge ID is already in use'],
            ],
        ]);
});

it('should update an employee', function () {
    $employee = Employee::factory()->create();

    $data = ['names' => 'Sheja Eddy', 'email' => 'sheja@eddy.com', 'phone_number' => '250784141587', 'badge_id' => 'SH123456'];

    $response = put('/api/employees/' . $employee->id, $data);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'Employee details updated successfully',
            'data' => [
                'names' => $data['names'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'badge_id' => $data['badge_id'],
            ],
        ]);
});

it('should fail to fetch a non-existing employee', function () {
    $response = get('/api/employees/100', []);

    $response->assertStatus(404)
        ->assertJson([
            'status' => 404,
            'message' => 'Employee not found',
        ]);
});

it('should fetch an employee', function () {
    $employee = Employee::factory()->create();

    $response = get('/api/employees/' . $employee->id, []);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'Employee details fetched successfully',
            'data' => $employee->toArray(),
        ]);
});

it('should fail to delete a non-existing employee', function () {
    $response = delete('/api/employees/100', []);

    $response->assertStatus(404)
        ->assertJson([
            'status' => 404,
            'message' => 'Employee not found',
        ]);
});

it('should delete an employee', function () {
    $employee = Employee::factory()->create();

    $response = delete('/api/employees/' . $employee->id, []);

    $employee = Employee::withTrashed()->find($employee->id);

    expect($employee->email)->toContain(env('DELETED_EMPLOYEE_SUFFIX', '_ERASED'));
    expect($employee->badge_id)->toContain(env('DELETED_EMPLOYEE_SUFFIX', '_ERASED'));
    expect($employee->phone_number)->toContain(env('DELETED_EMPLOYEE_SUFFIX', '_ERASED'));
    expect($employee->deleted_at)->not->toBeNull();
    expect($employee->trashed())->toBeTrue();
    assertDatabaseHas('employees', $employee->toArray());

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'Employee deleted successfully',
        ]);
});

it('should not fetch deleted employees', function () {
    $employee = Employee::factory()->create();

    delete('/api/employees/' . $employee->id, []);

    $response = get('/api/employees/' . $employee->id, []);

    $response->assertStatus(404)
        ->assertJson([
            'status' => 404,
            'message' => 'Employee not found',
        ]);
});
