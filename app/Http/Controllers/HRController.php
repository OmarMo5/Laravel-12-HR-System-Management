<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Session;
use Validator;
use App\Models\User;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\LeaveInformation;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class HRController extends Controller
{

    public function changeLang($lang)
    {
        App::setLocale($lang);
        session()->put('locale', $lang);
        return redirect()->back();
    }

    /** Employee list */
    public function employeeList(Request $request)
    {
        // Search functionality
        $search = $request->input('search');
        $employeeList = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('user_id', 'like', '%' . $search . '%')
                ->orWhere('position', 'like', '%' . $search . '%')
                ->orWhere('department', 'like', '%' . $search . '%');
        })->paginate(10); // Pagination with 10 items per page

        // Get the latest user ID and generate the next employee ID
        $latestUser = User::orderBy('id', 'DESC')->first();
        $userId     = $latestUser ? (int) substr($latestUser->user_id, 4) + 1 : 1;
        $employeeId = 'KH_' . str_pad($userId, 3, '0', STR_PAD_LEFT);

        // Retrieve necessary data for the view
        $roleName = DB::table('role_type_users')->get();
        $position = DB::table('position_types')->get();
        $department = DB::table('departments')->get();
        $statusUser = DB::table('user_types')->get();

        return view('HR.employee', compact('employeeList', 'employeeId', 'roleName', 'position', 'department', 'statusUser', 'search'));
    }

    public function employeeSaveRecord(Request $request)
    {
        $request->validate([
            'avatar'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Changed from photo to avatar
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'position'     => 'required|string',
            'department'   => 'required|string',
            'role_name'    => 'required|string',
            'status'       => 'required|string',
            'phone_number' => 'required|numeric',
            'location'     => 'required|string',
            'join_date'    => 'required|string',
            'experience'   => 'required|string',
            'designation'  => 'required|string',
            'user_id'      => 'required|string|unique:users', // التأكد من أن user_id فريد
        ]);

        try {
            // Generate a unique file name for the avatar
            $avatarName = time() . '_' . $request->name . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('assets/images/user'), $avatarName);

            // Create a new user instance and populate fields
            $register = new User();
            $register->user_id      = $request->user_id; // Added user_id from the hidden field
            $register->name         = $request->name;
            $register->email        = $request->email;
            $register->position     = $request->position;
            $register->department   = $request->department;
            $register->role_name    = $request->role_name;
            $register->status       = $request->status;
            $register->phone_number = $request->phone_number;
            $register->location     = $request->location;
            $register->join_date    = $request->join_date;
            $register->experience   = $request->experience;
            $register->designation  = $request->designation;
            $register->avatar       = $avatarName; // Changed from $photo to $avatarName
            $register->password     = Hash::make('Hello@123'); // Default password
            $register->save();

            flash()->success('Employee added successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error saving employee: ' . $e->getMessage());
            flash()->error('Failed to add employee. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    /** Update Record Employee */
    public function employeeUpdateRecord(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email,' . $request->id, // Ignore current user's email
            'position'     => 'required|string',
            'department'   => 'required|string',
            'role_name'    => 'required|string',
            'status'       => 'required|string',
            'phone_number' => 'required|numeric',
            'location'     => 'required|string',
            'join_date'    => 'required|string',
            'experience'   => 'required|string',
            'designation'  => 'required|string',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Changed to avatar and nullable
        ]);

        try {
            $user = User::findOrFail($request->id);

            // Handle avatar upload
            if ($request->hasFile('avatar')) { // Changed from 'photo'
                // Delete old avatar if exists
                if (!empty($user->avatar) && file_exists(public_path('assets/images/user/' . $user->avatar))) {
                    unlink(public_path('assets/images/user/' . $user->avatar));
                }

                // Generate a unique file name for the new avatar
                $avatarName = time() . '_' . $request->name . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('assets/images/user'), $avatarName);
                $user->avatar = $avatarName; // Changed from $photo
            }

            // Update other fields
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->position     = $request->position;
            $user->department   = $request->department;
            $user->role_name    = $request->role_name;
            $user->status       = $request->status;
            $user->phone_number = $request->phone_number;
            $user->location     = $request->location;
            $user->join_date    = $request->join_date;
            $user->experience   = $request->experience;
            $user->designation  = $request->designation;
            // user_id is usually not updated, so we don't touch it.

            $user->save();

            flash()->success('Employee record updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::info('Error updating employee: ' . $e->getMessage());
            flash()->error('Failed to update employee record. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    /** Delete Record Employee */
    public function employeeDeleteRecord(Request $request)
    {
        $request->validate([
            'id_delete' => 'required|exists:users,id',
        ]);

        try {
            $user = User::findOrFail($request->id_delete);

            // Delete avatar file if exists
            if (!empty($user->avatar) && file_exists(public_path('assets/images/user/' . $user->avatar))) {
                unlink(public_path('assets/images/user/' . $user->avatar));
            }

            $user->delete();

            flash()->success('Employee deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::info('Error deleting employee: ' . $e->getMessage());
            flash()->error('Failed to delete employee.');
            return redirect()->back();
        }
    }

    /** holiday Page */
    public function holidayPage(Request $request)
    {
        $search = $request->input('search');
        $holidayList = Holiday::when($search, function ($query, $search) {
            return $query->where('holiday_name', 'like', '%' . $search . '%')
                ->orWhere('holiday_type', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('HR.holidays', compact('holidayList', 'search'));
    }

    /** save record holiday */
    public function holidaySaveRecord(Request $request)
    {
        $request->validate([
            'holiday_type' => 'required|string',
            'holiday_name' => 'required|string',
            'holiday_date' => 'required|string',
        ]);

        try {
            $holiday = Holiday::updateOrCreate(
                ['id' => $request->idUpdate],
                [
                    'holiday_type' => $request->holiday_type,
                    'holiday_name' => $request->holiday_name,
                    'holiday_date' => $request->holiday_date,
                ]
            );

            flash()->success('Holiday created or updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to add holiday :)');
            return redirect()->back();
        }
    }

    /** delete record */
    public function holidayDeleteRecord(Request $request)
    {
        try {
            $holiday = Holiday::findOrFail($request->id_delete);
            $holiday->delete();

            flash()->success('Holiday deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to delete holiday :)');
            return redirect()->back();
        }
    }

    /** get information leave */
    public function getInformationLeave(Request $request)
    {
        try {
            $numberOfDay = $request->number_of_day ?? 0;
            $leaveType   = $request->leave_type;
            $staffId     = $request->staff_id ?? Session::get('user_id');

            // Get total used leaves for this staff and leave type
            $usedLeaves = Leave::where('staff_id', $staffId)
                ->where('leave_type', $leaveType)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');

            $leaveInfo = LeaveInformation::where('leave_type', $leaveType)->first();

            if ($leaveInfo) {
                $remainingDays = $leaveInfo->leave_days - $usedLeaves - ($numberOfDay ?? 0);
            } else {
                $remainingDays = 0;
            }

            $data = [
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Get success',
                'leave_type'    => max(0, $remainingDays),
                'number_of_day' => $numberOfDay,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    /** get employee leave info for HR page */
    /** get employee leave info for HR page */
    public function getEmployeeLeaveInfo(Request $request)
    {
        try {
            $staffId = $request->staff_id;
            $leaveInfo = [];

            if ($staffId) {
                $leaveTypes = LeaveInformation::all();

                foreach ($leaveTypes as $type) {
                    $usedLeaves = Leave::where('staff_id', $staffId)
                        ->where('leave_type', $type->leave_type)
                        ->whereIn('status', ['Approved', 'Pending'])
                        ->sum('number_of_day');

                    $remaining = $type->leave_days - $usedLeaves;
                    $leaveInfo[$type->leave_type] = max(0, $remaining);
                }
            }

            return response()->json([
                'response_code' => 200,
                'data' => $leaveInfo
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'response_code' => 500,
                'error' => 'An error occurred.'
            ], 500);
        }
    }

    /** leave Employee */
    public function leaveEmployee(Request $request)
    {
        $search = $request->input('search');
        $staffId = Session::get('user_id');

        $leave = Leave::where('staff_id', $staffId)
            ->when($search, function ($query, $search) {
                return $query->where('leave_type', 'like', '%' . $search . '%')
                    ->orWhere('reason', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate leave statistics
        $totalLeaves = Leave::where('staff_id', $staffId)->count();
        $approvedLeaves = Leave::where('staff_id', $staffId)->where('status', 'Approved')->count();
        $pendingLeaves = Leave::where('staff_id', $staffId)->where('status', 'Pending')->count();
        $rejectedLeaves = Leave::where('staff_id', $staffId)->where('status', 'Rejected')->count();

        return view('HR.LeavesManage.leave-employee', compact('leave', 'search', 'totalLeaves', 'approvedLeaves', 'pendingLeaves', 'rejectedLeaves'));
    }

    /** create Leave Employee */
    public function createLeaveEmployee()
    {
        $staffId = Session::get('user_id');
        $leaveInformation = LeaveInformation::all();
        // Calculate remaining leaves for each type
        $remainingLeaves = [];
        foreach ($leaveInformation as $info) {
            $usedLeaves = Leave::where('staff_id', $staffId)
                ->where('leave_type', $info->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');
            $remainingLeaves[$info->leave_type] = max(0, $info->leave_days - $usedLeaves);
        }

        return view('HR.LeavesManage.create-leave-employee', compact('leaveInformation', 'remainingLeaves'));
    }

    /** save record leave */
    public function saveRecordLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'date_from'  => 'required',
            'date_to'    => 'required',
            'reason'     => 'required',
        ]);
        try {
            // Check if enough leave days remaining
            $leaveInfo = LeaveInformation::where('leave_type', $request->leave_type)->first();
            $usedLeaves = Leave::where('staff_id', Session::get('user_id'))
                ->where('leave_type', $request->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');

            $remainingDays = $leaveInfo->leave_days - $usedLeaves;

            if ($remainingDays < $request->number_of_day) {
                flash()->error('Insufficient leave balance!');
                return redirect()->back();
            }

            $save = new Leave;
            $save->staff_id         = Session::get('user_id');
            $save->employee_name    = Session::get('name');
            $save->leave_type       = $request->leave_type;
            $save->remaining_leave  = $remainingDays - $request->number_of_day;
            $save->date_from        = $request->date_from;
            $save->date_to          = $request->date_to;
            $save->number_of_day    = $request->number_of_day;
            $save->leave_date       = json_encode($request->leave_date);
            $save->leave_day        = json_encode($request->select_leave_day);
            $save->status           = 'Pending';
            $save->reason           = $request->reason;
            $save->save();

            flash()->success('Apply Leave successfully :)');
            return redirect()->route('hr/leave/employee/page');
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed Apply Leave :)');
            return redirect()->back();
        }
    }

    /** view detail leave employee */
    public function viewDetailLeave($id)
    {
        $leaveInformation = LeaveInformation::all();
        $leaveDetail = Leave::findOrFail($id);
        $leaveDate   = json_decode($leaveDetail->leave_date, true);
        $leaveDay    = json_decode($leaveDetail->leave_day, true);
        $user = User::where('user_id', $leaveDetail->staff_id)->first();

        return view('HR.LeavesManage.view-detail-leave', compact('leaveInformation', 'leaveDetail', 'leaveDate', 'leaveDay', 'user'));
    }

    /** leave HR - List all leaves */
    public function leaveHR(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $leaveType = $request->input('leave_type');

        $query = Leave::with('user')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', '%' . $search . '%')
                    ->orWhere('staff_id', 'like', '%' . $search . '%')
                    ->orWhere('reason', 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($leaveType) {
            $query->where('leave_type', $leaveType);
        }

        $leaves = $query->paginate(10);

        // Statistics
        $today = date('Y-m-d');
        $todayLeaves = Leave::whereDate('date_from', '<=', $today)
            ->whereDate('date_to', '>=', $today)
            ->where('status', 'Approved')
            ->count();

        $totalLeaves = Leave::count();
        $pendingLeaves = Leave::where('status', 'Pending')->count();
        $approvedLeaves = Leave::where('status', 'Approved')->count();
        $rejectedLeaves = Leave::where('status', 'Rejected')->count();

        $leaveTypes = LeaveInformation::all();

        return view('HR.LeavesManage.leave-hr', compact(
            'leaves',
            'search',
            'status',
            'leaveType',
            'todayLeaves',
            'totalLeaves',
            'pendingLeaves',
            'approvedLeaves',
            'rejectedLeaves',
            'leaveTypes'
        ));
    }

    /** create Leave HR */
    public function createLeaveHR()
    {
        $users = User::all();
        $leaveInformation = LeaveInformation::all();
        return view('HR.LeavesManage.create-leave-hr', compact('users', 'leaveInformation'));
    }

    /** save record leave HR */
    public function saveRecordLeaveHR(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string',
            'leave_type'    => 'required|string',
            'date_from'     => 'required',
            'date_to'       => 'required',
            'reason'        => 'required',
            'leave_date'    => 'required|array',
            'select_leave_day' => 'required|array',
            'number_of_day' => 'required|numeric',
        ]);

        try {
            // Get user by name
            $user = User::where('name', $request->employee_name)->first();

            if (!$user) {
                flash()->error('Employee not found!');
                return redirect()->back();
            }

            // Check leave balance
            $leaveInfo = LeaveInformation::where('leave_type', $request->leave_type)->first();
            $usedLeaves = Leave::where('staff_id', $user->user_id)
                ->where('leave_type', $request->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');

            $remainingDays = $leaveInfo->leave_days - $usedLeaves;

            if ($remainingDays < $request->number_of_day) {
                flash()->error('Insufficient leave balance for this employee!');
                return redirect()->back();
            }

            $save = new Leave;
            $save->staff_id         = $user->user_id;
            $save->employee_name    = $request->employee_name;
            $save->leave_type       = $request->leave_type;
            $save->remaining_leave  = $remainingDays - $request->number_of_day;
            $save->date_from        = $request->date_from;
            $save->date_to          = $request->date_to;
            $save->number_of_day    = $request->number_of_day;
            $save->leave_date       = json_encode($request->leave_date);
            $save->leave_day        = json_encode($request->select_leave_day);
            $save->status           = 'Approved'; // HR adds leaves as approved
            $save->reason           = $request->reason;
            $save->approved_by      = Session::get('name');
            $save->save();

            flash()->success('Leave added successfully :)');
            return redirect()->route('hr/leave/hr/page');
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to add leave :)');
            return redirect()->back();
        }
    }

    /** update leave status */
    public function updateLeaveStatus(Request $request)
    {
        try {
            $leave = Leave::findOrFail($request->id);
            $leave->status = $request->status;

            if ($request->status == 'Approved' || $request->status == 'Rejected') {
                $leave->approved_by = Session::get('name');
            }

            $leave->save();

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'Leave status updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    /** delete leave */
    public function deleteLeave(Request $request)
    {
        try {
            $leave = Leave::findOrFail($request->id);
            $leave->delete();

            flash()->success('Leave deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to delete leave :)');
            return redirect()->back();
        }
    }

    /** attendance page */
    public function attendance(Request $request)
    {
        $users = User::where('status', 'Active')->get();
        $selectedUserId = $request->user_id ?? Session::get('user_id');

        // Get date range
        $dateRange = $request->date_range;
        $dates = explode(' to ', $dateRange);
        $startDate = isset($dates[0]) ? Carbon::parse($dates[0]) : Carbon::now()->startOfMonth();
        $endDate = isset($dates[1]) ? Carbon::parse($dates[1]) : Carbon::now()->endOfMonth();

        // Get attendance records for selected user
        $attendances = Attendance::where('user_id', $selectedUserId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->paginate(10);

        // Get user details
        $selectedUser = User::where('user_id', $selectedUserId)->first();

        // Calculate statistics for selected user
        $stats = $this->calculateUserStats($selectedUserId, $startDate, $endDate);

        return view('HR.Attendance.attendance', compact(
            'users',
            'selectedUserId',
            'selectedUser',
            'attendances',
            'startDate',
            'endDate',
            'stats'
        ));
    }

    /** get attendance details */
    public function getAttendanceDetails($id)
    {
        try {
            $attendance = Attendance::with('user')->find($id);
            if (!$attendance) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            return view('HR.Attendance.attendance-details-modal', compact('attendance'));
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /** calculate user statistics */
    private function calculateUserStats($userId, $startDate, $endDate)
    {
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $stats = [
            'approved_hours' => 0,
            'rejected_hours' => 0,
            'pending_hours' => 0,
            'total_hours' => 0,
            'regular_hours' => 0,
            'overtime_hours' => 0,
            'late_days' => 0,
            'early_departure_days' => 0,
            'present_days' => 0,
            'absent_days' => 0
        ];

        foreach ($attendances as $attendance) {
            $stats['total_hours'] += $attendance->working_hours;
            $stats['regular_hours'] += min($attendance->working_hours, 8);
            $stats['overtime_hours'] += $attendance->overtime_hours;

            if ($attendance->late_minutes > 0) $stats['late_days']++;
            if ($attendance->early_departure_minutes > 0) $stats['early_departure_days']++;
            if ($attendance->status == 'present') $stats['present_days']++;
            if ($attendance->status == 'absent') $stats['absent_days']++;
        }

        return $stats;
    }

    /** get employee attendance data for AJAX */
    public function getEmployeeAttendanceData($userId)
    {
        try {
            $user = User::where('user_id', $userId)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Get today's attendance
            $todayAttendance = Attendance::where('user_id', $userId)
                ->where('date', Carbon::today())
                ->first();

            // Calculate stats for current month
            $stats = $this->calculateUserStats($userId, Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());

            return response()->json([
                'user' => [
                    'name' => $user->name,
                    'user_id' => $user->user_id,
                    'avatar' => $user->avatar ?? 'profile.png',
                    'position' => $user->position,
                    'experience' => $user->experience,
                    'join_date' => $user->join_date,
                    'department' => $user->department,
                ],
                'today' => $todayAttendance,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /** check in */
    public function checkIn(Request $request)
    {
        try {
            $userId = $request->user_id;
            $now = Carbon::now();

            // Check if already checked in today
            $existing = Attendance::where('user_id', $userId)
                ->where('date', $now->toDateString())
                ->first();

            if ($existing && $existing->check_in) {
                return response()->json(['error' => 'Already checked in today'], 400);
            }

            // Determine if late (after 10:00 AM)
            $lateMinutes = 0;
            $status = 'present';

            $checkInTime = $now->format('H:i:s');
            $lateThreshold = '10:00:00';

            if ($checkInTime > $lateThreshold) {
                $lateMinutes = Carbon::parse($checkInTime)->diffInMinutes(Carbon::parse($lateThreshold));
                $status = 'late';
            }

            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $userId,
                    'date' => $now->toDateString()
                ],
                [
                    'check_in' => $now->toTimeString(),
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'notes' => $request->notes
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Check in successful',
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Check in failed'], 500);
        }
    }

    /** check out */
    public function checkOut(Request $request)
    {
        try {
            $userId = $request->user_id;
            $now = Carbon::now();

            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $now->toDateString())
                ->first();

            if (!$attendance || !$attendance->check_in) {
                return response()->json(['error' => 'No check in record found'], 400);
            }

            if ($attendance->check_out) {
                return response()->json(['error' => 'Already checked out today'], 400);
            }

            // Calculate working hours
            $checkIn = Carbon::parse($attendance->check_in);
            $checkOut = $now;

            $workingHours = $checkOut->diffInHours($checkIn) + ($checkOut->diffInMinutes($checkIn) % 60) / 60;

            // Check if left early (before completing 8 hours)
            $earlyMinutes = 0;
            $requiredHours = 8;

            if ($workingHours < $requiredHours) {
                $earlyMinutes = ($requiredHours - $workingHours) * 60;
                $attendance->status = $attendance->status == 'late' ? 'late_early' : 'early_departure';
            }

            // Calculate overtime (if worked more than 8 hours)
            $overtimeHours = max(0, $workingHours - $requiredHours);

            $attendance->check_out = $now->toTimeString();
            $attendance->working_hours = $workingHours;
            $attendance->overtime_hours = $overtimeHours;
            $attendance->early_departure_minutes = $earlyMinutes;
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Check out successful',
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Check out failed'], 500);
        }
    }

    /** update attendance status (approve/reject) */
    public function updateAttendanceStatus(Request $request)
    {
        try {
            $attendance = Attendance::find($request->id);
            if (!$attendance) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            $attendance->status = $request->status;
            $attendance->approved_by = Session::get('name');
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Update failed'], 500);
        }
    }

    /** bulk approve attendance */
    public function bulkApproveAttendance(Request $request)
    {
        try {
            $ids = $request->ids;
            Attendance::whereIn('id', $ids)->update([
                'status' => 'approved',
                'approved_by' => Session::get('name')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Records approved successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Bulk approve failed'], 500);
        }
    }

    /** bulk reject attendance */
    public function bulkRejectAttendance(Request $request)
    {
        try {
            $ids = $request->ids;
            Attendance::whereIn('id', $ids)->update([
                'status' => 'rejected',
                'approved_by' => Session::get('name')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Records rejected successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Bulk reject failed'], 500);
        }
    }

    /** attendance Main */
    public function attendanceMain()
    {
        return view('HR.Attendance.attendance-main');
    }

    /** department */
    public function department(Request $request)
    {
        $search = $request->input('search');
        $departmentList = Department::when($search, function ($query, $search) {
            return $query->where('department', 'like', '%' . $search . '%')
                ->orWhere('head_of', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('HR.department', compact('departmentList', 'search'));
    }

    /** save record department */
    public function saveRecordDepartment(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'department'      => 'required|string|max:255',
            'head_of'         => 'required|string|max:255',
            'phone_number'    => 'required|numeric',
            'email'           => 'required|email|max:255',
            'total_employee'  => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'department'     => $request->department,
                'head_of'        => $request->head_of,
                'phone_number'   => $request->phone_number,
                'email'          => $request->email,
                'total_employee' => $request->total_employee,
            ];

            if (!empty($request->id_update)) {
                // تحديث سجل موجود
                $department = Department::findOrFail($request->id_update);
                $department->update($data);
                $message = 'Department updated successfully :)';
            } else {
                // إنشاء سجل جديد
                Department::create($data);
                $message = 'Department created successfully :)';
            }

            flash()->success($message);
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error saving department: ' . $e->getMessage());
            flash()->error('Failed to save department. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    /** delete record department */
    public function deleteRecordDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_delete' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $department = Department::findOrFail($request->id_delete);
            $department->delete();

            flash()->success('Department deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error deleting department: ' . $e->getMessage());
            flash()->error('Failed to delete department. Please try again.');
            return redirect()->back();
        }
    }
}
