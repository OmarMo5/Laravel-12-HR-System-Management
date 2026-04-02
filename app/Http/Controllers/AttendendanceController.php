<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendendanceController extends Controller
{
    /** Employee Attendance Dashboard */
    public function employeeAttendanceDashboard()
    {
        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();
        $today = Carbon::today();
        $year = Carbon::now()->year;

        // Get today's attendance record
        $todayAttendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        // Get current server time
        $currentTime = Carbon::now();

        // Check if already checked in today
        $checkedIn = $todayAttendance && $todayAttendance->check_in ? true : false;
        $checkedOut = $todayAttendance && $todayAttendance->check_out ? true : false;

        // Get last 7 days attendance for history
        $recentAttendance = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        // Get monthly stats
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

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
            'total_hours' => Attendance::where('user_id', $userId)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('working_hours'),
        ];

        return view('HR.EmployeeAttendance.employee-dashboard', compact(
            'user',
            'todayAttendance',
            'currentTime',
            'checkedIn',
            'checkedOut',
            'recentAttendance',
            'monthlyStats',
            'year'
        ));
    }

    /** Calculate absent days (weekdays only) */
    private function calculateAbsentDays($userId, $startDate, $endDate)
    {
        $workingDays = 0;
        $period = Carbon::parse($startDate)->daysUntil($endDate);

        foreach ($period as $date) {
            // Skip weekends (Saturday and Sunday)
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
            $now = Carbon::now();
            $today = $now->toDateString();

            // Check if already checked in
            $existing = Attendance::where('user_id', $userId)
                ->whereDate('date', $today)
                ->first();

            if ($existing) {
                if ($existing->check_in) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already checked in today!'
                    ], 400);
                }
            }

            // Determine if late (after 10:00 AM)
            $lateThreshold = Carbon::parse($today . ' 10:00:00');
            $lateMinutes = 0;
            $status = 'present';

            if ($now->gt($lateThreshold)) {
                $lateMinutes = $lateThreshold->diffInMinutes($now);
                $status = 'late';
            }

            // Create or update attendance
            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $userId,
                    'date' => $today
                ],
                [
                    'check_in' => $now->format('H:i:s'),
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'notes' => $request->notes ?? null
                ]
            );

            // Get updated recent attendance
            $recentAttendance = Attendance::where('user_id', $userId)
                ->orderBy('date', 'desc')
                ->limit(7)
                ->get();

            // Get updated monthly stats
            $monthStart = Carbon::now()->startOfMonth();
            $monthEnd = Carbon::now()->endOfMonth();

            $monthlyStats = [
                'present' => Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->whereIn('status', ['present', 'late', 'early_departure', 'late_early'])
                    ->count(),
                'late' => Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->where('status', 'late')
                    ->count(),
                'total_hours' => Attendance::where('user_id', $userId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->sum('working_hours'),
            ];

            // Render the updated table HTML
            $tableHtml = view('HR.Attendance.partials.recent-attendance-table', compact('recentAttendance'))->render();
            $statsHtml = view('HR.Attendance.partials.monthly-stats-cards', compact('monthlyStats'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Check-in successful!',
                'data' => [
                    'check_in' => $now->format('h:i A'),
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'user_name' => $user->name,
                    'user_avatar' => $user->avatar ?? 'profile.png'
                ],
                'tableHtml' => $tableHtml,
                'statsHtml' => $statsHtml,
                'checkedIn' => true,
                'todayAttendance' => [
                    'check_in' => $now->format('h:i A'),
                    'late_minutes' => $lateMinutes,
                    'status' => $status
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Check-in failed!'
            ], 500);
        }
    }

    /** Employee Check Out */
    public function employeeCheckOut(Request $request)
    {
        try {
            $userId = Session::get('user_id');
            $user = User::where('user_id', $userId)->first();
            $now = Carbon::now();
            $today = $now->toDateString();

            // Get today's attendance
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

            // Calculate working hours
            $checkIn = Carbon::parse($attendance->check_in);
            $checkOut = $now;

            $workingHours = round($checkOut->diffInMinutes($checkIn) / 60, 2);

            // Check if left early (before 8 hours)
            $requiredHours = 8;
            $earlyMinutes = 0;
            $status = $attendance->status;

            if ($workingHours < $requiredHours) {
                $earlyMinutes = ($requiredHours - $workingHours) * 60;
                // If already late, mark as late_early, else early_departure
                $status = ($status == 'late') ? 'late_early' : 'early_departure';
            } else {
                // If worked 8+ hours and was late, keep late status
                $status = ($status == 'late') ? 'late' : 'present';
            }

            // Calculate overtime
            $overtimeHours = max(0, $workingHours - $requiredHours);

            // Update attendance
            $attendance->check_out = $now->format('H:i:s');
            $attendance->working_hours = $workingHours;
            $attendance->overtime_hours = $overtimeHours;
            $attendance->early_departure_minutes = $earlyMinutes;
            $attendance->status = $status;
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Check-out successful! You worked ' . number_format($workingHours, 1) . ' hours.',
                'data' => [
                    'check_out' => $now->format('h:i A'),
                    'working_hours' => $workingHours,
                    'overtime_hours' => $overtimeHours,
                    'status' => $status
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Check-out failed!'
            ], 500);
        }
    }

    /** Employee Attendance History */
    public function employeeAttendanceHistory(Request $request)
    {
        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // عدد أيام الشهر
        $daysInMonth = $endDate->day;

        // أول يوم في الشهر (للتحكم في بداية التقويم)
        $firstDayOfWeek = Carbon::create($year, $month, 1)->dayOfWeek;
        $firstDayOfWeek = $firstDayOfWeek == 0 ? 6 : $firstDayOfWeek - 1; // تعديل ليبدأ من الاثنين

        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('HR.EmployeeAttendance.employee-history', compact(
            'user',
            'attendances',
            'month',
            'year',
            'daysInMonth', // أضف هذا
            'firstDayOfWeek' // أضف هذا
        ));
    }
}
