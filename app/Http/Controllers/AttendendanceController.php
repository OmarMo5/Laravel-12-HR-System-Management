<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Leave;
use Carbon\Carbon;

class AttendendanceController extends Controller
{
    /** Employee Attendance Dashboard */
    public function employeeAttendanceDashboard(Request $request)
    {
        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();
        
        $now = Carbon::now('Africa/Cairo');
        $month = (int)$request->get('month', $now->month);
        $year = (int)$request->get('year', $now->year);
        
        $selectedDate = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Cairo');
        $monthStart = $selectedDate->copy()->startOfMonth();
        $monthEnd = $selectedDate->copy()->endOfMonth();
        $daysInMonth = $monthEnd->day;

        // Fetch attendance for the month
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->date)->toDateString();
            });

        // Fetch holidays for the month
        $holidays = Holiday::where(function($query) use ($monthStart, $monthEnd) {
            $query->whereBetween('start_date', [$monthStart, $monthEnd])
                  ->orWhereBetween('end_date', [$monthStart, $monthEnd]);
        })->get();

        // Fetch approved leaves for the month
        $leaves = Leave::where('staff_id', $userId)
            ->where('status', 'Approved')
            ->where(function($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('date_from', [$monthStart, $monthEnd])
                      ->orWhereBetween('date_to', [$monthStart, $monthEnd]);
            })->get();

        $calendarData = [];
        $totalWorkedMinutes = 0;
        $totalExpectedMinutes = 0;
        $totalLeaveMinutes = 0;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $selectedDate->copy()->day($day);
            $dateString = $date->toDateString();
            
            $isWeekend = Attendance::isWeekend($date);
            $holiday = $holidays->first(function($h) use ($date) {
                return $date->between($h->start_date, $h->end_date);
            });
            
            $leave = $leaves->first(function($l) use ($date) {
                $from = Carbon::parse($l->date_from);
                $to = Carbon::parse($l->date_to);
                return $date->between($from, $to);
            });

            $attendance = $attendances->get($dateString);
            
            $status = 'future';
            if ($attendance) {
                $status = $attendance->status;
                $totalWorkedMinutes += abs($attendance->working_hours) * 60;
            }

            if ($date->lte($now->copy()->endOfDay())) {
                if (!$attendance) {
                    if ($isWeekend) {
                        $status = 'weekend';
                    } elseif ($holiday) {
                        $status = 'holiday';
                    } elseif ($leave) {
                        $status = 'leave';
                        $totalLeaveMinutes += 8.5 * 60; 
                    } else {
                        $status = 'absent';
                    }
                }
            }

            if (!$isWeekend && !$holiday) {
                $totalExpectedMinutes += 8.5 * 60;
            }

            $calendarData[] = [
                'date' => $date,
                'date_string' => $dateString,
                'day_name' => $date->format('D'),
                'day_num' => $day,
                'is_weekend' => $isWeekend,
                'holiday' => $holiday,
                'leave' => $leave,
                'attendance' => $attendance,
                'status' => $status,
                'is_today' => $date->isToday(),
            ];
        }

        $monthlyStats = [
            'expected_hours' => round($totalExpectedMinutes / 60, 1),
            'worked_hours' => round($totalWorkedMinutes / 60, 1),
            'break_hours' => 0, 
            'leave_hours' => round($totalLeaveMinutes / 60, 1),
            'remaining_hours' => round(($totalExpectedMinutes - $totalWorkedMinutes - $totalLeaveMinutes) / 60, 1),
        ];

        $todayAttendance = Attendance::where('user_id', $userId)->whereDate('date', $now->toDateString())->first();
        $checkedIn = $todayAttendance && $todayAttendance->check_in ? true : false;
        $checkedOut = $todayAttendance && $todayAttendance->check_out ? true : false;
        $currentTime = $now;

        $listData = array_reverse(array_filter($calendarData, function($day) use ($now) {
            return $day['date']->lte($now->copy()->endOfDay());
        }));

        return view('HR.EmployeeAttendance.employee-dashboard', compact(
            'user', 'calendarData', 'listData', 'monthlyStats', 'month', 'year', 'selectedDate',
            'todayAttendance', 'checkedIn', 'checkedOut', 'currentTime'
        ));
    }

    private function calculateAbsentDays($userId, $startDate, $endDate)
    {
        $workingDays = 0;
        $period = Carbon::parse($startDate)->daysUntil($endDate);

        foreach ($period as $date) {
            if ($date->isWeekday()) {
                $workingDays++;
            }
        }

        $presentDays = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('status', ['present', 'late', 'early_departure', 'late_early'])
            ->count();

        return max(0, $workingDays - $presentDays);
    }

    /** Employee Check In */
    public function employeeCheckIn(Request $request)
    {
        try {
            $userId = Session::get('user_id');
            $user = User::where('user_id', $userId)->first();
            
            $now = Carbon::now('Africa/Cairo');
            $today = $now->toDateString();

            $existing = Attendance::where('user_id', $userId)
                ->whereDate('date', $today)
                ->first();

            if ($existing && $existing->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already checked in today!'
                ], 400);
            }

            $lateThreshold = Carbon::parse($today . ' 10:00:00', 'Africa/Cairo');
            $lateMinutes = 0;
            $status = 'present';

            if ($now->gt($lateThreshold)) {
                $lateMinutes = $lateThreshold->diffInMinutes($now);
                $status = 'late';
            }

            $attendance = Attendance::updateOrCreate(
                ['user_id' => $userId, 'date' => $today],
                [
                    'check_in' => $now->format('H:i:s'),
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'notes' => $request->notes ?? null
                ]
            );

            // Get updated data
            $todayAttendance = Attendance::where('user_id', $userId)->whereDate('date', $today)->first();
            $checkedIn = true;
            $checkedOut = $todayAttendance && $todayAttendance->check_out ? true : false;

            $recentAttendance = Attendance::where('user_id', $userId)
                ->orderBy('date', 'desc')
                ->limit(7)
                ->get()
                ->map(function($item) {
                    if ($item->check_in) {
                        $item->check_in_display = Carbon::parse($item->check_in)->format('h:i A');
                    }
                    if ($item->check_out) {
                        $item->check_out_display = Carbon::parse($item->check_out)->format('h:i A');
                    }
                    return $item;
                });

            $monthStart = Carbon::now('Africa/Cairo')->startOfMonth();
            $monthEnd = Carbon::now('Africa/Cairo')->endOfMonth();

            $monthlyStats = [
                'present' => Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->whereIn('status', ['present', 'late', 'early_departure', 'late_early'])
                    ->count(),
                'late' => Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->where('status', 'late')
                    ->count(),
                'absent' => $this->calculateAbsentDays($userId, $monthStart, $monthEnd),
                'total_hours' => round(Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->sum('working_hours'), 1),
            ];

            // Render partials
            $recentAttendanceHtml = view('HR.Attendance.partials.recent-attendance-table', compact('recentAttendance'))->render();
            $statsCardsHtml = view('HR.Attendance.partials.monthly-stats-cards', compact('monthlyStats'))->render();
            $checkInCardHtml = view('HR.Attendance.partials.check-in-card', compact('checkedIn', 'todayAttendance', 'checkedOut'))->render();
            $checkOutCardHtml = view('HR.Attendance.partials.check-out-card', compact('checkedIn', 'checkedOut', 'todayAttendance'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Check-in successful! Time: ' . $now->format('h:i A'),
                'html' => [
                    'recentAttendance' => $recentAttendanceHtml,
                    'statsCards' => $statsCardsHtml,
                    'checkInCard' => $checkInCardHtml,
                    'checkOutCard' => $checkOutCardHtml
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Check-in error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Check-in failed! ' . $e->getMessage()
            ], 500);
        }
    }

    /** Employee Check Out */
    public function employeeCheckOut(Request $request)
    {
        try {
            $userId = Session::get('user_id');
            $user = User::where('user_id', $userId)->first();
            
            $now = Carbon::now('Africa/Cairo');
            $today = $now->toDateString();

            $attendance = Attendance::where('user_id', $userId)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance || !$attendance->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must check in first!'
                ], 400);
            }

            if ($attendance->check_out) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already checked out today!'
                ], 400);
            }

            $checkIn = Carbon::parse($attendance->check_in);
            $checkOut = $now;
            $workingHours = round($checkOut->diffInMinutes($checkIn) / 60, 2);

            $requiredHours = 8.5;
            $earlyMinutes = 0;
            $status = $attendance->status;

            if ($workingHours < $requiredHours) {
                $earlyMinutes = ($requiredHours - $workingHours) * 60;
                $status = ($status == 'late') ? 'late_early' : 'early_departure';
            }

            $overtimeHours = max(0, $workingHours - $requiredHours);

            $attendance->check_out = $now->format('H:i:s');
            $attendance->working_hours = $workingHours;
            $attendance->overtime_hours = $overtimeHours;
            $attendance->early_departure_minutes = $earlyMinutes;
            $attendance->status = $status;
            $attendance->notes = $request->notes ?? $attendance->notes;
            $attendance->save();

            // Get updated data
            $todayAttendance = Attendance::where('user_id', $userId)->whereDate('date', $today)->first();
            $checkedIn = true;
            $checkedOut = true;

            $recentAttendance = Attendance::where('user_id', $userId)
                ->orderBy('date', 'desc')
                ->limit(7)
                ->get()
                ->map(function($item) {
                    if ($item->check_in) {
                        $item->check_in_display = Carbon::parse($item->check_in)->format('h:i A');
                    }
                    if ($item->check_out) {
                        $item->check_out_display = Carbon::parse($item->check_out)->format('h:i A');
                    }
                    return $item;
                });

            $monthStart = Carbon::now('Africa/Cairo')->startOfMonth();
            $monthEnd = Carbon::now('Africa/Cairo')->endOfMonth();

            $monthlyStats = [
                'present' => Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->whereIn('status', ['present', 'late', 'early_departure', 'late_early'])
                    ->count(),
                'late' => Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->where('status', 'late')
                    ->count(),
                'absent' => $this->calculateAbsentDays($userId, $monthStart, $monthEnd),
                'total_hours' => round(Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->sum('working_hours'), 1),
            ];

            // Render partials
            $recentAttendanceHtml = view('HR.Attendance.partials.recent-attendance-table', compact('recentAttendance'))->render();
            $statsCardsHtml = view('HR.Attendance.partials.monthly-stats-cards', compact('monthlyStats'))->render();
            $checkInCardHtml = view('HR.Attendance.partials.check-in-card', compact('checkedIn', 'todayAttendance', 'checkedOut'))->render();
            $checkOutCardHtml = view('HR.Attendance.partials.check-out-card', compact('checkedIn', 'checkedOut', 'todayAttendance'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Check-out successful! You worked ' . number_format($workingHours, 1) . ' hours.',
                'html' => [
                    'recentAttendance' => $recentAttendanceHtml,
                    'statsCards' => $statsCardsHtml,
                    'checkInCard' => $checkInCardHtml,
                    'checkOutCard' => $checkOutCardHtml
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Check-out error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Check-out failed! ' . $e->getMessage()
            ], 500);
        }
    }

    /** Employee Attendance History */
    public function employeeAttendanceHistory(Request $request)
    {
        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();
        
        $month = $request->get('month', Carbon::now('Africa/Cairo')->month);
        $year = $request->get('year', Carbon::now('Africa/Cairo')->year);

        $startDate = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Cairo')->startOfMonth();
        $endDate = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Cairo')->endOfMonth();

        $daysInMonth = $endDate->day;
        $firstDayOfWeek = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Cairo')->dayOfWeek;
        $firstDayOfWeek = $firstDayOfWeek == 0 ? 6 : $firstDayOfWeek - 1;

        $employees = User::where('status', 'Active')->orderBy('name')->get();
        $selectedEmployee = $request->get('employee_id', $userId);
        
        $userRole = Session::get('role_name');
        $isHR = in_array($userRole, ['Admin', 'HR Manager', 'HR']);
        
        $attendanceQuery = Attendance::whereBetween('date', [$startDate, $endDate]);
        
        if ($isHR && $selectedEmployee != 'all') {
            $attendanceQuery->where('user_id', $selectedEmployee);
        } elseif (!$isHR) {
            $attendanceQuery->where('user_id', $userId);
        }
        
        $attendances = $attendanceQuery->orderBy('date', 'desc')->paginate(15);
        
        $selectedUser = null;
        if ($selectedEmployee != 'all') {
            $selectedUser = User::where('user_id', $selectedEmployee)->first();
        }

        return view('HR.EmployeeAttendance.employee-history', compact(
            'user', 'attendances', 'month', 'year', 'daysInMonth', 'firstDayOfWeek',
            'employees', 'selectedEmployee', 'selectedUser', 'isHR'
        ));
    }
}