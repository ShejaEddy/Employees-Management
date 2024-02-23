<?php

use App\Mail\EmployeeAttendanceRecordMail;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\post;
use function Pest\Laravel\withHeader;

beforeEach(function () {
    $this->admin = Admin::factory()->create();
    $response = post('/api/admins/login', [
        'email' => $this->admin->email,
        'password' => 'password',
    ]);

    $this->token = $response['data']['token'];

    withHeader('Authorization', 'Bearer ' . $this->token);
});

it('should record employee arrival attendance and Send Email to employee', function (Employee $employee) {
    Mail::fake();

    $response = post("/api/employees/$employee->id/attendance/arrival");

    Mail::assertQueued(EmployeeAttendanceRecordMail::class, function ($mail) use ($employee) {
        expect($mail->to[0]['address'])->toBe($employee->email);
        expect($mail->type)->toBe('Arrival');
        expect($mail->time_recorded)->toBe(now()->format('h:i A'));
        expect($employee->names)->toContain($mail->employee_name);
        expect($mail->build()->subject)->toBe('Arrival Attendance Recorded');
        expect($mail->build()->view)->toBe('emails.employee_attendance_mail');

        return true;
    });

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'Arrival recorded successfully',
        ]);
})->with('employee');

it('should return error if employee arrival attendance is already recorded', function (Employee $employee) {
    post("/api/employees/$employee->id/attendance/arrival");

    $response = post("/api/employees/$employee->id/attendance/arrival");

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'Arrival already recorded for the employee today',
        ]);
})->with('employee');

it('should return error if arriving employee is not found', function () {
    $response = post('/api/employees/100/attendance/arrival');

    $response->assertStatus(404)
        ->assertJson([
            'status' => 404,
            'message' => 'Employee not found',
        ]);
});

it('should record employee departure attendance and Send Email to employee', function (Employee $employee) {
    post("/api/employees/$employee->id/attendance/arrival");

    Mail::fake();

    $response = post("/api/employees/$employee->id/attendance/departure");

    Mail::assertQueued(EmployeeAttendanceRecordMail::class, function ($mail) use ($employee) {
        expect($mail->to[0]['address'])->toBe($employee->email);
        expect($mail->type)->toBe('Departure');
        expect($mail->time_recorded)->toBe(now()->format('h:i A'));
        expect($employee->names)->toContain($mail->employee_name);
        expect($mail->build()->subject)->toBe('Departure Attendance Recorded');
        expect($mail->build()->view)->toBe('emails.employee_attendance_mail');

        return true;
    });

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'Departure recorded successfully',
        ]);
})->with('employee');

it('should return error if departing employee is not found', function () {
    $response = post('/api/employees/100/attendance/departure');

    $response->assertStatus(404)
        ->assertJson([
            'status' => 404,
            'message' => 'Employee not found',
        ]);
});

it('should return error No arrival recorded for the employee if employee departure attendance is recorded without arrival', function (Employee $employee) {
    $response = post("/api/employees/$employee->id/attendance/departure");

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'No arrival recorded for the employee',
        ]);
})->with('employee');

it('should return error if employee departure attendance is already recorded', function (Employee $employee) {
    post("/api/employees/$employee->id/attendance/arrival");
    post("/api/employees/$employee->id/attendance/departure");

    $response = post("/api/employees/$employee->id/attendance/departure");

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'Departure already recorded for the employee today',
        ]);
})->with('employee');

it('should download attendance excel report with headings', function () {
    Employee::factory(10)->create();
    Attendance::factory(10)->create();
    $response = $this->get('/api/attendance/report/excel');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->assertHeader('Content-Disposition', 'attachment; filename=attendance_report.xlsx');
});

it('should download attendance excel report with date range for excel report', function () {
    Employee::factory(10)->create();
    Attendance::factory(20)->create();
    $response = $this->get('/api/attendance/report/excel?from=2024-01-01&to=2024-02-31&limit=10');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->assertHeader('Content-Disposition', 'attachment; filename=attendance_report.xlsx');
});

it('should return error if invalid start date is provided for excel report', function () {
    $response = $this->get('/api/attendance/report/excel?from=weird');

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'Invalid start date',
        ]);
});

it('should return error if invalid end date is provided for excel report', function () {
    $response = $this->get('/api/attendance/report/excel?to=weird');

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'Invalid end date',
        ]);
});

it('should return error if start date is greater than end date for excel report', function () {
    $response = $this->get('/api/attendance/report/excel?from=2024-01-01&to=2023-01-01');

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'Start date cannot be greater than end date',
        ]);
});

it('should download attendance pdf report with date range', function () {
    Employee::factory(10)->create();
    Attendance::factory(20)->create();
    $response = $this->get('/api/attendance/report/pdf?from=2024-01-01&to=2024-02-31&limit=10');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/pdf')
        ->assertHeader('Content-Disposition', 'attachment; filename=attendance_report.pdf');
});

it('should return error if invalid start date is provided for pdf report', function () {
    $response = $this->get('/api/attendance/report/pdf?from=weird');

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'Invalid start date',
        ]);
});

it('should return error if invalid end date is provided for pdf report', function () {
    $response = $this->get('/api/attendance/report/pdf?to=weird');

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'Invalid end date',
        ]);
});

it('should return error if start date is greater than end date for pdf report', function () {
    $response = $this->get('/api/attendance/report/pdf?from=2024-01-01&to=2023-01-01');

    $response->assertStatus(400)
        ->assertJson([
            'status' => 400,
            'message' => 'Start date cannot be greater than end date',
        ]);
});

