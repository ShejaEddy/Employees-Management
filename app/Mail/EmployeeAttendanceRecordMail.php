<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeAttendanceRecordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $type;
    public string $time_recorded;
    public string $employee_name;

    public function __construct($employee_name, $type, $time_recorded)
    {
        $this->employee_name = $employee_name;
        $this->type = $type;
        $this->time_recorded = $time_recorded;
    }


    public function build()
    {
        return $this->view('emails.employee_attendance_mail')
            ->subject('Attendance Recorded');
    }
}
