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
use App\Models\Notification;

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

    // ─────────────────────────────────────────────────────────────
    //  Helper: Generate next Employee ID  →  ASC_001, ASC_002 …
    // ─────────────────────────────────────────────────────────────
    private function generateEmployeeId(): string
    {
        $prefix = 'ASC_';

        // Grab all user_ids that match our prefix, extract the numeric part
        $lastUser = User::where('user_id', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(user_id, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();

        if ($lastUser) {
            $lastNum = (int) substr($lastUser->user_id, strlen($prefix));
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    // ─────────────────────────────────────────────────────────────
    //  Employee List
    // ─────────────────────────────────────────────────────────────
    public function employeeList(Request $request)
    {
        $search     = $request->input('search');
        $authUser   = Auth::user();
        $isManager  = in_array($authUser->role_name, ['Manager', 'manager']);
        $department = $authUser->department;

        $query = User::query();
        if ($isManager) {
            $query->where('department', $department);
        }

        $employeeList = $query->when($search, function ($q, $search) {
            return $q->where(function ($inner) use ($search) {
                $inner->where('name',       'like', '%' . $search . '%')
                      ->orWhere('email',      'like', '%' . $search . '%')
                      ->orWhere('user_id',    'like', '%' . $search . '%')
                      ->orWhere('position',   'like', '%' . $search . '%')
                      ->orWhere('department', 'like', '%' . $search . '%');
            });
        })->paginate(10);

        // Pre-generate the next employee ID (for the Add modal)
        $employeeId = $this->generateEmployeeId();

        $roleName   = DB::table('role_type_users')->get();
        $position   = DB::table('position_types')->get();
        $department = DB::table('departments')->get();
        $statusUser = DB::table('user_types')->get();

        return view('HR.employee', compact(
            'employeeList', 'employeeId', 'roleName',
            'position', 'department', 'statusUser', 'search'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  Export Employees to CSV
    // ─────────────────────────────────────────────────────────────
    public function exportEmployees(Request $request)
    {
        try {
            $search    = $request->input('search');
            $authUser  = Auth::user();
            $isManager = in_array($authUser->role_name, ['Manager', 'manager']);

            $query = User::query();
            if ($isManager) {
                $query->where('department', $authUser->department);
            }

            $employees = $query->when($search, function ($q, $search) {
                return $q->where(function ($inner) use ($search) {
                    $inner->where('name',       'like', '%' . $search . '%')
                          ->orWhere('email',      'like', '%' . $search . '%')
                          ->orWhere('user_id',    'like', '%' . $search . '%')
                          ->orWhere('position',   'like', '%' . $search . '%')
                          ->orWhere('department', 'like', '%' . $search . '%');
                });
            })->orderBy('created_at', 'desc')->get();

            $fileName = 'employees_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $callback = function () use ($employees) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

                fputcsv($handle, [
                    '#',
                    __('messages.employee_id'),
                    __('messages.name'),
                    __('messages.email'),
                    __('messages.phone'),
                    __('messages.job_type'),
                    __('messages.department'),
                    __('messages.designation'),
                    __('messages.role'),
                    __('messages.gender'),
                    __('messages.location'),
                    __('messages.join_date'),
                    __('messages.experience'),
                    __('messages.last_login'),
                ]);

                $counter = 1;
                foreach ($employees as $employee) {
                    $jobType = $employee->position;
                    switch ($employee->position) {
                        case 'Full-Time Onsite': $jobType = __('messages.full_time');  break;
                        case 'Part-Time':        $jobType = __('messages.part_time');  break;
                        case 'Remote':           $jobType = __('messages.remote');     break;
                        case 'Hybrid Work':      $jobType = __('messages.hybrid');     break;
                        case 'Contractor':       $jobType = __('messages.contractor'); break;
                    }

                    $gender = $employee->status;
                    if ($employee->status === 'Male')   $gender = __('messages.male');
                    if ($employee->status === 'Female') $gender = __('messages.female');

                    fputcsv($handle, [
                        $counter++,
                        $employee->user_id,
                        $employee->name,
                        $employee->email,
                        $employee->phone_number,
                        $jobType,
                        $employee->department,
                        $employee->designation,
                        $employee->role_name,
                        $gender,
                        $employee->location,
                        \Carbon\Carbon::parse($employee->join_date)->format('d/m/Y'),
                        $employee->experience . ' ' . __('messages.years'),
                        $employee->last_login ? Carbon::parse($employee->last_login)->format('Y-m-d H:i:s') : 'N/A',
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            flash()->error(__('messages.error_occurred') . ': ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Save (Create) Employee
    // ─────────────────────────────────────────────────────────────
    public function employeeSaveRecord(Request $request)
    {
        // ── Validation ──────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            // Section 1 – Basic Info
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email',
            'phone_number' => 'required',

            // Section 2 – Work Info
            'department'   => 'required|string',
            'designation'  => 'required|string',
            'position'     => 'required|string',
            'join_date'    => 'required',

            // Section 3 – System Access
            'role_name'    => 'required|string',
            'password'     => 'required|string|min:6|confirmed',

            // Avatar (optional on create now – adjust if you want it required)
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Custom messages (English – blade handles translation display)
            'name.required'         => 'Employee name is required.',
            'email.required'        => 'Email address is required.',
            'email.unique'          => 'This email is already taken.',
            'phone_number.required' => 'Phone number is required.',
            'department.required'   => 'Please select a department.',
            'designation.required'  => 'Please select a designation.',
            'position.required'     => 'Please select an employment type.',
            'join_date.required'    => 'Joining date is required.',
            'role_name.required'    => 'Please select a role.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 6 characters.',
            'password.confirmed'    => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'create')
                ->withInput()
                ->with('open_add_modal', true);
        }

        try {
            // Auto-generate unique Employee ID
            $newEmployeeId = $this->generateEmployeeId();

            // Make sure it's truly unique (race-condition safety)
            while (User::where('user_id', $newEmployeeId)->exists()) {
                $num = (int) substr($newEmployeeId, strlen('ASC_')) + 1;
                $newEmployeeId = 'ASC_' . str_pad($num, 3, '0', STR_PAD_LEFT);
            }

            // Handle avatar upload
            $avatarName = null;
            if ($request->hasFile('avatar')) {
                $avatarName = time() . '_' . $request->name . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('assets/images/user'), $avatarName);
            }

            $register               = new User();
            $register->user_id      = $newEmployeeId;
            $register->name         = $request->name;
            $register->email        = $request->email;
            $register->position     = $request->position;
            $register->department   = $request->department;
            $register->role_name    = $request->role_name;
            $register->status       = $request->status ?? 'Male'; // gender stored in status
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

    // ─────────────────────────────────────────────────────────────
    //  Update Employee
    // ─────────────────────────────────────────────────────────────
    public function employeeUpdateRecord(Request $request)
    {
        // ── Validation ──────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            // Section 1 – Basic Info
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $request->id,
            'phone_number' => 'required',

            // Section 2 – Work Info
            'department'   => 'required|string',
            'designation'  => 'required|string',
            'position'     => 'required|string',
            'join_date'    => 'required',

            // Section 3 – System Access
            'role_name'    => 'required|string',
            'password'     => 'nullable|string|min:6|confirmed',

            // Avatar optional
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required'         => 'Employee name is required.',
            'email.required'        => 'Email address is required.',
            'email.unique'          => 'This email is already taken.',
            'phone_number.required' => 'Phone number is required.',
            'department.required'   => 'Please select a department.',
            'designation.required'  => 'Please select a designation.',
            'position.required'     => 'Please select an employment type.',
            'join_date.required'    => 'Joining date is required.',
            'role_name.required'    => 'Please select a role.',
            'password.min'          => 'Password must be at least 6 characters.',
            'password.confirmed'    => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'update')
                ->withInput()
                ->with('open_edit_modal', $request->id);
        }

        try {
            $user = User::findOrFail($request->id);

            // Track if anything actually changed
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->position     = $request->position;
            $user->department   = $request->department;
            $user->role_name    = $request->role_name;
            $user->status       = $request->status ?? $user->status;
            $user->phone_number = $request->phone_number;
            $user->location     = $request->location;
            $user->join_date    = $request->join_date;
            $user->experience   = $request->experience;
            $user->designation  = $request->designation;

            $hasFile = $request->hasFile('avatar');
            $hasPassword = !empty($request->password);
            $isDirty = $user->isDirty();

            if (!$isDirty && !$hasFile && !$hasPassword) {
                flash()->info('No changes were made to the employee record.');
                return redirect()->back();
            }

            // Handle avatar upload
            if ($hasFile) {
                if (!empty($user->avatar) && file_exists(public_path('assets/images/user/' . $user->avatar))) {
                    unlink(public_path('assets/images/user/' . $user->avatar));
                }
                $avatarName = time() . '_' . $request->name . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('assets/images/user'), $avatarName);
                $user->avatar = $avatarName;
            }

            // Update password only if provided
            if ($hasPassword) {
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

    // ─────────────────────────────────────────────────────────────
    //  Delete Employee
    // ─────────────────────────────────────────────────────────────
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

    // ─────────────────────────────────────────────────────────────
    //  Holiday Page
    // ─────────────────────────────────────────────────────────────
    public function holidayPage(Request $request)
    {
        $search      = $request->input('search');
        $date_search = $request->input('date_search');

        $holidayList = Holiday::query();

        if ($search) {
            $holidayList->where(function ($query) use ($search) {
                $query->where('holiday_name', 'like', '%' . $search . '%')
                      ->orWhere('holiday_type', 'like', '%' . $search . '%');
            });
        }

        if ($date_search) {
            if (strpos($date_search, ' to ') !== false) {
                $range = explode(' to ', $date_search);
                if (count($range) === 2) {
                    $start = trim($range[0]);
                    $end   = trim($range[1]);
                    $holidayList->where(function ($query) use ($start, $end) {
                        $query->whereBetween('start_date', [$start, $end])
                              ->orWhereBetween('end_date', [$start, $end])
                              ->orWhere(function ($q) use ($start, $end) {
                                  $q->where('start_date', '<=', $start)
                                    ->where('end_date', '>=', $end);
                              });
                    });
                }
            } else {
                $holidayList->where(function ($query) use ($date_search) {
                    $query->where('start_date', '<=', $date_search)
                          ->where('end_date', '>=', $date_search);
                });
            }
        }

        $holidayList = $holidayList->orderBy('start_date', 'asc')->paginate(10);

        return view('HR.holidays', compact('holidayList', 'search', 'date_search'));
    }

    // ─────────────────────────────────────────────────────────────
    //  Save Holiday
    // ─────────────────────────────────────────────────────────────
    public function holidaySaveRecord(Request $request)
    {
        $request->validate([
            'holiday_type' => 'required|string',
            'holiday_name' => 'required|string',
            'date_range'   => 'required|string',
        ]);

        try {
            $dates = $this->parseDateRange($request->date_range);

            if (empty($dates)) {
                flash()->error('Invalid date format. Please use a single date or a valid range.');
                return redirect()->back();
            }

            $isNewRecord = empty($request->idUpdate);

            if ($isNewRecord) {
                $holiday = Holiday::create([
                    'holiday_type' => $request->holiday_type,
                    'holiday_name' => $request->holiday_name,
                    'start_date'   => $dates['start'],
                    'end_date'     => $dates['end'],
                ]);
                $this->sendHolidayNotificationToEmployees($holiday);
            } else {
                $holiday = Holiday::findOrFail($request->idUpdate);
                $holiday->update([
                    'holiday_type' => $request->holiday_type,
                    'holiday_name' => $request->holiday_name,
                    'start_date'   => $dates['start'],
                    'end_date'     => $dates['end'],
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

    private function parseDateRange($dateRange)
    {
        if (strpos($dateRange, ' to ') !== false) {
            $range = explode(' to ', $dateRange);
            if (count($range) === 2) {
                $start = Carbon::parse(trim($range[0]));
                $end   = Carbon::parse(trim($range[1]));
                if ($start->gt($end)) { [$start, $end] = [$end, $start]; }
                return ['start' => $start->format('Y-m-d'), 'end' => $end->format('Y-m-d')];
            }
        } else {
            $single = Carbon::parse(trim($dateRange));
            if ($single) {
                return ['start' => $single->format('Y-m-d'), 'end' => $single->format('Y-m-d')];
            }
        }
        return null;
    }

    private function sendHolidayNotificationToEmployees($holiday)
    {
        try {
            $employees = User::where('role_name', 'Employee')->where('status', 'Active')->get();
            if ($employees->isEmpty()) return;

            foreach ($employees as $employee) {
                if ($employee->email && filter_var($employee->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::to($employee->email)->send(new HolidayNotificationMail($holiday));
                        //Mail::to($employee->email)->queue(new HolidayNotificationMail($holiday));
                    } catch (\Exception $e) {
                        Log::error('Failed to send email to: ' . $employee->email . ' - ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in sendHolidayNotificationToEmployees: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Delete Holiday
    // ─────────────────────────────────────────────────────────────
    public function holidayDeleteRecord(Request $request)
    {
        try {
            Holiday::findOrFail($request->id_delete)->delete();
            flash()->success('Holiday deleted successfully :)');
        } catch (\Exception $e) {
            Log::error($e);
            flash()->error('Failed to delete holiday.');
        }
        return redirect()->back();
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Information (AJAX)
    // ─────────────────────────────────────────────────────────────
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
                ? ($leaveInfo->leave_days >= 999 ? 'Unlimited' : $leaveInfo->leave_days - $usedLeaves - ($numberOfDay ?? 0))
                : 0;

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Get success',
                'leave_type'    => $remainingDays,
                'number_of_day' => $numberOfDay,
            ]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Employee Leave Info (AJAX)
    // ─────────────────────────────────────────────────────────────
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

                    $remaining = ($type->leave_days >= 999) ? 'Unlimited' : max(0, $type->leave_days - $usedLeaves);
                    $leaveInfo[$type->leave_type] = $remaining;
                }
            }

            return response()->json(['response_code' => 200, 'data' => $leaveInfo]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['response_code' => 500, 'error' => 'An error occurred.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Employee Page
    // ─────────────────────────────────────────────────────────────
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

    // ─────────────────────────────────────────────────────────────
    //  Leave – Create (Employee)
    // ─────────────────────────────────────────────────────────────
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
            $remainingLeaves[$info->leave_type] = ($info->leave_days >= 999) ? 'Unlimited' : max(0, $info->leave_days - $usedLeaves);
        }

        return view('HR.LeavesManage.create-leave-employee', compact('leaveInformation', 'remainingLeaves'));
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Save Record (Employee)
    // ─────────────────────────────────────────────────────────────
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

            $this->notifyNewLeaveRequest($save);

            flash()->success('Apply Leave successfully :)');
            return redirect()->route('hr/leave/employee/page');

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed Apply Leave :)');
            return redirect()->back();
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – View Detail
    // ─────────────────────────────────────────────────────────────
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

    // ─────────────────────────────────────────────────────────────
    //  Leave – Edit Form
    // ─────────────────────────────────────────────────────────────
    public function editLeave($id)
    {
        $leave     = Leave::findOrFail($id);
        $leaveDate = json_decode($leave->leave_date, true);
        $leaveDay  = json_decode($leave->leave_day, true);

        $leaveInformation = LeaveInformation::all();
        $staffId          = $leave->staff_id;
        $remainingLeaves  = [];

        foreach ($leaveInformation as $info) {
            $usedLeaves = Leave::where('staff_id', $staffId)
                ->where('leave_type', $info->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->where('id', '!=', $id)
                ->sum('number_of_day');
            $remainingLeaves[$info->leave_type] = max(0, $info->leave_days - $usedLeaves);
        }

        $users = User::all();

        return view('HR.LeavesManage.edit-leave', compact(
            'leave', 'leaveDate', 'leaveDay', 'leaveInformation', 'remainingLeaves', 'users'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Update
    // ─────────────────────────────────────────────────────────────
    public function updateLeave(Request $request, $id)
    {
        $request->validate([
            'leave_type'    => 'required|string',
            'date_from'     => 'required',
            'date_to'       => 'required',
            'reason'        => 'required',
            'number_of_day' => 'required|numeric|min:0.5',
        ]);

        try {
            $leave     = Leave::findOrFail($id);
            $oldStatus = $leave->status;
            $leaveInfo = LeaveInformation::where('leave_type', $request->leave_type)->first();

            $usedLeaves = Leave::where('staff_id', $leave->staff_id)
                ->where('leave_type', $request->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->where('id', '!=', $id)
                ->sum('number_of_day');

            if (in_array($oldStatus, ['Approved', 'Pending'])) {
                $usedLeaves += $leave->number_of_day;
            }

            $remainingDays = $leaveInfo->leave_days - $usedLeaves;

            if ($remainingDays < $request->number_of_day && $request->number_of_day > $leave->number_of_day) {
                $extraNeeded = $request->number_of_day - $leave->number_of_day;
                if ($remainingDays < $extraNeeded) {
                    flash()->error('Insufficient leave balance! Remaining: ' . max(0, $remainingDays));
                    return redirect()->back()->withInput();
                }
            }

            $leave->leave_type    = $request->leave_type;
            $leave->date_from     = $request->date_from;
            $leave->date_to       = $request->date_to;
            $leave->number_of_day = $request->number_of_day;
            $leave->reason        = $request->reason;

            if (!empty($request->leave_date))       $leave->leave_date = json_encode($request->leave_date);
            if (!empty($request->select_leave_day)) $leave->leave_day  = json_encode($request->select_leave_day);

            $newUsedLeaves = Leave::where('staff_id', $leave->staff_id)
                ->where('leave_type', $request->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->where('id', '!=', $id)
                ->sum('number_of_day');
            $newUsedLeaves        += $request->number_of_day;
            $leave->remaining_leave = max(0, $leaveInfo->leave_days - $newUsedLeaves);
            $leave->save();

            flash()->success('Leave updated successfully :)');

            return (auth()->user()->role_name === 'HR' || auth()->user()->role_name === 'Admin')
                ? redirect()->route('hr/leave/hr/page')
                : redirect()->route('hr/leave/employee/page');

        } catch (\Exception $e) {
            \Log::error('Error updating leave: ' . $e->getMessage());
            flash()->error('Failed to update leave: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – HR Page
    // ─────────────────────────────────────────────────────────────
    public function leaveHR(Request $request)
    {
        $search    = $request->input('search');
        $status    = $request->input('status');
        $leaveType = $request->input('leave_type');

        $query = Leave::with('user')->orderBy('created_at', 'desc');

        if (Auth::user()->role_name === 'Manager') {
            $dept = Auth::user()->department;
            $query->whereHas('user', function ($q) use ($dept) {
                $q->where('department', $dept);
            });
        }

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

        $today         = date('Y-m-d');
        $baseStatQuery = Leave::query();
        if (Auth::user()->role_name === 'Manager') {
            $dept = Auth::user()->department;
            $baseStatQuery->whereHas('user', fn ($q) => $q->where('department', $dept));
        }

        $todayLeaves    = (clone $baseStatQuery)->whereDate('date_from', '<=', $today)->whereDate('date_to', '>=', $today)->where('status', 'Approved')->count();
        $totalLeaves    = (clone $baseStatQuery)->count();
        $pendingLeaves  = (clone $baseStatQuery)->where('status', 'Pending')->count();
        $approvedLeaves = (clone $baseStatQuery)->where('status', 'Approved')->count();
        $rejectedLeaves = (clone $baseStatQuery)->where('status', 'Rejected')->count();
        $leaveTypes     = LeaveInformation::all();

        return view('HR.LeavesManage.leave-hr', compact(
            'leaves', 'search', 'status', 'leaveType',
            'todayLeaves', 'totalLeaves', 'pendingLeaves',
            'approvedLeaves', 'rejectedLeaves', 'leaveTypes'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Create (HR)
    // ─────────────────────────────────────────────────────────────
    public function createLeaveHR()
    {
        $usersQuery = User::query();
        if (Auth::user()->role_name === 'Manager') {
            $usersQuery->where('department', Auth::user()->department);
        }
        $users            = $usersQuery->get();
        $leaveInformation = LeaveInformation::all();
        return view('HR.LeavesManage.create-leave-hr', compact('users', 'leaveInformation'));
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Save (HR)
    // ─────────────────────────────────────────────────────────────
    /* public function saveRecordLeaveHR(Request $request)
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
            $user = User::where('user_id', $request->employee_name)->first();
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

            $save                 = new Leave;
            $save->staff_id       = $user->user_id;
            $save->employee_name  = $user->name;
            $save->leave_type     = $request->leave_type;
            $save->remaining_leave = $remainingDays - $request->number_of_day;
            $save->date_from      = $request->date_from;
            $save->date_to        = $request->date_to;
            $save->number_of_day  = $request->number_of_day;
            $save->leave_date     = json_encode($request->leave_date);
            $save->leave_day      = json_encode($request->select_leave_day);
            $save->status         = 'Approved';
            $save->manager_status = 'Approved';
            $save->reason         = $request->reason;
            $save->approved_by    = Session::get('name');
            $save->save();

            flash()->success('Leave added successfully :)');
            return redirect()->route('hr/leave/hr/page');

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to add leave :)');
            return redirect()->back();
        }
    } */
    public function saveRecordLeaveHR(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string',
            'leave_type'    => 'required|string',
            'date_from'     => 'required',
            'date_to'       => 'required',
            'reason'        => 'required',
            'number_of_day' => 'required|numeric',
        ]);

        try {
            $user = User::where('user_id', $request->employee_name)->first();
            if (!$user) {
                flash()->error('Employee not found!');
                return redirect()->back()->withInput();
            }

            $leaveInfo = LeaveInformation::where('leave_type', $request->leave_type)->first();
            if (!$leaveInfo) {
                flash()->error('Leave type not found!');
                return redirect()->back()->withInput();
            }

            $usedLeaves = Leave::where('staff_id', $user->user_id)
                ->where('leave_type', $request->leave_type)
                ->whereIn('status', ['Approved', 'Pending'])
                ->sum('number_of_day');

            $remainingDays = ($leaveInfo->leave_days >= 999) ? 9999 : ($leaveInfo->leave_days - $usedLeaves);

            if ($leaveInfo->leave_days < 999 && $remainingDays < $request->number_of_day) {
                flash()->error('Insufficient leave balance for this employee! Remaining: ' . max(0, $remainingDays) . ' days');
                return redirect()->back()->withInput();
            }

            // Parse dates - handle both "d M, Y" (flatpickr) and "Y-m-d" formats
            try {
                $dateFrom = Carbon::parse($request->date_from)->format('Y-m-d');
                $dateTo   = Carbon::parse($request->date_to)->format('Y-m-d');
            } catch (\Exception $e) {
                flash()->error('Invalid date format. Please select dates again.');
                return redirect()->back()->withInput();
            }

            // Build leave_date and leave_day arrays
            // If HR didn't get per-day selects (non-annual leave), auto-generate them
            if (!empty($request->leave_date) && is_array($request->leave_date)) {
                $leaveDates = $request->leave_date;
                $leaveDays  = $request->select_leave_day ?? array_fill(0, count($leaveDates), 'Full-Day Leave');
            } else {
                // Auto-generate dates between from and to
                $leaveDates = [];
                $leaveDays  = [];
                $current    = Carbon::parse($dateFrom);
                $end        = Carbon::parse($dateTo);
                while ($current->lte($end)) {
                    $leaveDates[] = $current->format('d M, Y');
                    $leaveDays[]  = 'Full-Day Leave';
                    $current->addDay();
                }
            }

            $newRemainingDays = ($leaveInfo->leave_days >= 999) ? 9999 : max(0, $remainingDays - $request->number_of_day);

            $save                  = new Leave();
            $save->staff_id        = $user->user_id;
            $save->employee_name   = $user->name;
            $save->leave_type      = $request->leave_type;
            $save->remaining_leave = $newRemainingDays;
            $save->date_from       = $dateFrom;
            $save->date_to         = $dateTo;
            $save->number_of_day   = $request->number_of_day;
            $save->leave_date      = json_encode($leaveDates);
            $save->leave_day       = json_encode($leaveDays);
            $save->status          = 'Approved';
            $save->manager_status  = 'Approved';
            $save->reason          = $request->reason;
            $save->approved_by     = Session::get('name') ?? Auth::user()->name;
            $save->save();

            flash()->success('Leave assigned successfully!');
            return redirect()->route('hr/leave/hr/page');

        } catch (\Exception $e) {
            \Log::error('saveRecordLeaveHR Error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            flash()->error('Failed to assign leave: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Update Status (AJAX)
    // ─────────────────────────────────────────────────────────────
    public function updateLeaveStatus(Request $request)
    {
        try {
            $leave     = Leave::findOrFail($request->id);
            $user      = Auth::user();
            $newStatus = $request->status;
            $oldStatus = $leave->status;

            if ($user->role_name === 'Manager') {
                $leave->manager_status = $newStatus;
                $leave->approved_by    = $user->name;

                if ($newStatus === 'Rejected') {
                    $leave->status = 'Rejected';
                    $this->sendLeaveStatusNotification($leave, $oldStatus, 'Rejected');
                } else {
                    $this->notifyManagerApproval($leave);
                }
                $leave->save();

            } elseif ($user->role_name === 'HR' || $user->role_name === 'Admin') {
                if ($leave->manager_status !== 'Approved' && $newStatus === 'Approved') {
                    return response()->json(['response_code' => 400, 'status' => 'error', 'message' => 'Manager must approve this leave first.']);
                }
                $leave->status      = $newStatus;
                $leave->approved_by = $user->name;
                $leave->save();
                $this->sendLeaveStatusNotification($leave, $oldStatus, $newStatus);
            }

            return response()->json(['response_code' => 200, 'status' => 'success', 'message' => 'Leave status updated successfully']);

        } catch (\Exception $e) {
            \Log::error('Error updating leave status: ' . $e->getMessage());
            return response()->json(['response_code' => 500, 'status' => 'error', 'message' => 'Failed to update status: ' . $e->getMessage()], 500);
        }
    }

    private function sendLeaveStatusNotification($leave, $oldStatus, $newStatus)
    {
        try {
            if ($oldStatus === $newStatus) return;

            $employee = User::where('user_id', $leave->staff_id)->first();
            if (!$employee) return;

            if ($newStatus === 'Approved') {
                $title   = app()->getLocale() === 'ar' ? '✅ تم قبول الإجازة'  : '✅ Leave Approved';
                $message = app()->getLocale() === 'ar'
                    ? "عزيزي {$employee->name}، تم قبول طلب الإجازة ({$leave->leave_type}) من {$leave->date_from} إلى {$leave->date_to} بواسطة " . Session::get('name')
                    : "Dear {$employee->name}, your {$leave->leave_type} leave from {$leave->date_from} to {$leave->date_to} has been APPROVED by " . Session::get('name');
                $type = 'leave_approved';
            } elseif ($newStatus === 'Rejected') {
                $title   = app()->getLocale() === 'ar' ? '❌ تم رفض الإجازة'  : '❌ Leave Rejected';
                $message = app()->getLocale() === 'ar'
                    ? "عزيزي {$employee->name}، تم رفض طلب الإجازة ({$leave->leave_type}) من {$leave->date_from} إلى {$leave->date_to} بواسطة " . Session::get('name')
                    : "Dear {$employee->name}, your {$leave->leave_type} leave from {$leave->date_from} to {$leave->date_to} has been REJECTED by " . Session::get('name');
                $type = 'leave_rejected';
            } else {
                return;
            }

            Notification::create([
                'user_id'  => $leave->staff_id,
                'type'     => $type,
                'title'    => $title,
                'message'  => $message,
                'leave_id' => $leave->id,
                'is_read'  => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending notification: ' . $e->getMessage());
        }
    }

    private function notifyNewLeaveRequest($leave)
    {
        try {
            $employee = User::where('user_id', $leave->staff_id)->first();
            if (!$employee) return;

            $manager = User::where('role_name', 'Manager')->where('department', $employee->department)->where('status', 'Active')->first();
            if ($manager) {
                Notification::create([
                    'user_id'  => $manager->user_id,
                    'type'     => 'new_leave_request',
                    'title'    => app()->getLocale() === 'ar' ? '🆕 طلب إجازة جديد' : '🆕 New Leave Request',
                    'message'  => app()->getLocale() === 'ar'
                        ? "قدم {$employee->name} طلب إجازة جديد ({$leave->leave_type}) يحتاج لموافقتك."
                        : "{$employee->name} submitted a new {$leave->leave_type} leave request that requires your approval.",
                    'leave_id' => $leave->id,
                    'is_read'  => false,
                ]);
            }

            $hrAdmins = User::whereIn('role_name', ['HR', 'Admin'])->where('status', 'Active')->get();
            foreach ($hrAdmins as $u) {
                Notification::create([
                    'user_id'  => $u->user_id,
                    'type'     => 'new_leave_request',
                    'title'    => app()->getLocale() === 'ar' ? '🆕 طلب إجازة جديد' : '🆕 New Leave Request',
                    'message'  => app()->getLocale() === 'ar'
                        ? "قدم {$employee->name} طلب إجازة جديد ({$leave->leave_type})."
                        : "{$employee->name} submitted a new {$leave->leave_type} leave request.",
                    'leave_id' => $leave->id,
                    'is_read'  => false,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error notifying new leave: ' . $e->getMessage());
        }
    }

    private function notifyManagerApproval($leave)
    {
        try {
            $employee = User::where('user_id', $leave->staff_id)->first();
            if (!$employee) return;

            $hrAdmins = User::whereIn('role_name', ['HR', 'Admin'])->where('status', 'Active')->get();
            foreach ($hrAdmins as $u) {
                Notification::create([
                    'user_id'  => $u->user_id,
                    'type'     => 'manager_approved_leave',
                    'title'    => app()->getLocale() === 'ar' ? '✅ موافقة المدير على الإجازة' : '✅ Manager Approved Leave',
                    'message'  => app()->getLocale() === 'ar'
                        ? "وافق المدير على طلب إجازة {$employee->name} ({$leave->leave_type}). يمكنك الآن إعطاء الموافقة النهائية."
                        : "The manager approved {$employee->name}'s {$leave->leave_type} leave. You can now provide final approval.",
                    'leave_id' => $leave->id,
                    'is_read'  => false,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error notifying manager approval: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Leave – Delete
    // ─────────────────────────────────────────────────────────────
    public function deleteLeave(Request $request)
    {
        try {
            $leaveId = $request->id ?? $request->id_delete;

            if (!$leaveId) {
                if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Leave ID is required'], 400);
                flash()->error('Leave ID is required');
                return redirect()->back();
            }

            $leave          = Leave::findOrFail($leaveId);
            $userRole       = auth()->user()->role_name;
            $loggedInUserId = auth()->user()->user_id;
            $canDelete      = false;

            if ($userRole === 'HR' || $userRole === 'Admin') {
                $canDelete = true;
            } elseif ($leave->staff_id === $loggedInUserId && $leave->status === 'Pending') {
                $canDelete = true;
            }

            if (!$canDelete) {
                if ($request->ajax()) return response()->json(['success' => false, 'message' => 'You are not authorized to delete this leave.'], 403);
                flash()->error('You are not authorized to delete this leave.');
                return redirect()->back();
            }

            $leave->delete();

            if ($request->ajax()) return response()->json(['success' => true, 'message' => 'Leave deleted successfully']);

            flash()->success('Leave deleted successfully');
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error('Error deleting leave: ' . $e->getMessage());
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Failed to delete leave: ' . $e->getMessage()], 500);
            flash()->error('Failed to delete leave: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Attendance – Employee Page
    // ─────────────────────────────────────────────────────────────
    public function attendance(Request $request)
    {
        $selectedUserId = $request->user_id ?? Session::get('user_id');
        $authUser       = Auth::user();
        $isManager      = in_array($authUser->role_name, ['Manager', 'manager']);

        $usersQuery = User::query();
        if ($isManager) {
            $usersQuery->where('department', $authUser->department);
            if ($selectedUserId && !User::where('user_id', $selectedUserId)->where('department', $authUser->department)->exists()) {
                flash()->error('Unauthorized access to employee record.');
                $selectedUserId = $authUser->user_id;
            }
        }
        $users = $usersQuery->get();

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

    public function getAttendanceDetails($id)
    {
        try {
            $attendance = Attendance::with('user')->find($id);
            if (!$attendance) return response()->json(['error' => 'Record not found'], 404);
            return view('HR.Attendance.attendance-details-modal', compact('attendance'));
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    private function calculateUserStats($userId, $startDate, $endDate)
    {
        $attendances = Attendance::where('user_id', $userId)->whereBetween('date', [$startDate, $endDate])->get();

        $stats = [
            'approved_hours'       => 0,
            'rejected_hours'       => 0,
            'pending_hours'        => 0,
            'total_hours'          => 0,
            'regular_hours'        => 0,
            'overtime_hours'       => 0,
            'late_days'            => 0,
            'early_departure_days' => 0,
            'present_days'         => 0,
            'absent_days'          => 0,
        ];

        foreach ($attendances as $a) {
            $stats['total_hours']    += $a->working_hours;
            $stats['regular_hours']  += min($a->working_hours, 8);
            $stats['overtime_hours'] += $a->overtime_hours;
            if ($a->late_minutes > 0)            $stats['late_days']++;
            if ($a->early_departure_minutes > 0) $stats['early_departure_days']++;
            if ($a->status === 'present')        $stats['present_days']++;
            if ($a->status === 'absent')         $stats['absent_days']++;
        }

        return $stats;
    }

    public function getEmployeeAttendanceData($userId)
    {
        try {
            $user = User::where('user_id', $userId)->first();
            if (!$user) return response()->json(['error' => 'User not found'], 404);

            $todayAttendance = Attendance::where('user_id', $userId)->where('date', Carbon::today())->first();
            $stats           = $this->calculateUserStats($userId, Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());

            return response()->json([
                'user'  => [
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

    // ─────────────────────────────────────────────────────────────
    //  Attendance – Check In / Out
    // ─────────────────────────────────────────────────────────────
    public function checkIn(Request $request)
    {
        try {
            $userId = $request->user_id;
            $now    = Carbon::now();

            $existing = Attendance::where('user_id', $userId)->where('date', $now->toDateString())->first();
            if ($existing && $existing->check_in) return response()->json(['error' => 'Already checked in today'], 400);

            $lateMinutes   = 0;
            $status        = 'present';
            $checkInTime   = $now->format('H:i:s');
            $lateThreshold = '10:00:00';

            if ($checkInTime > $lateThreshold) {
                $lateMinutes = Carbon::parse($checkInTime)->diffInMinutes(Carbon::parse($lateThreshold));
                $status      = 'late';
            }

            $attendance = Attendance::updateOrCreate(
                ['user_id' => $userId, 'date' => $now->toDateString()],
                ['check_in' => $now->toTimeString(), 'status' => $status, 'late_minutes' => $lateMinutes, 'notes' => $request->notes]
            );

            return response()->json(['success' => true, 'message' => 'Check in successful', 'data' => $attendance]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Check in failed'], 500);
        }
    }

    public function checkOut(Request $request)
    {
        try {
            $userId     = $request->user_id;
            $now        = Carbon::now();
            $attendance = Attendance::where('user_id', $userId)->where('date', $now->toDateString())->first();

            if (!$attendance || !$attendance->check_in) return response()->json(['error' => 'No check in record found'], 400);
            if ($attendance->check_out)                  return response()->json(['error' => 'Already checked out today'], 400);

            $checkIn       = Carbon::parse($attendance->check_in);
            $workingHours  = $now->diffInHours($checkIn) + ($now->diffInMinutes($checkIn) % 60) / 60;
            $earlyMinutes  = 0;
            $requiredHours = 8;

            if ($workingHours < $requiredHours) {
                $earlyMinutes       = ($requiredHours - $workingHours) * 60;
                $attendance->status = ($attendance->status === 'late') ? 'late_early' : 'early_departure';
            }

            $attendance->check_out               = $now->toTimeString();
            $attendance->working_hours           = $workingHours;
            $attendance->overtime_hours          = max(0, $workingHours - $requiredHours);
            $attendance->early_departure_minutes = $earlyMinutes;
            $attendance->save();

            return response()->json(['success' => true, 'message' => 'Check out successful', 'data' => $attendance]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Check out failed'], 500);
        }
    }

    public function updateAttendanceStatus(Request $request)
    {
        try {
            $attendance = Attendance::find($request->id);
            if (!$attendance) return response()->json(['error' => 'Record not found'], 404);
            $attendance->status      = $request->status;
            $attendance->approved_by = Session::get('name');
            $attendance->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Update failed'], 500);
        }
    }

    public function bulkApproveAttendance(Request $request)
    {
        try {
            Attendance::whereIn('id', $request->ids)->update(['status' => 'approved', 'approved_by' => Session::get('name')]);
            return response()->json(['success' => true, 'message' => 'Records approved successfully']);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Bulk approve failed'], 500);
        }
    }

    public function bulkRejectAttendance(Request $request)
    {
        try {
            Attendance::whereIn('id', $request->ids)->update(['status' => 'rejected', 'approved_by' => Session::get('name')]);
            return response()->json(['success' => true, 'message' => 'Records rejected successfully']);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Bulk reject failed'], 500);
        }
    }

    private function getWorkingDaysInMonth($year, $month)
    {
        $workingDays = 0;
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::create($year, $month, $d, 0, 0, 0, 'Africa/Cairo');
            if ($date->dayOfWeek != 5 && $date->dayOfWeek != 6) $workingDays++;
        }
        return $workingDays;
    }

    // ─────────────────────────────────────────────────────────────
    //  Attendance – Main (Report)
    // ─────────────────────────────────────────────────────────────
    public function attendanceMain(Request $request)
    {
        $now     = Carbon::now('Africa/Cairo');
        $month   = (int) $request->get('month', $now->month);
        $year    = (int) $request->get('year',  $now->year);
        $search  = $request->get('search', '');
        $perPage = max(1, (int) $request->get('per_page', 25));

        $startDate   = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Cairo')->startOfMonth();
        $endDate     = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Cairo')->endOfMonth();
        $daysInMonth = $endDate->day;

        $today     = $now->toDateString();
        $authUser  = Auth::user();
        $isManager = in_array($authUser->role_name, ['Manager', 'manager']);

        $activeUsersQuery = User::query();
        if ($isManager) $activeUsersQuery->where('department', $authUser->department);
        $totalEmployees = $activeUsersQuery->count();
        $activeUserIds  = (clone $activeUsersQuery)->pluck('user_id');

        $presentToday = Attendance::whereDate('date', $today)
            ->whereIn('status', ['present', 'late', 'early_departure', 'late_early', 'approved'])
            ->whereIn('user_id', $activeUserIds)
            ->distinct('user_id')
            ->count('user_id');
        $absentToday  = max(0, $totalEmployees - $presentToday);

        $workingDays = $this->getWorkingDaysInMonth($year, $month);

        $attendancesQuery = User::query();
        if ($isManager) $attendancesQuery->where('department', $authUser->department);

        $attendances = $attendancesQuery->when($search, function ($q) use ($search) {
                return $q->where(fn ($inner) => $inner->where('name', 'like', "%{$search}%")->orWhere('user_id', 'like', "%{$search}%"));
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $userIds        = $attendances->pluck('user_id')->toArray();
        $attendanceData = Attendance::whereIn('user_id', $userIds)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->groupBy('user_id');

        $attendanceMatrix = [];
        foreach ($userIds as $uid) {
            $attendanceMatrix[$uid] = [];
            foreach ($attendanceData->get($uid, collect()) as $rec) {
                $attendanceMatrix[$uid][(int) Carbon::parse($rec->date)->format('j')] = $rec->status;
            }
        }

        $weekends = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dayObj = Carbon::create($year, $month, $d, 0, 0, 0, 'Africa/Cairo');
            if ($dayObj->dayOfWeek == 5 || $dayObj->dayOfWeek == 6) $weekends[] = $d;
        }

        return view('HR.Attendance.attendance-main', compact(
            'attendances', 'attendanceData', 'month', 'year', 'daysInMonth', 'attendanceMatrix',
            'weekends', 'totalEmployees', 'presentToday', 'absentToday',
            'workingDays', 'search', 'startDate', 'endDate', 'perPage'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  Attendance – Export Main
    // ─────────────────────────────────────────────────────────────
    public function exportAttendanceMain(Request $request)
    {
        try {
            $month   = (int) $request->get('month', Carbon::now('Africa/Cairo')->month);
            $year    = (int) $request->get('year',  Carbon::now('Africa/Cairo')->year);
            $search  = $request->get('search', '');

            $startDate   = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Cairo')->startOfMonth();
            $endDate     = Carbon::create($year, $month, 1, 0, 0, 0, 'Africa/Cairo')->endOfMonth();
            $daysInMonth = $endDate->day;

            $authUser  = Auth::user();
            $isManager = in_array($authUser->role_name, ['Manager', 'manager']);

            $usersQuery = User::query();
            if ($isManager) $usersQuery->where('department', $authUser->department);

            $users = $usersQuery->when($search, function ($q) use ($search) {
                    return $q->where(fn ($inner) => $inner->where('name', 'like', "%{$search}%")->orWhere('user_id', 'like', "%{$search}%"));
                })
                ->orderBy('name')
                ->get();

            $userIds     = $users->pluck('user_id')->toArray();
            $attendances = Attendance::whereIn('user_id', $userIds)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->get()
                ->groupBy('user_id');

            $attendanceMatrix = [];
            foreach ($userIds as $uid) {
                $attendanceMatrix[$uid] = [];
                foreach ($attendances->get($uid, collect()) as $rec) {
                    $attendanceMatrix[$uid][(int) Carbon::parse($rec->date)->format('j')] = $rec->status;
                }
            }

            $fileName = 'attendance_' . $year . '_' . $month . '_' . date('Y-m-d_His') . '.csv';
            $headers  = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $callback = function () use ($users, $daysInMonth, $year, $month, $attendanceMatrix) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

                $headerRow = [__('messages.employee_name'), __('messages.employee_id'), __('messages.department')];
                for ($d = 1; $d <= $daysInMonth; $d++) $headerRow[] = $d;
                $headerRow[] = __('messages.present_days');
                $headerRow[] = __('messages.absent_days');
                fputcsv($handle, $headerRow);

                foreach ($users as $user) {
                    $row          = [$user->name, $user->user_id, $user->department ?? '—'];
                    $presentCount = 0;
                    $absentCount  = 0;

                    for ($d = 1; $d <= $daysInMonth; $d++) {
                        $dayObj    = Carbon::create($year, $month, $d, 0, 0, 0, 'Africa/Cairo');
                        $isWeekend = ($dayObj->dayOfWeek == 5 || $dayObj->dayOfWeek == 6);
                        $isFuture  = $dayObj->isFuture();
                        $status    = $attendanceMatrix[$user->user_id][$d] ?? null;

                        if ($isWeekend)      { $row[] = __('messages.weekend'); }
                        elseif ($isFuture)   { $row[] = '—'; }
                        elseif ($status === null) { $row[] = __('messages.absent'); $absentCount++; }
                        elseif (in_array($status, ['present', 'late', 'early_departure', 'late_early', 'approved'])) { $row[] = __('messages.present'); $presentCount++; }
                        elseif ($status === 'absent' || $status === 'rejected') { $row[] = __('messages.absent'); $absentCount++; }
                        else { $row[] = $status; }
                    }

                    $row[] = $presentCount;
                    $row[] = $absentCount;
                    fputcsv($handle, $row);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            flash()->error(__('messages.error_occurred') . ': ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Department
    // ─────────────────────────────────────────────────────────────
    public function department(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $departmentList = Department::withCount('users')
            ->when($search, function ($q, $search) {
                return $q->where('department', 'like', '%' . $search . '%')
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

    public function saveRecordDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department'   => 'required|string|max:255|unique:departments,department,' . $request->id_update,
            'head_of'      => 'required|string|max:255|exists:users,name',
            'phone_number' => 'required|numeric',
            'email'        => 'required|email|max:255',
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
                Department::findOrFail($request->id_update)->update($data);
                flash()->success('Department updated successfully :)');
            } else {
                Department::create($data);
                flash()->success('Department created successfully :)');
            }

            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error('Error saving department: ' . $e->getMessage());
            flash()->error('Failed to save department. Please try again.');
            return redirect()->back()->withInput()->with('open_modal_' . ($request->id_update ? 'edit' : 'add'), true);
        }
    }

    public function deleteRecordDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_delete' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Department::findOrFail($request->id_delete)->delete();
            flash()->success('Department deleted successfully :)');
        } catch (\Exception $e) {
            \Log::error('Error deleting department: ' . $e->getMessage());
            flash()->error('Failed to delete department. Please try again.');
        }

        return redirect()->back();
    }
}