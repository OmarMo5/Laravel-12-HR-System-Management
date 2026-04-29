{{-- المسار: resources/views/hr/employee/index.blade.php --}}
@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.employee_list') }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.hr_management') }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        {{ __('messages.employee_list') }}
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                        <h6 class="text-15">{{ __('messages.employee_list') }}</h6>
                        <div class="flex items-center gap-3">
                            {{-- Search --}}
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="{{ __('messages.search') }}..."
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 pl-8"
                                    value="{{ request('search') }}">
                                <i data-lucide="search" class="absolute size-4 left-2.5 top-2.5 text-slate-400"></i>
                            </div>

                            {{-- Export --}}
                            <div>
                                <a href="{{ route('hr/employee/export', ['search' => request('search')]) }}"
                                    class="text-white btn bg-green-500 border-green-500 hover:text-white hover:bg-green-600 hover:border-green-600 focus:text-white focus:bg-green-600 focus:border-green-600 focus:ring focus:ring-green-100 active:text-white active:bg-green-600 active:border-green-600 active:ring active:ring-green-100 dark:ring-green-400/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-download inline-block size-4 mr-1">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" x2="12" y1="15" y2="3"></line>
                                    </svg>
                                    <span class="align-middle">{{ __('messages.export') }}</span>
                                </a>
                            </div>

                            {{-- زر Import --}}
                            <!-- <div>
                                <button data-modal-target="importEmployeeModal" type="button"
                                    class="text-white btn bg-green-500 border-green-500 hover:text-white hover:bg-green-600 hover:border-green-600 focus:text-white focus:bg-green-600 focus:border-green-600 focus:ring focus:ring-green-100 active:text-white active:bg-green-600 active:border-green-600 active:ring active:ring-green-100 dark:ring-green-400/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload inline-block size-4 mr-1">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" x2="12" y1="3" y2="15"></line>
                                    </svg>
                                    <span class="align-middle">{{ __('messages.import') }}</span>
                                </button>
                            </div> -->

                            {{-- Add Employee --}}
                            <div>
                                <button data-modal-target="addEmployeeModal" type="button"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-plus inline-block size-4">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5v14"></path>
                                    </svg>
                                    <span class="align-middle">{{ __('messages.add_employee') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-100 dark:bg-zink-600 border-b border-slate-200 dark:border-zink-500">
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.no') }}</th>
                                    <th hidden>ID</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.employee_id') }}</th>
                                    <th hidden>Photo</th>
                                    <th hidden>Location</th>
                                    <th hidden>Join Date</th>
                                    <th hidden>Status</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.name') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.job_type') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.email') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.phone') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.experience') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.join_date') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.last_login') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.role') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.designation') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.department') }}</th>
                                    <th
                                        class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.gender') }}</th>
                                    <th
                                        class="px-4 py-3 text-center text-sm font-semibold text-slate-700 dark:text-zink-100">
                                        {{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employeeList as $key => $employee)
                                    @php
                                        $fullName = $employee->name;
                                        $parts = explode(' ', $fullName);
                                        $initials = '';
                                        foreach ($parts as $part) {
                                            $initials .= strtoupper(substr($part, 0, 1));
                                        }

                                        $joinDate = \Carbon\Carbon::parse($employee->join_date);
                                        $now = \Carbon\Carbon::now();
                                        $years = $now->year - $joinDate->year;
                                        $months = $now->month - $joinDate->month;
                                        $days = $now->day - $joinDate->day;

                                        if ($days < 0) {
                                            $months--;
                                            $days += $joinDate->copy()->addYears($years)->addMonths($months)->daysInMonth;
                                        }
                                        if ($months < 0) {
                                            $years--;
                                            $months += 12;
                                        }

                                        $joinDateText = '';
                                        if ($years > 0)
                                            $joinDateText .= $years . ' ' . ($years == 1 ? __('messages.year') : __('messages.years'));
                                        if ($months > 0) {
                                            if ($joinDateText)
                                                $joinDateText .= ' ' . __('messages.and') . ' ';
                                            $joinDateText .= $months . ' ' . ($months == 1 ? __('messages.month') : __('messages.months'));
                                        }
                                        if ($days > 0 && $years == 0) {
                                            if ($joinDateText)
                                                $joinDateText .= ' ' . __('messages.and') . ' ';
                                            $joinDateText .= $days . ' ' . ($days == 1 ? __('messages.day') : __('messages.days'));
                                        }
                                        if (empty($joinDateText))
                                            $joinDateText = __('messages.today');
                                    @endphp
                                    <tr class="border-b border-slate-200 dark:border-zink-500 hover:bg-slate-50 dark:hover:bg-zink-600 transition-colors">
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200">{{ $employeeList->firstItem() + $key }}</td>
                                        <td hidden class="id">{{ $employee->id }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ url('page/account/' . $employee->user_id) }}"
                                                class="transition-all duration-150 ease-linear text-custom-500 hover:text-custom-600 user_id">
                                                {{ $employee->user_id }}
                                            </a>
                                        </td>
                                        <td hidden class="photo">{{ $employee->avatar }}</td>
                                        <td hidden class="location">{{ $employee->location }}</td>
                                        <td hidden class="join_date">{{ $employee->join_date }}</td>
                                        <td hidden class="statuss">{{ $employee->status }}</td>
                                        <td hidden class="raw_position">{{ $employee->position }}</td>
                                        <td hidden class="raw_department">{{ $employee->department }}</td>
                                        <td hidden class="raw_role_name">{{ $employee->role_name }}</td>
                                        <td hidden class="raw_designation">{{ $employee->designation }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center justify-center font-medium rounded-full size-8 shrink-0 bg-slate-200 text-slate-800 dark:text-zink-50 dark:bg-zink-600">
                                                    @if (!empty($employee->avatar))
                                                        <img src="{{ URL::to('assets/images/user/' . $employee->avatar) }}"
                                                            alt="" class="w-8 h-8 rounded-full object-cover">
                                                    @else
                                                        <span class="text-xs">{{ $initials }}</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ url('page/account/' . $employee->user_id) }}"
                                                        class="name font-medium text-slate-700 dark:text-zink-100 hover:text-custom-500">
                                                        {{ $employee->name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 position">
                                            @switch($employee->position)
                                                @case('Full-Time Onsite')
                                                    {{ __('messages.full_time') }}
                                                @break

                                                @case('Part-Time')
                                                    {{ __('messages.part_time') }}
                                                @break

                                                @case('Remote')
                                                    {{ __('messages.remote') }}
                                                @break

                                                @case('Hybrid Work')
                                                    {{ __('messages.hybrid') }}
                                                @break

                                                @case('Contractor')
                                                    {{ __('messages.contractor') }}
                                                @break

                                                @default
                                                    {{ $employee->position }}
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 email">
                                            {{ $employee->email }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 phone_number">
                                            {{ $employee->phone_number }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 experience">
                                            {{ $employee->experience }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200">
                                            {{ \Carbon\Carbon::parse($employee->join_date)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200">
                                            {{ \Carbon\Carbon::parse($employee->last_login)->diffForHumans() }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 role_name">
                                            {{ $employee->role_name }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 designation">
                                            {{ $employee->designation }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 department">
                                            {{ $employee->department }}</td>
                                        <td class="px-4 py-3">
                                            @if ($employee->status === 'Male')
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">{{ __('messages.male') }}</span>
                                            @elseif ($employee->status === 'Female')
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-600 dark:bg-pink-500/20 dark:text-pink-400">{{ __('messages.female') }}</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-500 dark:bg-slate-500/20 dark:text-slate-400">{{ $employee->status }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                <a class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-7 bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500"
                                                    href="{{ url('page/account/' . $employee->user_id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="size-3.5">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7Z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <a data-modal-target="editEmployeeModal" id="editEmployee"
                                                    class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-7 text-slate-500 bg-slate-100 hover:text-white hover:bg-slate-500 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-white dark:hover:bg-zink-500 cursor-pointer">
                                                    <i data-lucide="pencil" class="size-3.5"></i>
                                                </a>
                                                <a data-modal-target="deleteModal" id="deleteRecord"
                                                    class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-7 bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-red-500 dark:hover:bg-red-500/20 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="size-3.5">
                                                        <path d="M3 6h18"></path>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                        <line x1="10" x2="10" y1="11" y2="17"></line>
                                                        <line x1="14" x2="14" y1="11" y2="17"></line>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="px-4 py-8 text-center text-slate-500 dark:text-zink-200">
                                            <i data-lucide="inbox" class="size-12 mx-auto mb-3 opacity-50"></i>
                                            <p>{{ __('messages.no_records_found') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="flex flex-wrap items-center justify-between mt-4 pagination-container">
                        <div class="mb-3 text-sm text-slate-500 dark:text-zink-200 md:mb-0">
                            @if ($employeeList->total() > 0)
                                {{ __('messages.showing_entries', [
                                    'from' => $employeeList->firstItem(),
                                    'to' => $employeeList->lastItem(),
                                    'total' => $employeeList->total(),
                                ]) }}
                            @else
                                {{ __('messages.no_entries_found') }}
                            @endif
                        </div>
                        <div>
                            {{ $employeeList->appends(['search' => request('search')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->

    {{-- ══════════════════════════════════════════════════════════════
         ADD EMPLOYEE MODAL – تصغير الحجم وتحسين التمرير
    ══════════════════════════════════════════════════════════════ --}}
    <div id="addEmployeeModal" modal-center=""
        class="fixed flex flex-col transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4
               {{ $errors->create->any() || session('open_add_modal') ? '' : 'hidden' }}">
        {{-- تغيير العرض من md:w-[36rem] إلى md:w-[32rem] --}}
        <div class="w-screen md:w-[32rem] bg-white shadow rounded-md dark:bg-zink-600">
            {{-- Header ثابت --}}
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500 shrink-0">
                <h5 class="text-16 font-semibold">{{ __('messages.add_employee') }}</h5>
                <button data-modal-close="addEmployeeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- منطقة التمرير الرئيسية --}}
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                <form id="create-form" action="{{ route('hr/employee/save') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $employeeId }}">

                    {{-- Global error summary --}}
                    @if ($errors->create->any())
                        <div
                            class="px-4 py-3 mb-4 text-sm text-red-500 border border-red-200 rounded-md bg-red-50 dark:bg-red-500/20">
                            <p class="font-semibold mb-1">Please fix the errors below:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->create->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ── Avatar ── --}}
                    <div class="flex justify-center mb-4">
                        <div class="relative rounded-full shadow-md size-24 bg-slate-100 dark:bg-zink-500">
                            <img id="add-user-profile-image" src="{{ URL::to('assets/images/user.png') }}" alt=""
                                class="object-cover w-full h-full rounded-full">
                            <div
                                class="absolute bottom-0 flex items-center justify-center rounded-full size-8 ltr:right-0 rtl:left-0">
                                <input id="add-profile-img-file-input" name="avatar" type="file" class="hidden">
                                <label for="add-profile-img-file-input"
                                    class="flex items-center justify-center bg-white rounded-full shadow-lg cursor-pointer size-8 dark:bg-zink-600">
                                    <i data-lucide="image-plus"
                                        class="size-4 text-slate-500 fill-slate-200 dark:text-zink-200 dark:fill-zink-500"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════
                         SECTION 1 – Basic Info
                    ══════════════════════════════════════ --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200 dark:border-zink-500">
                            <div
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-custom-500 text-white text-xs font-bold shrink-0">
                                1</div>
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">Basic Information</h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Name --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.employee_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full
                                           {{ $errors->create->has('name') ? 'border-red-400' : '' }}"
                                    placeholder="{{ __('messages.enter_employee_name') }}" value="{{ old('name') }}">
                                @if ($errors->create->has('name'))
                                    <span class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('name') }}</span>
                                @endif
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.email') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full
                                           {{ $errors->create->has('email') ? 'border-red-400' : '' }}"
                                    placeholder="example@company.com" value="{{ old('email') }}">
                                @if ($errors->create->has('email'))
                                    <span class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('email') }}</span>
                                @endif
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.phone_number') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone_number"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full
                                           {{ $errors->create->has('phone_number') ? 'border-red-400' : '' }}"
                                    placeholder="{{ __('messages.enter_phone') }}" value="{{ old('phone_number') }}">
                                @if ($errors->create->has('phone_number'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('phone_number') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2 – Work Info --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200 dark:border-zink-500">
                            <div
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-custom-500 text-white text-xs font-bold shrink-0">
                                2</div>
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">Work Information</h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Employee ID (auto) --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.employee_id') }}
                                    <span class="ml-1 text-xs text-slate-400">(auto-generated)</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 w-full"
                                        value="{{ $employeeId }}" disabled>
                                    <span class="absolute inset-y-0 right-3 flex items-center">
                                        <i data-lucide="lock" class="size-3.5 text-slate-400"></i>
                                    </span>
                                </div>
                            </div>

                            {{-- Department --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.department') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="department"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full
                                           {{ $errors->create->has('department') ? 'border-red-400' : '' }}">
                                    <option value="">{{ __('messages.select_department') }}</option>
                                    @foreach ($department as $dept)
                                        <option value="{{ $dept->department }}"
                                            {{ old('department') == $dept->department ? 'selected' : '' }}>
                                            {{ $dept->department }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->create->has('department'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('department') }}</span>
                                @endif
                            </div>

                            {{-- Designation --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.designation') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="designation"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full
                                           {{ $errors->create->has('designation') ? 'border-red-400' : '' }}">
                                    <option value="">{{ __('messages.designation_select') }}</option>
                                    @foreach ($position as $value)
                                        <option value="{{ $value->position }}"
                                            {{ old('designation') == $value->position ? 'selected' : '' }}>
                                            {{ $value->position }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->create->has('designation'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('designation') }}</span>
                                @endif
                            </div>

                            {{-- Employment Type --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.job_type') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="position"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full
                                           {{ $errors->create->has('position') ? 'border-red-400' : '' }}">
                                    <option value="">{{ __('messages.job_type') }}</option>
                                    <option value="Full-Time Onsite"
                                        {{ old('position') == 'Full-Time Onsite' ? 'selected' : '' }}>
                                        {{ __('messages.full_time') }}</option>
                                    <option value="Part-Time" {{ old('position') == 'Part-Time' ? 'selected' : '' }}>
                                        {{ __('messages.part_time') }}</option>
                                    <option value="Remote" {{ old('position') == 'Remote' ? 'selected' : '' }}>
                                        {{ __('messages.remote') }}</option>
                                    <option value="Hybrid Work" {{ old('position') == 'Hybrid Work' ? 'selected' : '' }}>
                                        {{ __('messages.hybrid') }}</option>
                                    <option value="Contractor" {{ old('position') == 'Contractor' ? 'selected' : '' }}>
                                        {{ __('messages.contractor') }}</option>
                                </select>
                                @if ($errors->create->has('position'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('position') }}</span>
                                @endif
                            </div>

                            {{-- Joining Date --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.joining_date') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="join_date"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full datepicker
                                           {{ $errors->create->has('join_date') ? 'border-red-400' : '' }}"
                                    placeholder="{{ __('messages.select_date') }}" value="{{ old('join_date') }}">
                                @if ($errors->create->has('join_date'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('join_date') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3 – System Access --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200 dark:border-zink-500">
                            <div
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-custom-500 text-white text-xs font-bold shrink-0">
                                3</div>
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">System Access</h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Role --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.role_name') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="role_name"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full
                                           {{ $errors->create->has('role_name') ? 'border-red-400' : '' }}">
                                    <option value="">{{ __('messages.select_role') }}</option>
                                    @foreach ($roleName as $value)
                                        <option value="{{ $value->role_type }}"
                                            {{ old('role_name') == $value->role_type ? 'selected' : '' }}>
                                            {{ $value->role_type }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->create->has('role_name'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('role_name') }}</span>
                                @endif
                            </div>

                            {{-- Password --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.password') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="add_password"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 pr-10 w-full
                                               {{ $errors->create->has('password') ? 'border-red-400' : '' }}"
                                        placeholder="••••••">
                                    <button type="button" onclick="togglePassword('add_password','add_eye')"
                                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-zink-200">
                                        <i id="add_eye" data-lucide="eye" class="size-4"></i>
                                    </button>
                                </div>
                                @if ($errors->create->has('password'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->create->first('password') }}</span>
                                @endif
                            </div>

                            {{-- Password Confirmation --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.password_confirmation') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="add_password_confirmation"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 pr-10 w-full"
                                        placeholder="••••••">
                                    <button type="button"
                                        onclick="togglePassword('add_password_confirmation','add_eye_confirm')"
                                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-zink-200">
                                        <i id="add_eye_confirm" data-lucide="eye" class="size-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4 – Additional --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200 dark:border-zink-500">
                            <div
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-slate-400 text-white text-xs font-bold shrink-0">
                                4</div>
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">
                                Additional Info
                                <span class="ml-1 text-xs font-normal text-slate-400">(Optional)</span>
                            </h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Location --}}
                            <div>
                                <label
                                    class="inline-block mb-1 text-sm font-medium">{{ __('messages.location') }}</label>
                                <input type="text" name="location"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full"
                                    placeholder="{{ __('messages.enter_location') }}" value="{{ old('location') }}">
                            </div>

                            {{-- Experience --}}
                            <div>
                                <label
                                    class="inline-block mb-1 text-sm font-medium">{{ __('messages.experience_years') }}</label>
                                <input type="number" name="experience" step="0.1"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full"
                                    placeholder="0.0" value="{{ old('experience') }}">
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">{{ __('messages.gender') }}</label>
                                <select name="status"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full">
                                    <option value="">{{ __('messages.gender') }}</option>
                                    <option value="Male" {{ old('status') == 'Male' ? 'selected' : '' }}>
                                        {{ __('messages.male') }}</option>
                                    <option value="Female" {{ old('status') == 'Female' ? 'selected' : '' }}>
                                        {{ __('messages.female') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-slate-200 dark:border-zink-500 sticky bottom-0 bg-white dark:bg-zink-600 py-2">
                        <button type="reset" data-modal-close="addEmployeeModal"
                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                            <i data-lucide="user-plus" class="size-4 inline-block mr-1"></i>
                            {{ __('messages.add') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         EDIT EMPLOYEE MODAL 
    ══════════════════════════════════════════════════════════════ --}}
    <div id="editEmployeeModal" modal-center=""
        class="fixed flex flex-col transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4
               {{ $errors->update->any() || session('open_edit_modal') ? '' : 'hidden' }}">
        {{-- تغيير العرض من md:w-[36rem] إلى md:w-[32rem] --}}
        <div class="w-screen md:w-[32rem] bg-white shadow rounded-md dark:bg-zink-600">
            {{-- Header ثابت --}}
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500 shrink-0">
                <h5 class="text-16 font-semibold">{{ __('messages.edit_employee') }}</h5>
                <button data-modal-close="editEmployeeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- منطقة التمرير الرئيسية --}}
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                <form id="edit-form" action="{{ route('hr/employee/update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="e_id"
                        value="{{ old('id', session('open_edit_modal') ? old('id') : '') }}">
                    <input type="hidden" name="old_avatar" id="old_avatar" value="{{ old('old_avatar') }}">

                    {{-- Global error summary --}}
                    @if ($errors->update->any())
                        <div
                            class="px-4 py-3 mb-4 text-sm text-red-500 border border-red-200 rounded-md bg-red-50 dark:bg-red-500/20">
                            <p class="font-semibold mb-1">Please fix the errors below:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->update->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ── Avatar ── --}}
                    <div class="flex justify-center mb-4">
                        <div class="relative rounded-full shadow-md size-24 bg-slate-100 dark:bg-zink-500">
                            <img id="e_avatar_edit" src="{{ URL::to('assets/images/user.png') }}" alt=""
                                class="object-cover w-full h-full rounded-full">
                            <div
                                class="absolute bottom-0 flex items-center justify-center rounded-full size-8 ltr:right-0 rtl:left-0">
                                <input id="edit-profile-img-file-input" name="avatar" type="file" class="hidden">
                                <label for="edit-profile-img-file-input"
                                    class="flex items-center justify-center bg-white rounded-full shadow-lg cursor-pointer size-8 dark:bg-zink-600">
                                    <i data-lucide="image-plus"
                                        class="size-4 text-slate-500 fill-slate-200 dark:text-zink-200 dark:fill-zink-500"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 1 – Basic Info --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200 dark:border-zink-500">
                            <div
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-custom-500 text-white text-xs font-bold shrink-0">
                                1</div>
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">Basic Information</h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Name --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.employee_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="e_name"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full
                                           {{ $errors->update->has('name') ? 'border-red-400' : '' }}"
                                    placeholder="{{ __('messages.enter_employee_name') }}" value="{{ old('name') }}">
                                @if ($errors->update->has('name'))
                                    <span class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('name') }}</span>
                                @endif
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.email') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="e_email"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full
                                           {{ $errors->update->has('email') ? 'border-red-400' : '' }}"
                                    placeholder="example@company.com" value="{{ old('email') }}">
                                @if ($errors->update->has('email'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('email') }}</span>
                                @endif
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.phone_number') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone_number" id="e_phone_number"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full
                                           {{ $errors->update->has('phone_number') ? 'border-red-400' : '' }}"
                                    placeholder="{{ __('messages.enter_phone') }}" value="{{ old('phone_number') }}">
                                @if ($errors->update->has('phone_number'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('phone_number') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2 – Work Info --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200 dark:border-zink-500">
                            <div
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-custom-500 text-white text-xs font-bold shrink-0">
                                2</div>
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">Work Information</h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Employee ID (read-only) --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.employee_id') }}
                                    <span class="ml-1 text-xs text-slate-400">(read-only)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="employee_id" id="e_employee_id"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 w-full"
                                        readonly value="{{ old('employee_id') }}">
                                    <span class="absolute inset-y-0 right-3 flex items-center">
                                        <i data-lucide="lock" class="size-3.5 text-slate-400"></i>
                                    </span>
                                </div>
                            </div>

                            {{-- Department --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.department') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="department" id="e_department"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full
                                           {{ $errors->update->has('department') ? 'border-red-400' : '' }}">
                                    <option value="">{{ __('messages.select_department') }}</option>
                                    @foreach ($department as $dept)
                                        <option value="{{ $dept->department }}" {{ old('department') == $dept->department ? 'selected' : '' }}>{{ $dept->department }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->update->has('department'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('department') }}</span>
                                @endif
                            </div>

                            {{-- Designation --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.designation') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="designation" id="e_designation"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full
                                           {{ $errors->update->has('designation') ? 'border-red-400' : '' }}">
                                    <option value="">{{ __('messages.designation_select') }}</option>
                                    @foreach ($position as $value)
                                        <option value="{{ $value->position }}" {{ old('designation') == $value->position ? 'selected' : '' }}>{{ $value->position }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->update->has('designation'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('designation') }}</span>
                                @endif
                            </div>

                            {{-- Employment Type --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.job_type') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="position" id="e_position"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full
                                           {{ $errors->update->has('position') ? 'border-red-400' : '' }}">
                                    <option value="">{{ __('messages.job_type') }}</option>
                                    <option value="Full-Time Onsite" {{ old('position') == 'Full-Time Onsite' ? 'selected' : '' }}>{{ __('messages.full_time') }}</option>
                                    <option value="Part-Time" {{ old('position') == 'Part-Time' ? 'selected' : '' }}>{{ __('messages.part_time') }}</option>
                                    <option value="Remote" {{ old('position') == 'Remote' ? 'selected' : '' }}>{{ __('messages.remote') }}</option>
                                    <option value="Hybrid Work" {{ old('position') == 'Hybrid Work' ? 'selected' : '' }}>{{ __('messages.hybrid') }}</option>
                                    <option value="Contractor" {{ old('position') == 'Contractor' ? 'selected' : '' }}>{{ __('messages.contractor') }}</option>
                                </select>
                                @if ($errors->update->has('position'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('position') }}</span>
                                @endif
                            </div>

                            {{-- Joining Date --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.joining_date') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="join_date" id="e_join_date"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full datepicker
                                           {{ $errors->update->has('join_date') ? 'border-red-400' : '' }}"
                                    placeholder="{{ __('messages.select_date') }}" value="{{ old('join_date') }}">
                                @if ($errors->update->has('join_date'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('join_date') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3 – System Access --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200 dark:border-zink-500">
                            <div
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-custom-500 text-white text-xs font-bold shrink-0">
                                3</div>
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">System Access</h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Role --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.role_name') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="role_name" id="e_role_name"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full
                                           {{ $errors->update->has('role_name') ? 'border-red-400' : '' }}">
                                    <option value="">{{ __('messages.select_role') }}</option>
                                    @foreach ($roleName as $value)
                                        <option value="{{ $value->role_type }}" {{ old('role_name') == $value->role_type ? 'selected' : '' }}>{{ $value->role_type }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->update->has('role_name'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('role_name') }}</span>
                                @endif
                            </div>

                            {{-- Password Info --}}
                            <div>
                                <div
                                    class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 rounded-md px-3 py-2 mb-3 flex items-center gap-2">
                                    <i data-lucide="info" class="size-4 text-amber-500 shrink-0"></i>
                                    <p class="text-xs text-amber-600 dark:text-amber-400">
                                        Leave password fields <strong>empty</strong> to keep the current password unchanged.
                                    </p>
                                </div>
                            </div>

                            {{-- New Password --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.new_password') }}
                                    <span class="text-slate-400 text-xs font-normal">(optional)</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="e_password"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 pr-10 w-full
                                               {{ $errors->update->has('password') ? 'border-red-400' : '' }}"
                                        placeholder="••••••">
                                    <button type="button" onclick="togglePassword('e_password','e_eye')"
                                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-zink-200">
                                        <i id="e_eye" data-lucide="eye" class="size-4"></i>
                                    </button>
                                </div>
                                @if ($errors->update->has('password'))
                                    <span
                                        class="text-red-500 text-xs mt-1 block">{{ $errors->update->first('password') }}</span>
                                @endif
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">
                                    {{ __('messages.password_confirmation') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="e_password_confirmation"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 pr-10 w-full"
                                        placeholder="••••••">
                                    <button type="button" onclick="togglePassword('e_password_confirmation','e_eye_confirm')"
                                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-zink-200">
                                        <i id="e_eye_confirm" data-lucide="eye" class="size-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4 – Additional --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200 dark:border-zink-500">
                            <div
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-slate-400 text-white text-xs font-bold shrink-0">
                                4</div>
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">
                                Additional Info
                                <span class="ml-1 text-xs font-normal text-slate-400">(Optional)</span>
                            </h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Location --}}
                            <div>
                                <label
                                    class="inline-block mb-1 text-sm font-medium">{{ __('messages.location') }}</label>
                                <input type="text" name="location" id="e_location"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full"
                                    placeholder="{{ __('messages.enter_location') }}" value="{{ old('location') }}">
                            </div>

                            {{-- Experience --}}
                            <div>
                                <label
                                    class="inline-block mb-1 text-sm font-medium">{{ __('messages.experience_years') }}</label>
                                <input type="number" name="experience" id="e_experience" step="0.1"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 w-full"
                                    placeholder="0.0" value="{{ old('experience') }}">
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label class="inline-block mb-1 text-sm font-medium">{{ __('messages.gender') }}</label>
                                <select name="status" id="e_status"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full">
                                    <option value="">{{ __('messages.gender') }}</option>
                                    <option value="Male" {{ old('status') == 'Male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                    <option value="Female" {{ old('status') == 'Female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-slate-200 dark:border-zink-500 sticky bottom-0 bg-white dark:bg-zink-600 py-2">
                        <button type="reset" data-modal-close="editEmployeeModal"
                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                            <i data-lucide="save" class="size-4 inline-block mr-1"></i>
                            {{ __('messages.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
     IMPORT EMPLOYEES MODAL
    ══════════════════════════════════════════════════════════════ --}}
    <div id="importEmployeeModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500 shrink-0">
                <h5 class="text-16 font-semibold">{{ __('messages.import_employees') }}</h5>
                <button data-modal-close="importEmployeeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                <form id="import-form" action="{{ route('hr/employee/import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-500/10 rounded-md border border-blue-200 dark:border-blue-500/30">
                        <div class="flex items-start gap-2">
                            <i data-lucide="info" class="size-5 text-blue-500 shrink-0 mt-0.5"></i>
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                <p class="font-semibold mb-1">{{ __('messages.import_instructions') }}</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>{{ __('messages.import_format_csv') }}</li>
                                    <li>{{ __('messages.import_required_fields') }}</li>
                                    <li>{{ __('messages.import_download_template') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="inline-block mb-2 text-sm font-medium">
                            {{ __('messages.import_file') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" name="import_file" id="import_file" accept=".csv,.xlsx,.xls,.txt"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 w-full"
                                required>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">{{ __('messages.import_file_hint') }}</p>
                    </div>
                    
                    <div class="flex justify-between items-center gap-2 mt-4 pt-3 border-t border-slate-200 dark:border-zink-500">
                        <a href="{{ route('hr/employee/import-template') }}"
                            class="text-custom-500 bg-white btn border border-custom-500 hover:text-white hover:bg-custom-500 dark:bg-zink-600">
                            <i data-lucide="download" class="size-4 inline-block mr-1"></i>
                            {{ __('messages.download_template') }}
                        </a>
                        <div class="flex gap-2">
                            <button type="reset" data-modal-close="importEmployeeModal"
                                class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 dark:bg-zink-600">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit"
                                class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600">
                                <i data-lucide="upload" class="size-4 inline-block mr-1"></i>
                                {{ __('messages.import') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div id="deleteModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                <div class="float-right">
                    <button data-modal-close="deleteModal"
                        class="transition-all duration-200 ease-linear text-slate-500 hover:text-red-500">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAMAAAD04JH5AAAC8VBMVEUAAAD/6u7/cZD/3uL/5+r/T4T9O4T/4ub9RIX/ooz/7/D/noz+PoT/3uP9TYf/XoX/m4z/oY39Tob/oYz/oo39O4T9TYb/po3/n4z/4Ob/3+X/nIz+fon/4eb/nI39Xoj9fIn/8fP9SoX9coj/noz/XYb/6e38R4b/XIf/cIn/ZYj/Rof/6+//cIr/oYz/a4P/7/L+X4f+bYn+QoX/pIz/7vH/noz/8PH/7O7/4ub/oIz/moz/oY3/O4X/cYn/RYX+aIj/5+r9QIX+XYf+cYn+Z4j+i5j9PoT/po3/8vT/ucD/09f+hYr/8vT8R4X8UYb/3uH+ZIn+W4f+cIn/7O/+hIr+VYf+b4j+ZYj+VYb/6Ov9RYX9UIb9bYn9O4T/oIz9Y4f9WIb/gov/bIj/dYr/gYr/pY3/7e//dYr9PoX/pY3/8vL/PID/7/L+hor+hor/8fP/8fP/o43/o43/7O//n4v/n47/nI7/8PL/6+7/6ez/5+v9QIX/7fD9SoX9SIX9RYX9Q4X+YIf/6u7/7/H+g4r+gYr+gIr+for+fYr+cYn9O4T+e4n+a4j+ZYj+VYb9T4b9PYT+eIn9TYb/8vT+dYn+c4n+don+cIj+Zoj+bYj+aIj+XYf+Yof+W4f/xs/+Wof9U4b+V4b/0Nf/ur3+hor+hYr/1Nv/oY39TIb+eon/1t3/3eL/3+T/0dn/y9P/m4z+aoj9Uob+WYf9UYb/ydL/yNH/2+H/ztb/xM7/197/2uD/0dr/zNT/2d//zdX/noz/w83/4eb/oIz/2N//o43/pI3/nYz/uMX/qr7/u8f/pY3/vcn/p7v/wcv/tcP/sMP/ssL/r8H/rb//usf/wMv/tcP/kKL+h5f/sr7/o7f/oLT/k6/+mav+kKr+lKH+fqH+bZf+dJb+hJH9X5H+e4z/v8n+iKX+h6H/rL//rbr/mrP/mbD+dp3+fpz+jJv+fpf9ZJT+e5D+aZD/qbf+oa/+hp3+bpD+co/+ZI/+Xoz9Vos1azWoAAAAeHRSTlMAvwe8iBv3u3BtPR61ZUcx9/Xy7ebf3dHPt7Gtqqebm5aMh4V3cXBcW1pGMSUaEgX729qtqqmll3VlRT84Ny8g/vr48fDw7u7t5tzVz8vIx8bGxfu7srCwsKaWnZybko6Ghn1wb2hkX0Q+KhMT+eTjx8bDwa1NSEgfarKCAAAHAElEQVR42uzTv2qDQBwH8F/cjEtEQUEQBOkUrIMxRX2AZMiWPVsCCYX+rxacmkfIQzjeIwRK28GXKvQ0talytvg7MvRz2/c47ntwP/i7tehpkzyfaJ64Bu4EUcsrNFEArpbq2xF1CfxIN681biXgJFSyWkoEXARy1kAOgINIzhrJEaBz1Jcvur9Y+HolUB3AZuxLii3RSLKVQ+gBsvt9yaw81jEP8QPg0t8LInwjlrkOqB5JwYYjNikEgMkglNG85QMiYUA+DST4QSr3zgFPSCgTapiECqEDfWs2jXediaczq/+b669iBNetK1zQA7sOF2VBK+MYzbjd+xGdAdPwMkbkDoFltEU1AoaNu0XlbhgFVimyFWsEUmSsUbxLkLE+wTxJUsSVJHNGgV6CrHfyBZ6RnX6BJ2T/BT5orWOXBOIogOMPCoTg/gBFQQiCoAiaagmCaKiGlpbGKGiqP8C51HA60MYGqyF/56ig4CAOIuIk3g1yg5yDiyD6B+Tdc/i9Gn734Odn/HLv8bjppzrgNrVmt6rXWGrNtkDh6DS1RqdhXiQ7m0uf2vlbd/YgrKcvzZ6B5+pbsyvguXnR7AZ44i+axYEn+apZEnjuXjW7A56HtGYPENZxIhKJXF+kNbu4Xq5NHINStBmoZDSr4N4oKBhNVMxoVmwi1T9IWKiU1axkoVjIA0RWMxHyAMNaGeW0GlkrBihELWTntLItFAUlI7axdHn+89fIHf1r3nTqhfrw/NLfGjMgtLhJeR0hhJOj0S0LUXZp8xwhRMczqThwJU2qI3wT0uya32o2iRPh65hUEri23wlbBBqeHB2MjtzMWtCqNp3fBq57usAVaCrHHrae3KYCuXT+Hrh188SgigZy7GHrKT707QLXY56wq2ioOmBYRTadfwSukwIxq6OFHPvY+nJb1NGMzp8A936ByLdw71x1wBxbK0/n94HroPBGFBsBR25jbGO5OdiKdLpwAGxndEUFF7dVB7SxfdDpM+A7pCvGrUBfbl1sXbn1aVs5BL7fVsjktYkwDOMvAwk5hAQEey1USmuLiHp2QRFvigouuKB4EvwTxO2ouOHFfT2ICAaXiBFFvNWQybSJFZI0JKGQaFtpLbiexHm/+eZ7AlXnnfnd5sf7PN+TbL8MjL90yZquwK5guiy7cUxvp+DsxIpPXPzoXwMesfuE6Z0UnH1XgepD5rThCqwKhjqtzqqY3kfBWYIVE6r5i+HyrPKG+qLOJjC9hIJz6CzwQTXPGs4bYKhZdfYB04coOEux4ut9pmMOYGUO6Kizr5heSsEZwopZ1Wz+tDKrsvlHqbNZTA9RcNKPge+qecJw3gBDTaiz75heQ8FZdg14/Iqbq4YbYTViqCqrV48xvYyCY63DjswrF9scwMocYLPKYHadRQI2XgHec/WYobwBhhpj9R6zG0nCCiwZeeQy8ndVRqVYSRK2ngNKXP3WUN4AQ71lVcLsVpKwC0sqXJ0x1DircUNlWFUwu4sk9GLJ9D3mijGAjTHgijqaxmwvSThwA6ir7m++8gb45ps6qmP2AEnox5KO6m75ymHj+KaljjqY7ScJg6eAz6r7s6+8AQsdaQZJwhCWtF4wHV+Nshn1TVsdtTA7RBLSWDKvuut/G1BXR/OYTZOE2Cnk9RuXaWMAG2PANJvXXdEYSbCuIzkur/jGG+CbCptcV9QiERuwpfzaxfbNGJsx37xjU8bkBpKx4ianghs1DQ/wzSgaxQqSsQ1r7IxL3hjAxnguz8bG5DaSseM2MMXlOd+U2JR8k2MzhcndJKMXa2pcnr2+8IDrWTY1TPaSjINPgXaW+aFNiUVJix/qpI3JgySj/y7QUO1NbbwBWjTVSQOT/SRjEGtaz5kZbT6y+KjFjDppYXKQZKTOA/OqvaGNN0CLhjqZx2SKZKSx5uctpq3NOxbvtGirk5+YTJOM2HlEtdcXHlBXJ13BGMmw7iAFbp/SwhugxRSLQlfQIiGLsMfh+srCAyosHMwtIik9TwDvvQDCpYekbHkGVHMujhY2C1sLh0UVc1tIyo4LQI3ry1p4A7Qos6hhbjdJ2YtFjbcutr+IRc1fxKKBub0kpQ+LfjlufVOLycKf78KkFk33wPmFuT6SkriETNrFYn7GEE2nWHSahpjJF4v2ZFcsQVIG3DxMmHsC3xfm5vDgyZz7PDBAUlIPIiFFUoaPRcIwSVkbzYAYSbGiGWCRmEXHI2ARyemJYkAPydkcxYDNJCd5IgJWkZw9UQzYQ3L6ohjQR3ISJyMgQXIGohgwQHKGoxgwTHKs9UdDs345hWBV+AGrKAyp8AMOUyiSYd9PUjjWbroYik1rKSSr42Hejx+m0KxefEbM4tUUAUf2x2XPx/cfoWiIJZKLA46IL04mYvQf/AaSGokYCo6ekAAAAABJRU5ErkJggg=="
                    alt="" class="block h-12 mx-auto">
                <form action="{{ route('hr/employee/delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_delete" id="e_idDelete">
                    <input type="hidden" name="del_avatar" id="del_avatar">
                    <div class="mt-5 text-center">
                        <h5 class="mb-1">{{ __('messages.are_you_sure') }}</h5>
                        <p class="text-slate-500 dark:text-zink-200">{{ __('messages.confirm_delete') }}</p>
                        <div class="flex justify-center gap-2 mt-6">
                            <button type="reset" data-modal-close="deleteModal"
                                class="bg-white text-slate-500 btn hover:text-slate-500 hover:bg-slate-100 focus:text-slate-500 focus:bg-slate-100 active:text-slate-500 active:bg-slate-100 dark:bg-zink-600 dark:hover:bg-slate-500/10 dark:focus:bg-slate-500/10 dark:active:bg-slate-500/10">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit"
                                class="text-white bg-red-500 border-red-500 btn hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 dark:ring-custom-400/20">
                                {{ __('messages.yes_delete') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // ── Toggle password visibility ────────────────────────────────────────────────
    function togglePassword(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    $(document).ready(function() {

        // ── Show import modal on button click ───────────────────────────────────
        $('[data-modal-target="importEmployeeModal"]').on('click', function() {
            $('#importEmployeeModal').removeClass('hidden');
        });

        // ── Re-open modal if validation failed ───────────────────────────────────
        @if ($errors->create->any() || session('open_add_modal'))
            $('#addEmployeeModal').removeClass('hidden');
        @endif

        @if ($errors->update->any() || session('open_edit_modal'))
            $('#editEmployeeModal').removeClass('hidden');
            // Scroll to first error inside the modal
            setTimeout(function() {
                var $firstError = $('#editEmployeeModal .text-red-500').first();
                if ($firstError.length) {
                    $('#editEmployeeModal .overflow-y-auto').scrollTop($firstError.offset().top - 200);
                }
            }, 100);
        @endif

        // ── Close modals ─────────────────────────────────────────────────────────
        $('[data-modal-close]').on('click', function() {
            var modalId = $(this).attr('data-modal-close');
            $('#' + modalId).addClass('hidden');
            var formEl = document.querySelector('#' + modalId + ' form');
            if (formEl) formEl.reset();

            if (modalId === 'addEmployeeModal') {
                $('#add-user-profile-image').attr('src', "{{ URL::to('assets/images/user.png') }}");
            }
            if (modalId === 'editEmployeeModal') {
                $('#e_avatar_edit').attr('src', "{{ URL::to('assets/images/user.png') }}");
            }
        });

        // ── Open add modal ───────────────────────────────────────────────────────
        $('[data-modal-target="addEmployeeModal"]').on('click', function() {
            $('#addEmployeeModal').removeClass('hidden');
        });

        // ── Live search with debounce ─────────────────────────────────────────────
        var searchTimeout;
        $('#searchInput').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                var search = $('#searchInput').val();
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('search', search);
                currentUrl.searchParams.set('page', '1');
                window.location.href = currentUrl.toString();
            }, 500);
        });
    });

    // ── Populate Edit modal from table row ───────────────────────────────────────
    $(document).on('click', '#editEmployee', function() {
        var row = $(this).closest('tr');
        var avatar = row.find('.photo').text().trim();
        var avatarUrl = avatar ?
            "{{ asset('assets/images/user') }}/" + avatar :
            "{{ asset('assets/images/user.png') }}";

        $('#e_avatar_edit').attr('src', avatarUrl);
        $('#old_avatar').val(avatar);
        $('#e_id').val(row.find('.id').text().trim());
        $('#e_employee_id').val(row.find('.user_id').text().trim());
        $('#e_name').val(row.find('.name').text().trim());
        $('#e_email').val(row.find('.email').text().trim());
        $('#e_phone_number').val(row.find('.phone_number').text().trim());
        $('#e_location').val(row.find('.location').text().trim());
        $('#e_join_date').val(row.find('.join_date').text().trim());
        $('#e_experience').val(row.find('.experience').text().trim());
        $('#e_position').val(row.find('.raw_position').text().trim());
        $('#e_department').val(row.find('.raw_department').text().trim());
        $('#e_role_name').val(row.find('.raw_role_name').text().trim());
        $('#e_status').val(row.find('.statuss').text().trim());
        $('#e_designation').val(row.find('.raw_designation').text().trim());
        $('#e_password').val('');
        $('#e_password_confirmation').val('');

        $('#editEmployeeModal').removeClass('hidden');
        // Scroll to top of modal content
        $('#editEmployeeModal .overflow-y-auto').scrollTop(0);
    });

    // ── Populate Delete modal ────────────────────────────────────────────────────
    $(document).on('click', '#deleteRecord', function() {
        var row = $(this).closest('tr');
        $('#e_idDelete').val(row.find('.id').text().trim());
        $('#del_avatar').val(row.find('.photo').text().trim());
        $('#deleteModal').removeClass('hidden');
    });

    // ── Avatar preview – Add modal ───────────────────────────────────────────────
    document.getElementById('add-profile-img-file-input')?.addEventListener('change', function() {
        var preview = document.getElementById('add-user-profile-image');
        var reader = new FileReader();
        reader.addEventListener('load', function() {
            preview.src = reader.result;
        });
        if (this.files[0]) reader.readAsDataURL(this.files[0]);
    });

    // ── Avatar preview – Edit modal ──────────────────────────────────────────────
    document.getElementById('edit-profile-img-file-input')?.addEventListener('change', function() {
        var preview = document.getElementById('e_avatar_edit');
        var reader = new FileReader();
        reader.addEventListener('load', function() {
            preview.src = reader.result;
        });
        if (this.files[0]) reader.readAsDataURL(this.files[0]);
    });

    // ── Re-initialise Lucide icons ───────────────────────────────────────────────
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>

{{-- تهيئة Flatpickr لتاريخ الانضمام --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تهيئة جميع حقول التاريخ التي تحمل كلاس 'datepicker'
        document.querySelectorAll('.datepicker').forEach(function(input) {
            flatpickr(input, {
                dateFormat: "d M, Y",
                allowInput: true
            });
        });
    });
</script>
@endsection
