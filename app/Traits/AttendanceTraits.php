<?php

namespace App\Traits;

use App\Mail\EmployeeAttendanceRecordMail;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Exception;

trait AttendanceTraits
{
    use BaseTraits, EmployeeTraits;

    public function getDateLimit()
    {
        // Today, but you can set limit to more days
        return now()->toDateString();
    }

    protected function sendAttendanceEmail(Employee $employee, string $type)
    {
        $name = $this->getFirstName($employee->names);
        $email = $employee->email;
        $time = now()->format('h:i A');

        $this->sendEmail(EmployeeAttendanceRecordMail::class, $email, [$name, $type, $time]);
    }

    public function validateDateRange(?string $start, ?string $end)
    {
        if ($start && !strtotime($start)) {
            throw new Exception('Invalid start date', Response::HTTP_BAD_REQUEST);
        }

        if ($end && !strtotime($end)) {
            throw new Exception('Invalid end date', Response::HTTP_BAD_REQUEST);
        }

        if ($start && $end && strtotime($start) > strtotime($end)) {
            throw new Exception('Start date cannot be greater than end date', Response::HTTP_BAD_REQUEST);
        }

        return true;
    }

    public function getRefactoredAttendances(?string $start = null, ?string $end = null, ?int $limit = 20): Collection
    {
        $data = Attendance::with('employee:id,names')
            ->when($start, function ($query) use ($start) {
                $start = Carbon::parse($start)->format('Y-m-d');
                return $query->whereDate('arrival_time', '>=', $start);
            })
            ->when($end, function ($query) use ($end) {
                $end = Carbon::parse($end)->format('Y-m-d');
                return $query->whereDate('arrival_time', '<=', $end);
            })
            ->when($limit, function ($query) use ($limit) {
                return $query->limit($limit);
            })
            ->get();

            return $data->map(function ($attendance) {
                $employee_name = $attendance->employee->names ?? 'N/A';
                $departure_time = $attendance->departure_time ? Carbon::parse($attendance->departure_time)->format('H:i A') : 'N/A';
                $arrival_time = $attendance->arrival_time ? Carbon::parse($attendance->arrival_time)->format('H:i A') : 'N/A';
                $date = $attendance->arrival_time ? Carbon::parse($attendance->arrival_time)->format('Y-m-d') : 'N/A';

                return [
                    'date' => $date,
                    'name' => $employee_name,
                    'arrival_time' => $arrival_time,
                    'departure_time' => $departure_time,
                ];
            });
    }
}
