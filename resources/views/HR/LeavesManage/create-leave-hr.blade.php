@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.add_leave_hr') }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.leaves_manage') }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        {{ __('messages.add_leave_hr') }}
                    </li>
                </ul>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
                <div class="xl:col-span-9">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15 grow">{{ __('messages.add_leave') }}</h6>
                            <form id="applyLeave" action="{{ route('hr/create/leave/hr/save') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="employee_name"
                                                class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.employee') }}</label>
                                            <select name="employee_name" id="employee_name"
                                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                                data-choices="">
                                                <option value="">{{ __('messages.select_employee') }}</option>
                                                @foreach ($users as $key => $user)
                                                    <option value="{{ $user->name }}" data-id="{{ $user->user_id }}"
                                                        data-email="{{ $user->email }}">
                                                        {{ $user->name }} - {{ $user->user_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="employeeId"
                                                class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.employee_id') }}</label>
                                            <input type="text" id="employeeId" name="employee_id"
                                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                                readonly="">
                                        </div>
                                    </div>

                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="leave_type"
                                                class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.leave_type') }}</label>
                                            <select name="leave_type" id="leave_type"
                                                class="leave_type form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                                data-choices="" data-choices-search-false="">
                                                <option value="">{{ __('messages.select_leave_type') }}</option>
                                                @foreach ($leaveInformation as $info)
                                                    <option value="{{ $info->leave_type }}"
                                                        data-days="{{ $info->leave_days }}">
                                                        {{ $info->leave_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('leave_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="remaining_leave"
                                                class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.remaining_leaves') }}</label>
                                            <input type="text" name="remaining_leave" id="remaining_leave"
                                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                                value="0" readonly="">
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6">
                                        <label for="date_from"
                                            class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.from') }}</label>
                                        <input type="text" name="date_from" id="date_from"
                                            class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 @error('date_from') is-invalid @enderror"
                                            placeholder="{{ __('messages.select_date') }}" data-provider="flatpickr"
                                            data-date-format="d M, Y">
                                        @error('date_from')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="xl:col-span-6">
                                        <label for="date_to"
                                            class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.to') }}</label>
                                        <input type="text" name="date_to" id="date_to"
                                            class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 @error('date_to') is-invalid @enderror"
                                            placeholder="{{ __('messages.select_date') }}" data-provider="flatpickr"
                                            data-date-format="d M, Y">
                                        @error('date_to')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="xl:col-span-12" id="leave_dates_container"></div>

                                    <div class="xl:col-span-12">
                                        <div>
                                            <label for="number_of_day"
                                                class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.number_of_days') }}</label>
                                            <input type="text" name="number_of_day" id="number_of_day"
                                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                                value="0" readonly="">
                                        </div>
                                    </div>

                                    <div class="md:col-span-2 xl:col-span-12">
                                        <div>
                                            <label for="reason"
                                                class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.reason') }}</label>
                                            <textarea name="reason"
                                                class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 @error('reason') is-invalid @enderror"
                                                rows="3"></textarea>
                                            @error('reason')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="reset" id="reset_btn"
                                        class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-700 dark:text-zink-100 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">{{ __('messages.reset') }}</button>
                                    <button type="submit" id="apply_leave"
                                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">{{ __('messages.add_leave_button') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-3">
                    <div class="card" id="employeeLeaveInfo" style="display: none;">
                        <div class="card-body">
                            <h6 class="mb-4 text-15 dark:text-zink-100">{{ __('messages.employee_leave_info') }} (<span
                                    id="selectedEmployeeName"></span>)
                            </h6>
                            <div id="leaveInfoContent">
                                <p class="text-center text-slate-500 dark:text-zink-300">{{ __('messages.select_employee_to_view') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="mb-4 text-15 dark:text-zink-100">{{ __('messages.leave_information') }} ({{ date('Y') }})</h6>
                            <div>
                                <table class="w-full mb-0">
                                    <tbody>
                                        @foreach ($leaveInformation as $key => $value)
                                            <tr>
                                                <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent dark:text-zink-100">
                                                    {{ $value->leave_type }}
                                                </td>
                                                <th class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent dark:text-zink-100">
                                                    {{ $value->leave_days }}
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

@section('script')
    <script>
        var url = "{{ route('hr/get/information/leave') }}";
        var employeeInfoUrl = "{{ route('hr/get/employee/leave/info') }}";

        // Handle employee selection
        $('#employee_name').on('change', function() {
            var selected = $(this).find(':selected');
            var employeeId = selected.data('id');
            var employeeName = selected.val();

            $('#employeeId').val(employeeId || '');

            if (employeeId) {
                $('#selectedEmployeeName').text(employeeName);
                $('#employeeLeaveInfo').show();
                $('#leaveInfoContent').html('<p class="text-center dark:text-zink-300">{{ __('messages.loading') }}</p>');

                $.ajax({
                    url: employeeInfoUrl,
                    type: 'POST',
                    data: {
                        staff_id: employeeId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.response_code == 200) {
                            var html = '<table class="w-full mb-0">';
                            $.each(response.data, function(key, value) {
                                html += '<tr>' +
                                    '<td class="px-3.5 py-2.5 border-y border-transparent dark:text-zink-100">' +
                                    key + '</td>' +
                                    '<th class="px-3.5 py-2.5 border-y border-transparent dark:text-zink-100">' +
                                    value + ' {{ __('messages.days') }}</th>' +
                                    '</tr>';
                            });
                            html += '</table>';
                            $('#leaveInfoContent').html(html);

                            // Reset remaining leave field when employee changes
                            $('#remaining_leave').val('0');
                            $('#leave_type').val('');
                        } else {
                            $('#leaveInfoContent').html(
                                '<p class="text-center text-red-500 dark:text-red-400">{{ __('messages.failed_to_load') }}</p>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        $('#leaveInfoContent').html(
                            '<p class="text-center text-red-500 dark:text-red-400">{{ __('messages.error_loading') }}</p>'
                        );
                    }
                });
            } else {
                $('#employeeLeaveInfo').hide();
                $('#leaveInfoContent').html(
                    '<p class="text-center text-slate-500 dark:text-zink-300">{{ __('messages.select_employee_to_view') }}</p>');
            }
        });

        function handleLeaveTypeChange() {
            var leaveType = $('#leave_type').val();
            var employeeId = $('#employeeId').val();
            var numberOfDay = $('#number_of_day').val() || 0;

            if (!employeeId) {
                toastr.warning('{{ __('messages.select_employee_to_view') }}');
                return;
            }

            if (leaveType && employeeId) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        leave_type: leaveType,
                        staff_id: employeeId,
                        number_of_day: numberOfDay,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.response_code == 200) {
                            $('#remaining_leave').val(data.leave_type);

                            // Disable submit button if no remaining leaves
                            if (data.leave_type <= 0) {
                                $('#apply_leave').prop('disabled', true);
                                toastr.warning('{{ __('messages.no_remaining_leaves') }}');
                            } else {
                                $('#apply_leave').prop('disabled', false);
                            }
                        }
                    },
                    error: function() {
                        toastr.error('{{ __('messages.error_loading') }}');
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

                // Generate leave dates and day selects
                generateLeaveDates(numDays, dateFrom);

                // Check remaining leave balance
                handleLeaveTypeChange();
            }
        }

        function generateLeaveDates(numDays, startDate) {
            var container = $('#leave_dates_container');
            container.empty();

            if (numDays > 1) {
                container.append('<h6 class="mb-3 text-15 dark:text-zink-100">{{ __('messages.leave_dates_details') }}</h6>');
            }

            for (let i = 0; i < numDays; i++) {
                let currentDate = new Date(startDate);
                currentDate.setDate(startDate.getDate() + i);
                let formattedDate = currentDate.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                let dateHtml = `
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12 mb-4">
                    <div class="xl:col-span-6">
                        <label class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.leave_date') }} ${i+1}</label>
                        <input type="text" name="leave_date[]"
                            class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                            value="${formattedDate}" readonly>
                    </div>
                    <div class="xl:col-span-6">
                        <label class="inline-block mb-2 text-base font-medium dark:text-zink-200">{{ __('messages.leave_day') }} ${i+1}</label>
                        <select name="select_leave_day[]" class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500 leave-day-select">
                            <option value="Full-Day Leave">{{ __('messages.full_day_leave') }}</option>
                            <option value="Half-Day Morning Leave">{{ __('messages.half_day_morning') }}</option>
                            <option value="Half-Day Afternoon Leave">{{ __('messages.half_day_afternoon') }}</option>
                            <option value="Public Holiday">{{ __('messages.public_holiday') }}</option>
                            <option value="Off Schedule">{{ __('messages.off_schedule') }}</option>
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
                if (value.includes('Half-Day')) {
                    totalDays += 0.5;
                } else {
                    totalDays += 1;
                }
            });

            $('#number_of_day').val(totalDays);

            // Update remaining leave based on total days
            handleLeaveTypeChange();
        }

        // Event listeners
        $(document).on('change', '#leave_type', handleLeaveTypeChange);
        $(document).on('change', '#date_from, #date_to', countLeaveDays);

        // Reset form
        $(document).on('click', '#reset_btn', function(e) {
            e.preventDefault();
            $('#leave_dates_container').empty();
            $('#number_of_day').val('0');
            $('#date_from').val('');
            $('#date_to').val('');
            $('#leave_type').val('');
            $('#remaining_leave').val('0');
            $('#employee_name').val('').trigger('change');
            $('#employeeId').val('');
            $('#employeeLeaveInfo').hide();
            $('#apply_leave').prop('disabled', false);
            $('#leaveInfoContent').html(
                '<p class="text-center text-slate-500 dark:text-zink-300">{{ __('messages.select_employee_to_view') }}</p>');
        });

        // Initialize Choices for select elements if needed
        if (typeof Choices !== 'undefined') {
            new Choices('#employee_name', {
                searchEnabled: true,
                itemSelectText: '',
            });
            new Choices('#leave_type', {
                searchEnabled: false,
                itemSelectText: '',
            });
        }
    </script>
@endsection
@endsection