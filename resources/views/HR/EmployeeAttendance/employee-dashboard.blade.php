@extends('layouts.master')

@section('content')
<style>
    .pf-container { background-color: #f8f9fa; min-height: 100vh; }
    .dark .pf-container { background-color: #1a1d21; }
    
    .pf-stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .pf-stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.25rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .dark .pf-stat-card { background: #212529; border-color: #32383e; }
    
    .pf-stat-label { color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; margin-bottom: 0.5rem; }
    .pf-stat-value { color: #111827; font-size: 1.25rem; font-weight: 700; }
    .dark .pf-stat-value { color: #f8f9fa; }
    
    .pf-calendar-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .dark .pf-calendar-card { background: #212529; border-color: #32383e; }
    
    .pf-grid-header { display: grid; grid-template-columns: repeat(7, 1fr); background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; }
    .dark .pf-grid-header { background-color: #2b3035; border-color: #32383e; }
    
    .pf-header-cell { padding: 0.75rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: capitalize; }
    
    .pf-grid-body { display: grid; grid-template-columns: repeat(7, 1fr); }
    .pf-day-cell { aspect-ratio: 1 / 1; padding: 1rem; border-right: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6; position: relative; cursor: pointer; transition: background-color 0.2s; }
    .dark .pf-day-cell { border-color: #32383e; }
    .pf-day-cell:hover { background-color: #f9fafb; }
    .dark .pf-day-cell:hover { background-color: #2b3035; }
    
    .pf-date-num { font-size: 0.875rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem; display: inline-block; }
    .dark .pf-date-num { color: #f8f9fa; }
    .pf-today-badge { background-color: #3b82f6; color: #fff; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; }
    
    .pf-diff-pill { width: 100%; padding: 0.25rem; border-radius: 9999px; text-align: center; font-size: 0.6875rem; font-weight: 700; margin-bottom: 0.75rem; }
    .pf-diff-neg { background-color: #fee2e2; color: #b91c1c; }
    .pf-diff-pos { background-color: #dcfce7; color: #15803d; }
    
    .pf-cell-footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; }
    .pf-footer-label { font-size: 0.625rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; line-height: 1; }
    .pf-footer-val { font-size: 0.75rem; font-weight: 700; color: #374151; }
    .dark .pf-footer-val { color: #dee2e6; }

    /* PeopleForce Drawer Styles */
    .pf-drawer-overlay { position: fixed; inset: 0; background: rgba(31, 41, 55, 0.4); backdrop-blur: 2px; z-index: 2000; }
    .pf-drawer { position: fixed; top: 0; bottom: 0; right: 0; width: 100%; max-width: 450px; background: #fff; z-index: 2001; box-shadow: -10px 0 15px -3px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; }
    .dark .pf-drawer { background: #212529; }
    
    .pf-drawer-header { padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; }
    .dark .pf-drawer-header { border-color: #32383e; }
    .pf-drawer-title { font-size: 1.125rem; font-weight: 700; color: #111827; }
    .dark .pf-drawer-title { color: #f8f9fa; }
    
    .pf-drawer-stats { display: grid; grid-template-columns: repeat(4, 1fr); padding: 1.5rem; border-bottom: 1px solid #f3f4f6; text-align: center; }
    .dark .pf-drawer-stats { border-color: #32383e; }
    .pf-drawer-stat-label { font-size: 0.6875rem; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; }
    .pf-drawer-stat-val { font-size: 0.875rem; font-weight: 700; color: #111827; }
    .dark .pf-drawer-stat-val { color: #f8f9fa; }
    
    .pf-entry-section { padding: 1.5rem; }
    .pf-entry-title { font-size: 0.75rem; font-weight: 700; color: #111827; margin-bottom: 1rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.5rem; }
    .dark .pf-entry-title { color: #f8f9fa; border-color: #32383e; }
    
    .pf-entry-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
    .pf-entry-label { display: flex; items-center: center; gap: 0.75rem; font-size: 0.8125rem; font-weight: 500; color: #374151; }
    .dark .pf-entry-label { color: #ced4da; }
    .pf-dot { width: 8px; height: 8px; border-radius: 9999px; background-color: #10b981; }
    .pf-entry-time { font-size: 0.8125rem; font-weight: 600; color: #111827; }
    .dark .pf-entry-time { color: #f8f9fa; }
    .pf-entry-duration { font-size: 0.8125rem; font-weight: 600; color: #3b82f6; }

    /* Dropdown */
    .pf-dropdown { position: relative; display: inline-block; }
    .pf-dropdown-content { display: none; position: absolute; background-color: #fff; min-width: 180px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); z-index: 1002; border-radius: 0.75rem; border: 1px solid #e5e7eb; top: 110%; overflow: hidden; }
    .dark .pf-dropdown-content { background-color: #2b3035; border-color: #32383e; }
    .pf-dropdown-content.show { display: block; }

    @media (max-width: 1024px) { .pf-stats-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .pf-stats-grid { grid-template-columns: repeat(2, 1fr); } .pf-drawer { max-width: 100%; } }
</style>

<div x-data="{ 
    viewMode: 'grid', 
    showDrawer: false, 
    selectedDay: null,
    showMonthDropdown: false,
    listPage: 1,
    perPage: 10,
    calendarDataCount: {{ count($listData) }},
    
    get totalPages() { return Math.ceil(this.calendarDataCount / this.perPage); }
}" 
class="pf-container group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-8 px-4">
    
    <div class="max-w-[1600px] mx-auto">
        <!-- Header -->
        <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between pt-4">
            <div class="flex items-center gap-6">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-zink-50">{{ __('messages.my_attendance') }}</h2>
                <div class="flex items-center gap-3">
                    <div class="pf-dropdown">
                        <button @click="showMonthDropdown = !showMonthDropdown" @click.away="showMonthDropdown = false" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-slate-700 dark:text-zink-100 bg-white dark:bg-zink-700 border border-slate-200 dark:border-zink-600 rounded-xl shadow-sm hover:bg-slate-50 transition-colors">
                            {{ __('messages.select_month') }}
                            <i data-lucide="calendar" class="size-4 text-slate-400"></i>
                        </button>
                        <div class="pf-dropdown-content shadow-2xl" :class="{ 'show': showMonthDropdown }">
                            @php $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december']; @endphp
                            @foreach($months as $index => $mKey)
                                <a href="{{ route('employee/attendance/dashboard', ['month' => $index + 1, 'year' => $selectedDate->year]) }}" class="block px-4 py-3 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-zink-600 {{ ($index + 1) == $selectedDate->month ? 'text-blue-600' : 'text-slate-700 dark:text-zink-200' }}">
                                    {{ __('messages.' . $mKey) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex items-center bg-white dark:bg-zink-700 border border-slate-200 dark:border-zink-600 rounded-xl p-1 shadow-sm">
                        <a href="{{ route('employee/attendance/dashboard', ['month' => $selectedDate->copy()->subMonth()->month, 'year' => $selectedDate->copy()->subMonth()->year]) }}" class="p-2 hover:bg-slate-100 dark:hover:bg-zink-600 rounded-lg text-slate-400 transition-all"><i data-lucide="chevron-left" class="size-4"></i></a>
                        <a href="{{ route('employee/attendance/dashboard', ['month' => $selectedDate->copy()->addMonth()->month, 'year' => $selectedDate->copy()->addMonth()->year]) }}" class="p-2 hover:bg-slate-100 dark:hover:bg-zink-600 rounded-lg text-slate-400 ml-1 transition-all"><i data-lucide="chevron-right" class="size-4"></i></a>
                    </div>
                    <span class="text-base font-bold text-slate-800 dark:text-zink-50">{{ __('messages.' . strtolower($selectedDate->format('F'))) }}, {{ $selectedDate->year }}</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center bg-white dark:bg-zink-700 border border-slate-200 dark:border-zink-600 rounded-xl p-1 shadow-sm">
                    <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-slate-100 dark:bg-zink-600 text-blue-600' : 'text-slate-400'" class="p-2 rounded-lg transition-all"><i data-lucide="grid-3x3" class="size-4"></i></button>
                    <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-slate-100 dark:bg-zink-600 text-blue-600' : 'text-slate-400'" class="p-2 rounded-lg transition-all ml-1"><i data-lucide="list" class="size-4"></i></button>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="pf-stats-grid">
            <div class="pf-stat-card"><p class="pf-stat-label">{{ __('messages.expected_hours') }}</p><h3 class="pf-stat-value">
                @php
                    $eh = floor($monthlyStats['expected_hours']);
                    $em = round(($monthlyStats['expected_hours'] - $eh) * 60);
                @endphp
                {{ sprintf('%d:%02d', $eh, $em) }}
            </h3></div>
            <div class="pf-stat-card"><p class="pf-stat-label">{{ __('messages.worked_hours') }}</p><h3 class="pf-stat-value">
                @php
                    $wh = abs($monthlyStats['worked_hours']);
                    $wh_h = floor($wh);
                    $wh_m = round(($wh - $wh_h) * 60);
                @endphp
                {{ sprintf('%d:%02d', $wh_h, $wh_m) }}
            </h3></div>
            <div class="pf-stat-card"><p class="pf-stat-label">{{ __('messages.break_hours') }}</p><h3 class="pf-stat-value">0:24</h3></div>
            <div class="pf-stat-card"><p class="pf-stat-label">{{ __('messages.leave_hours') }}</p><h3 class="pf-stat-value">{{ $monthlyStats['leave_hours'] }}:00</h3></div>
            <div class="pf-stat-card"><p class="pf-stat-label">{{ __('messages.remaining_hours') }}</p><h3 class="pf-stat-value {{ $monthlyStats['remaining_hours'] > 0 ? 'text-[#e66c4c]' : 'text-green-500' }}">
                @php
                    $rh = abs($monthlyStats['remaining_hours']);
                    $rh_h = floor($rh);
                    $rh_m = round(($rh - $rh_h) * 60);
                @endphp
                {{ ($monthlyStats['remaining_hours'] < 0 ? '-' : '') . sprintf('%d:%02d', $rh_h, $rh_m) }}
            </h3></div>
        </div>

        <!-- Calendar Section -->
        <div x-show="viewMode === 'grid'" class="pf-calendar-card">
            <div class="pf-grid-header">@foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day)<div class="pf-header-cell">{{ __('messages.' . $day) }}</div>@endforeach</div>
            <div class="pf-grid-body">
                @php $firstDay = ($selectedDate->copy()->startOfMonth()->dayOfWeek + 6) % 7; @endphp
                @for($i = 0; $i < $firstDay; $i++)<div class="pf-day-cell bg-slate-50/20 dark:bg-zink-800/10"></div>@endfor
                @foreach($calendarData as $day)
                    <div @click="selectedDay = {{ json_encode($day) }}; showDrawer = true;" class="pf-day-cell">
                        <div class="mb-3">@if($day['is_today'])<span class="pf-today-badge">{{ $day['day_num'] }} {{ __('messages.' . strtolower($selectedDate->format('F'))) }}</span>@else<span class="pf-date-num">{{ $day['day_num'] }} {{ __('messages.' . strtolower($selectedDate->format('F'))) }}</span>@endif</div>
                        <div class="mb-5">
                            @if($day['status'] === 'weekend') <div class="h-[22px]"></div>
                            @elseif($day['status'] === 'holiday' || $day['status'] === 'leave') <div class="pf-diff-pill bg-blue-50 text-blue-600 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-900/30 truncate px-2">{{ $day['status'] === 'holiday' ? $day['holiday']->holiday_name : $day['leave']->leave_type }}</div>
                            @elseif($day['status'] !== 'future')
                                @php 
                                    $dailyExpected = ($day['status'] === 'weekend' || $day['status'] === 'holiday') ? 0 : 8.5;
                                    $diff = ($day['attendance'] ? $day['attendance']->working_hours : 0) - $dailyExpected; 
                                @endphp
                                <div class="pf-diff-pill {{ $diff >= 0 ? 'pf-diff-pos' : 'pf-diff-neg' }}">
                                    {{ $diff >= 0 ? '+' : '' }}{{ sprintf('%d:%02d', floor(abs($diff)), abs($diff * 60) % 60) }}
                                </div>
                            @else <div class="h-[22px]"></div> @endif
                        </div>
                        @if($day['status'] !== 'future' && $day['status'] !== 'weekend' && $day['status'] !== 'holiday')
                            <div class="pf-cell-footer"><div class="flex flex-col"><span class="pf-footer-label">{{ __('messages.expected') }}</span><span class="pf-footer-val">8:30</span></div><div class="flex flex-col items-end"><span class="pf-footer-label">{{ __('messages.worked') }}</span><span class="pf-footer-val">
                                @if($day['attendance'])
                                    @php
                                        $wh_cell = abs($day['attendance']->working_hours);
                                        $wh_cell_h = floor($wh_cell);
                                        $wh_cell_m = round(($wh_cell - $wh_cell_h) * 60);
                                    @endphp
                                    {{ sprintf('%d:%02d', $wh_cell_h, $wh_cell_m) }}
                                @else
                                    0:00
                                @endif
                            </span></div></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- List View -->
        <div x-show="viewMode === 'list'" class="pf-calendar-card">
            <div class="overflow-x-auto"><table class="w-full text-left"><thead class="bg-slate-50 dark:bg-zink-600 border-b border-slate-200 dark:border-zink-600"><tr><th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-slate-500">{{ __('messages.day_date') }}</th><th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-slate-500">{{ __('messages.start_end') }}</th><th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-slate-500">{{ __('messages.submitted_h') }}</th><th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-slate-500">{{ __('messages.expected') }}</th><th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-slate-500">{{ __('messages.difference') }}</th><th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-slate-500">{{ __('messages.status') }}</th><th class="px-6 py-5 text-right">{{ __('messages.action') }}</th></tr></thead><tbody class="divide-y divide-slate-100 dark:divide-zink-700">
                @foreach($listData as $index => $day)
                    <tr class="hover:bg-slate-50 dark:hover:bg-zink-600/30 transition-all" x-show="listPage === {{ ceil(($index + 1) / 10) }}">
                        <td class="px-6 py-5"><span class="text-sm font-bold text-slate-800 dark:text-zink-50">{{ $day['date']->format('d M, l') }}</span>@if($day['is_today'])<span class="ml-2 px-2 py-0.5 rounded-full bg-blue-500 text-white text-[9px] font-black uppercase">{{ __('messages.today') }}</span>@endif</td>
                        <td class="px-6 py-5 text-sm font-mono text-slate-500">{{ $day['attendance'] ? $day['attendance']->formatted_check_in . ' / ' . $day['attendance']->formatted_check_out : '--:-- / --:--' }}</td>
                        <td class="px-6 py-5 text-base font-black text-slate-800 dark:text-zink-50">
                            @if($day['attendance'] && $day['attendance']->working_hours)
                                @php
                                    $workingHours = abs($day['attendance']->working_hours);
                                    $totalMinutes = round($workingHours * 60);
                                    $h = floor($totalMinutes / 60);
                                    $m = $totalMinutes % 60;
                                @endphp
                                {{ sprintf('%02d:%02d', $h, $m) }}
                            @else
                                00:00
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-400">{{ ($day['status'] === 'weekend' || $day['status'] === 'holiday') ? '0:00' : '8:30' }}</td>
                        <td class="px-6 py-5">
                            @php 
                                $dailyExpected = ($day['status'] === 'weekend' || $day['status'] === 'holiday') ? 0 : 8.5;
                                $diff = ($day['attendance'] ? $day['attendance']->working_hours : 0) - $dailyExpected;
                                $totalDiffMinutes = round(abs($diff) * 60);
                                $dh = floor($totalDiffMinutes / 60);
                                $dm = $totalDiffMinutes % 60;
                                
                                $diffText = '';
                                if ($dh > 0) {
                                    $diffText .= $dh . ' ' . __('messages.hrs');
                                }
                                if ($dm > 0) {
                                    if ($dh > 0) $diffText .= ' ' . __('messages.and') . ' ';
                                    $diffText .= $dm . ' ' . __('messages.minutes');
                                }
                                if ($dh == 0 && $dm == 0) $diffText = '0';
                                
                                $label = $diff >= 0 ? 'زيادة' : 'ناقص';
                            @endphp
                            <span class="font-black {{ $diff >= 0 ? 'text-[#3ab67d]' : 'text-[#e66c4c]' }}">
                                {{ $label }} {{ $diffText }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            @if($day['attendance'])
                                @if($day['attendance']->status == 'late' || $day['attendance']->status == 'late_early')
                                    <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-[10px] font-bold">{{ __('messages.late') }}</span>
                                @endif
                                @if($day['attendance']->status == 'early_departure' || $day['attendance']->status == 'late_early')
                                    <span class="px-2 py-1 rounded-full bg-orange-100 text-orange-700 text-[10px] font-bold ml-1">{{ __('messages.early_departure') }}</span>
                                @endif
                                @if($day['attendance']->status == 'present' || $day['attendance']->status == 'approved')
                                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-bold">{{ __('messages.present') }}</span>
                                @endif
                            @else
                                <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">{{ __('messages.' . $day['status']) ?? $day['status'] }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right"><button @click="selectedDay = {{ json_encode($day) }}; showDrawer = true;" class="p-3 bg-slate-50 dark:bg-zink-600 text-slate-400 hover:text-blue-500 rounded-xl"><i data-lucide="eye" class="size-4"></i></button></td>
                    </tr>
                @endforeach
            </tbody></table></div>
            <div class="px-8 py-6 flex items-center justify-between border-t border-slate-100"><span class="text-xs font-black text-slate-400 uppercase tracking-[2px]">{{ __('messages.page') }} <span class="text-slate-800 dark:text-zink-50" x-text="listPage"></span> / <span x-text="totalPages"></span></span><div class="flex items-center gap-3"><button @click="if(listPage > 1) listPage--" class="p-3 bg-white dark:bg-zink-700 border border-slate-200 rounded-xl" :disabled="listPage === 1"><i data-lucide="chevron-left" class="size-4"></i></button><button @click="if(listPage < totalPages) listPage++" class="p-3 bg-white dark:bg-zink-700 border border-slate-200 rounded-xl" :disabled="listPage === totalPages"><i data-lucide="chevron-right" class="size-4"></i></button></div></div>
        </div>
    </div>

    <!-- PeopleForce Drawer (Pixel Perfect Replicated) -->
    <template x-if="showDrawer">
        <div class="pf-drawer-overlay" @click="showDrawer = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="pf-drawer" @click.stop x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="ltr:translate-x-full rtl:-translate-x-full" x-transition:enter-end="translate-x-0">
                <!-- Header -->
                <div class="pf-drawer-header">
                    <h2 class="pf-drawer-title" x-text="selectedDay ? moment(selectedDay.date_string).format('DD MMM YYYY, dddd') : ''"></h2>
                    <button @click="showDrawer = false" class="p-2 hover:bg-slate-100 dark:hover:bg-zink-600 rounded-lg text-slate-400 transition-colors">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>
                
                <!-- Quick Stats Row -->
                <div class="pf-drawer-stats">
                    <div><p class="pf-drawer-stat-label">Worked</p><p class="pf-drawer-stat-val" x-text="selectedDay.attendance ? (Math.floor(Math.abs(selectedDay.attendance.working_hours)) + ':' + String(Math.round((Math.abs(selectedDay.attendance.working_hours) % 1) * 60)).padStart(2, '0')) : '0:00'"></p></div>
                    <div><p class="pf-drawer-stat-label">Break</p><p class="pf-drawer-stat-val">--:--</p></div>
                    <div><p class="pf-drawer-stat-label">Leave</p><p class="pf-drawer-stat-val" x-text="selectedDay.status === 'leave' ? '8:30' : '--:--'"></p></div>
                    <div><p class="pf-drawer-stat-label">Expected</p><p class="pf-drawer-stat-val" x-text="selectedDay.status === 'weekend' || selectedDay.status === 'holiday' ? '0:00' : '8:30'"></p></div>
                </div>

                <!-- Entry Section -->
                <div class="pf-entry-section">
                    <h3 class="pf-entry-title">Entry 1</h3>
                    <div class="flex flex-col gap-5">
                        <div class="pf-entry-row">
                            <div class="pf-entry-label"><i data-lucide="play-circle" class="size-4 text-[#10b981]"></i> Clock in</div>
                            <div class="pf-entry-time" x-text="selectedDay.attendance ? moment(selectedDay.attendance.check_in, 'HH:mm:ss').format('HH:mm') : '--:--'"></div>
                        </div>
                        <div class="pf-entry-row">
                            <div class="pf-entry-label"><i data-lucide="pause-circle" class="size-4 text-amber-500"></i> Break</div>
                            <div class="pf-entry-time">--:--</div>
                        </div>
                        <div class="pf-entry-row">
                            <div class="pf-entry-label"><i data-lucide="stop-circle" class="size-4 text-[#ef4444]"></i> Clock out</div>
                            <div class="flex items-center gap-10">
                                <div class="pf-entry-time" x-text="selectedDay.attendance ? moment(selectedDay.attendance.check_out, 'HH:mm:ss').format('HH:mm') : '--:--'"></div>
                                <div class="pf-entry-duration" x-text="selectedDay.attendance ? (Math.floor(Math.abs(selectedDay.attendance.working_hours)) + ':' + String(Math.round((Math.abs(selectedDay.attendance.working_hours) % 1) * 60)).padStart(2, '0')) : ''"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Entry Button -->
                <div class="mt-auto p-6 border-t border-slate-100 dark:border-zink-600">
                    <button class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-slate-600 dark:text-zink-200 bg-slate-50 dark:bg-zink-600 rounded-lg hover:bg-slate-100 transition-all border border-slate-200 dark:border-zink-500">
                        <i data-lucide="plus" class="size-4"></i>
                        New entry
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => { if(window.lucide) window.lucide.createIcons(); });
</script>
@endsection