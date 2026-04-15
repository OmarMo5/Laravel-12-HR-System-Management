@extends('layouts.master')
@section('content')
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">{{ __('messages.edit_leave') ?? 'Edit Leave' }}</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.leaves_manage') ?? 'Leaves Manage' }}</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">{{ __('messages.edit_leave') ?? 'Edit Leave' }}</li>
            </ul>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
            <div class="xl:col-span-9">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 text-15 grow">{{ __('messages.edit_leave') ?? 'Edit Leave' }}</h6>
                        <form action="{{ route('hr/leave/update', $leave->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                
                                @if(auth()->user()->role_name == 'HR' || auth()->user()->role_name == 'Admin')
                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="employee_name" class="inline-block mb-2 text-base font-medium">{{ __('messages.employee') ?? 'Employee' }}</label>
                                        <select name="employee_name" id="employee_name" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 focus:outline-none focus:border-custom-500" data-choices>
                                            <option value="">{{ __('messages.select_employee') ?? 'Select Employee' }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->name }}" data-id="{{ $user->user_id }}" {{ $leave->employee_name == $user->name ? 'selected' : '' }}>
                                                    {{ $user->name }} - {{ $user->user_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="staff_id" id="staff_id" value="{{ $leave->staff_id }}">
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="employeeId" class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_id') ?? 'Employee ID' }}</label>
                                        <input type="text" id="employeeId" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500" value="{{ $leave->staff_id }}" readonly>
                                    </div>
                                </div>
                                @else
                                <input type="hidden" name="staff_id" value="{{ $leave->staff_id }}">
                                @endif

                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="leave_type" class="inline-block mb-2 text-base font-medium">{{ __('messages.leave_type') ?? 'Leave Type' }}</label>
                                        <select name="leave_type" id="leave_type" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 focus:outline-none focus:border-custom-500" data-choices data-choices-search-false>
                                            <option value="">{{ __('messages.select_leave_type') ?? 'Select Leave Type' }}</option>
                                            @foreach ($leaveInformation as $info)
                                                <option value="{{ $info->leave_type }}" data-days="{{ $info->leave_days }}" {{ $leave->leave_type == $info->leave_type ? 'selected' : '' }}>
                                                    {{ $info->leave_type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="remaining_leave" class="inline-block mb-2 text-base font-medium">{{ __('messages.remaining_leaves') ?? 'Remaining Leaves' }}</label>
                                        <input type="text" name="remaining_leave" id="remaining_leave" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500" value="0" readonly>
                                    </div>
                                </div>

                                <div class="xl:col-span-6">
                                    <label for="date_from" class="inline-block mb-2 text-base font-medium">{{ __('messages.from') ?? 'From' }}</label>
                                    <input type="text" name="date_from" id="date_from" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 @error('date_from') is-invalid @enderror" value="{{ $leave->date_from }}" data-provider="flatpickr" data-date-format="d M, Y">
                                </div>

                                <div class="xl:col-span-6">
                                    <label for="date_to" class="inline-block mb-2 text-base font-medium">{{ __('messages.to') ?? 'To' }}</label>
                                    <input type="text" name="date_to" id="date_to" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 @error('date_to') is-invalid @enderror" value="{{ $leave->date_to }}" data-provider="flatpickr" data-date-format="d M, Y">
                                </div>

                                <div class="xl:col-span-12" id="leave_dates_container"></div>

                                <div class="xl:col-span-12">
                                    <div>
                                        <label for="number_of_day" class="inline-block mb-2 text-base font-medium">{{ __('messages.number_of_days') ?? 'Number of Days' }}</label>
                                        <input type="text" name="number_of_day" id="number_of_day" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500" value="{{ $leave->number_of_day }}" readonly>
                                    </div>
                                </div>

                                <div class="md:col-span-2 xl:col-span-12">
                                    <div>
                                        <label for="reason" class="inline-block mb-2 text-base font-medium">{{ __('messages.reason') ?? 'Reason' }}</label>
                                        <textarea name="reason" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500" rows="3">{{ $leave->reason }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 mt-4">
                                <a href="{{ auth()->user()->role_name == 'HR' || auth()->user()->role_name == 'Admin' ? route('hr/leave/hr/page') : route('hr/leave/employee/page') }}" class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100">{{ __('messages.cancel') ?? 'Cancel' }}</a>
                                <button type="submit" class="text-white btn bg-custom-500 border-custom-500">{{ __('messages.update_leave') ?? 'Update Leave' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 text-15">{{ __('messages.leave_information') ?? 'Leave Information' }} ({{ date('Y') }})</h6>
                        <div>
                            <table class="w-full mb-0">
                                <tbody>
                                    @foreach ($leaveInformation as $key => $value)
                                    <tr>
                                        <td class="px-3.5 py-2.5">{{ $value->leave_type }}</td>
                                        <th class="px-3.5 py-2.5">{{ $value->leave_days }}</th>
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

@section('script')
<script>
    var url = "{{ route('hr/get/information/leave') }}";
    var currentLeaveId = {{ $leave->id }};
    var currentLeaveType = "{{ $leave->leave_type }}";
    var currentStaffId = "{{ $leave->staff_id }}";
    
    var existingLeaveDates = @json($leaveDate ?? []);
    var existingLeaveDays = @json($leaveDay ?? []);
    
    function generateLeaveDates(numDays, startDate) {
        var container = $('#leave_dates_container');
        container.empty();
        
        if (numDays > 1) {
            container.append('<h6 class="mb-3 text-15">{{ __('messages.leave_dates_details') ?? 'Leave Days Details' }}</h6>');
        }
        
        for (let i = 0; i < numDays; i++) {
            let currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + i);
            let formattedDate = currentDate.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            
            let existingValue = (existingLeaveDates && existingLeaveDates[i]) ? existingLeaveDates[i] : formattedDate;
            let existingDayValue = (existingLeaveDays && existingLeaveDays[i]) ? existingLeaveDays[i] : 'Full-Day Leave';
            
            let dateHtml = `
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12 mb-4">
                <div class="xl:col-span-6">
                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.leave_date') ?? 'Leave Date' }} ${i+1}</label>
                    <input type="text" name="leave_date[]" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500" value="${existingValue}" readonly>
                </div>
                <div class="xl:col-span-6">
                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.leave_day') ?? 'Leave Day' }} ${i+1}</label>
                    <select name="select_leave_day[]" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 leave-day-select">
                        <option value="Full-Day Leave" ${existingDayValue == 'Full-Day Leave' ? 'selected' : ''}>{{ __('messages.full_day_leave') ?? 'Full-Day Leave' }}</option>
                        <option value="Half-Day Morning Leave" ${existingDayValue == 'Half-Day Morning Leave' ? 'selected' : ''}>{{ __('messages.half_day_morning') ?? 'Half-Day Morning Leave' }}</option>
                        <option value="Half-Day Afternoon Leave" ${existingDayValue == 'Half-Day Afternoon Leave' ? 'selected' : ''}>{{ __('messages.half_day_afternoon') ?? 'Half-Day Afternoon Leave' }}</option>
                        <option value="Public Holiday" ${existingDayValue == 'Public Holiday' ? 'selected' : ''}>{{ __('messages.public_holiday') ?? 'Public Holiday' }}</option>
                        <option value="Off Schedule" ${existingDayValue == 'Off Schedule' ? 'selected' : ''}>{{ __('messages.off_schedule') ?? 'Off Schedule' }}</option>
                    </select>
                </div>
            </div>
            `;
            container.append(dateHtml);
        }
        
        $('.leave-day-select').on('change', calculateTotalDays);
    }
    
    function calculateTotalDays() {
        var totalDays = 0;
        $('.leave-day-select').each(function() {
            var value = $(this).val();
            if (value && value.includes('Half-Day')) {
                totalDays += 0.5;
            } else {
                totalDays += 1;
            }
        });
        $('#number_of_day').val(totalDays);
        updateRemainingLeave(totalDays);
    }
    
    function updateRemainingLeave(numDays) {
        var leaveType = $('#leave_type').val();
        var staffId = currentStaffId;
        
        if (leaveType && staffId) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    leave_type: leaveType,
                    staff_id: staffId,
                    number_of_day: numDays,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(data) {
                    if (data.response_code == 200) {
                        $('#remaining_leave').val(data.leave_type);
                        if (data.leave_type <= 0 && numDays > 0) {
                            toastr.warning('{{ __('messages.no_remaining_leaves') ?? 'No remaining leaves for this type!' }}');
                        }
                    }
                }
            });
        }
    }
    
    function countLeaveDays() {
        var dateFrom = new Date($('#date_from').val());
        var dateTo = new Date($('#date_to').val());
        
        if (!isNaN(dateFrom) && !isNaN(dateTo) && dateFrom <= dateTo) {
            var numDays = Math.ceil((dateTo - dateFrom) / (1000 * 3600 * 24)) + 1;
            $('#number_of_day').val(numDays);
            generateLeaveDates(numDays, dateFrom);
            updateRemainingLeave(numDays);
        }
    }
    
    function handleLeaveTypeChange() {
        var leaveType = $('#leave_type').val();
        var staffId = currentStaffId;
        var numberOfDay = $('#number_of_day').val() || 0;
        
        if (leaveType && staffId) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    leave_type: leaveType,
                    staff_id: staffId,
                    number_of_day: numberOfDay,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(data) {
                    if (data.response_code == 200) {
                        $('#remaining_leave').val(data.leave_type);
                    }
                }
            });
        }
    }
    
    $(document).ready(function() {
        var dateFrom = new Date($('#date_from').val());
        var dateTo = new Date($('#date_to').val());
        if (!isNaN(dateFrom) && !isNaN(dateTo)) {
            var numDays = Math.ceil((dateTo - dateFrom) / (1000 * 3600 * 24)) + 1;
            generateLeaveDates(numDays, dateFrom);
        }
        handleLeaveTypeChange();
    });
    
    $(document).on('change', '#leave_type', handleLeaveTypeChange);
    $(document).on('change', '#date_from, #date_to', countLeaveDays);
    
    @if(auth()->user()->role_name == 'HR' || auth()->user()->role_name == 'Admin')
    $('#employee_name').on('change', function() {
        var selected = $(this).find(':selected');
        var employeeId = selected.data('id');
        $('#employeeId').val(employeeId || '');
        $('#staff_id').val(employeeId || '');
        currentStaffId = employeeId || '';
        if (employeeId) {
            handleLeaveTypeChange();
        }
    });
    @endif
</script>
@endsection
@endsection