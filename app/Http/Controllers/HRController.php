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
use Illuminate\Support\Facades\Auth;
use App\Mail\HolidayNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class HRController extends Controller
{

    public function changeLang($lang)
    {
        if (in_array($lang, ['en', 'ar'])) {
            session()->put('locale', $lang);
            App::setLocale($lang);
            if (Auth::check()) {
                $user = Auth::user();
                $user->language = $lang;
                $user->save();
            }
        }
        return redirect()->back();
    }

    /** Employee list */
    public function employeeList(Request $request)
    {
        $search = $request->input('search');
        $employeeList = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('user_id', 'like', '%' . $search . '%')
                ->orWhere('position', 'like', '%' . $search . '%')
                ->orWhere('department', 'like', '%' . $search . '%');
        })->paginate(7);

        $latestUser = User::orderBy('id', 'DESC')->first();
        $userId     = $latestUser ? (int) substr($latestUser->user_id, 4) + 1 : 1;
        $employeeId = 'KH_' . str_pad($userId, 3, '0', STR_PAD_LEFT);

        $roleName   = DB::table('role_type_users')->get();
        $position   = DB::table('position_types')->get();
        $department = DB::table('departments')->get();
        $statusUser = DB::table('user_types')->get();

        return view('HR.employee', compact(
            'employeeList', 'employeeId', 'roleName',
            'position', 'department', 'statusUser', 'search'
        ));
    }

    /** Save (Create) Employee */
    public function employeeSaveRecord(Request $request)
    {
        // ── Validation ──────────────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'avatar'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email',
            'password'     => 'required|string|min:8|confirmed',
            'position'     => 'required|string',
            'department'   => 'required|string',
            'role_name'    => 'required|string',
            'status'       => 'required|string',
            'phone_number' => 'required|numeric',
            'location'     => 'required|string',
            'join_date'    => 'required|string',
            'experience'   => 'required|string',
            'designation'  => 'required|string',
            'user_id'      => 'required|string|unique:users,user_id',
        ]);

        // إذا فشل الـ Validation نرجع بدون إغلاق الـ Modal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'create')
                ->withInput()
                ->with('open_add_modal', true);
        }

        try {
            $avatarName = time() . '_' . $request->name . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('assets/images/user'), $avatarName);

            $register               = new User();
            $register->user_id      = $request->user_id;
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
            $register->avatar       = $avatarName;
            $register->password     = Hash::make($request->password);
            $register->save();

            flash()->success('Employee added successfully :)');
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error('Error saving employee: ' . $e->getMessage());
            flash()->error('Failed to add employee. Please try again.');
            return redirect()->back()
                ->withInput()
                ->with('open_add_modal', true);
        }
    }

    /** Update Employee */
    public function employeeUpdateRecord(Request $request)
    {
        // ── Validation ──────────────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'password'     => 'nullable|string|min:8|confirmed',
            'position'     => 'required|string',
            'department'   => 'required|string',
            'role_name'    => 'required|string',
            'status'       => 'required|string',
            'phone_number' => 'required|numeric',
            'location'     => 'required|string',
            'join_date'    => 'required|string',
            'experience'   => 'required|string',
            'designation'  => 'required|string',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'update')
                ->withInput()
                ->with('open_edit_modal', $request->id);
        }

        try {
            $user = User::findOrFail($request->id);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                if (!empty($user->avatar) && file_exists(public_path('assets/images/user/' . $user->avatar))) {
                    unlink(public_path('assets/images/user/' . $user->avatar));
                }
                $avatarName = time() . '_' . $request->name . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('assets/images/user'), $avatarName);
                $user->avatar = $avatarName;
            }

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

            // تحديث الباسورد فقط لو تم إدخاله
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            flash()->success('Employee record updated successfully :)');
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::info('Error updating employee: ' . $e->getMessage());
            flash()->error('Failed to update employee record. Please try again.');
            return redirect()->back()
                ->withInput()
                ->with('open_edit_modal', $request->id);
        }
    }

    /** Delete Employee */
    public function employeeDeleteRecord(Request $request)
    {
        $request->validate([
            'id_delete' => 'required|exists:users,id',
        ]);

        try {
            $user = User::findOrFail($request->id_delete);

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

    /** Holiday Page */
    public function holidayPage(Request $request)
    {
        $search = $request->input('search');
        $date_search = $request->input('date_search'); // البحث بالتاريخ
        
        $holidayList = Holiday::query();
        
        // البحث بالنص (الاسم أو النوع)
        if ($search) {
            $holidayList->where(function($query) use ($search) {
                $query->where('holiday_name', 'like', '%' . $search . '%')
                    ->orWhere('holiday_type', 'like', '%' . $search . '%');
            });
        }
        
        // البحث بالتاريخ (يدعم يوم واحد أو رينج)
        if ($date_search) {
            // لو التاريخ فيه " to " يبقى رينج
            if (strpos($date_search, ' to ') !== false) {
                $range = explode(' to ', $date_search);
                if (count($range) == 2) {
                    $start = trim($range[0]);
                    $end = trim($range[1]);
                    $holidayList->where(function($query) use ($start, $end) {
                        $query->whereBetween('start_date', [$start, $end])
                            ->orWhereBetween('end_date', [$start, $end])
                            ->orWhere(function($q) use ($start, $end) {
                                $q->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                            });
                    });
                }
            } else {
                // يوم واحد - يجيب أي إجازة فيها التاريخ ده
                $holidayList->where(function($query) use ($date_search) {
                    $query->where('start_date', '<=', $date_search)
                        ->where('end_date', '>=', $date_search);
                });
            }
        }
        
        $holidayList = $holidayList->orderBy('start_date', 'asc')->paginate(10);
        
        return view('HR.holidays', compact('holidayList', 'search', 'date_search'));
    }

    /** Save Holiday (Supports Single Date or Date Range) */
    public function holidaySaveRecord(Request $request)
    {
        $request->validate([
            'holiday_type' => 'required|string',
            'holiday_name' => 'required|string',
            'date_range'   => 'required|string',
        ]);

        try {
            // تحليل التواريخ من المدخل
            $dates = $this->parseDateRange($request->date_range);
            
            if (empty($dates)) {
                flash()->error('Invalid date format. Please use a single date or a valid range.');
                return redirect()->back();
            }

            $startDate = $dates['start'];
            $endDate   = $dates['end'];

            $isNewRecord = empty($request->idUpdate);

            if ($isNewRecord) {
                // إضافة جديدة
                $holiday = Holiday::create([
                    'holiday_type' => $request->holiday_type,
                    'holiday_name' => $request->holiday_name,
                    'start_date'   => $startDate,
                    'end_date'     => $endDate,
                ]);

                // بعت إيميل للموظفين
                $this->sendHolidayNotificationToEmployees($holiday);
            } else {
                // تعديل
                $holiday = Holiday::findOrFail($request->idUpdate);
                $holiday->update([
                    'holiday_type' => $request->holiday_type,
                    'holiday_name' => $request->holiday_name,
                    'start_date'   => $startDate,
                    'end_date'     => $endDate,
                ]);
            }

            flash()->success($isNewRecord ? 'Holiday added successfully :)' : 'Holiday updated successfully :)');
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error('Holiday Save Error: ' . $e->getMessage());
            flash()->error('Failed to save holiday. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Parse date range string (supports single date or "YYYY-MM-DD to YYYY-MM-DD")
     * Returns array with 'start' and 'end' dates
     */
    private function parseDateRange($dateRange)
    {
        // لو فيه " to " يبقى رينج
        if (strpos($dateRange, ' to ') !== false) {
            $range = explode(' to ', $dateRange);
            if (count($range) == 2) {
                $start = Carbon::parse(trim($range[0]));
                $end   = Carbon::parse(trim($range[1]));

                // لو تاريخ البداية بعد تاريخ النهاية، بدلهم
                if ($start->gt($end)) {
                    $temp = $start;
                    $start = $end;
                    $end = $temp;
                }

                return [
                    'start' => $start->format('Y-m-d'),
                    'end'   => $end->format('Y-m-d'),
                ];
            }
        } else {
            // يوم واحد
            $singleDate = Carbon::parse(trim($dateRange));
            if ($singleDate) {
                return [
                    'start' => $singleDate->format('Y-m-d'),
                    'end'   => $singleDate->format('Y-m-d'),
                ];
            }
        }

        return null;
    }

    /** Send holiday notification to all employees */
    private function sendHolidayNotificationToEmployees($holiday)
    {
        try {
            $employees = User::where('role_name', 'Employee')
                ->where('status', 'Active')
                ->get();

            if ($employees->isEmpty()) {
                Log::info('No active employees found to send holiday notification');
                return;
            }

            $successCount = 0;
            $failCount = 0;

            foreach ($employees as $employee) {
                if ($employee->email && filter_var($employee->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        // هنا بنبعت الـ Holiday object كامل عشان فيه start_date و end_date
                        Mail::to($employee->email)->send(new HolidayNotificationMail($holiday));
                        //Mail::to("omarmo5tar12@gmail.com")->queue(new HolidayNotificationMail($holiday));
                        $successCount++;
                    } catch (\Exception $e) {
                        $failCount++;
                        Log::error('Failed to send email to: ' . $employee->email . ' - ' . $e->getMessage());
                    }
                } else {
                    $failCount++;
                    Log::warning('Invalid email for employee: ' . ($employee->user_id ?? 'unknown'));
                }
            }

            Log::info("Holiday notification sent: Success: {$successCount}, Failed: {$failCount}");

        } catch (\Exception $e) {
            Log::error('Error in sendHolidayNotificationToEmployees: ' . $e->getMessage());
        }
    }

    /** Delete Holiday */
    public function holidayDeleteRecord(Request $request)
    {
        try {
            $holiday = Holiday::findOrFail($request->id_delete);
            $holiday->delete();

            flash()->success('Holiday deleted successfully :)');
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error($e);
            flash()->error('Failed to delete holiday :)');
            return redirect()->back();
        }
    }

    /** Get Information Leave */
    public function getInformationLeave(Request $request)
    {
        try {
            $numberOfDay = $request->number_of_day ?? 0;
            $leaveType   = $request->leave_type;
            $staffId     = $request->staff_id ?? Session::get('user_id');

            $usedLeaves = Leave::where('staff_id', $staffId)
                ->where('leave_type', $leaveType)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');

            $leaveInfo = LeaveInformation::where('leave_type', $leaveType)->first();

            $remainingDays = $leaveInfo
                ? $leaveInfo->leave_days - $usedLeaves - ($numberOfDay ?? 0)
                : 0;

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Get success',
                'leave_type'    => max(0, $remainingDays),
                'number_of_day' => $numberOfDay,
            ]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    /** Get Employee Leave Info for HR Page */
    public function getEmployeeLeaveInfo(Request $request)
    {
        try {
            $staffId   = $request->staff_id;
            $leaveInfo = [];

            if ($staffId) {
                $leaveTypes = LeaveInformation::all();
                foreach ($leaveTypes as $type) {
                    $usedLeaves = Leave::where('staff_id', $staffId)
                        ->where('leave_type', $type->leave_type)
                        ->whereIn('status', ['Approved', 'Pending'])
                        ->sum('number_of_day');

                    $remaining                    = $type->leave_days - $usedLeaves;
                    $leaveInfo[$type->leave_type] = max(0, $remaining);
                }
            }

            return response()->json(['response_code' => 200, 'data' => $leaveInfo]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['response_code' => 500, 'error' => 'An error occurred.'], 500);
        }
    }

    /** Leave Employee */
    public function leaveEmployee(Request $request)
    {
        $search  = $request->input('search');
        $staffId = Session::get('user_id');

        $leave = Leave::where('staff_id', $staffId)
            ->when($search, function ($query, $search) {
                return $query->where('leave_type', 'like', '%' . $search . '%')
                    ->orWhere('reason', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalLeaves    = Leave::where('staff_id', $staffId)->count();
        $approvedLeaves = Leave::where('staff_id', $staffId)->where('status', 'Approved')->count();
        $pendingLeaves  = Leave::where('staff_id', $staffId)->where('status', 'Pending')->count();
        $rejectedLeaves = Leave::where('staff_id', $staffId)->where('status', 'Rejected')->count();

        return view('HR.LeavesManage.leave-employee', compact(
            'leave', 'search', 'totalLeaves', 'approvedLeaves', 'pendingLeaves', 'rejectedLeaves'
        ));
    }

    /** Create Leave Employee */
    public function createLeaveEmployee()
    {
        $staffId          = Session::get('user_id');
        $leaveInformation = LeaveInformation::all();
        $remainingLeaves  = [];

        foreach ($leaveInformation as $info) {
            $usedLeaves = Leave::where('staff_id', $staffId)
                ->where('leave_type', $info->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');
            $remainingLeaves[$info->leave_type] = max(0, $info->leave_days - $usedLeaves);
        }

        return view('HR.LeavesManage.create-leave-employee', compact('leaveInformation', 'remainingLeaves'));
    }

    /** Save Leave Record */
    public function saveRecordLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'date_from'  => 'required',
            'date_to'    => 'required',
            'reason'     => 'required',
        ]);

        try {
            $leaveInfo  = LeaveInformation::where('leave_type', $request->leave_type)->first();
            $usedLeaves = Leave::where('staff_id', Session::get('user_id'))
                ->where('leave_type', $request->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');

            $remainingDays = $leaveInfo->leave_days - $usedLeaves;

            if ($remainingDays < $request->number_of_day) {
                flash()->error('Insufficient leave balance!');
                return redirect()->back();
            }

            $save                  = new Leave;
            $save->staff_id        = Session::get('user_id');
            $save->employee_name   = Session::get('name');
            $save->leave_type      = $request->leave_type;
            $save->remaining_leave = $remainingDays - $request->number_of_day;
            $save->date_from       = $request->date_from;
            $save->date_to         = $request->date_to;
            $save->number_of_day   = $request->number_of_day;
            $save->leave_date      = json_encode($request->leave_date);
            $save->leave_day       = json_encode($request->select_leave_day);
            $save->status          = 'Pending';
            $save->reason          = $request->reason;
            $save->save();

            flash()->success('Apply Leave successfully :)');
            return redirect()->route('hr/leave/employee/page');

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed Apply Leave :)');
            return redirect()->back();
        }
    }

    /** View Detail Leave */
    public function viewDetailLeave($id)
    {
        $leaveInformation = LeaveInformation::all();
        $leaveDetail      = Leave::findOrFail($id);
        $leaveDate        = json_decode($leaveDetail->leave_date, true);
        $leaveDay         = json_decode($leaveDetail->leave_day, true);
        $user             = User::where('user_id', $leaveDetail->staff_id)->first();

        return view('HR.LeavesManage.view-detail-leave', compact(
            'leaveInformation', 'leaveDetail', 'leaveDate', 'leaveDay', 'user'
        ));
    }

    /** Leave HR - List all leaves */
    public function leaveHR(Request $request)
    {
        $search    = $request->input('search');
        $status    = $request->input('status');
        $leaveType = $request->input('leave_type');

        $query = Leave::with('user')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', '%' . $search . '%')
                    ->orWhere('staff_id', 'like', '%' . $search . '%')
                    ->orWhere('reason', 'like', '%' . $search . '%');
            });
        }

        if ($status)    $query->where('status', $status);
        if ($leaveType) $query->where('leave_type', $leaveType);

        $leaves = $query->paginate(10);

        $today          = date('Y-m-d');
        $todayLeaves    = Leave::whereDate('date_from', '<=', $today)
            ->whereDate('date_to', '>=', $today)
            ->where('status', 'Approved')->count();

        $totalLeaves    = Leave::count();
        $pendingLeaves  = Leave::where('status', 'Pending')->count();
        $approvedLeaves = Leave::where('status', 'Approved')->count();
        $rejectedLeaves = Leave::where('status', 'Rejected')->count();
        $leaveTypes     = LeaveInformation::all();

        return view('HR.LeavesManage.leave-hr', compact(
            'leaves', 'search', 'status', 'leaveType',
            'todayLeaves', 'totalLeaves', 'pendingLeaves',
            'approvedLeaves', 'rejectedLeaves', 'leaveTypes'
        ));
    }

    /** Create Leave HR */
    public function createLeaveHR()
    {
        $users            = User::all();
        $leaveInformation = LeaveInformation::all();
        return view('HR.LeavesManage.create-leave-hr', compact('users', 'leaveInformation'));
    }

    /** Save Leave HR */
    public function saveRecordLeaveHR(Request $request)
    {
        $request->validate([
            'employee_name'    => 'required|string',
            'leave_type'       => 'required|string',
            'date_from'        => 'required',
            'date_to'          => 'required',
            'reason'           => 'required',
            'leave_date'       => 'required|array',
            'select_leave_day' => 'required|array',
            'number_of_day'    => 'required|numeric',
        ]);

        try {
            $user = User::where('name', $request->employee_name)->first();

            if (!$user) {
                flash()->error('Employee not found!');
                return redirect()->back();
            }

            $leaveInfo  = LeaveInformation::where('leave_type', $request->leave_type)->first();
            $usedLeaves = Leave::where('staff_id', $user->user_id)
                ->where('leave_type', $request->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');

            $remainingDays = $leaveInfo->leave_days - $usedLeaves;

            if ($remainingDays < $request->number_of_day) {
                flash()->error('Insufficient leave balance for this employee!');
                return redirect()->back();
            }

            $save                  = new Leave;
            $save->staff_id        = $user->user_id;
            $save->employee_name   = $request->employee_name;
            $save->leave_type      = $request->leave_type;
            $save->remaining_leave = $remainingDays - $request->number_of_day;
            $save->date_from       = $request->date_from;
            $save->date_to         = $request->date_to;
            $save->number_of_day   = $request->number_of_day;
            $save->leave_date      = json_encode($request->leave_date);
            $save->leave_day       = json_encode($request->select_leave_day);
            $save->status          = 'Approved';
            $save->reason          = $request->reason;
            $save->approved_by     = Session::get('name');
            $save->save();

            flash()->success('Leave added successfully :)');
            return redirect()->route('hr/leave/hr/page');

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to add leave :)');
            return redirect()->back();
        }
    }

    /** Update Leave Status */
    public function updateLeaveStatus(Request $request)
    {
        try {
            $leave         = Leave::findOrFail($request->id);
            $leave->status = $request->status;

            if (in_array($request->status, ['Approved', 'Rejected'])) {
                $leave->approved_by = Session::get('name');
            }

            $leave->save();

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Leave status updated successfully',
            ]);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Failed to update status',
            ], 500);
        }
    }

    /** Delete Leave */
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

    /** Attendance Page */
    public function attendance(Request $request)
    {
        $users           = User::where('status', 'Active')->get();
        $selectedUserId  = $request->user_id ?? Session::get('user_id');

        $dateRange = $request->date_range;
        $dates     = explode(' to ', $dateRange);
        $startDate = isset($dates[0]) ? Carbon::parse($dates[0]) : Carbon::now()->startOfMonth();
        $endDate   = isset($dates[1]) ? Carbon::parse($dates[1]) : Carbon::now()->endOfMonth();

        $attendances = Attendance::where('user_id', $selectedUserId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->paginate(10);

        $selectedUser = User::where('user_id', $selectedUserId)->first();
        $stats        = $this->calculateUserStats($selectedUserId, $startDate, $endDate);

        return view('HR.Attendance.attendance', compact(
            'users', 'selectedUserId', 'selectedUser',
            'attendances', 'startDate', 'endDate', 'stats'
        ));
    }

    /** Get Attendance Details */
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

    /** Calculate User Statistics */
    private function calculateUserStats($userId, $startDate, $endDate)
    {
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $stats = [
            'approved_hours'        => 0,
            'rejected_hours'        => 0,
            'pending_hours'         => 0,
            'total_hours'           => 0,
            'regular_hours'         => 0,
            'overtime_hours'        => 0,
            'late_days'             => 0,
            'early_departure_days'  => 0,
            'present_days'          => 0,
            'absent_days'           => 0,
        ];

        foreach ($attendances as $attendance) {
            $stats['total_hours']    += $attendance->working_hours;
            $stats['regular_hours']  += min($attendance->working_hours, 8);
            $stats['overtime_hours'] += $attendance->overtime_hours;

            if ($attendance->late_minutes > 0)            $stats['late_days']++;
            if ($attendance->early_departure_minutes > 0) $stats['early_departure_days']++;
            if ($attendance->status == 'present')         $stats['present_days']++;
            if ($attendance->status == 'absent')          $stats['absent_days']++;
        }

        return $stats;
    }

    /** Get Employee Attendance Data (AJAX) */
    public function getEmployeeAttendanceData($userId)
    {
        try {
            $user = User::where('user_id', $userId)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $todayAttendance = Attendance::where('user_id', $userId)
                ->where('date', Carbon::today())
                ->first();

            $stats = $this->calculateUserStats(
                $userId,
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            );

            return response()->json([
                'user' => [
                    'name'       => $user->name,
                    'user_id'    => $user->user_id,
                    'avatar'     => $user->avatar ?? 'profile.png',
                    'position'   => $user->position,
                    'experience' => $user->experience,
                    'join_date'  => $user->join_date,
                    'department' => $user->department,
                ],
                'today' => $todayAttendance,
                'stats' => $stats,
            ]);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /** Check In */
    public function checkIn(Request $request)
    {
        try {
            $userId = $request->user_id;
            $now    = Carbon::now();

            $existing = Attendance::where('user_id', $userId)
                ->where('date', $now->toDateString())
                ->first();

            if ($existing && $existing->check_in) {
                return response()->json(['error' => 'Already checked in today'], 400);
            }

            $lateMinutes  = 0;
            $status       = 'present';
            $checkInTime  = $now->format('H:i:s');
            $lateThreshold = '10:00:00';

            if ($checkInTime > $lateThreshold) {
                $lateMinutes = Carbon::parse($checkInTime)->diffInMinutes(Carbon::parse($lateThreshold));
                $status      = 'late';
            }

            $attendance = Attendance::updateOrCreate(
                ['user_id' => $userId, 'date' => $now->toDateString()],
                [
                    'check_in'     => $now->toTimeString(),
                    'status'       => $status,
                    'late_minutes' => $lateMinutes,
                    'notes'        => $request->notes,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Check in successful',
                'data'    => $attendance,
            ]);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Check in failed'], 500);
        }
    }

    /** Check Out */
    public function checkOut(Request $request)
    {
        try {
            $userId = $request->user_id;
            $now    = Carbon::now();

            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $now->toDateString())
                ->first();

            if (!$attendance || !$attendance->check_in) {
                return response()->json(['error' => 'No check in record found'], 400);
            }

            if ($attendance->check_out) {
                return response()->json(['error' => 'Already checked out today'], 400);
            }

            $checkIn      = Carbon::parse($attendance->check_in);
            $workingHours = $now->diffInHours($checkIn) + ($now->diffInMinutes($checkIn) % 60) / 60;
            $earlyMinutes = 0;
            $requiredHours = 8;

            if ($workingHours < $requiredHours) {
                $earlyMinutes       = ($requiredHours - $workingHours) * 60;
                $attendance->status = ($attendance->status == 'late') ? 'late_early' : 'early_departure';
            }

            $overtimeHours                       = max(0, $workingHours - $requiredHours);
            $attendance->check_out               = $now->toTimeString();
            $attendance->working_hours           = $workingHours;
            $attendance->overtime_hours          = $overtimeHours;
            $attendance->early_departure_minutes = $earlyMinutes;
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Check out successful',
                'data'    => $attendance,
            ]);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Check out failed'], 500);
        }
    }

    /** Update Attendance Status */
    public function updateAttendanceStatus(Request $request)
    {
        try {
            $attendance = Attendance::find($request->id);
            if (!$attendance) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            $attendance->status      = $request->status;
            $attendance->approved_by = Session::get('name');
            $attendance->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully']);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Update failed'], 500);
        }
    }

    /** Bulk Approve Attendance */
    public function bulkApproveAttendance(Request $request)
    {
        try {
            Attendance::whereIn('id', $request->ids)->update([
                'status'      => 'approved',
                'approved_by' => Session::get('name'),
            ]);
            return response()->json(['success' => true, 'message' => 'Records approved successfully']);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Bulk approve failed'], 500);
        }
    }

    /** Bulk Reject Attendance */
    public function bulkRejectAttendance(Request $request)
    {
        try {
            Attendance::whereIn('id', $request->ids)->update([
                'status'      => 'rejected',
                'approved_by' => Session::get('name'),
            ]);
            return response()->json(['success' => true, 'message' => 'Records rejected successfully']);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Bulk reject failed'], 500);
        }
    }

    /** Attendance Main */
    public function attendanceMain()
    {
        return view('HR.Attendance.attendance-main');
    }

    /** Department Page */
    public function department(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        
        $departmentList = Department::withCount('users')
            ->when($search, function ($query, $search) {
                return $query->where('department', 'like', '%' . $search . '%')
                    ->orWhere('head_of', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        $managers = User::where('role_name', 'Manager')
            ->orWhere('role_name', 'manager')
            ->orderBy('name', 'asc')
            ->get();

        return view('HR.department', compact('departmentList', 'search', 'managers', 'perPage'));
    }

    /** Save Department */
    public function saveRecordDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department'     => 'required|string|max:255|unique:departments,department,' . $request->id_update,
            'head_of'        => 'required|string|max:255|exists:users,name',
            'phone_number'   => 'required|numeric',
            'email'          => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, $request->id_update ? 'update' : 'create')
                ->withInput()
                ->with('open_modal_' . ($request->id_update ? 'edit' : 'add'), true);
        }

        try {
            $employeeCount = User::where('department', $request->department)->count();

            $data = [
                'department'     => $request->department,
                'head_of'        => $request->head_of,
                'phone_number'   => $request->phone_number,
                'email'          => $request->email,
                'total_employee' => $employeeCount,
            ];

            if (!empty($request->id_update)) {
                $department = Department::findOrFail($request->id_update);
                $department->update($data);
                $message = 'Department updated successfully :)';
            } else {
                Department::create($data);
                $message = 'Department created successfully :)';
            }

            flash()->success($message);
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error('Error saving department: ' . $e->getMessage());
            flash()->error('Failed to save department. Please try again.');
            return redirect()->back()
                ->withInput()
                ->with('open_modal_' . ($request->id_update ? 'edit' : 'add'), true);
        }
    }

    /** Delete Department */
    public function deleteRecordDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_delete' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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