<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Attendance;
use Carbon\Carbon;
use Session;

class AttendanceViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $userId = Session::get('user_id');
        
        if ($userId) {
            $now = Carbon::now('Africa/Cairo');
            $today = $now->toDateString();

            $todayAttendance = Attendance::where('user_id', $userId)
                ->whereDate('date', $today)
                ->first();

            $view->with([
                'globalCheckedIn' => $todayAttendance && $todayAttendance->check_in ? true : false,
                'globalCheckedOut' => $todayAttendance && $todayAttendance->check_out ? true : false,
                'globalTodayAttendance' => $todayAttendance,
                'globalServerTime' => $now,
            ]);
        }
    }
}
