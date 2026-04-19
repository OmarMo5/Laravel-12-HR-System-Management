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

                        {{-- Month --}}
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium mb-1 text-slate-600 dark:text-zink-300">{{ __('messages.month') }}</label>
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
                            <label class="block text-sm font-medium mb-1 text-slate-600 dark:text-zink-300">{{ __('messages.year') }}</label>
                            <select name="year" id="yearSelect"
                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500">
                                @foreach(range(date('Y')-2, date('Y')+1) as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Per Page --}}
                        <!-- <div class="lg:col-span-1">
                            <label class="block text-sm font-medium mb-1 text-slate-600 dark:text-zink-300">{{ __('messages.show') }}</label>
                            <select name="per_page" id="perPageSelect"
                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500">
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                <option value="250" {{ $perPage == 250 ? 'selected' : '' }}>250</option>
                                <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                                <option value="1000" {{ $perPage == 1000 ? 'selected' : '' }}>1000</option>
                            </select>
                        </div> -->

                        {{-- Search --}}
                        <!-- <div class="lg:col-span-3">
                            <label class="block text-sm font-medium mb-1 text-slate-600 dark:text-zink-300">{{ __('messages.search') }}</label>
                            <div class="relative">
                                <input type="text" name="search" id="searchInput" value="{{ $search }}"
                                    class="ltr:pl-8 rtl:pr-8 form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 w-full"
                                    placeholder="{{ __('messages.search_for') }}" autocomplete="off">
                                <i data-lucide="search" class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500"></i>
                            </div>
                        </div> -->

                        {{-- Filter Button --}}
                        <!-- <div class="lg:col-span-2 flex items-end">
                            <button type="submit"
                                class="w-full text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600 focus:ring focus:ring-custom-100">
                                <i data-lucide="filter" class="inline-block size-4 ltr:mr-1 rtl:ml-1"></i>
                                {{ __('messages.filter') }}
                            </button>
                        </div> -->

                        {{-- Export Button - واضح في الـ Light Mode --}}
                        <div class="lg:col-span-2 flex items-end">
                            <button type="button" onclick="exportAttendance()"
                                class="w-full btn bg-emerald-500 hover:bg-emerald-600 text-white border-emerald-500 focus:ring focus:ring-emerald-200 transition-all duration-200">
                                <i data-lucide="file-spreadsheet" class="inline-block size-4 ltr:mr-1 rtl:ml-1"></i>
                                {{ __('messages.export_excel') }}
                            </button>
                        </div>

                    </div>
                </form>

                {{-- Legend --}}
                <div class="flex items-center gap-4 flex-wrap text-xs mb-4 pb-2 border-b border-slate-200 dark:border-zink-500">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="text-slate-600 dark:text-zink-300">{{ __('messages.present') }}</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-yellow-500"></span>
                        <span class="text-slate-600 dark:text-zink-300">{{ __('messages.late') }}</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-orange-500"></span>
                        <span class="text-slate-600 dark:text-zink-300">{{ __('messages.early_departure') }}</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-slate-600 dark:text-zink-300">{{ __('messages.absent') }}</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-purple-500"></span>
                        <span class="text-slate-600 dark:text-zink-300">{{ __('messages.weekend_friday_saturday') }}</span>
                    </span>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-sm" id="mainAttendanceTable">
                        <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                            <tr>
                                <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 sticky ltr:left-0 rtl:right-0 bg-slate-100 dark:bg-zink-600 z-10 min-w-[220px]">
                                    {{ __('messages.employee_name') }}
                                </th>
                                @for($d = 1; $d <= $daysInMonth; $d++)
                                    @php
                                        $dayObj   = Carbon\Carbon::create($year, $month, $d, 0, 0, 0, 'Africa/Cairo');
                                        $isWeekend = ($dayObj->dayOfWeek == 5 || $dayObj->dayOfWeek == 6);
                                        $isToday   = $dayObj->isToday();
                                    @endphp
                                    <th class="px-2 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 text-center w-10
                                        {{ $isWeekend ? 'bg-purple-200 dark:bg-purple-800/40 text-purple-700 dark:text-purple-300' : '' }}
                                        {{ $isToday ? 'bg-custom-200 text-custom-700 dark:bg-custom-500/30 dark:text-custom-300' : '' }}">
                                        <div class="text-sm font-bold">{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-[10px] font-normal opacity-80 mt-0.5">
                                            @if($dayObj->dayOfWeek == 5)
                                                {{ app()->getLocale() == 'ar' ? 'جمعة' : 'FRI' }}
                                            @elseif($dayObj->dayOfWeek == 6)
                                                {{ app()->getLocale() == 'ar' ? 'سبت' : 'SAT' }}
                                            @else
                                                {{ $dayObj->format('D') }}
                                            @endif
                                        </div>
                                    </th>
                                @endfor
                                <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 text-center min-w-[100px] bg-green-100 dark:bg-green-900/30">
                                    {{ __('messages.present_days') }}
                                </th>
                                <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 text-center min-w-[100px] bg-red-100 dark:bg-red-900/30">
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
                                <tr class="border-y border-slate-200 dark:border-zink-500 hover:bg-slate-50 dark:hover:bg-zink-700 transition-colors">
                                    <td class="px-3.5 py-2.5 sticky ltr:left-0 rtl:right-0 bg-white dark:bg-zink-800 z-10 border-y border-slate-200 dark:border-zink-500">
                                        <div class="flex items-center gap-3">
                                            <div class="size-9 rounded-full bg-slate-100 dark:bg-zink-600 shrink-0 overflow-hidden ring-2 ring-slate-200 dark:ring-zink-500">
                                                <img src="{{ $user->avatar ? asset('assets/images/user/'.$user->avatar) : asset('assets/images/profile.png') }}"
                                                    alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full">
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-700 dark:text-zink-100">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-400 dark:text-zink-400">{{ $user->user_id }}</p>
                                                <p class="text-xs text-slate-400 dark:text-zink-400 mt-0.5">{{ $user->position ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    @for($d = 1; $d <= $daysInMonth; $d++)
                                        @php
                                            $dayObj    = Carbon\Carbon::create($year, $month, $d, 0, 0, 0, 'Africa/Cairo');
                                            $isWeekend = ($dayObj->dayOfWeek == 5 || $dayObj->dayOfWeek == 6);
                                            $isToday   = $dayObj->isToday();
                                            $isFuture  = $dayObj->isFuture();
                                            $status    = $userAttendance[$d] ?? null;

                                            if (!$isWeekend && !$isFuture && $status !== null) {
                                                if (in_array($status, ['present','late','early_departure','late_early','approved'])) {
                                                    $presentCount++;
                                                } elseif ($status === 'absent' || $status === 'rejected') {
                                                    $absentCount++;
                                                }
                                            } elseif (!$isWeekend && !$isFuture && $status === null) {
                                                $absentCount++;
                                            }
                                            
                                            $statusColor = '';
                                            $statusIcon = '';
                                            $statusTitle = '';
                                            
                                            if ($isWeekend) {
                                                $statusColor = 'text-purple-500 dark:text-purple-400';
                                                $statusIcon = 'calendar-off';
                                                $statusTitle = __('messages.weekend');
                                            } elseif ($isFuture) {
                                                $statusColor = 'text-slate-300 dark:text-zink-500';
                                                $statusIcon = 'minus';
                                                $statusTitle = '';
                                            } elseif ($status === null) {
                                                $statusColor = 'text-red-500 dark:text-red-400';
                                                $statusIcon = 'x-circle';
                                                $statusTitle = __('messages.absent');
                                            } elseif (in_array($status, ['present', 'approved'])) {
                                                $statusColor = 'text-green-500 dark:text-green-400';
                                                $statusIcon = 'check-circle';
                                                $statusTitle = __('messages.present');
                                            } elseif ($status === 'late') {
                                                $statusColor = 'text-yellow-500 dark:text-yellow-400';
                                                $statusIcon = 'clock';
                                                $statusTitle = __('messages.late');
                                            } elseif ($status === 'early_departure') {
                                                $statusColor = 'text-orange-500 dark:text-orange-400';
                                                $statusIcon = 'log-out';
                                                $statusTitle = __('messages.early_departure');
                                            } elseif ($status === 'late_early') {
                                                $statusColor = 'text-red-500 dark:text-red-400';
                                                $statusIcon = 'alert-triangle';
                                                $statusTitle = __('messages.late_early');
                                            } elseif ($status === 'absent' || $status === 'rejected') {
                                                $statusColor = 'text-red-500 dark:text-red-400';
                                                $statusIcon = 'x-circle';
                                                $statusTitle = __('messages.absent');
                                            } else {
                                                $statusColor = 'text-slate-400 dark:text-zink-500';
                                                $statusIcon = 'minus';
                                                $statusTitle = ucfirst($status);
                                            }
                                        @endphp
                                        <td class="px-1 py-2.5 text-center border-y border-slate-200 dark:border-zink-500
                                            {{ $isWeekend ? 'bg-purple-50 dark:bg-purple-900/15' : '' }}
                                            {{ $isToday ? 'bg-custom-50 dark:bg-custom-500/10' : '' }}">
                                            <div class="flex flex-col items-center group cursor-pointer" title="{{ $statusTitle }}">
                                                <i data-lucide="{{ $statusIcon }}" class="size-4 {{ $statusColor }} mx-auto"></i>
                                                @if($isWeekend)
                                                    <span class="text-[10px] {{ $statusColor }} mt-0.5 font-medium">
                                                        {{ $dayObj->dayOfWeek == 5 ? __('messages.fri_short') : __('messages.sat_short') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    @endfor

                                    <td class="px-3.5 py-2.5 text-center border-y border-slate-200 dark:border-zink-500 bg-green-50 dark:bg-green-900/15">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-500 text-white dark:bg-green-600">
                                            {{ $presentCount }}
                                        </span>
                                     </td>
                                    <td class="px-3.5 py-2.5 text-center border-y border-slate-200 dark:border-zink-500 bg-red-50 dark:bg-red-900/15">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-500 text-white dark:bg-red-600">
                                            {{ $absentCount }}
                                        </span>
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $daysInMonth + 3 }}" class="px-3.5 py-12 text-center text-slate-500 dark:text-zink-300">
                                        <i data-lucide="inbox" class="size-12 mx-auto mb-3 text-slate-300 dark:text-zink-500"></i>
                                        <p>{{ __('messages.no_records') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex flex-col items-center justify-between mt-5 md:flex-row gap-3">
                    <div class="text-sm text-slate-500 dark:text-zink-300">
                        {{ __('messages.showing') }} <b>{{ $users->firstItem() ?? 0 }}</b>
                        {{ __('messages.to') }} <b>{{ $users->lastItem() ?? 0 }}</b>
                        {{ __('messages.of') }} <b>{{ $users->total() }}</b>
                        {{ __('messages.results') }}
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
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Auto-submit form when month/year/per_page changes
    document.getElementById('monthSelect')?.addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
    
    document.getElementById('yearSelect')?.addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
    
    document.getElementById('perPageSelect')?.addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });

    // Live search with debounce
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

    // Export to Excel
    function exportAttendance() {
        let currentUrl = new URL(window.location.href);
        let params = currentUrl.searchParams;
        
        let exportUrl = '{{ route("hr/attendance/export") }}?' + params.toString();
        window.open(exportUrl, '_blank');
    }

    function refreshIcons() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>

<style>
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
    
    .dark .overflow-x-auto::-webkit-scrollbar-track {
        background: #1e293b;
    }
    
    .dark .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #475569;
    }
    
    .sticky.ltr\:left-0, .sticky.rtl\:right-0 {
        box-shadow: 2px 0 5px -2px rgba(0,0,0,0.1);
        z-index: 20;
    }
    
    .dark .sticky.ltr\:left-0, .dark .sticky.rtl\:right-0 {
        box-shadow: 2px 0 5px -2px rgba(0,0,0,0.3);
    }
    
    /* تحسين ظهور زر الـ Export */
    .btn.bg-emerald-500 {
        background-color: #10b981 !important;
        color: white !important;
    }
    .btn.bg-emerald-500:hover {
        background-color: #059669 !important;
    }
</style>
@endsection