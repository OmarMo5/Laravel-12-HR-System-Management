@extends('layouts.master')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Page Header -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.attendance_history') }}</h5>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.track_attendance_records') }}</p>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400">
                        <a href="{{ route('employee/attendance/dashboard') }}"
                            class="text-slate-400 hover:text-custom-500">{{ __('messages.attendance') }}</a>
                    </li>
                    <li class="text-slate-700">{{ __('messages.history') }}</li>
                </ul>
            </div>

            <!-- Employee Filter Card (for HR) -->
            @if(isset($isHR) && $isHR)
            <div class="card mb-5 border-l-4 border-l-custom-500">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="size-10 rounded-full bg-custom-100 flex items-center justify-center">
                            <i data-lucide="users" class="size-5 text-custom-500"></i>
                        </div>
                        <div>
                            <h6 class="text-15 font-semibold">{{ __('messages.employee_filter') }}</h6>
                            <p class="text-xs text-slate-500">{{ __('messages.view_attendance_by_employee') }}</p>
                        </div>
                    </div>
                    
                    <form method="GET" action="{{ route('employee/attendance/history') }}" class="grid grid-cols-1 gap-4 md:grid-cols-12">
                        <div class="md:col-span-5">
                            <label class="inline-block mb-2 text-sm font-medium">{{ __('messages.select_employee') }}</label>
                            <select name="employee_id" class="form-input w-full border-slate-200 focus:border-custom-500">
                                <option value="all">{{ __('messages.all_employees') }}</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->user_id }}" {{ $selectedEmployee == $emp->user_id ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ $emp->user_id }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="inline-block mb-2 text-sm font-medium">{{ __('messages.month') }}</label>
                            <select name="month" class="form-input w-full border-slate-200 focus:border-custom-500">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="inline-block mb-2 text-sm font-medium">{{ __('messages.year') }}</label>
                            <select name="year" class="form-input w-full border-slate-200 focus:border-custom-500">
                                @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="md:col-span-2 flex items-end gap-2">
                            <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600 flex-1">
                                <i data-lucide="filter" class="inline-block size-4 mr-1"></i>
                                {{ __('messages.filter') }}
                            </button>
                            <a href="{{ route('employee/attendance/history') }}" class="btn bg-slate-200 text-slate-800 hover:bg-slate-300">
                                <i data-lucide="refresh-cw" class="inline-block size-4"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Selected Employee Info -->
            @if(isset($selectedUser) && $selectedUser)
            <div class="card mb-5 bg-gradient-to-r from-custom-500/10 to-custom-600/10 border border-custom-200">
                <div class="card-body py-3">
                    <div class="flex items-center gap-3">
                        <div class="size-12 rounded-full bg-custom-500/20 flex items-center justify-center">
                            <i data-lucide="user" class="size-6 text-custom-500"></i>
                        </div>
                        <div>
                            <h6 class="text-base font-semibold">{{ $selectedUser->name }}</h6>
                            <p class="text-xs text-slate-500">{{ $selectedUser->user_id }} • {{ $selectedUser->position ?? 'Employee' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Monthly Overview Card - تصميم تقويم مرتب -->
            <div class="card mb-5">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <div>
                            <h6 class="text-15 font-semibold">{{ __('messages.monthly_overview') }}</h6>
                            <p class="text-xs text-slate-500">{{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="inline-flex items-center gap-1 text-xs"><span class="w-2 h-2 rounded-full bg-green-500"></span> {{ __('messages.present') }}</span>
                            <span class="inline-flex items-center gap-1 text-xs"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> {{ __('messages.late') }}</span>
                            <span class="inline-flex items-center gap-1 text-xs"><span class="w-2 h-2 rounded-full bg-orange-500"></span> {{ __('messages.early') }}</span>
                            <span class="inline-flex items-center gap-1 text-xs"><span class="w-2 h-2 rounded-full bg-red-500"></span> {{ __('messages.absent') }}</span>
                        </div>
                    </div>

                    <!-- تقويم الشهر - 7 أعمدة ثابتة بدون اسكرول -->
                    <div class="w-full overflow-hidden">
                        <div class="grid grid-cols-7 gap-1 text-center">
                            <!-- أيام الأسبوع -->
                            <div class="text-xs font-semibold py-2 bg-slate-100 dark:bg-zink-600 rounded-t-lg">{{ __('messages.mon') }}</div>
                            <div class="text-xs font-semibold py-2 bg-slate-100 dark:bg-zink-600 rounded-t-lg">{{ __('messages.tue') }}</div>
                            <div class="text-xs font-semibold py-2 bg-slate-100 dark:bg-zink-600 rounded-t-lg">{{ __('messages.wed') }}</div>
                            <div class="text-xs font-semibold py-2 bg-slate-100 dark:bg-zink-600 rounded-t-lg">{{ __('messages.thu') }}</div>
                            <div class="text-xs font-semibold py-2 bg-slate-100 dark:bg-zink-600 rounded-t-lg">{{ __('messages.fri') }}</div>
                            <div class="text-xs font-semibold py-2 bg-red-100 text-red-600 dark:bg-red-500/20 rounded-t-lg">{{ __('messages.sat') }}</div>
                            <div class="text-xs font-semibold py-2 bg-red-100 text-red-600 dark:bg-red-500/20 rounded-t-lg">{{ __('messages.sun') }}</div>

                            <!-- الخلايا الفارغة قبل أول يوم في الشهر -->
                            @for($i = 0; $i < $firstDayOfWeek; $i++)
                                <div class="py-3 bg-slate-50 dark:bg-zink-700/30 rounded opacity-40"></div>
                            @endfor

                            <!-- أيام الشهر -->
                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $currentDate = Carbon\Carbon::create($year, $month, $day, 0, 0, 0, 'Africa/Cairo');
                                    $attendanceForDay = $attendances->first(function($item) use ($currentDate) {
                                        return Carbon\Carbon::parse($item->date)->format('Y-m-d') == $currentDate->format('Y-m-d');
                                    });
                                    
                                    $bgColor = 'bg-white dark:bg-zink-700';
                                    $textColor = 'text-slate-700 dark:text-zink-100';
                                    $ringClass = '';
                                    $statusIcon = '';
                                    
                                    if ($attendanceForDay) {
                                        switch ($attendanceForDay->status) {
                                            case 'present':
                                                $bgColor = 'bg-green-50 dark:bg-green-500/10';
                                                $ringClass = 'ring-1 ring-green-500';
                                                $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block ml-1 text-green-500"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                                                break;
                                            case 'late':
                                                $bgColor = 'bg-yellow-50 dark:bg-yellow-500/10';
                                                $ringClass = 'ring-1 ring-yellow-500';
                                                $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block ml-1 text-yellow-500"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                                                break;
                                            case 'early_departure':
                                                $bgColor = 'bg-orange-50 dark:bg-orange-500/10';
                                                $ringClass = 'ring-1 ring-orange-500';
                                                $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block ml-1 text-orange-500"><path d="M12 8v4l3 3"></path><circle cx="12" cy="12" r="10"></circle></svg>';
                                                break;
                                            case 'late_early':
                                                $bgColor = 'bg-red-50 dark:bg-red-500/10';
                                                $ringClass = 'ring-1 ring-red-500';
                                                $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block ml-1 text-red-500"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                                                break;
                                        }
                                    } else {
                                        if ($currentDate->isWeekend()) {
                                            $bgColor = 'bg-red-50 dark:bg-red-500/5';
                                            $textColor = 'text-red-400';
                                        }
                                    }
                                @endphp
                                <div id="day-{{ $day }}"
                                    class="day-cell py-3 {{ $bgColor }} {{ $ringClass }} rounded-lg cursor-pointer hover:scale-105 hover:shadow-md transition-all duration-200 group relative"
                                    data-day="{{ $day }}"
                                    data-date="{{ $currentDate->format('Y-m-d') }}"
                                    data-status="{{ $attendanceForDay ? ucfirst($attendanceForDay->status) : ($currentDate->isWeekend() ? 'Weekend' : 'Absent') }}"
                                    data-checkin="{{ $attendanceForDay && $attendanceForDay->check_in ? Carbon\Carbon::parse($attendanceForDay->check_in)->format('h:i A') : '—' }}"
                                    data-checkout="{{ $attendanceForDay && $attendanceForDay->check_out ? Carbon\Carbon::parse($attendanceForDay->check_out)->format('h:i A') : '—' }}"
                                    data-hours="{{ $attendanceForDay && $attendanceForDay->working_hours ? number_format($attendanceForDay->working_hours, 1) : '0' }}"
                                    onclick="showDayDetails(this)">
                                    
                                    <div class="flex items-center justify-center gap-0.5 flex-wrap">
                                        <span class="text-sm font-medium {{ $textColor }}">{{ $day }}</span>
                                        {!! $statusIcon !!}
                                    </div>
                                    
                                    <!-- Tooltip -->
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 hidden group-hover:block bg-gray-800 text-white text-[10px] rounded py-1 px-2 whitespace-nowrap z-50 shadow-lg pointer-events-none">
                                        <strong>{{ __('messages.day') }} {{ $day }}</strong><br>
                                        @if($attendanceForDay)
                                            {{ __('messages.check_in') }}: {{ Carbon\Carbon::parse($attendanceForDay->check_in)->format('h:i A') }}<br>
                                            @if($attendanceForDay->check_out)
                                                {{ __('messages.check_out') }}: {{ Carbon\Carbon::parse($attendanceForDay->check_out)->format('h:i A') }}
                                            @endif
                                        @else
                                            {{ $currentDate->isWeekend() ? __('messages.weekend') : __('messages.absent') }}
                                        @endif
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics Cards -->
            @php
                $totalRecords = $attendances->total();
                $presentCount = $attendances->filter(fn($item) => in_array($item->status, ['present', 'late', 'early_departure', 'late_early']))->count();
                $lateCount = $attendances->filter(fn($item) => $item->status == 'late' || $item->status == 'late_early')->count();
                $earlyCount = $attendances->filter(fn($item) => $item->status == 'early_departure')->count();
                $totalHours = $attendances->sum('working_hours');
                $avgHours = $presentCount > 0 ? round($totalHours / $presentCount, 1) : 0;
            @endphp

            <div class="grid grid-cols-2 gap-3 mb-5 lg:grid-cols-6">
                <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                    <div class="card-body py-2.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/80">{{ __('messages.total_days') }}</p>
                                <h4 class="text-xl font-bold">{{ $totalRecords }}</h4>
                            </div>
                            <i data-lucide="calendar" class="size-7 text-white/60"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
                    <div class="card-body py-2.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/80">{{ __('messages.present') }}</p>
                                <h4 class="text-xl font-bold">{{ $presentCount }}</h4>
                            </div>
                            <i data-lucide="check-circle" class="size-7 text-white/60"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
                    <div class="card-body py-2.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/80">{{ __('messages.late') }}</p>
                                <h4 class="text-xl font-bold">{{ $lateCount }}</h4>
                            </div>
                            <i data-lucide="alert-triangle" class="size-7 text-white/60"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-gradient-to-br from-orange-500 to-orange-600 text-white">
                    <div class="card-body py-2.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/80">{{ __('messages.early_departure') }}</p>
                                <h4 class="text-xl font-bold">{{ $earlyCount }}</h4>
                            </div>
                            <i data-lucide="log-out" class="size-7 text-white/60"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
                    <div class="card-body py-2.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/80">{{ __('messages.total_hours') }}</p>
                                <h4 class="text-xl font-bold">{{ number_format($totalHours, 1) }}</h4>
                            </div>
                            <i data-lucide="clock" class="size-7 text-white/60"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-gradient-to-br from-cyan-500 to-cyan-600 text-white">
                    <div class="card-body py-2.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/80">{{ __('messages.avg_hours') }}</p>
                                <h4 class="text-xl font-bold">{{ number_format($avgHours, 1) }}</h4>
                            </div>
                            <i data-lucide="trending-up" class="size-7 text-white/60"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <h6 class="text-15 font-semibold">{{ __('messages.attendance_records') }}</h6>
                        <div class="flex gap-2">
                            <button onclick="exportToExcel()" class="text-white btn bg-green-500 border-green-500 hover:bg-green-600 text-xs py-1.5 px-3">
                                <i data-lucide="file-spreadsheet" class="inline-block size-3 mr-1"></i>
                                {{ __('messages.export_excel') }}
                            </button>
                            <button onclick="window.print()" class="text-slate-700 btn bg-slate-200 border-slate-200 hover:bg-slate-300 text-xs py-1.5 px-3">
                                <i data-lucide="printer" class="inline-block size-3 mr-1"></i>
                                {{ __('messages.print') }}
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="attendanceTable">
                            <thead class="bg-slate-100 dark:bg-zink-600">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold">#</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold">{{ __('messages.date') }}</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold">{{ __('messages.day') }}</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold">{{ __('messages.check_in') }}</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold">{{ __('messages.check_out') }}</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold">{{ __('messages.status') }}</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold">{{ __('messages.hours') }}</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold">{{ __('messages.overtime') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $index => $attendance)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zink-600/50 transition-colors">
                                    <td class="px-3 py-2 border-b text-xs">{{ $attendances->firstItem() + $index }}</td>
                                    <td class="px-3 py-2 border-b text-xs">{{ Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}</td>
                                    <td class="px-3 py-2 border-b text-xs">{{ Carbon\Carbon::parse($attendance->date)->format('D') }}</td>
                                    <td class="px-3 py-2 border-b text-xs font-mono">
                                        {{ $attendance->check_in ? Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '—' }}
                                    </td>
                                    <td class="px-3 py-2 border-b text-xs font-mono">
                                        {{ $attendance->check_out ? Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '—' }}
                                    </td>
                                    <td class="px-3 py-2 border-b">
                                        @php
                                            $statusStyles = [
                                                'present' => 'bg-green-100 text-green-700',
                                                'late' => 'bg-yellow-100 text-yellow-700',
                                                'early_departure' => 'bg-orange-100 text-orange-700',
                                                'late_early' => 'bg-red-100 text-red-700',
                                            ];
                                            $statusLabels = [
                                                'present' => __('messages.present'),
                                                'late' => __('messages.late'),
                                                'early_departure' => __('messages.early'),
                                                'late_early' => __('messages.late_early'),
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusStyles[$attendance->status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $statusLabels[$attendance->status] ?? ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 border-b text-xs font-semibold">
                                        {{ $attendance->working_hours ? number_format($attendance->working_hours, 1) . 'h' : '—' }}
                                    </td>
                                    <td class="px-3 py-2 border-b text-xs">
                                        @if($attendance->overtime_hours > 0)
                                            <span class="text-green-600 font-semibold">+{{ number_format($attendance->overtime_hours, 1) }}h</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-3 py-8 text-center border-b text-sm text-slate-500">
                                        <i data-lucide="calendar-x" class="size-8 mx-auto mb-2 text-slate-400"></i>
                                        <p>{{ __('messages.no_attendance_records') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col items-center justify-between gap-3 mt-4 md:flex-row">
                        <div class="text-xs text-slate-500">
                            {{ __('messages.showing') }} {{ $attendances->firstItem() ?? 0 }} {{ __('messages.to') }}
                            {{ $attendances->lastItem() ?? 0 }} {{ __('messages.of') }}
                            {{ $attendances->total() }} {{ __('messages.entries') }}
                        </div>
                        <div class="flex items-center gap-1">
                            {{ $attendances->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Day Details Modal -->
    <div id="dayDetailsModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-zink-700 rounded-2xl max-w-sm w-full shadow-2xl transform transition-all">
            <div class="flex justify-between items-center p-4 border-b dark:border-zink-500">
                <h5 class="text-lg font-semibold">{{ __('messages.day_details') }}</h5>
                <button onclick="closeDayModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div id="dayDetailsContent" class="p-4 space-y-3">
                <!-- Content filled by JS -->
            </div>
            <div class="p-4 border-t dark:border-zink-500 text-right">
                <button onclick="closeDayModal()" class="px-4 py-2 text-sm bg-slate-200 dark:bg-zink-600 rounded-lg hover:bg-slate-300 dark:hover:bg-zink-500 transition">
                    {{ __('messages.close') }}
                </button>
            </div>
        </div>
    </div>

@endsection

@section('script')
<style>
/* تثبيت عرض التقويم */
.grid-cols-7 {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
}

.day-cell {
    transition: all 0.2s ease;
}

/* تحسين ظهور الـ tooltip */
.day-cell .group-hover\:block {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 6px;
    font-size: 10px;
    line-height: 1.4;
    min-width: 110px;
}

/* تحسين الجدول */
#attendanceTable th, #attendanceTable td {
    white-space: nowrap;
}

/* تحسين المودال */
#dayDetailsModal {
    z-index: 999999;
}
</style>

<script>
// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});

// Show day details
function showDayDetails(element) {
    let day = $(element).data('day');
    let date = $(element).data('date');
    let status = $(element).data('status');
    let checkin = $(element).data('checkin');
    let checkout = $(element).data('checkout');
    let hours = $(element).data('hours');
    
    let statusColor = 'text-slate-600';
    let statusBg = 'bg-slate-100';
    
    if (status === 'Present') {
        statusColor = 'text-green-600';
        statusBg = 'bg-green-100';
    } else if (status === 'Late') {
        statusColor = 'text-yellow-600';
        statusBg = 'bg-yellow-100';
    } else if (status === 'Early departure') {
        statusColor = 'text-orange-600';
        statusBg = 'bg-orange-100';
    } else if (status === 'Late early') {
        statusColor = 'text-red-600';
        statusBg = 'bg-red-100';
    } else if (status === 'Weekend') {
        statusColor = 'text-red-400';
        statusBg = 'bg-red-50';
    } else if (status === 'Absent') {
        statusColor = 'text-slate-500';
        statusBg = 'bg-slate-100';
    }
    
    let formattedDate = date ? new Date(date).toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    }) : 'Day ' + day;
    
    let content = `
        <div class="text-center mb-3">
            <div class="text-xl font-bold text-custom-500">${formattedDate}</div>
            <div class="text-sm text-slate-500 mt-1">Day ${day}</div>
        </div>
        <div class="border-t border-b border-slate-200 dark:border-zink-500 py-3 space-y-2">
            <div class="flex justify-between items-center">
                <span class="font-medium text-slate-600 dark:text-zink-200">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${statusBg} ${statusColor}">${status}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-600 dark:text-zink-200">Check In:</span>
                <span class="font-mono">${checkin}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-600 dark:text-zink-200">Check Out:</span>
                <span class="font-mono">${checkout}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-600 dark:text-zink-200">Hours Worked:</span>
                <span class="font-semibold">${hours} hrs</span>
            </div>
        </div>
    `;
    
    $('#dayDetailsContent').html(content);
    $('#dayDetailsModal').removeClass('hidden').css('display', 'flex');
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Close day modal
function closeDayModal() {
    $('#dayDetailsModal').addClass('hidden').css('display', 'none');
}

// Export to Excel
function exportToExcel() {
    let table = document.getElementById('attendanceTable');
    let html = table.outerHTML;
    let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    let link = document.createElement('a');
    link.download = 'attendance_{{ $month }}_{{ $year }}.xls';
    link.href = url;
    link.click();
}

// Close modal when clicking outside
$(document).on('click', function(e) {
    if ($(e.target).is('#dayDetailsModal')) {
        closeDayModal();
    }
});

// Close modal on escape key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDayModal();
    }
});

// Auto-submit filter on change
$('select[name="month"], select[name="year"], select[name="employee_id"]').on('change', function() {
    $(this).closest('form').submit();
});
</script>
@endsection
