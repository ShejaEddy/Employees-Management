<?php

namespace App\Http\Controllers\Api;

use App\Exports\AttendanceReport;
use App\Http\Controllers\Controller;
use App\Mail\EmployeeAttendanceRecordMail;
use App\Models\Attendance;
use App\Models\Employee;
use App\Traits\AttendanceTraits;
use App\Traits\BaseTraits;
use App\Traits\EmployeeTraits;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AttendanceController extends Controller
{
    use EmployeeTraits, BaseTraits, AttendanceTraits;

    public function recordArrival($id)
    {
        try {
            $employee = $this->getEmployeeById($id);

            $alreadyRecorded = Attendance::where('employee_id', $id)
                ->whereDate('arrival_time', $this->getDateLimit())
                ->exits();

            if ($alreadyRecorded) {
                throw new BadRequestException('Arrival already recorded for the employee today');
            }

            $attendance = Attendance::create([
                'employee_id' => $id,
                'arrival_time' => now(),
            ]);

            $this->sendAttendanceEmail($employee, Attendance::ATTENDANCE_ARRIVAL_TYPE);

            return $this->respondSuccess($attendance, 'Arrival recorded successfully', 201);
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    public function recordDeparture(Request $request, $id)
    {
        try {
            $employee = $this->getEmployeeById($id);

            $attendance_id = $request->input('attendance_id');

            $alreadyRecorded = Attendance::where('employee_id', $id)
                ->whereDate('departure_time', $this->getDateLimit())
                ->exists();

            if ($alreadyRecorded) {
                throw new BadRequestException('Departure already recorded for the employee today');
            }

            $attendance = Attendance::where('employee_id', $id)
                ->when($attendance_id, fn ($query) => $query->where('id', $id))
                ->when(empty($attendance_id), fn ($query) => $query->whereNull('departure_time'))
                ->latest()
                ->first();

            if (empty($attendance)) {
                throw new BadRequestException('No arrival recorded for the employee');
            }

            $attendance->update([
                'departure_time' => now(),
            ]);

            $this->sendAttendanceEmail($employee, Attendance::ATTENDANCE_DEPARTURE_TYPE);

            return $this->respondSuccess($attendance, 'Arrival recorded successfully', 201);
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    public function getAttendanceExcelReport(Request $request)
    {
        try {
            $from = $request->input('from');
            $to = $request->input('to');
            $limit = $request->input('limit', 20);

            $this->validateDateRange($from, $to);

            $attendance_report = new AttendanceReport($from, $to, $limit);

            return $attendance_report->download('attendance_report.xlsx');
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    public function getAttendancePDFReport(Request $request)
    {
        try {
            $from = $request->input('from');
            $to = $request->input('to');
            $limit = $request->input('limit', 20);

            $this->validateDateRange($from, $to);

            $attendances = $this->getRefactoredAttendances($from, $to, $limit);

            $pdf = SnappyPdf::loadView('reports.pdf-attendance', compact('attendances'))
                ->download('attendance_report.pdf');
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
