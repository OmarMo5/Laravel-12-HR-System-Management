@extends('layouts.master')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Page Header -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.my_attendance_dashboard') }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400">
                        <a href="#!" class="text-slate-400">{{ __('messages.attendance') }}</a>
                    </li>
                    <li class="text-slate-700">{{ __('messages.my_dashboard') }}</li>
                </ul>
            </div>

            <!-- User Info Card - أضف هذا بعد Page Header وقبل Server Time Card -->
            <div class="grid grid-cols-1 gap-5 mb-5 lg:grid-cols-4">
                <div class="lg:col-span-4">
                    <div class="card bg-gradient-to-r from-slate-700 to-slate-800">
                        <div class="card-body">
                            <div class="flex items-center gap-4">
                                <div class="size-16 rounded-full border-2 border-white overflow-hidden">
                                    <img src="{{ $user && $user->avatar ? asset('assets/images/user/' . $user->avatar) : asset('assets/images/profile.png') }}"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="text-xl text-white mb-1">{{ $user->name ?? 'Employee' }}</h4>
                                    <p class="text-white/80 text-sm">{{ $user->user_id ?? '—' }} •
                                        {{ $user->position ?? 'Employee' }}</p>
                                    <div class="flex gap-3 mt-2">
                                        <span class="text-xs text-white/60">
                                            <i data-lucide="mail" class="inline-block size-3 mr-1"></i>
                                            {{ $user->email ?? '—' }}
                                        </span>
                                        <span class="text-xs text-white/60">
                                            <i data-lucide="phone" class="inline-block size-3 mr-1"></i>
                                            {{ $user->phone_number ?? '—' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Server Time Card -->
            <div class="grid grid-cols-1 gap-5 mb-5 lg:grid-cols-3">
                <div class="lg:col-span-3">
                    <div class="card overflow-hidden bg-gradient-to-r from-custom-500 to-custom-600">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h6 class="mb-2 text-15 text-white/80">{{ __('messages.server_time') }}</h6>
                                    <h2 class="mb-1 text-3xl text-white" id="server-time">
                                        {{ $currentTime->format('h:i:s A') }}</h2>
                                    <p class="text-white/80">{{ $currentTime->format('l, d F Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="inline-block p-4 rounded-full bg-white/10">
                                        <i data-lucide="clock" class="size-12 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Check In/Out Cards -->
            <div class="grid grid-cols-1 gap-5 mb-5 lg:grid-cols-2">
                <!-- Check In Card -->
                <div class="col-span-1">
                    <div class="card {{ $checkedIn ? 'border-l-4 border-green-500' : '' }}">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-4">
                                <h6 class="text-15">{{ __('messages.check_in') }}</h6>
                                @if ($checkedIn)
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded bg-green-100 text-green-500">
                                        <i data-lucide="check" class="inline-block size-3"></i>
                                        {{ __('messages.checked_in') }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-center">
                                @if ($checkedIn)
                                    <div class="mb-4">
                                        <div class="inline-block p-4 rounded-full bg-green-100">
                                            <i data-lucide="log-in" class="size-12 text-green-500"></i>
                                        </div>
                                        <h4 class="mt-3 text-2xl">
                                            {{ $todayAttendance ? date('h:i A', strtotime($todayAttendance->check_in)) : '—' }}
                                        </h4>
                                        <p class="text-slate-500">{{ __('messages.your_check_in_time') }}</p>
                                        @if ($todayAttendance && $todayAttendance->late_minutes > 0)
                                            <span
                                                class="inline-block px-2.5 py-1 mt-2 text-xs font-medium rounded bg-yellow-100 text-yellow-600">
                                                {{ __('messages.late_by') }} {{ round($todayAttendance->late_minutes) }}
                                                {{ __('messages.minutes') }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-4">
                                        <div class="inline-block p-4 rounded-full bg-custom-100 animate-pulse">
                                            <i data-lucide="log-in" class="size-12 text-custom-500"></i>
                                        </div>
                                        <h4 class="mt-3 text-2xl">— : — : —</h4>
                                        <p class="text-slate-500">{{ __('messages.you_havent_checked_in') }}</p>
                                    </div>

                                    <button type="button" id="checkInBtn"
                                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 w-full py-3 text-16">
                                        <i data-lucide="log-in" class="inline-block size-5 mr-2"></i>
                                        {{ __('messages.check_in_now') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Check Out Card -->
                <div class="col-span-1">
                    <div class="card {{ $checkedOut ? 'border-l-4 border-red-500' : '' }}">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-4">
                                <h6 class="text-15">{{ __('messages.check_out') }}</h6>
                                @if ($checkedOut)
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded bg-red-100 text-red-500">
                                        <i data-lucide="check" class="inline-block size-3"></i>
                                        {{ __('messages.checked_out') }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-center">
                                @if ($checkedOut)
                                    <div class="mb-4">
                                        <div class="inline-block p-4 rounded-full bg-red-100">
                                            <i data-lucide="log-out" class="size-12 text-red-500"></i>
                                        </div>
                                        <h4 class="mt-3 text-2xl">
                                            {{ date('h:i A', strtotime($todayAttendance->check_out)) }}</h4>
                                        <p class="text-slate-500">{{ __('messages.your_check_out_time') }}</p>
                                        <div class="flex items-center justify-center gap-4 mt-3">
                                            <span class="text-sm">
                                                <span class="font-semibold">{{ $todayAttendance->working_hours }}</span>
                                                {{ __('messages.hrs') }} {{ __('messages.hours_worked') }}
                                            </span>
                                            @if ($todayAttendance->overtime_hours > 0)
                                                <span class="text-sm text-green-600">
                                                    +{{ $todayAttendance->overtime_hours }} {{ __('messages.hrs') }}
                                                    {{ __('messages.ot') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($checkedIn)
                                    <div class="mb-4">
                                        <div class="inline-block p-4 rounded-full bg-yellow-100">
                                            <i data-lucide="log-out" class="size-12 text-yellow-500"></i>
                                        </div>
                                        <h4 class="mt-3 text-2xl">— : — : —</h4>
                                        <p class="text-slate-500">{{ __('messages.waiting_for_check_out') }}</p>
                                    </div>

                                    <button type="button" id="checkOutBtn"
                                        class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600 w-full py-3 text-16">
                                        <i data-lucide="log-out" class="inline-block size-5 mr-2"></i>
                                        {{ __('messages.check_out_now') }}
                                    </button>
                                @else
                                    <div class="mb-4">
                                        <div class="inline-block p-4 rounded-full bg-slate-100">
                                            <i data-lucide="log-out" class="size-12 text-slate-400"></i>
                                        </div>
                                        <h4 class="mt-3 text-2xl">— : — : —</h4>
                                        <p class="text-slate-500">{{ __('messages.check_in_first') }}</p>
                                    </div>

                                    <button type="button" disabled
                                        class="text-white btn bg-slate-300 border-slate-300 cursor-not-allowed w-full py-3 text-16">
                                        <i data-lucide="log-out" class="inline-block size-5 mr-2"></i>
                                        {{ __('messages.check_out_disabled') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Stats -->
            <div class="grid grid-cols-1 gap-5 mb-5 lg:grid-cols-4">
                <div class="col-span-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center rounded-md size-12 text-green-500 bg-green-100">
                                    <i data-lucide="check-circle" class="size-6"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 text-xl">{{ $monthlyStats['present'] }}</h5>
                                    <p class="text-slate-500">{{ __('messages.present_days') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center rounded-md size-12 text-yellow-500 bg-yellow-100">
                                    <i data-lucide="alert-circle" class="size-6"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 text-xl">{{ $monthlyStats['late'] }}</h5>
                                    <p class="text-slate-500">{{ __('messages.late_days') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center rounded-md size-12 text-red-500 bg-red-100">
                                    <i data-lucide="x-circle" class="size-6"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 text-xl">{{ $monthlyStats['absent'] }}</h5>
                                    <p class="text-slate-500">{{ __('messages.absent_days') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center rounded-md size-12 text-purple-500 bg-purple-100">
                                    <i data-lucide="clock" class="size-6"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 text-xl">{{ number_format($monthlyStats['total_hours'], 1) }}</h5>
                                    <p class="text-slate-500">{{ __('messages.total_hours') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-15">{{ __('messages.recent_attendance') }}</h6>
                        <a href="{{ route('employee/attendance/history') }}" class="text-custom-500 hover:underline">
                            {{ __('messages.view_all') }} <i data-lucide="arrow-right" class="inline-block size-4"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.date') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.check_in') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.check_out') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.status') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.hours') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendance as $attendance)
                                    <tr>
                                        <td class="px-3.5 py-2.5 border-y">
                                            {{ date('d M, Y', strtotime($attendance->date)) }}
                                            <span class="px-2 py-0.5 text-xs rounded bg-slate-100 ltr:ml-2">
                                                {{ date('D', strtotime($attendance->date)) }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y">
                                            {{ $attendance->check_in ? date('h:i A', strtotime($attendance->check_in)) : '—' }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y">
                                            {{ $attendance->check_out ? date('h:i A', strtotime($attendance->check_out)) : '—' }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y">
                                            @php
                                                $statusColors = [
                                                    'present' => 'bg-green-100 text-green-500',
                                                    'late' => 'bg-yellow-100 text-yellow-500',
                                                    'early_departure' => 'bg-orange-100 text-orange-500',
                                                    'late_early' => 'bg-red-100 text-red-500',
                                                    'absent' => 'bg-slate-100 text-slate-500',
                                                ];
                                                $statusText = [
                                                    'present' => __('messages.present'),
                                                    'late' => __('messages.late'),
                                                    'early_departure' => __('messages.early'),
                                                    'late_early' => __('messages.late_early'),
                                                    'absent' => __('messages.absent'),
                                                ];
                                            @endphp
                                            <span
                                                class="px-2.5 py-0.5 text-xs font-medium rounded {{ $statusColors[$attendance->status] ?? 'bg-slate-100' }}">
                                                {{ $statusText[$attendance->status] ?? ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y">
                                            {{ $attendance->working_hours ? number_format($attendance->working_hours, 1) . ' ' . __('messages.hrs') : '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3.5 py-2.5 text-center border-y">
                                            {{ __('messages.no_records') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notes Modal for Check In - نسخة محسنة في المنتصف -->
            <div id="checkInModal"
                class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300"
                style="display: none;">
                <div
                    class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl dark:bg-zink-700 transform transition-all duration-300 scale-100">
                    <!-- Modal Header with gradient -->
                    <div
                        class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-zink-500 bg-gradient-to-r from-custom-500 to-custom-600 rounded-t-2xl sticky top-0 z-10">
                        <div class="flex items-center gap-3">
                            <div class="size-14 rounded-full bg-white/20 flex items-center justify-center">
                                <i data-lucide="log-in" class="size-7 text-white"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl font-semibold text-white">{{ __('messages.check_in_confirmation') }}
                                </h5>
                                <p class="text-sm text-white/80">{{ __('messages.please_confirm_check_in') }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-white/80 hover:text-white transition-colors"
                            data-modal-close="checkInModal">
                            <i data-lucide="x" class="size-7"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <!-- Time Display Card -->
                        <div
                            class="mb-8 p-8 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-500/10 dark:to-indigo-500/10 rounded-xl border-2 border-blue-200 dark:border-blue-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-medium text-blue-600 dark:text-blue-400 mb-2">
                                        {{ __('messages.check_in_time') }}</p>
                                    <h2 class="text-5xl font-bold text-blue-700 dark:text-blue-300 font-mono"
                                        id="checkInTimeDisplay">{{ $currentTime->format('h:i:s A') }}</h2>
                                    <p class="text-base text-blue-500 dark:text-blue-400 mt-3">
                                        <i data-lucide="calendar" class="inline-block size-4 mr-1"></i>
                                        {{ $currentTime->format('l, d F Y') }}
                                    </p>
                                </div>
                                <div
                                    class="size-24 rounded-full bg-blue-500/20 flex items-center justify-center animate-pulse">
                                    <i data-lucide="clock" class="size-12 text-blue-600 dark:text-blue-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="mb-6">
                            <label class="block mb-3 text-base font-semibold text-slate-700 dark:text-zink-200">
                                {{ __('messages.notes_optional') }}
                            </label>
                            <textarea id="checkInNotes" rows="4"
                                class="w-full px-5 py-4 text-base border-2 border-slate-200 dark:border-zink-500 rounded-xl focus:border-custom-500 dark:focus:border-custom-800 focus:ring-2 focus:ring-custom-500/20 dark:focus:ring-custom-800/20 outline-none transition-all resize-none bg-white dark:bg-zink-600"
                                placeholder="{{ __('messages.add_remarks') }}"></textarea>
                        </div>

                        <!-- Info Alert -->
                        <div
                            class="p-5 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-start gap-3">
                                <div
                                    class="size-10 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center shrink-0">
                                    <i data-lucide="info" class="size-5 text-amber-600 dark:text-amber-400"></i>
                                </div>
                                <div>
                                    <h6 class="text-base font-semibold text-amber-700 dark:text-amber-300 mb-1">
                                        {{ __('messages.server_time_used') }}</h6>
                                    <p class="text-base text-amber-600 dark:text-amber-400">
                                        {{ __('messages.server_time_info') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div
                        class="flex justify-end gap-3 p-6 border-t border-slate-200 dark:border-zink-500 bg-slate-50 dark:bg-zink-800 rounded-b-2xl sticky bottom-0">
                        <button type="button" data-modal-close="checkInModal"
                            class="px-8 py-3 text-base font-medium text-slate-700 bg-white border-2 border-slate-300 rounded-xl hover:bg-slate-50 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-500 dark:hover:bg-zink-500 transition-all duration-200 hover:scale-105">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="button" id="confirmCheckIn"
                            class="px-10 py-3 text-base font-medium text-white bg-gradient-to-r from-custom-500 to-custom-600 rounded-xl hover:from-custom-600 hover:to-custom-700 transition-all duration-200 hover:scale-105 shadow-lg shadow-custom-500/30">
                            <i data-lucide="check-circle" class="inline-block size-5 mr-2"></i>
                            {{ __('messages.confirm_check_in') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notes Modal for Check Out - نسخة محسنة في المنتصف -->
            <div id="checkOutModal"
                class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300"
                style="display: none;">
                <div
                    class="relative w-full max-w-2xl max-h-[50vh] overflow-y-auto bg-white rounded-2xl shadow-2xl dark:bg-zink-700 transform transition-all duration-300 scale-100">
                    <!-- Modal Header with gradient -->
                    <div
                        class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500 bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-2xl sticky top-0 z-10">
                        <div class="flex items-center gap-3">
                            <div class="size-14 rounded-full bg-white/20 flex items-center justify-center">
                                <i data-lucide="log-out" class="size-7 text-white"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl font-semibold text-white">{{ __('messages.check_out_confirmation') }}
                                </h5>
                                <p class="text-sm text-white/80">{{ __('messages.please_confirm_check_out') }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-white/80 hover:text-white transition-colors"
                            data-modal-close="checkOutModal">
                            <i data-lucide="x" class="size-7"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <!-- Time Display Card -->
                        <div
                            class="mb-8 p-4 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-500/10 dark:to-orange-500/10 rounded-xl border-2 border-amber-200 dark:border-amber-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-medium text-amber-600 dark:text-amber-400 mb-2">
                                        {{ __('messages.check_out_time') }}
                                    </p>
                                    <h2 class="text-5xl font-bold text-amber-700 dark:text-amber-300 font-mono"
                                        id="checkOutTimeDisplay">{{ $currentTime->format('h:i:s A') }}</h2>
                                    <p class="text-base text-amber-500 dark:text-amber-400 mt-3">
                                        <i data-lucide="calendar" class="inline-block size-4 mr-1"></i>
                                        {{ $currentTime->format('l, d F Y') }}
                                    </p>
                                </div>
                                <div
                                    class="size-24 rounded-full bg-amber-500/20 flex items-center justify-center animate-pulse">
                                    <i data-lucide="clock" class="size-12 text-amber-600 dark:text-amber-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Working Hours Preview (if check-in exists) -->
                        @if ($checkedIn && !$checkedOut)
                            <div
                                class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 rounded-xl border border-green-200 dark:border-green-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-base font-medium text-green-600 dark:text-green-400 mb-1">
                                            {{ __('messages.todays_working_hours') }}</p>
                                        <h3 class="text-3xl font-bold text-green-700 dark:text-green-300">
                                            {{ $todayAttendance ? \Carbon\Carbon::parse($todayAttendance->check_in)->diffInHours(\Carbon\Carbon::now()) : 0 }}
                                            {{ __('messages.hrs') }}
                                        </h3>
                                    </div>
                                    <i data-lucide="trending-up" class="size-10 text-green-500"></i>
                                </div>
                            </div>
                        @endif

                        <!-- Notes Section -->
                        <div class="mb-6">
                            <label class="block mb-3 text-base font-semibold text-slate-700 dark:text-zink-200">
                                {{ __('messages.notes_optional') }}
                            </label>
                            <textarea id="checkOutNotes" rows="4"
                                class="w-full px-5 py-4 text-base border-2 border-slate-200 dark:border-zink-500 rounded-xl focus:border-amber-500 dark:focus:border-amber-800 focus:ring-2 focus:ring-amber-500/20 dark:focus:ring-amber-800/20 outline-none transition-all resize-none bg-white dark:bg-zink-600"
                                placeholder="{{ __('messages.add_remarks') }}"></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div
                        class="flex justify-end gap-3 p-6 border-t border-slate-200 dark:border-zink-500 bg-slate-50 dark:bg-zink-800 rounded-b-2xl sticky bottom-0">
                        <button type="button" data-modal-close="checkOutModal"
                            class="px-8 py-3 text-base font-medium text-slate-700 bg-white border-2 border-slate-300 rounded-xl hover:bg-slate-50 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-500 dark:hover:bg-zink-500 transition-all duration-200 hover:scale-105">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="button" id="confirmCheckOut"
                            class="px-10 py-3 text-base font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 hover:scale-105 shadow-lg shadow-amber-500/30">
                            <i data-lucide="check-circle" class="inline-block size-5 mr-2"></i>
                            {{ __('messages.confirm_check_out') }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @section('script')
        <style>
            /* Modal animations */
            [id$="Modal"] {
                animation: fadeIn 0.3s ease;
            }

            [id$="Modal"]>div {
                animation: slideIn 0.3s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes slideIn {
                from {
                    transform: translateY(-20px) scale(0.95);
                    opacity: 0;
                }

                to {
                    transform: translateY(0) scale(1);
                    opacity: 1;
                }
            }

            /* Ensure modal is above everything */
            [id$="Modal"] {
                z-index: 99999 !important;
            }
        </style>

        <script>
            // Live server time update with 12-hour format
            function updateServerTime() {
                let now = new Date();
                let hours = now.getHours();
                let minutes = now.getMinutes().toString().padStart(2, '0');
                let seconds = now.getSeconds().toString().padStart(2, '0');
                let ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                let timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                $('#server-time').text(timeString);
            }
            setInterval(updateServerTime, 1000);

            // Check In button click
            $('#checkInBtn').on('click', function() {
                let now = new Date();
                let hours = now.getHours();
                let minutes = now.getMinutes().toString().padStart(2, '0');
                let seconds = now.getSeconds().toString().padStart(2, '0');
                let ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                let timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                $('#checkInTimeDisplay').text(timeString);
                openModal('checkInModal');
            });

            // Check Out button click
            $('#checkOutBtn').on('click', function() {
                let now = new Date();
                let hours = now.getHours();
                let minutes = now.getMinutes().toString().padStart(2, '0');
                let seconds = now.getSeconds().toString().padStart(2, '0');
                let ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                let timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                $('#checkOutTimeDisplay').text(timeString);
                openModal('checkOutModal');
            });

            // Confirm Check In
            $('#confirmCheckIn').on('click', function() {
                let notes = $('#checkInNotes').val();
                let button = $(this);

                button.prop('disabled', true).html(
                    '<i class="animate-spin inline-block size-4 mr-2">⏳</i> {{ __('messages.processing') }}');

                $.ajax({
                    url: '{{ route('employee/attendance/check-in') }}',
                    type: 'POST',
                    data: {
                        notes: notes,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Close modal first
                            closeModal('checkInModal');

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('messages.success') }}',
                                text: response.message,
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                // Refresh the page after message
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        button.prop('disabled', false).text('{{ __('messages.confirm_check_in') }}');
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('messages.error') }}',
                            text: xhr.responseJSON?.message ||
                                '{{ __('messages.check_in_success') }}'
                        });
                    }
                });
            });

            // Confirm Check Out
            $('#confirmCheckOut').on('click', function() {
                let notes = $('#checkOutNotes').val();
                let button = $(this);

                button.prop('disabled', true).html(
                    '<i class="animate-spin inline-block size-4 mr-2">⏳</i> {{ __('messages.processing') }}');

                $.ajax({
                    url: '{{ route('employee/attendance/check-out') }}',
                    type: 'POST',
                    data: {
                        notes: notes,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Close modal first
                            closeModal('checkOutModal');

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('messages.success') }}',
                                text: response.message,
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                // Refresh the page after message
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        button.prop('disabled', false).text('{{ __('messages.confirm_check_out') }}');
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('messages.error') }}',
                            text: xhr.responseJSON?.message ||
                                '{{ __('messages.check_out_success') }}'
                        });
                    }
                });
            });

            // Modal helpers
            function openModal(modalId) {
                $('#' + modalId).removeClass('hidden').css('display', 'flex');
                $('body').css('overflow', 'hidden');
            }

            function closeModal(modalId) {
                $('#' + modalId).addClass('hidden').css('display', 'none');
                $('#' + modalId + ' textarea').val('');
                $('body').css('overflow', '');
            }

            // Close modal on overlay click
            $(document).on('click', function(e) {
                if ($(e.target).is('[id$="Modal"]')) {
                    let modalId = $(e.target).attr('id');
                    closeModal(modalId);
                }
            });

            // Close modal on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('[id$="Modal"]:visible').each(function() {
                        closeModal($(this).attr('id'));
                    });
                }
            });

            $('[data-modal-close]').on('click', function() {
                let modalId = $(this).closest('[id$="Modal"]').attr('id');
                closeModal(modalId);
            });


            // Day jump functionality
            $('#dayJump').on('change', function() {
                let dayId = $(this).val();
                if (dayId) {
                    let targetElement = $('#' + dayId);
                    if (targetElement.length) {
                        // Scroll to the day
                        $('html, body').animate({
                            scrollTop: targetElement.offset().top - 100
                        }, 500);

                        // Highlight the day
                        targetElement.addClass('ring-4 ring-custom-500 scale-105');
                        setTimeout(() => {
                            targetElement.removeClass('ring-4 ring-custom-500 scale-105');
                        }, 2000);
                    }
                }
            });
        </script>
    @endsection
@endsection
