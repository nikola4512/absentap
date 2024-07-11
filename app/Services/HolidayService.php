<!-- // app/Services/HolidayService.php -->
<?php
namespace App\Services;

use App\Models\Holiday;
use Carbon\Carbon;

class HolidayService
{
    public function isHoliday($date)
    {
        $date = Carbon::parse($date);

        // Check if the date is a weekend
        if ($date->isWeekend()) {
            return true;
        }

        // Check if the date is a national holiday
        $holiday = Holiday::where('date', $date->format('Y-m-d'))->first();

        return $holiday ? true : false;
    }
}