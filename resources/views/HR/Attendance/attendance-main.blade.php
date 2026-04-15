@extends('layouts.master')
@section('content')
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">{{ __('messages.main_attendance') }}</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.hr_management') }}</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">{{ __('messages.main_attendance') }}</li>
            </ul>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 gap-x-5 md:grid-cols-2 xl:grid-cols-4 mb-5">
            <div class="card">
                <div class="flex items-center gap-4 card-body">
                    <div class="flex items-center justify-center rounded-md size-12 text-sky-500 bg-sky-100 text-15 dark:bg-sky-500/20 shrink-0">
                        <i data-lucide="users-2"></i>
                    </div>
                    <div class="overflow-hidden grow">
                        <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $totalEmployees }}">0</span></h5>
                        <p class="truncate text-slate-500 dark:text-zink-200">{{ __('messages.total_employee') }}</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="flex items-center gap-4 card-body">
                    <div class="flex items-center justify-center text-red-500 bg-red-100 rounded-md size-12 text-15 dark:bg-red-500/20 shrink-0">
                        <i data-lucide="user-x-2"></i>
                    </div>
                    <div class="overflow-hidden grow">
                        <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $absentToday }}">0</span></h5>
                        <p class="truncate text-slate-500 dark:text-zink-200">{{ __('messages.absent_today') }}</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="flex items-center gap-4 card-body">
                    <div class="flex items-center justify-center text-green-500 bg-green-100 rounded-md size-12 text-15 dark:bg-green-500/20 shrink-0">
                        <i data-lucide="user-check-2"></i>
                    </div>
                    <div class="overflow-hidden grow">
                        <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $presentToday }}">0</span></h5>
                        <p class="truncate text-slate-500 dark:text-zink-200">{{ __('messages.present_today') }}</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="flex items-center gap-4 card-body">
                    <div class="flex items-center justify-center rounded-md size-12 text-custom-500 bg-custom-100 text-15 dark:bg-custom-500/20 shrink-0">
                        <i data-lucide="briefcase"></i>
                    </div>
                    <div class="overflow-hidden grow">
                        <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $workingDays }}">0</span></h5>
                        <p class="truncate text-slate-500 dark:text-zink-200">{{ __('messages.working_days') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="card">
            <div class="card-body">

                {{-- Filters --}}
                <form method="GET" action="{{ route('hr/attendance/main/page') }}" id="filterForm">
                    <div class="grid grid-cols-1 gap-4 mb-5 lg:grid-cols-12">

                        {{-- Search --}}
                        <!-- <div class="lg:col-span-3">
                            <div class="relative">
                                <input type="text" name="search" id="searchInput" value="{{ $search }}"
                                    class="ltr:pl-8 rtl:pr-8 form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                    placeholder="{{ __('messages.search_for') }}" autocomplete="off">
                                <i data-lucide="search" class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500"></i>
                            </div>
                        </div> -->

                        {{-- Month --}}
                        <div class="lg:col-span-2">
                            <select name="month" id="monthSelect"
                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500">
                                @foreach(range(1,12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create(null, $m, 1)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Year --}}
                        <div class="lg:col-span-2">
                            <select name="year" id="yearSelect"
                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500">
                                @foreach(range(date('Y')-2, date('Y')+1) as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit --}}
                        <!-- <div class="lg:col-span-2">
                            <button type="submit"
                                class="w-full text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600 focus:ring focus:ring-custom-100">
                                <i data-lucide="filter" class="inline-block size-4 ltr:mr-1 rtl:ml-1"></i>
                                {{ __('messages.filter') }}
                            </button>
                        </div> -->

                        {{-- Legend - Updated for Friday & Saturday weekend --}}
                        <div class="lg:col-span-3 flex items-center gap-3 flex-wrap text-xs">
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> {{ __('messages.present') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-3 h-3 rounded-full bg-yellow-500"></span> {{ __('messages.late') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-3 h-3 rounded-full bg-orange-500"></span> {{ __('messages.early_departure') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-3 h-3 rounded-full bg-red-500"></span> {{ __('messages.absent') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-3 h-3 rounded-full bg-purple-500"></span> {{ __('messages.weekend_friday_saturday') }}
                            </span>
                        </div>

                    </div>
                </form>

                {{-- Table with horizontal scroll --}}
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-sm" id="mainAttendanceTable">
                        <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                            <tr>
                                <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 sticky ltr:left-0 rtl:right-0 bg-slate-100 dark:bg-zink-600 z-10 min-w-[180px]">
                                    {{ __('messages.employee_name') }}
                                </th>
                                @for($d = 1; $d <= $daysInMonth; $d++)
                                    @php
                                        $dayObj   = Carbon\Carbon::create($year, $month, $d, 0, 0, 0, 'Africa/Cairo');
                                        $isWeekend = ($dayObj->dayOfWeek == 5 || $dayObj->dayOfWeek == 6); // Friday (5) or Saturday (6)
                                        $isToday   = $dayObj->isToday();
                                        
                                        // Get day name in Arabic or English based on locale
                                        $dayName = '';
                                        if(app()->getLocale() == 'ar') {
                                            $dayNames = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                                            $dayName = $dayNames[$dayObj->dayOfWeek];
                                        } else {
                                            $dayName = $dayObj->format('D');
                                        }
                                    @endphp
                                    <th class="px-2 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 text-center w-10
                                        {{ $isWeekend ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600' : '' }}
                                        {{ $isToday ? 'bg-custom-100 text-custom-500 dark:bg-custom-500/20' : '' }}">
                                        <div class="text-sm font-bold">{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-[10px] font-normal opacity-80 mt-0.5">
                                            @if($dayObj->dayOfWeek == 5)
                                                {{ app()->getLocale() == 'ar' ? 'جمعة' : 'FRI' }}
                                            @elseif($dayObj->dayOfWeek == 6)
                                                {{ app()->getLocale() == 'ar' ? 'سبت' : 'SAT' }}
                                            @else
                                                {{ $dayName }}
                                            @endif
                                        </div>
                                    </th>
                                @endfor
                                <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 text-center min-w-[90px]">
                                    {{ __('messages.present_days') }}
                                </th>
                                <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 text-center min-w-[90px]">
                                    {{ __('messages.absent_days') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $userAttendance = $attendanceMatrix[$user->user_id] ?? [];
                                    $presentCount = 0;
                                    $absentCount  = 0;
                                @endphp
                                <tr class="border-y border-slate-200 dark:border-zink-500 hover:bg-slate-50 dark:hover:bg-zink-600/50 transition-colors">
                                    {{-- Employee Name Column (Sticky) --}}
                                    <td class="px-3.5 py-2.5 sticky ltr:left-0 rtl:right-0 bg-white dark:bg-zink-700 z-10 border-y border-slate-200 dark:border-zink-500">
                                        <div class="flex items-center gap-3">
                                            <div class="size-9 rounded-full bg-slate-100 dark:bg-zink-600 shrink-0 overflow-hidden ring-2 ring-slate-200 dark:ring-zink-500">
                                                <img src="{{ $user->avatar ? asset('assets/images/user/'.$user->avatar) : asset('assets/images/profile.png') }}"
                                                    alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full">
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-700 dark:text-zink-100">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-400 dark:text-zink-300">{{ $user->user_id }}</p>
                                                <p class="text-xs text-slate-400 dark:text-zink-300 mt-0.5">{{ $user->position ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Days Columns --}}
                                    @for($d = 1; $d <= $daysInMonth; $d++)
                                        @php
                                            $dayObj    = Carbon\Carbon::create($year, $month, $d, 0, 0, 0, 'Africa/Cairo');
                                            $isWeekend = ($dayObj->dayOfWeek == 5 || $dayObj->dayOfWeek == 6);
                                            $isToday   = $dayObj->isToday();
                                            $isFuture  = $dayObj->isFuture();
                                            $status    = $userAttendance[$d] ?? null;

                                            // Calculate present/absent counts (excluding weekends and future days)
                                            if (!$isWeekend && !$isFuture && $status !== null) {
                                                if (in_array($status, ['present','late','early_departure','late_early','approved'])) {
                                                    $presentCount++;
                                                } elseif ($status === 'absent' || $status === 'rejected') {
                                                    $absentCount++;
                                                }
                                            } elseif (!$isWeekend && !$isFuture && $status === null) {
                                                // Working day without record = absent
                                                $absentCount++;
                                            }
                                        @endphp
                                        <td class="px-1 py-2.5 text-center border-y border-slate-200 dark:border-zink-500
                                            {{ $isWeekend ? 'bg-purple-50 dark:bg-purple-900/10' : '' }}
                                            {{ $isToday ? 'bg-custom-50 dark:bg-custom-500/10' : '' }}">
                                            @if($isWeekend)
                                                {{-- Friday or Saturday weekend --}}
                                                <div class="flex flex-col items-center">
                                                    <i data-lucide="calendar-off" class="size-4 text-purple-400 dark:text-purple-500 mx-auto"></i>
                                                    <span class="text-[10px] text-purple-400 dark:text-purple-500 mt-0.5 font-medium">
                                                        {{ $dayObj->dayOfWeek == 5 ? __('messages.fri_short') : __('messages.sat_short') }}
                                                    </span>
                                                </div>
                                            @elseif($isFuture)
                                                <span class="text-slate-300 dark:text-zink-500 text-xs">—</span>
                                            @elseif($status === null)
                                                {{-- Working day without record = absent --}}
                                                <div class="flex flex-col items-center group cursor-pointer" title="{{ __('messages.absent_no_record') }}">
                                                    <i data-lucide="x-circle" class="size-4 text-red-400 mx-auto"></i>
                                                    <span class="text-[10px] text-red-400 mt-0.5 hidden group-hover:inline">{{ __('messages.absent') }}</span>
                                                </div>
                                            @elseif(in_array($status, ['present', 'approved']))
                                                <div class="flex flex-col items-center group" title="{{ __('messages.present') }}">
                                                    <i data-lucide="check-circle" class="size-4 text-green-500 mx-auto"></i>
                                                    <span class="text-[10px] text-green-500 mt-0.5 hidden group-hover:inline">{{ __('messages.present') }}</span>
                                                </div>
                                            @elseif($status === 'late')
                                                <div class="flex flex-col items-center group" title="{{ __('messages.late') }}">
                                                    <i data-lucide="clock" class="size-4 text-yellow-500 mx-auto"></i>
                                                    <span class="text-[10px] text-yellow-500 mt-0.5 hidden group-hover:inline">{{ __('messages.late') }}</span>
                                                </div>
                                            @elseif($status === 'early_departure')
                                                <div class="flex flex-col items-center group" title="{{ __('messages.early_departure') }}">
                                                    <i data-lucide="log-out" class="size-4 text-orange-500 mx-auto"></i>
                                                    <span class="text-[10px] text-orange-500 mt-0.5 hidden group-hover:inline">{{ __('messages.early') }}</span>
                                                </div>
                                            @elseif($status === 'late_early')
                                                <div class="flex flex-col items-center group" title="{{ __('messages.late_early') }}">
                                                    <i data-lucide="alert-triangle" class="size-4 text-red-400 mx-auto"></i>
                                                    <span class="text-[10px] text-red-400 mt-0.5 hidden group-hover:inline">{{ __('messages.both') }}</span>
                                                </div>
                                            @elseif($status === 'absent' || $status === 'rejected')
                                                <div class="flex flex-col items-center group" title="{{ __('messages.absent') }}">
                                                    <i data-lucide="x-circle" class="size-4 text-red-500 mx-auto"></i>
                                                    <span class="text-[10px] text-red-500 mt-0.5 hidden group-hover:inline">{{ __('messages.absent') }}</span>
                                                </div>
                                            @else
                                                <div class="flex flex-col items-center" title="{{ ucfirst($status) }}">
                                                    <i data-lucide="minus" class="size-4 text-slate-400 mx-auto"></i>
                                                </div>
                                            @endif
                                        </td>
                                    @endfor

                                    {{-- Totals --}}
                                    <td class="px-3.5 py-2.5 text-center border-y border-slate-200 dark:border-zink-500 bg-green-50 dark:bg-green-900/10">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400">
                                            {{ $presentCount }}
                                        </span>
                                     </td>
                                    <td class="px-3.5 py-2.5 text-center border-y border-slate-200 dark:border-zink-500 bg-red-50 dark:bg-red-900/10">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400">
                                            {{ $absentCount }}
                                        </span>
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $daysInMonth + 3 }}" class="px-3.5 py-12 text-center text-slate-500 dark:text-zink-200">
                                        <i data-lucide="inbox" class="size-12 mx-auto mb-3 text-slate-300 dark:text-zink-500"></i>
                                        <p>{{ __('messages.no_records') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex flex-col items-center mt-5 md:flex-row">
                    <div class="mb-4 grow md:mb-0">
                        <p class="text-slate-500 dark:text-zink-200">
                            {{ __('messages.showing') }} <b>{{ $users->firstItem() ?? 0 }}</b>
                            {{ __('messages.to') }} <b>{{ $users->lastItem() ?? 0 }}</b>
                            {{ __('messages.of') }} <b>{{ $users->total() }}</b>
                            {{ __('messages.results') }}
                        </p>
                    </div>
                    <div class="pagination-wrapper">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Auto-submit form when month/year changes
    document.getElementById('monthSelect')?.addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
    
    document.getElementById('yearSelect')?.addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });

    // Live search with debounce (500ms)
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    }

    // Re-initialize icons after any AJAX or DOM changes (if needed)
    function refreshIcons() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>

{{-- Additional Styles for better weekend visibility --}}
<style>
    /* Tooltip-like hover effects */
    td .flex-col {
        transition: all 0.2s ease;
    }
    
    td .flex-col:hover {
        transform: scale(1.1);
    }
    
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Dark mode scrollbar */
    .dark .overflow-x-auto::-webkit-scrollbar-track {
        background: #1e293b;
    }
    
    .dark .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #475569;
    }
    
    /* Sticky column shadow */
    .sticky.ltr\:left-0, .sticky.rtl\:right-0 {
        box-shadow: 2px 0 5px -2px rgba(0,0,0,0.1);
    }
    
    .dark .sticky.ltr\:left-0, .dark .sticky.rtl\:right-0 {
        box-shadow: 2px 0 5px -2px rgba(0,0,0,0.3);
    }
</style>
@endsection