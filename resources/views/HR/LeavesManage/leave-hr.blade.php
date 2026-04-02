@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.leave_manage_hr') }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.leaves_manage') }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        {{ __('messages.leave_manage_hr') }}
                    </li>
                </ul>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-x-5 md:grid-cols-2 xl:grid-cols-4">
                <div class="col-span-1">
                    <div class="card">
                        <div class="flex items-center gap-3 card-body">
                            <div
                                class="flex items-center justify-center rounded-md size-12 text-15 bg-custom-100 text-custom-500 dark:bg-custom-500/20 shrink-0">
                                <i data-lucide="calendar"></i>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value"
                                        data-target="{{ $todayLeaves }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">{{ __('messages.todays_leaves') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div class="card">
                        <div class="flex items-center gap-3 card-body">
                            <div
                                class="flex items-center justify-center text-green-500 bg-green-100 rounded-md size-12 text-15 dark:bg-green-500/20 shrink-0">
                                <i data-lucide="check-circle"></i>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value"
                                        data-target="{{ $approvedLeaves }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">{{ __('messages.approved_leaves') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div class="card">
                        <div class="flex items-center gap-3 card-body">
                            <div
                                class="flex items-center justify-center text-yellow-500 bg-yellow-100 rounded-md size-12 text-15 dark:bg-yellow-500/20 shrink-0">
                                <i data-lucide="clock"></i>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value"
                                        data-target="{{ $pendingLeaves }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">{{ __('messages.pending_leaves') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div class="card">
                        <div class="flex items-center gap-3 card-body">
                            <div
                                class="flex items-center justify-center text-red-500 bg-red-100 rounded-md size-12 text-15 dark:bg-red-500/20 shrink-0">
                                <i data-lucide="x-circle"></i>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value"
                                        data-target="{{ $rejectedLeaves }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">{{ __('messages.rejected_leaves') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Add Button -->
            <div class="card">
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-4 mb-5 lg:grid-cols-2 xl:grid-cols-12">
                        <div class="xl:col-span-9">
                            <form method="GET" action="{{ route('hr/leave/hr/page') }}" class="flex flex-wrap gap-3">
                                <div class="flex-1 min-w-[200px]">
                                    <input type="text" name="search" value="{{ $search ?? '' }}"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500"
                                        placeholder="{{ __('messages.search_by_employee') }}">
                                </div>
                                <div class="w-[150px]">
                                    <select name="status"
                                        class="form-input border-slate-200 focus:outline-none focus:border-custom-500">
                                        <option value="">{{ __('messages.all_status') }}</option>
                                        <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>
                                            {{ __('messages.pending') }}
                                        </option>
                                        <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>
                                            {{ __('messages.approved') }}
                                        </option>
                                        <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>
                                            {{ __('messages.rejected') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="w-[180px]">
                                    <select name="leave_type"
                                        class="form-input border-slate-200 focus:outline-none focus:border-custom-500">
                                        <option value="">{{ __('messages.all_leave_types') }}</option>
                                        @foreach ($leaveTypes as $type)
                                            <option value="{{ $type->leave_type }}"
                                                {{ $leaveType == $type->leave_type ? 'selected' : '' }}>
                                                {{ $type->leave_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="text-white btn bg-custom-500 border-custom-500">
                                    <i data-lucide="search" class="inline-block size-4"></i> {{ __('messages.filter') }}
                                </button>
                                <a href="{{ route('hr/leave/hr/page') }}"
                                    class="btn bg-slate-200 text-slate-800 hover:bg-slate-300">
                                    <i data-lucide="x" class="inline-block size-4"></i> {{ __('messages.clear') }}
                                </a>
                            </form>
                        </div>
                        <div class="xl:col-span-3">
                            <div class="ltr:lg:text-right rtl:lg:text-left">
                                <a href="{{ route('hr/create/leave/hr/page') }}" type="button"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600">
                                    <i data-lucide="plus" class="inline-block size-4"></i>
                                    <span class="align-middle">{{ __('messages.add_leave') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Leaves Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">#
                                    </th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.employee') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.leave_type') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.reason') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.days') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.from') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.to') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.status') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $index => $leave)
                                    <tr>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $leaves->firstItem() + $index }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="size-8 rounded-full bg-slate-200 flex items-center justify-center">
                                                    <span
                                                        class="text-sm font-medium">{{ substr($leave->employee_name, 0, 2) }}</span>
                                                </div>
                                                <div>
                                                    <h6 class="text-sm">{{ $leave->employee_name }}</h6>
                                                    <p class="text-xs text-slate-500">{{ $leave->staff_id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $leave->leave_type }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ Str::limit($leave->reason, 30) }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $leave->number_of_day }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $leave->date_from }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $leave->date_to }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            @if ($leave->status == 'Approved')
                                                <span
                                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-green-100 border-green-100 text-green-500 dark:bg-green-400/20 dark:border-transparent">
                                                    {{ __('messages.approved') }}
                                                </span>
                                            @elseif($leave->status == 'Pending')
                                                <span
                                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-yellow-100 border-yellow-100 text-yellow-500 dark:bg-yellow-400/20 dark:border-transparent">
                                                    {{ __('messages.pending') }}
                                                </span>
                                            @elseif($leave->status == 'Rejected')
                                                <span
                                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-red-100 border-red-100 text-red-500 dark:bg-red-400/20 dark:border-transparent">
                                                    {{ __('messages.rejected') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            <div class="flex gap-2">
                                                <a href="{{ route('hr/view/detail/leave', $leave->id) }}"
                                                    class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 text-slate-500 bg-slate-100 hover:text-white hover:bg-slate-500 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-white dark:hover:bg-zink-500"
                                                    title="{{ __('messages.view_details') }}">
                                                    <i data-lucide="eye" class="size-4"></i>
                                                </a>

                                                @if ($leave->status == 'Pending')
                                                    <button type="button"
                                                        onclick="updateLeaveStatus({{ $leave->id }}, 'Approved')"
                                                        class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"
                                                        title="{{ __('messages.approve') }}">
                                                        <i data-lucide="check" class="size-4"></i>
                                                    </button>

                                                    <button type="button"
                                                        onclick="updateLeaveStatus({{ $leave->id }}, 'Rejected')"
                                                        class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"
                                                        title="{{ __('messages.reject') }}">
                                                        <i data-lucide="x" class="size-4"></i>
                                                    </button>
                                                @endif

                                                <button type="button" onclick="deleteLeave({{ $leave->id }})"
                                                    class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"
                                                    title="{{ __('messages.delete') }}">
                                                    <i data-lucide="trash-2" class="size-4"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="px-3.5 py-2.5 text-center border-y border-slate-200 dark:border-zink-500">
                                            {{ __('messages.no_leaves_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $leaves->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" modal-center
        class="fixed flex flex-col hidden transition-all duration-300 -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-full max-w-lg max-h-fit bg-white rounded-lg dark:bg-zink-600 z-[9999]">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
            <h5 class="text-16">{{ __('messages.confirm_delete') }}</h5>
            <button type="button" class="text-2xl text-slate-400 close" data-modal-close="deleteModal">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
        <div class="p-4">
            <p class="text-slate-500 dark:text-zink-200">{{ __('messages.delete_confirmation') }}</p>
        </div>
        <div class="flex justify-end p-4 border-t border-slate-200 dark:border-zink-500">
            <button type="button" data-modal-close="deleteModal"
                class="text-slate-500 bg-white btn hover:text-slate-700 hover:bg-slate-100 focus:text-slate-700 focus:bg-slate-100 active:text-slate-700 active:bg-slate-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-zink-500 dark:focus:bg-zink-500 dark:active:bg-zink-500">{{ __('messages.cancel') }}</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                <button type="submit"
                    class="text-white bg-red-500 border-red-500 btn hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 dark:ring-custom-400/20 ml-2">{{ __('messages.delete') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function updateLeaveStatus(id, status) {
            let statusText = status === 'Approved' ? '{{ __('messages.approve') }}' : '{{ __('messages.reject') }}';
            let confirmText = status === 'Approved' ? '{{ __('messages.yes_approve') }}' :
                '{{ __('messages.yes_reject') }}';
            let confirmColor = status === 'Approved' ? '#28a745' : '#dc3545';
            let message = status === 'Approved' ? '{{ __('messages.want_to_approve') }}' :
                '{{ __('messages.want_to_reject') }}';

            Swal.fire({
                title: '{{ __('messages.are_you_sure') }}',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmText
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('hr/update/leave/status') }}",
                        type: "POST",
                        data: {
                            id: id,
                            status: status,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.response_code == 200) {
                                Swal.fire(
                                    '{{ __('messages.updated') }}',
                                    '{{ __('messages.status_updated') }}',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function() {
                            Swal.fire(
                                '{{ __('messages.error') }}',
                                '{{ __('messages.update_failed') }}',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function deleteLeave(id) {
            Swal.fire({
                title: '{{ __('messages.are_you_sure') }}',
                text: '{{ __('messages.cannot_revert') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('messages.yes_delete') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('hr/delete/leave') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                '{{ __('messages.deleted') }}',
                                '{{ __('messages.record_deleted') }}',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                '{{ __('messages.error') }}',
                                '{{ __('messages.delete_failed') }}',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endsection
