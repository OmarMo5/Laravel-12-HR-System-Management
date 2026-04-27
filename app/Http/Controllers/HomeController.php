<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->role_name == 'Employee') {
            return redirect()->route('page/account', $user->user_id);
        }

        $isManager = ($user->role_name == 'Manager');
        $department = $user->department;

        // --------------------------------------------------------------
        // 1. بيانات الموظفين الكلية
        // --------------------------------------------------------------
        $totalEmployeesQuery = User::query();
        if ($isManager) {
            $totalEmployeesQuery->where('department', $department);
        }
        $totalEmployees = $totalEmployeesQuery->count();
        
        // --------------------------------------------------------------
        // 2. بيانات الحضور والغياب والتأخير (لليوم الحالي)
        // --------------------------------------------------------------
        $today = Carbon::today()->toDateString();
        
        $attendanceTodayQuery = Attendance::where('date', $today)
            ->whereNotNull('check_in');
        
        if ($isManager) {
            $attendanceTodayQuery->whereHas('user', function($q) use ($department) {
                $q->where('department', $department);
            });
        }
        $attendanceToday = $attendanceTodayQuery->count();
        
        $lateTodayQuery = Attendance::where('date', $today)
            ->whereNotNull('check_in')
            ->whereTime('check_in', '>', '09:00:00');
        
        if ($isManager) {
            $lateTodayQuery->whereHas('user', function($q) use ($department) {
                $q->where('department', $department);
            });
        }
        $lateToday = $lateTodayQuery->count();
        
        $absentToday = max(0, $totalEmployees - $attendanceToday);
        
        // --------------------------------------------------------------
        // 3. بيانات الموظفين
        // --------------------------------------------------------------
        $employeesQuery = User::select('user_id', 'name', 'email', 'designation', 'position', 'status', 'avatar')
            ->orderBy('created_at', 'desc');
        
        if ($isManager) {
            $employeesQuery->where('department', $department);
        }
        $employees = $employeesQuery->get();
        
        $employeePerformance = $employees->map(function ($employee) {
            $performances = ['Low', 'Good', 'Excellent', 'Average'];
            $randomPerformance = $performances[array_rand($performances)];
            
            $performanceColor = match(strtolower($randomPerformance)) {
                'low' => 'text-red-500',
                'good' => 'text-green-500',
                'excellent' => 'text-purple-500',
                default => 'text-blue-500',
            };
            
            return [
                'id' => $employee->user_id,
                'name' => $employee->name,
                'email' => $employee->email,
                'designation' => $employee->designation ?? $employee->position ?? 'Employee',
                'performance' => $randomPerformance,
                'performance_color' => $performanceColor,
                'status' => $employee->status ?? 'active',
                'avatar' => $employee->avatar ?? 'assets/images/avatar-default.png',
            ];
        });
        
        // --------------------------------------------------------------
        // 4. حساب نسبة الزيادة
        // --------------------------------------------------------------
        $lastMonthQuery = User::whereMonth('created_at', Carbon::now()->subMonth()->month);
        if ($isManager) {
            $lastMonthQuery->where('department', $department);
        }
        $lastMonthCount = $lastMonthQuery->count();
        
        $employeeIncreasePercent = $lastMonthCount > 0 
            ? round((($totalEmployees - $lastMonthCount) / $lastMonthCount) * 100)
            : 0;
        
        return view('dashboard.home', compact(
            'totalEmployees',
            'attendanceToday',
            'lateToday',
            'absentToday',
            'employeePerformance',
            'employeeIncreasePercent'
        ));
    }
}