<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Traits\AttendanceTraits;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceReport implements FromCollection, WithHeadings
{
    use Exportable;
    use AttendanceTraits;

    protected $from;
    protected $to;
    protected $limit;

    public function __construct(?string $from = null, ?string $to = null, ?int $limit = 20)
    {
        $this->from = $from;
        $this->to = $to;
        $this->limit = $limit;
    }

    public function collection()
    {
        return $this->getRefactoredAttendances($this->from, $this->to);
    }

    public function headings(): array
    {
        return [
            'Date recorded',
            'Employee Name',
            'Arrival Time',
            'Departure Time',
        ];
    }
}
