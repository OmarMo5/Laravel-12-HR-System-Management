@extends('layouts.master')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Page Header -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.my_attendance_history') }}</h5>
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

            <!-- Filters Card -->
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('employee/attendance/history') }}"
                        class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <!-- Month Filter -->
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium">{{ __('messages.month') }}</label>
                            <select name="month" class="form-input w-full border-slate-200 focus:border-custom-500">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Year Filter -->
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium">{{ __('messages.year') }}</label>
                            <select name="year" class="form-input w-full border-slate-200 focus:border-custom-500">
                                @for ($y = date('Y'); $y >= date('Y') - 2; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600 w-full">
                                <i data-lucide="filter" class="inline-block size-4 mr-1"></i>
                                {{ __('messages.apply_filter') }}
                            </button>
                        </div>

                        <!-- Reset Button -->
                        <div class="flex items-end">
                            <a href="{{ route('employee/attendance/history') }}"
                                class="btn bg-slate-200 text-slate-800 hover:bg-slate-300 w-full">
                                <i data-lucide="x" class="inline-block size-4 mr-1"></i>
                                {{ __('messages.reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Monthly Overview Card - مصغر وأنيق -->
            <div class="card mb-5">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h6 class="text-15">{{ __('messages.monthly_overview') }} -
                            {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            {{ $year }}</h6>
                        <div class="flex gap-1">
                            <span class="w-2 h-2 rounded-full bg-green-500" title="{{ __('messages.present') }}"></span>
                            <span class="w-2 h-2 rounded-full bg-yellow-500" title="{{ __('messages.late') }}"></span>
                            <span class="w-2 h-2 rounded-full bg-orange-500" title="{{ __('messages.early') }}"></span>
                            <span class="w-2 h-2 rounded-full bg-red-500" title="{{ __('messages.absent') }}"></span>
                        </div>
                    </div>

                    <!-- Quick Jump to Day -->
                    <div class="mb-3 flex items-center gap-2">
                        <label class="text-xs font-medium">{{ __('messages.jump_to') }}</label>
                        <select id="dayJump"
                            class="form-input text-xs py-1 w-24 border-slate-200 focus:border-custom-500">
                            <option value="">{{ __('messages.select_day') }}</option>
                            @for ($d = 1; $d <= $daysInMonth; $d++)
                                <option value="day-{{ $d }}">{{ __('messages.day') }} {{ $d }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Days Grid - تصميم مصغر 7 أعمدة -->
                    <div class="grid grid-cols-7 gap-1" id="daysGrid">
                        <!-- Weekday headers - مصغرة -->
                        <div class="text-center text-[10px] font-semibold py-1 bg-slate-100 dark:bg-zink-600 rounded">
                            {{ __('messages.mon') }}</div>
                        <div class="text-center text-[10px] font-semibold py-1 bg-slate-100 dark:bg-zink-600 rounded">
                            {{ __('messages.tue') }}</div>
                        <div class="text-center text-[10px] font-semibold py-1 bg-slate-100 dark:bg-zink-600 rounded">
                            {{ __('messages.wed') }}</div>
                        <div class="text-center text-[10px] font-semibold py-1 bg-slate-100 dark:bg-zink-600 rounded">
                            {{ __('messages.thu') }}</div>
                        <div class="text-center text-[10px] font-semibold py-1 bg-slate-100 dark:bg-zink-600 rounded">
                            {{ __('messages.fri') }}</div>
                        <div class="text-center text-[10px] font-semibold py-1 bg-red-100 text-red-600 rounded">
                            {{ __('messages.sat') }}</div>
                        <div class="text-center text-[10px] font-semibold py-1 bg-red-100 text-red-600 rounded">
                            {{ __('messages.sun') }}</div>

                        <!-- Empty cells for days before month start -->
                        @for ($i = 0; $i < $firstDayOfWeek; $i++)
                            <div class="text-center py-1 bg-slate-50 dark:bg-zink-700 rounded opacity-30"></div>
                        @endfor

                        <!-- Days of the month - مصغرة مع نقاط دلالية -->
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $currentDate = Carbon\Carbon::create($year, $month, $day);
                                $attendanceForDay = $attendances->first(function ($item) use ($currentDate) {
                                    return Carbon\Carbon::parse($item->date)->format('Y-m-d') ==
                                        $currentDate->format('Y-m-d');
                                });

                                $bgColor = 'bg-slate-100 dark:bg-zink-600';
                                $dotColor = '';
                                $statusText = '';

                                if ($attendanceForDay) {
                                    switch ($attendanceForDay->status) {
                                        case 'present':
                                            $bgColor = 'bg-green-50 dark:bg-green-500/10';
                                            $dotColor = 'bg-green-500';
                                            $statusText = __('messages.present');
                                            break;
                                        case 'late':
                                            $bgColor = 'bg-yellow-50 dark:bg-yellow-500/10';
                                            $dotColor = 'bg-yellow-500';
                                            $statusText = __('messages.late');
                                            break;
                                        case 'early_departure':
                                            $bgColor = 'bg-orange-50 dark:bg-orange-500/10';
                                            $dotColor = 'bg-orange-500';
                                            $statusText = __('messages.early');
                                            break;
                                        case 'late_early':
                                            $bgColor = 'bg-red-50 dark:bg-red-500/10';
                                            $dotColor = 'bg-red-500';
                                            $statusText = __('messages.late_early');
                                            break;
                                    }
                                } else {
                                    if ($currentDate->isWeekend()) {
                                        $bgColor = 'bg-red-50 dark:bg-red-500/5';
                                        $statusText = __('messages.weekend');
                                    } else {
                                        $statusText = __('messages.absent');
                                    }
                                }
                            @endphp
                            <div id="day-{{ $day }}"
                                class="day-cell relative text-center py-1 {{ $bgColor }} rounded cursor-pointer hover:scale-110 transition-transform group"
                                data-day="{{ $day }}" data-status="{{ $statusText }}"
                                data-checkin="{{ $attendanceForDay ? date('h:i A', strtotime($attendanceForDay->check_in)) : '—' }}"
                                data-checkout="{{ $attendanceForDay && $attendanceForDay->check_out ? date('h:i A', strtotime($attendanceForDay->check_out)) : '—' }}"
                                data-hours="{{ $attendanceForDay && $attendanceForDay->working_hours ? number_format($attendanceForDay->working_hours, 1) : '0' }}"
                                onclick="showDayDetails(this)">

                                <!-- رقم اليوم -->
                                <span class="text-xs font-medium">{{ $day }}</span>

                                <!-- نقطة دلالية للحضور -->
                                @if ($attendanceForDay)
                                    <span
                                        class="absolute top-0 right-0 block w-1.5 h-1.5 {{ $dotColor }} rounded-full"></span>
                                @endif

                                <!-- Tooltip يظهر عند التحويم -->
                                <div
                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 hidden group-hover:block bg-gray-800 text-white text-[10px] rounded py-1 px-2 whitespace-nowrap z-10">
                                    <strong>{{ __('messages.day') }} {{ $day }}</strong><br>
                                    {{ __('messages.status') }}: {{ $statusText }}<br>
                                    @if ($attendanceForDay)
                                        {{ __('messages.check_in') }}:
                                        {{ date('h:i A', strtotime($attendanceForDay->check_in)) }}<br>
                                        @if ($attendanceForDay->check_out)
                                            {{ __('messages.check_out') }}:
                                            {{ date('h:i A', strtotime($attendanceForDay->check_out)) }}<br>
                                            {{ __('messages.hours') }}:
                                            {{ number_format($attendanceForDay->working_hours, 1) }}h
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Day Details Modal -->
                    <div id="dayDetailsModal"
                        class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                        <div class="bg-white dark:bg-zink-700 rounded-2xl max-w-sm w-full p-5">
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="text-lg font-semibold">{{ __('messages.day_details') }}</h5>
                                <button onclick="closeDayModal()" class="text-slate-400 hover:text-slate-600">
                                    <i data-lucide="x" class="size-4"></i>
                                </button>
                            </div>
                            <div id="dayDetailsContent" class="space-y-2 text-sm">
                                <!-- Content will be filled by JavaScript -->
                            </div>
                            <div class="mt-4 text-right">
                                <button onclick="closeDayModal()"
                                    class="px-3 py-1 text-xs bg-slate-200 rounded-lg hover:bg-slate-300">{{ __('messages.close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 gap-5 mb-5 lg:grid-cols-4">
                @php
                    $totalDays = $attendances->total();
                    $presentDays = $attendances
                        ->filter(function ($item) {
                            return in_array($item->status, ['present', 'late', 'early_departure', 'late_early']);
                        })
                        ->count();
                    $lateDays = $attendances
                        ->filter(function ($item) {
                            return $item->status == 'late' || $item->status == 'late_early';
                        })
                        ->count();
                    $earlyDays = $attendances
                        ->filter(function ($item) {
                            return $item->status == 'early_departure';
                        })
                        ->count();
                    $totalHours = $attendances->sum('working_hours');
                    $totalOT = $attendances->sum('overtime_hours');
                @endphp

                <div class="col-span-1">
                    <div class="card bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-500/20 dark:to-blue-600/20">
                        <div class="card-body py-3">
                            <div class="flex items-center gap-2">
                                <div class="size-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                                    <i data-lucide="calendar" class="size-5 text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-zink-200">{{ __('messages.total') }}</p>
                                    <h4 class="text-lg font-semibold">{{ $attendances->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div
                        class="card bg-gradient-to-br from-green-50 to-green-100 dark:from-green-500/20 dark:to-green-600/20">
                        <div class="card-body py-3">
                            <div class="flex items-center gap-2">
                                <div class="size-10 rounded-full bg-green-500/20 flex items-center justify-center">
                                    <i data-lucide="check-circle" class="size-5 text-green-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-zink-200">{{ __('messages.present') }}</p>
                                    <h4 class="text-lg font-semibold">{{ $presentDays - $lateDays - $earlyDays }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div
                        class="card bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-500/20 dark:to-yellow-600/20">
                        <div class="card-body py-3">
                            <div class="flex items-center gap-2">
                                <div class="size-10 rounded-full bg-yellow-500/20 flex items-center justify-center">
                                    <i data-lucide="alert-circle" class="size-5 text-yellow-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-zink-200">{{ __('messages.late') }}</p>
                                    <h4 class="text-lg font-semibold">{{ $lateDays }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div
                        class="card bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-500/20 dark:to-purple-600/20">
                        <div class="card-body py-3">
                            <div class="flex items-center gap-2">
                                <div class="size-10 rounded-full bg-purple-500/20 flex items-center justify-center">
                                    <i data-lucide="clock" class="size-5 text-purple-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-zink-200">{{ __('messages.total_hours') }}
                                    </p>
                                    <h4 class="text-lg font-semibold">{{ number_format($totalHours, 1) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Table Card -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-15">{{ __('messages.records_for') }}
                            {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            {{ $year }}</h6>
                        <button onclick="exportToExcel()"
                            class="text-white btn bg-green-500 border-green-500 hover:bg-green-600 text-xs py-1 px-3">
                            <i data-lucide="download" class="inline-block size-3 mr-1"></i>
                            {{ __('messages.export') }}
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="attendanceTable">
                            <thead class="bg-slate-100 dark:bg-zink-600">
                                <tr>
                                    <th class="px-2 py-2 text-left text-xs">#</th>
                                    <th class="px-2 py-2 text-left text-xs">{{ __('messages.date') }}</th>
                                    <th class="px-2 py-2 text-left text-xs">{{ __('messages.check_in') }}</th>
                                    <th class="px-2 py-2 text-left text-xs">{{ __('messages.check_out') }}</th>
                                    <th class="px-2 py-2 text-left text-xs">{{ __('messages.status') }}</th>
                                    <th class="px-2 py-2 text-left text-xs">{{ __('messages.hours') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $index => $attendance)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-zink-600/50 transition-colors text-xs">
                                        <td class="px-2 py-2 border-b">{{ $attendances->firstItem() + $index }}</td>
                                        <td class="px-2 py-2 border-b">{{ date('d M', strtotime($attendance->date)) }}
                                        </td>
                                        <td class="px-2 py-2 border-b">
                                            {{ $attendance->check_in ? date('h:i A', strtotime($attendance->check_in)) : '—' }}
                                        </td>
                                        <td class="px-2 py-2 border-b">
                                            {{ $attendance->check_out ? date('h:i A', strtotime($attendance->check_out)) : '—' }}
                                        </td>
                                        <td class="px-2 py-2 border-b">
                                            @php
                                                $statusColors = [
                                                    'present' => 'text-green-600',
                                                    'late' => 'text-yellow-600',
                                                    'early_departure' => 'text-orange-600',
                                                    'late_early' => 'text-red-600',
                                                    'absent' => 'text-slate-400',
                                                ];
                                                $statusShort = [
                                                    'present' => __('messages.present_short'),
                                                    'late' => __('messages.late_short'),
                                                    'early_departure' => __('messages.early_short'),
                                                    'late_early' => __('messages.late_early_short'),
                                                    'absent' => __('messages.absent_short'),
                                                ];
                                            @endphp
                                            <span class="{{ $statusColors[$attendance->status] ?? 'text-slate-600' }}">
                                                {{ $statusShort[$attendance->status] ?? substr($attendance->status, 0, 3) }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-2 border-b">
                                            {{ $attendance->working_hours ? number_format($attendance->working_hours, 1) : '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-2 py-4 text-center border-b text-xs">
                                            {{ __('messages.no_records') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col items-center justify-between gap-2 mt-3 md:flex-row">
                        <div class="text-xs text-slate-500">
                            {{ __('messages.showing') }} {{ $attendances->firstItem() ?? 0 }} {{ __('messages.to') }}
                            {{ $attendances->lastItem() ?? 0 }} {{ __('messages.of') }}
                            {{ $attendances->total() }}
                        </div>
                        <div class="flex items-center gap-1">
                            {{ $attendances->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <style>
        /* تقليل حجم التقويم */
        #daysGrid {
            max-height: 160px;
            overflow-y: auto;
            padding-right: 2px;
        }

        /* تخصيص شريط التمرير */
        #daysGrid::-webkit-scrollbar {
            width: 3px;
        }

        #daysGrid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        #daysGrid::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        #daysGrid::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* تحسين ظهور خلايا الأيام */
        .day-cell {
            min-width: 24px;
            min-height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Tooltip مخصص */
        .day-cell .group-hover\:block {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            font-size: 9px;
            line-height: 1.3;
            min-width: 100px;
            pointer-events: none;
            z-index: 99999;
        }

        /* تقليل حجم الكروت */
        .card-body {
            padding: 1rem;
        }

        /* تحسين الجدول */
        #attendanceTable {
            font-size: 0.75rem;
        }

        #attendanceTable th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* تحسين المودال */
        #dayDetailsModal {
            z-index: 999999;
        }

        #dayDetailsModal .bg-white {
            max-width: 320px;
        }
    </style>

    <script>
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Export to Excel function
        function exportToExcel() {
            let table = document.getElementById('attendanceTable');
            let html = table.outerHTML;
            let url = 'data:application/vnd.ms-excel,' + escape(html);
            let link = document.createElement('a');
            link.download = 'attendance_history_{{ $month }}_{{ $year }}.xls';
            link.href = url;
            link.click();
        }

        // Auto-refresh on filter change
        $('select[name="month"], select[name="year"]').on('change', function() {
            $(this).closest('form').submit();
        });

        // Day jump functionality
        $('#dayJump').on('change', function() {
            let dayId = $(this).val();
            if (dayId) {
                let targetElement = $('#' + dayId);
                if (targetElement.length) {
                    $('#daysGrid').animate({
                        scrollTop: targetElement.position().top - $('#daysGrid').position().top + $(
                            '#daysGrid').scrollTop() - 20
                    }, 300);

                    targetElement.addClass('ring-2 ring-custom-500 scale-110');
                    setTimeout(() => {
                        targetElement.removeClass('ring-2 ring-custom-500 scale-110');
                    }, 1000);
                }
            }
        });

        // Show day details
        function showDayDetails(element) {
            let day = $(element).data('day');
            let status = $(element).data('status');
            let checkin = $(element).data('checkin');
            let checkout = $(element).data('checkout');
            let hours = $(element).data('hours');
            let date = '{{ $year }}-{{ $month }}-' + day;

            let statusColor = 'text-slate-600';
            let statusBg = 'bg-slate-100';

            if (status === '{{ __('messages.present') }}') {
                statusColor = 'text-green-600';
                statusBg = 'bg-green-100';
            } else if (status === '{{ __('messages.late') }}') {
                statusColor = 'text-yellow-600';
                statusBg = 'bg-yellow-100';
            } else if (status === '{{ __('messages.early') }}') {
                statusColor = 'text-orange-600';
                statusBg = 'bg-orange-100';
            } else if (status === '{{ __('messages.absent') }}') {
                statusColor = 'text-red-600';
                statusBg = 'bg-red-100';
            } else if (status === '{{ __('messages.weekend') }}') {
                statusColor = 'text-red-400';
                statusBg = 'bg-red-50';
            }

            let content = `
                <div class="text-center mb-2">
                    <div class="text-base font-bold">{{ __('messages.day') }} ${day}</div>
                    <div class="text-xs text-slate-500">${new Date(date).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })}</div>
                </div>
                <div class="border-t border-b border-slate-200 dark:border-zink-500 py-2 text-xs">
                    <div class="flex justify-between mb-1">
                        <span class="font-medium">{{ __('messages.status') }}:</span>
                        <span class="px-2 py-0.5 rounded-full ${statusBg} ${statusColor}">${status}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="font-medium">{{ __('messages.check_in') }}:</span>
                        <span>${checkin}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="font-medium">{{ __('messages.check_out') }}:</span>
                        <span>${checkout}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">{{ __('messages.hours') }}:</span>
                        <span>${hours} {{ __('messages.hrs') }}</span>
                    </div>
                </div>
            `;

            $('#dayDetailsContent').html(content);
            $('#dayDetailsModal').removeClass('hidden').css('display', 'flex');

            // Reinitialize Lucide icons in modal
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Close day modal
        function closeDayModal() {
            $('#dayDetailsModal').addClass('hidden').css('display', 'none');
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

        // Tooltips for notes (if any)
        $('[title]').each(function() {
            if ($(this).attr('title') && !$(this).hasClass('day-cell')) {
                $(this).on('mouseenter', function() {
                    let title = $(this).attr('title');
                    if (title) {
                        $(this).data('tippy', tippy(this, {
                            content: title,
                            placement: 'top',
                            theme: 'light'
                        }));
                    }
                });
            }
        });
    </script>
@endsection
