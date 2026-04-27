@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.add_leave_employee') }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.leaves_manage') }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">{{ __('messages.add_leave_employee') }}</li>
                </ul>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
                <div class="xl:col-span-9">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15 grow">{{ __('messages.apply_leave') }}</h6>
                            <form id="applyLeave" action="{{ route('hr/create/leave/employee/save') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="leaveType"
                                                class="inline-block mb-2 text-base font-medium">{{ __('messages.leave_type') }}</label>
                                            <select name="leave_type" id="leave_type"
                                                class="leave_type form-input border-slate-200 focus:outline-none focus:border-custom-500"
                                                data-choices="" data-choices-search-false="">
                                                <option value="">{{ __('messages.select_leave_type') }}</option>
                                                @foreach ($leaveInformation as $info)
                                                    <option value="{{ $info->leave_type }}">{{ $info->leave_type }}</option>
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
                                            <label for="remainingLeaves"
                                                class="inline-block mb-2 text-base font-medium">{{ __('messages.remaining_leaves') }}</label>
                                            <input type="text" name="remaining_leave" id="remaining_leave"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                value="0" readonly="">
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6">
                                        <label for="fromInput"
                                            class="inline-block mb-2 text-base font-medium">{{ __('messages.from') }}</label>
                                        <input type="text" name="date_from" id="date_from"
                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('date_from') is-invalid @enderror "
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
                                            class="inline-block mb-2 text-base font-medium">{{ __('messages.to') }}</label>
                                        <input type="text" name="date_to" id="date_to"
                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('date_to') is-invalid @enderror "
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
                                                class="inline-block mb-2 text-base font-medium">{{ __('messages.reason') }}</label>
                                            <textarea name="reason"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('reason') is-invalid @enderror"
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
                                        class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-700 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">{{ __('messages.reset') }}</button>
                                    <button type="submit" id="apply_leave"
                                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">{{ __('messages.apply_leave_button') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15">{{ __('messages.leave_information') }} ({{ date('Y') }})</h6>
                            <div class="overflow-x-auto">
                                <table class="w-full mb-0 text-sm">
                                    <thead>
                                        <tr class="text-slate-500 dark:text-zink-200 border-b border-slate-200 dark:border-zink-500">
                                            <th class="px-3.5 py-2.5 ltr:text-left rtl:text-right font-semibold">{{ __('messages.leave_type') }}</th>
                                            <th class="px-3.5 py-2.5 ltr:text-right rtl:text-left font-semibold">{{ __('messages.duration') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveInformation as $key => $value)
                                            <tr class="border-b border-slate-200 dark:border-zink-500 last:border-0">
                                                <td class="px-3.5 py-2.5 text-slate-600 dark:text-zink-200">
                                                    {{ $value->leave_type }}</td>
                                                <td class="px-3.5 py-2.5 ltr:text-right rtl:text-left font-medium">
                                                    {{ $value->leave_days }}</td>
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
        // Define the URL for the AJAX request
        var url = "{{ route('hr/get/information/leave') }}";

        $(document).ready(function() {
            // Initialize Choices
            var leaveTypeChoices;
            if (typeof Choices !== 'undefined') {
                const leaveTypeEl = document.getElementById('leave_type');
                if (leaveTypeEl) {
                    leaveTypeChoices = new Choices(leaveTypeEl, {
                        searchEnabled: false,
                        itemSelectText: '',
                    });
                }
            }

            function handleLeaveTypeChange() {
                var leaveType = $('#leave_type').val();
                var numberOfDay = $('#number_of_day').val() || 0;

                if (leaveType) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            leave_type: leaveType,
                            number_of_day: numberOfDay,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.response_code == 200) {
                                $('#remaining_leave').val(data.leave_type);
                                if (data.leave_type < 0) {
                                    $('#apply_leave').prop('disabled', true);
                                } else {
                                    $('#apply_leave').prop('disabled', false);
                                }
                            }
                        },
                        error: function() {
                            console.error('Error fetching leave info');
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
                    handleLeaveTypeChange();
                }
            }

            function generateLeaveDates(numDays, startDate) {
                var container = $('#leave_dates_container');
                container.empty();

                var leaveType = $('#leave_type').val();
                // Show details only for Annual Leave (الإجازة السنوية)
                if (leaveType !== 'الإجازة السنوية') {
                    calculateTotalDays();
                    return;
                }

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

                calculateTotalDays();
                $('.leave-day-select').on('change', calculateTotalDays);
            }

            function calculateTotalDays() {
                var totalDays = 0;
                var leaveType = $('#leave_type').val();

                if (leaveType === 'الإجازة السنوية' && $('.leave-day-select').length > 0) {
                    $('.leave-day-select').each(function() {
                        var value = $(this).val();
                        if (value === 'Public Holiday' || value === 'Off Schedule') {
                            totalDays += 0;
                        } else if (value.includes('Half-Day')) {
                            totalDays += 0.5;
                        } else {
                            totalDays += 1;
                        }
                    });
                } else {
                    var dateFrom = new Date($('#date_from').val());
                    var dateTo = new Date($('#date_to').val());
                    if (!isNaN(dateFrom) && !isNaN(dateTo) && dateFrom <= dateTo) {
                        totalDays = Math.ceil((dateTo - dateFrom) / (1000 * 3600 * 24)) + 1;
                    }
                }

                $('#number_of_day').val(totalDays);
                handleLeaveTypeChange();
            }

            // Event listeners
            $(document).on('change', '#leave_type', function() {
                countLeaveDays();
            });
            $(document).on('change', '#date_from, #date_to', countLeaveDays);

            $(document).on('click', '#reset_btn', function(e) {
                e.preventDefault();
                $('#leave_dates_container').empty();
                $('#number_of_day').val('0');
                $('#date_from').val('');
                $('#date_to').val('');
                $('#remaining_leave').val('0');
                if (leaveTypeChoices) {
                    leaveTypeChoices.setChoiceByValue('');
                } else {
                    $('#leave_type').val('');
                }
            });
        });
    </script>
@endsection
@endsection
