@extends('layouts.master')
@section('content')
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">{{ __('messages.attendance_management') }}</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.attendance') }}</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">{{ __('messages.attendance_management') }}</li>
            </ul>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 xl:grid-cols-12 gap-x-5">

            {{-- ============ Left Column ============ --}}
            <div class="lg:col-span-12 xl:col-span-3 xl:row-span-2">

                {{-- Employee Select --}}
                <div class="mb-5">
                    <label for="employeeSelect" class="inline-block mb-2 text-base font-medium">
                        {{ __('messages.select_employee') }}
                    </label>
                    <select class="form-input border-slate-200 focus:outline-none focus:border-custom-500 w-full"
                        data-choices="" data-choices-search-false="" name="employeeSelect" id="employeeSelect">
                        <option value="">{{ __('messages.select_employee') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->user_id }}" {{ $selectedUserId == $user->user_id ? 'selected' : '' }}>
                                {{ $user->name }} - {{ $user->user_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Employee Info Card --}}
                <div class="card" id="employeeInfoCard">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="mx-auto rounded-full size-20 bg-slate-100 dark:bg-zink-600 overflow-hidden">
                                <img src="{{ $selectedUser && $selectedUser->avatar ? asset('assets/images/user/' . $selectedUser->avatar) : asset('assets/images/profile.png') }}"
                                    alt="" class="h-20 w-20 rounded-full object-cover">
                            </div>
                            <h6 class="mt-3 mb-1 text-16">
                                <a href="#!">{{ $selectedUser->name ?? __('messages.no_employee_selected') }}</a>
                            </h6>
                            <p class="text-slate-500 dark:text-zink-200">{{ $selectedUser->position ?? '—' }}</p>
                        </div>

                        @if ($selectedUser)
                            <div class="mt-5 overflow-x-auto">
                                <table class="w-full mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.employee_id') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">{{ $selectedUser->user_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.department') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">{{ $selectedUser->department ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.experience') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">{{ $selectedUser->experience ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.joining_date') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">
                                                {{ $selectedUser->join_date ? date('d M, Y', strtotime($selectedUser->join_date)) : '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.present_days') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">{{ $stats['present_days'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.absent_days') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">{{ $stats['absent_days'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.late_days') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">{{ $stats['late_days'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.early_departures') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">{{ $stats['early_departure_days'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.total_hours') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">
                                                {{ number_format($stats['total_hours'] ?? 0, 1) }} {{ __('messages.hrs') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.regular_hours') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">
                                                {{ number_format($stats['regular_hours'] ?? 0, 1) }} {{ __('messages.hrs') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-zink-200">{{ __('messages.overtime') }}</td>
                                            <td class="px-3.5 py-2 font-semibold">
                                                {{ number_format($stats['overtime_hours'] ?? 0, 1) }} {{ __('messages.hrs') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="mt-5 text-center text-slate-500">
                                {{ __('messages.please_select_employee') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>{{-- end left col --}}

            {{-- ============ Stats Cards ============ --}}
            <div class="lg:col-span-4 xl:col-span-3">
                <div class="card">
                    <div class="flex items-center gap-4 card-body">
                        <div class="flex items-center justify-center rounded-md size-12 text-sky-500 bg-sky-100 text-15 dark:bg-sky-500/20 shrink-0">
                            <i data-lucide="clock"></i>
                        </div>
                        <div class="grow">
                            <h5 class="mb-1 text-16">
                                <span class="counter-value" data-target="{{ number_format($stats['approved_hours'] ?? 0, 1) }}">0</span>
                            </h5>
                            <p class="text-slate-500 dark:text-zink-200">{{ __('messages.approved_hours') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-4 xl:col-span-3">
                <div class="card">
                    <div class="flex items-center gap-4 card-body">
                        <div class="flex items-center justify-center text-red-500 bg-red-100 rounded-md size-12 text-15 dark:bg-red-500/20 shrink-0">
                            <i data-lucide="x-octagon"></i>
                        </div>
                        <div class="grow">
                            <h5 class="mb-1 text-16">
                                <span class="counter-value" data-target="{{ number_format($stats['rejected_hours'] ?? 0, 1) }}">0</span>
                            </h5>
                            <p class="text-slate-500 dark:text-zink-200">{{ __('messages.rejected_hours') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-4 xl:col-span-3">
                <div class="card">
                    <div class="flex items-center gap-4 card-body">
                        <div class="flex items-center justify-center text-yellow-500 bg-yellow-100 rounded-md size-12 text-15 dark:bg-yellow-500/20 shrink-0">
                            <i data-lucide="refresh-cw"></i>
                        </div>
                        <div class="grow">
                            <h5 class="mb-1 text-16">
                                <span class="counter-value" data-target="{{ number_format($stats['pending_hours'] ?? 0, 1) }}">0</span>
                            </h5>
                            <p class="text-slate-500 dark:text-zink-200">{{ __('messages.pending_hours') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ Attendance Table ============ --}}
            <div class="xl:col-span-9 lg:col-span-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Filters --}}
                        <div class="grid grid-cols-1 gap-4 mb-5 lg:grid-cols-2 xl:grid-cols-12">

                            {{-- Search by date --}}
                            <div class="xl:col-span-3">
                                <div class="relative">
                                    <input type="text" id="searchInput"
                                        
                                    class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                        placeholder="{{ __('messages.search_by_date_range') }}" autocomplete="off">
                                    <i data-lucide="search" class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200"></i>
                                </div>
                            </div>

                            {{-- Date range --}}
                            <div class="xl:col-span-3">
                                <form method="GET" action="{{ route('hr/attendance/page') }}" id="dateRangeForm">
                                    <input type="hidden" name="user_id" id="selectedUserId" value="{{ $selectedUserId }}">
                                    <input type="text" name="date_range" id="dateRange"
                                        class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                        data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"
                                        placeholder="{{ __('messages.select_date_range') }}"
                                        value="{{ $startDate->format('d M, Y') }} to {{ $endDate->format('d M, Y') }}">
                                </form>
                            </div>

                            {{-- Approve / Reject All --}}
                            <!-- <div class="flex justify-end gap-2 text-right lg:col-span-2 xl:col-span-4 xl:col-start-10">
                                <button type="button" id="rejectAllBtn"
                                    class="text-red-500 bg-white border-red-500 border-dashed btn hover:text-red-500 hover:bg-red-50 hover:border-red-600 focus:text-red-600 focus:bg-red-50 focus:border-red-600 active:text-red-600 active:bg-red-50 active:border-red-600 dark:bg-zink-700 dark:ring-red-400/20 dark:hover:bg-red-800/20">
                                    {{ __('messages.reject_all') }}
                                </button>
                                <button type="button" id="approveAllBtn"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                    {{ __('messages.approve_all') }}
                                </button>
                            </div> -->
                        </div>

                        {{-- Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap" id="attendanceTable">
                                <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                                    <tr>
                                        <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                            <input type="checkbox" id="selectAll" class="rounded border-slate-200 dark:border-zink-500">
                                        </th>
                                        <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">{{ __('messages.date') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">{{ __('messages.check_in') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">{{ __('messages.check_out') }}</th>
                                        <!-- <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">{{ __('messages.status') }}</th> -->
                                        <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">{{ __('messages.work_hours') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">{{ __('messages.overtime_short') }}</th>
                                        <!-- <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">{{ __('messages.late_early') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">{{ __('messages.action') }}</th> -->
                                    </tr>
                                </thead>
                                <tbody id="attendanceTableBody">
                                    @forelse($attendances as $attendance)
                                        @php
                                            $statusColors = [
                                                'present'          => 'bg-green-100 text-green-500 border-green-100',
                                                'late'             => 'bg-yellow-100 text-yellow-500 border-yellow-100',
                                                'early_departure'  => 'bg-orange-100 text-orange-500 border-orange-100',
                                                'late_early'       => 'bg-red-100 text-red-500 border-red-100',
                                                'absent'           => 'bg-slate-100 text-slate-500 border-slate-100',
                                                'approved'         => 'bg-green-100 text-green-500 border-green-100',
                                                'rejected'         => 'bg-red-100 text-red-500 border-red-100',
                                                'pending'          => 'bg-yellow-100 text-yellow-500 border-yellow-100',
                                            ];
                                            $statusText = [
                                                'present'         => __('messages.present'),
                                                'late'            => __('messages.late'),
                                                'early_departure' => __('messages.early_departure'),
                                                'late_early'      => __('messages.late_early'),
                                                'absent'          => __('messages.absent'),
                                                'approved'        => __('messages.approved'),
                                                'rejected'        => __('messages.rejected'),
                                                'pending'         => __('messages.pending'),
                                            ];
                                        @endphp
                                        <tr data-id="{{ $attendance->id }}">
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <input type="checkbox" class="attendance-checkbox rounded border-slate-200 dark:border-zink-500" value="{{ $attendance->id }}">
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                {{ date('d M, Y', strtotime($attendance->date)) }}
                                                <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">
                                                    {{ date('D', strtotime($attendance->date)) }}
                                                </span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                {{ $attendance->check_in ? date('h:i A', strtotime($attendance->check_in)) : '—' }}
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                {{ $attendance->check_out ? date('h:i A', strtotime($attendance->check_out)) : '—' }}
                                            </td>
                                            <!-- <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border {{ $statusColors[$attendance->status] ?? 'bg-slate-100 text-slate-500' }}">
                                                    {{ $statusText[$attendance->status] ?? ucfirst($attendance->status) }}
                                                </span>
                                            </td> -->
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                {{ number_format($attendance->working_hours, 2) }} {{ __('messages.hrs') }}
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                {{ number_format($attendance->overtime_hours, 2) }} {{ __('messages.hrs') }}
                                            </td>
                                            <!-- <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                @if ($attendance->late_minutes > 0)
                                                    <span class="text-yellow-600">{{ round($attendance->late_minutes) }} {{ __('messages.min_late') }}</span>
                                                @elseif($attendance->early_departure_minutes > 0)
                                                    <span class="text-orange-600">{{ round($attendance->early_departure_minutes) }} {{ __('messages.min_early') }}</span>
                                                @else
                                                    <span class="text-green-600">{{ __('messages.on_time') }}</span>
                                                @endif
                                            </td> -->
                                            <!-- <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    @if (!in_array($attendance->status, ['approved', 'rejected']))
                                                        <button type="button"
                                                            onclick="updateAttendanceStatus({{ $attendance->id }}, 'approved')"
                                                            class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"
                                                            title="{{ __('messages.approve') }}">
                                                            <i data-lucide="check" class="size-4"></i>
                                                        </button>
                                                        <button type="button"
                                                            onclick="updateAttendanceStatus({{ $attendance->id }}, 'rejected')"
                                                            class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"
                                                            title="{{ __('messages.reject') }}">
                                                            <i data-lucide="x" class="size-4"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                        onclick="viewAttendanceDetails({{ $attendance->id }})"
                                                        class="flex items-center justify-center text-slate-500 transition-all duration-200 ease-linear bg-slate-100 rounded-md size-8 hover:text-white hover:bg-slate-500 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-white dark:hover:bg-zink-500"
                                                        title="{{ __('messages.view_details') }}">
                                                        <i data-lucide="eye" class="size-4"></i>
                                                    </button>
                                                </div>
                                            </td> -->
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-3.5 py-2.5 text-center border-y border-slate-200 dark:border-zink-500">
                                                {{ __('messages.no_records') }}
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
                                    {{ __('messages.showing') }} <b>{{ $attendances->firstItem() ?? 0 }}</b>
                                    {{ __('messages.to') }} <b>{{ $attendances->lastItem() ?? 0 }}</b>
                                    {{ __('messages.of') }} <b>{{ $attendances->total() }}</b>
                                    {{ __('messages.results') }}
                                </p>
                            </div>
                            {{ $attendances->appends(request()->query())->links() }}
                        </div>

                    </div>
                </div>
            </div>{{-- end table col --}}

        </div>{{-- end grid --}}
    </div>
</div>

{{-- View Details Modal --}}
<div id="attendanceDetailModal" modal-center
    class="fixed flex flex-col hidden transition-all duration-300 -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-full max-w-2xl max-h-fit bg-white rounded-lg dark:bg-zink-600 z-[9999] shadow-xl">
    <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
        <h5 class="text-16">{{ __('messages.attendance_details') }}</h5>
        <button type="button" class="text-2xl text-slate-400 close" data-modal-close="attendanceDetailModal">
            <i data-lucide="x" class="size-4"></i>
        </button>
    </div>
    <div class="p-4" id="attendanceDetailContent">
        {{-- loaded via AJAX --}}
    </div>
    <div class="flex justify-end p-4 border-t border-slate-200 dark:border-zink-500">
        <button type="button" data-modal-close="attendanceDetailModal"
            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600">
            {{ __('messages.close') }}
        </button>
    </div>
</div>

@endsection

@section('script')
<script>
    // Init icons
    if (typeof lucide !== 'undefined') lucide.createIcons();

    // ── Employee select → redirect ──────────────────────────────────────────
    $('#employeeSelect').on('change', function () {
        var userId = $(this).val();
        if (userId) {
            window.location.href = "{{ route('hr/attendance/page') }}?user_id=" + userId;
        }
    });

    // ── Date range change → submit form ────────────────────────────────────
    $('#dateRange').on('change', function () {
        var val = $(this).val();
        if (val && val.trim() !== '') {
            $('#dateRangeForm').submit();
        }
    });

    // ── Update single attendance status ────────────────────────────────────
    function updateAttendanceStatus(id, status) {
        var confirmColor = status === 'approved' ? '#28a745' : '#dc3545';
        var message      = status === 'approved'
            ? '{{ __('messages.want_to_approve') }}'
            : '{{ __('messages.want_to_reject') }}';
        var confirmText  = status === 'approved'
            ? '{{ __('messages.yes_approve') }}'
            : '{{ __('messages.yes_reject') }}';

        Swal.fire({
            title: '{{ __('messages.are_you_sure') }}',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: '{{ __('messages.cancel') }}'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('hr/attendance/update-status') }}",
                    type: 'POST',
                    data: { id: id, status: status, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('{{ __('messages.success') }}', response.message, 'success')
                                .then(function () { location.reload(); });
                        }
                    },
                    error: function () {
                        Swal.fire('{{ __('messages.error') }}', '{{ __('messages.update_failed') }}', 'error');
                    }
                });
            }
        });
    }

    // ── View attendance details modal ───────────────────────────────────────
    function viewAttendanceDetails(id) {
        $.ajax({
            url: "{{ url('hr/attendance/get-details') }}/" + id,
            type: 'GET',
            success: function (response) {
                $('#attendanceDetailContent').html(response);
                openModal('attendanceDetailModal');
            },
            error: function () {
                Swal.fire('{{ __('messages.error') }}', '{{ __('messages.update_failed') }}', 'error');
            }
        });
    }

    // ── Select All checkboxes ───────────────────────────────────────────────
    $('#selectAll').on('change', function () {
        $('.attendance-checkbox').prop('checked', $(this).prop('checked'));
    });

    // ── Approve All ─────────────────────────────────────────────────────────
    $('#approveAllBtn').on('click', function () {
        var ids = getSelectedIds();
        if (!ids.length) {
            Swal.fire('{{ __('messages.warning') }}', '{{ __('messages.select_at_least_one') }}', 'warning');
            return;
        }
        Swal.fire({
            title: '{{ __('messages.are_you_sure') }}',
            text: '{{ __('messages.want_to_approve_all') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __('messages.yes_approve_all') }}',
            cancelButtonText: '{{ __('messages.cancel') }}'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('hr/attendance/bulk-approve') }}",
                    type: 'POST',
                    data: { ids: ids, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('{{ __('messages.success') }}', response.message, 'success')
                                .then(function () { location.reload(); });
                        }
                    },
                    error: function () {
                        Swal.fire('{{ __('messages.error') }}', '{{ __('messages.update_failed') }}', 'error');
                    }
                });
            }
        });
    });

    // ── Reject All ──────────────────────────────────────────────────────────
    $('#rejectAllBtn').on('click', function () {
        var ids = getSelectedIds();
        if (!ids.length) {
            Swal.fire('{{ __('messages.warning') }}', '{{ __('messages.select_at_least_one') }}', 'warning');
            return;
        }
        Swal.fire({
            title: '{{ __('messages.are_you_sure') }}',
            text: '{{ __('messages.want_to_reject_all') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __('messages.yes_reject_all') }}',
            cancelButtonText: '{{ __('messages.cancel') }}'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('hr/attendance/bulk-reject') }}",
                    type: 'POST',
                    data: { ids: ids, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('{{ __('messages.success') }}', response.message, 'success')
                                .then(function () { location.reload(); });
                        }
                    },
                    error: function () {
                        Swal.fire('{{ __('messages.error') }}', '{{ __('messages.update_failed') }}', 'error');
                    }
                });
            }
        });
    });

    // ── Search filter (client-side by date text) ────────────────────────────
    $('#searchInput').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        $('#attendanceTableBody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // ── Helper: get checked IDs ─────────────────────────────────────────────
    function getSelectedIds() {
        var ids = [];
        $('.attendance-checkbox:checked').each(function () { ids.push($(this).val()); });
        return ids;
    }

    // ── Open modal helper ───────────────────────────────────────────────────
    function openModal(modalId) {
        $('#' + modalId).removeClass('hidden');
    }
</script>
@endsection