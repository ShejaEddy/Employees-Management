<?php
namespace App\Traits;

trait AttendanceTraits {

    public function getDateLimit() {
        // Today, but you can set limit to more days
        return now()->toDateString();
    }
}
