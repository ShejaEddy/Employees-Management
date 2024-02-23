<?php

use App\Mail\EmployeeAttendanceRecordMail;
use App\Models\Attendance;
use App\Models\Employee;
use App\Traits\AttendanceTraits;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertTrue;

class AttendanceTraitsTest extends TestCase
{
    use AttendanceTraits, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetDateLimit()
    {
        $expectedDate = now()->toDateString();
        $actualDate = $this->getDateLimit();

        assertEquals($expectedDate, $actualDate);
    }

    public function testSendAttendanceEmail()
    {
        $employee = new Employee([
            'names' => 'Sheja Eddy',
            'email' => 'sheja@eddy.com',
        ]);

        Mail::fake();

        $this->sendAttendanceEmail($employee, 'Arrival');

        Mail::assertQueued(EmployeeAttendanceRecordMail::class, function ($mail) use ($employee) {
            assertStringContainsString($employee->email, $mail->to[0]['address']);
            assertEquals('Arrival', $mail->type);
            assertEquals(now()->format('h:i A'), $mail->time_recorded);
            assertStringContainsString($mail->employee_name, $employee->names);
            assertEquals('Arrival Attendance Recorded', $mail->build()->subject);
            assertEquals('emails.employee_attendance_mail', $mail->build()->view);

            return true;
        });
    }


    public function testValidateDateRangeWithValidDates()
    {
        assertTrue($this->validateDateRange('2024-02-23', '2024-02-24'));
    }

    public function testValidateDateRangeWithInvalidStart()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid start date');
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $this->validateDateRange('invalid_date', '2024-02-24');
    }

    public function testValidateDateRangeWithInvalidEnd()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid end date');
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $this->validateDateRange('2024-02-23', 'invalid_date');
    }

    public function testValidateDateRangeWithStartAfterEnd()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Start date cannot be greater than end date');
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $this->validateDateRange('2024-02-24', '2024-02-23');
    }

    public function testGetRefactoredAttendancesWithDefaultLimit()
    {
        Employee::factory(5)->create();
        Attendance::factory(50)->create();

        $start = now()->subMonth()->format('Y-m-d');
        $end = now()->format('Y-m-d');

        $result = $this->getRefactoredAttendances($start, $end);

        expect($result)->toBeCollection();
        expect(count($result))->toBeLessThanOrEqual(20);

        foreach ($result as $attendance) {
            expect($attendance['date'])->toBeString();
            expect($attendance['name'])->toBeString();
            expect($attendance['arrival_time'])->toBeString();
            expect($attendance['departure_time'])->toBeString();
        }
    }

    public function testGetRefactoredAttendancesWithCustomLimit()
    {
        Employee::factory(5)->create();
        Attendance::factory(30)->create();

        $start = now()->subMonth()->format('Y-m-d');
        $end = now()->format('Y-m-d');
        $limit = 10;

        $result = $this->getRefactoredAttendances($start, $end, $limit);

        expect($result)->toBeCollection();
        expect(count($result))->toBeLessThanOrEqual($limit);

        foreach ($result as $attendance) {
            expect($attendance['date'])->toBeString();
            expect($attendance['name'])->toBeString();
            expect($attendance['arrival_time'])->toBeString();
            expect($attendance['departure_time'])->toBeString();
        }
    }
}
