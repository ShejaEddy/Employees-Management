<?php

namespace App\Http\Controllers\Api;

use App\Exports\AttendanceReport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Traits\AttendanceTraits;
use App\Traits\BaseTraits;
use App\Traits\EmployeeTraits;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use OpenApi\Attributes as OA;

class AttendanceController extends Controller
{
    use EmployeeTraits, BaseTraits, AttendanceTraits;

    #[OA\Post(
        path: "/api/employees/{id}/attendance/arrival",
        description: "Record an employee's arrival",
        tags: ["Attendance"],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/EmployeeId"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Arrival recorded successfully",
                ref: "#/components/responses/RecordAttendanceSuccess"
            ),
            new OA\Response(
                response: 400,
                description: "If the attendance for arrival is already recorded for the employee today",
                ref: "#/components/responses/ArrivalAlreadyRecorded",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
        ]
    )]
    public function recordArrival($id)
    {
        try {
            $employee = $this->getEmployeeById($id);

            $alreadyRecorded = Attendance::where('employee_id', $id)
                ->whereDate('arrival_time', $this->getDateLimit())
                ->exists();

            if ($alreadyRecorded) {
                throw new BadRequestException('Arrival already recorded for the employee today', 400);
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

    #[OA\Post(
        path: "/api/employees/{id}/attendance/departure",
        description: "Record an employee's departure",
        tags: ["Attendance"],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/EmployeeId"
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Departure recorded successfully",
                ref: "#/components/responses/RecordAttendanceSuccess"
            ),
            new OA\Response(
                response: 400,
                description: "If the departure for the employee is already recorded today or no arrival recorded for the employee",
                ref: "#/components/responses/DepartureAlreadyRecorded",
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
        ]
    )]
    public function recordDeparture(Request $request, $id)
    {
        try {
            $employee = $this->getEmployeeById($id);

            $attendance_id = $request->input('attendance_id');

            $alreadyRecorded = Attendance::where('employee_id', $id)
                ->whereDate('departure_time', $this->getDateLimit())
                ->exists();

            if ($alreadyRecorded) {
                throw new BadRequestException('Departure already recorded for the employee today', 400);
            }

            $attendance = Attendance::where('employee_id', $id)
                ->when($attendance_id, fn ($query) => $query->where('id', $id))
                ->when(empty($attendance_id), fn ($query) => $query->whereNull('departure_time'))
                ->latest()
                ->first();

            if (empty($attendance)) {
                throw new BadRequestException('No arrival recorded for the employee', 400);
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

    #[OA\Get(
        path: "/api/attendance/report/excel",
        description: "Download the attendance report in excel format",
        tags: ["Attendance"],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/from"
            ),
            new OA\Parameter(
                ref: "#/components/parameters/to"
            ),
            new OA\Parameter(
                ref: "#/components/parameters/limit"
            )
        ],
        responses: [
            new OA\Response(
                response: 400,
                description: "Invalid date range or format",
                ref: "#/components/responses/InvalidDateRangeResponse"
            ),
            new OA\Response(
                response: 200,
                description: "Excel report downloaded successfully",
                content: new OA\MediaType(
                    mediaType: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    schema: new OA\Schema(
                        type: "string",
                        format: "binary"
                    )
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
        ]
    )]
    public function downloadAttendanceExcelReport(Request $request)
    {
        try {
            $from = $request->input('from');
            $to = $request->input('to');
            $limit = (int) $request->input('limit', 20);

            $this->validateDateRange($from, $to);

            $attendance_report = new AttendanceReport($from, $to, $limit);

            return $attendance_report->download('attendance_report.xlsx');
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }

    #[OA\Get(
        path: "/api/attendance/report/pdf",
        description: "Download the attendance report in pdf format",
        tags: ["Attendance"],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/from"
            ),
            new OA\Parameter(
                ref: "#/components/parameters/to"
            ),
            new OA\Parameter(
                ref: "#/components/parameters/limit"
            )
        ],
        responses: [
            new OA\Response(
                response: 400,
                description: "Invalid date range or format",
                ref: "#/components/responses/InvalidDateRangeResponse"
            ),
            new OA\Response(
                response: 200,
                description: "PDF report downloaded successfully",
                content: new OA\MediaType(
                    mediaType: "application/pdf",
                    schema: new OA\Schema(
                        type: "string",
                        format: "binary"
                    )
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                ref: "#/components/responses/InternalServerError",
            ),
        ]
    )]
    public function downloadAttendancePDFReport(Request $request)
    {
        try {
            $from = $request->input('from');
            $to = $request->input('to');
            $limit = (int) $request->input('limit', 20);

            $this->validateDateRange($from, $to);

            $attendances = $this->getRefactoredAttendances($from, $to, $limit);

            $pdf = SnappyPdf::loadView('reports.pdf-attendance', compact('attendances'))
                ->download('attendance_report.pdf');
        } catch (\Exception $exception) {
            return $this->respondExceptionError($exception);
        }
    }
}
