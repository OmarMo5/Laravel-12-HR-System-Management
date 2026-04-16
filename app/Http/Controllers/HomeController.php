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
        // --------------------------------------------------------------
        // 1. بيانات الموظفين الكلية
        // --------------------------------------------------------------
        $totalEmployees = User::count();
        
        // --------------------------------------------------------------
        // 2. بيانات الحضور والغياب والتأخير (لليوم الحالي)
        // --------------------------------------------------------------
        $today = Carbon::today()->toDateString();
        
        $attendanceToday = Attendance::where('date', $today)
            ->whereNotNull('check_in')
            ->count();
        
        $lateToday = Attendance::where('date', $today)
            ->whereNotNull('check_in')
            ->whereTime('check_in', '>', '09:00:00')
            ->count();
        
        $absentToday = $totalEmployees - $attendanceToday;
        
        // --------------------------------------------------------------
        // 3. بيانات الموظفين
        // --------------------------------------------------------------
        $employees = User::select('user_id', 'name', 'email', 'designation', 'position', 'status', 'avatar')
            ->orderBy('created_at', 'desc')
            ->get();
        
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
        $lastMonthCount = User::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
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