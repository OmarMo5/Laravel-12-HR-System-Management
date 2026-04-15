@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.view_detail_leave') ?? 'View Detail Leave' }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.leaves_manage') ?? 'Leaves Manage' }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">{{ __('messages.view_detail_leave') ?? 'View Detail Leave' }}</li>
                </ul>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
                <div class="xl:col-span-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-4">
                                <h6 class="text-15">{{ __('messages.leave_details') ?? 'Leave Details' }}</h6>
                                <span
                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded
                                    @if ($leaveDetail->status == 'Approved') bg-green-100 text-green-500
                                    @elseif($leaveDetail->status == 'Pending') bg-yellow-100 text-yellow-500
                                    @elseif($leaveDetail->status == 'Rejected') bg-red-100 text-red-500 @endif">
                                    {{ __("messages.{$leaveDetail->status}") ?? $leaveDetail->status }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                <div class="xl:col-span-6">
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_name') ?? 'Employee Name' }}</label>
                                        <input type="text"
                                            class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                            value="{{ $leaveDetail->employee_name }}" disabled>
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_id') ?? 'Employee ID' }}</label>
                                        <input type="text"
                                            class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                            value="{{ $leaveDetail->staff_id }}" disabled>
                                    </div>
                                </div>

                                <div class="xl:col-span-6">
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.leave_type') ?? 'Leave Type' }}</label>
                                        <input type="text"
                                            class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                            value="{{ $leaveDetail->leave_type }}" disabled>
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.remaining_leaves') ?? 'Remaining Leaves' }}</label>
                                        <input type="text"
                                            class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                            value="{{ $leaveDetail->remaining_leave }}" disabled>
                                    </div>
                                </div>

                                <div class="xl:col-span-6">
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.from') ?? 'From' }}</label>
                                    <input type="text"
                                        class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                        value="{{ $leaveDetail->date_from }}" disabled>
                                </div>
                                <div class="xl:col-span-6">
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.to') ?? 'To' }}</label>
                                    <input type="text"
                                        class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                        value="{{ $leaveDetail->date_to }}" disabled>
                                </div>

                                @if (is_array($leaveDate) && count($leaveDate) > 0)
                                    @foreach ($leaveDate as $key => $date)
                                        <div class="xl:col-span-6">
                                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.leave_date') ?? 'Leave Date' }}
                                                {{ $key + 1 }}</label>
                                            <input type="text"
                                                class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                                value="{{ $date }}" disabled>
                                        </div>
                                        <div class="xl:col-span-6">
                                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.leave_day') ?? 'Leave Day' }}
                                                {{ $key + 1 }}</label>
                                            <input type="text"
                                                class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                                value="{{ $leaveDay[$key] ?? '' }}" disabled>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="xl:col-span-12">
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.number_of_days') ?? 'Number of Days' }}</label>
                                        <input type="text"
                                            class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                            value="{{ $leaveDetail->number_of_day }}" disabled>
                                    </div>
                                </div>

                                <div class="md:col-span-2 xl:col-span-12">
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.reason') ?? 'Reason' }}</label>
                                        <textarea
                                            class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                            rows="3" disabled>{{ $leaveDetail->reason }}</textarea>
                                    </div>
                                </div>

                                @if ($leaveDetail->approved_by)
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.approved_rejected_by') ?? 'Approved/Rejected By' }}</label>
                                            <input type="text"
                                                class="form-input border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-700 text-slate-900 dark:text-white focus:outline-none focus:border-custom-500"
                                                value="{{ $leaveDetail->approved_by }}" disabled>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end gap-2 mt-4">
                                @php
                                    $userRole = auth()->user()->role_name ?? '';
                                    $isHR = in_array($userRole, ['HR', 'Admin']);
                                    $isEmployee = $userRole == 'Employee';
                                    $canEditDelete = false;
                                    
                                    if ($isHR) {
                                        $canEditDelete = true;
                                    } elseif ($isEmployee && $leaveDetail->status == 'Pending') {
                                        $canEditDelete = true;
                                    }
                                @endphp

                                @if ($canEditDelete)
                                    <a href="{{ route('hr/leave/edit', $leaveDetail->id) }}"
                                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600">
                                        <i data-lucide="edit" class="inline-block size-4 mr-1"></i> {{ __('messages.edit') ?? 'Edit' }}
                                    </a>
                                    
                                    <button type="button" onclick="deleteLeave({{ $leaveDetail->id }})"
                                        class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100">
                                        <i data-lucide="trash-2" class="inline-block size-4 mr-1"></i> {{ __('messages.delete') ?? 'Delete' }}
                                    </button>
                                @endif

                                <a href="{{ $isHR ? route('hr/leave/hr/page') : route('hr/leave/employee/page') }}"
                                    class="text-slate-500 bg-white btn hover:text-slate-700">
                                    {{ __('messages.back_to_list') ?? 'Back to List' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15">{{ __('messages.employee_leave_info') ?? 'Employee Leave Information' }} ({{ date('Y') }})</h6>
                            <div>
                                <table class="w-full mb-0">
                                    <tbody>
                                        @foreach ($leaveInformation as $key => $value)
                                            <tr>
                                                <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent">
                                                    {{ $value->leave_type }}</td>
                                                <th class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent">
                                                    @php
                                                        $usedLeaves = \App\Models\Leave::where(
                                                            'staff_id',
                                                            $leaveDetail->staff_id,
                                                        )
                                                            ->where('leave_type', $value->leave_type)
                                                            ->whereIn('status', ['Approved', 'Pending'])
                                                            ->sum('number_of_day');
                                                        $remaining = $value->leave_days - $usedLeaves;
                                                    @endphp
                                                    {{ max(0, $remaining) }} / {{ $value->leave_days }}
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    function deleteLeave(id) {
        Swal.fire({
            title: '{{ __("messages.are_you_sure") ?? "Are you sure?" }}',
            text: '{{ __("messages.cannot_revert") ?? "You won\'t be able to revert this!" }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("messages.yes_delete") ?? "Yes, delete it!" }}',
            cancelButtonText: '{{ __("messages.cancel") ?? "Cancel" }}'
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
                            '{{ __("messages.deleted") ?? "Deleted!" }}',
                            '{{ __("messages.record_deleted") ?? "Leave record has been deleted" }}',
                            'success'
                        ).then(() => {
                            @php
                                $redirectRoute = in_array(auth()->user()->role_name ?? '', ['HR', 'Admin']) 
                                    ? route('hr/leave/hr/page') 
                                    : route('hr/leave/employee/page');
                            @endphp
                            window.location.href = '{{ $redirectRoute }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON?.message || '{{ __("messages.delete_failed") ?? "Failed to delete record" }}';
                        Swal.fire(
                            '{{ __("messages.error") ?? "Error!" }}',
                            errorMsg,
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endsection