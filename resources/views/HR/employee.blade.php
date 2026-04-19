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
                            {{-- حقل البحث --}}
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="{{ __('messages.search') }}..."
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 pl-8"
                                    value="{{ request('search') }}">
                                <i data-lucide="search" class="absolute size-4 left-2.5 top-2.5 text-slate-400"></i>
                            </div>

                            {{-- زر Export --}}
                            <div>
                                <a href="{{ route('hr/employee/export', ['search' => request('search')]) }}" 
                                    class="text-white btn bg-green-500 border-green-500 hover:text-white hover:bg-green-600 hover:border-green-600 focus:text-white focus:bg-green-600 focus:border-green-600 focus:ring focus:ring-green-100 active:text-white active:bg-green-600 active:border-green-600 active:ring active:ring-green-100 dark:ring-green-400/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download inline-block size-4 mr-1">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" x2="12" y1="15" y2="3"></line>
                                    </svg>
                                    <span class="align-middle">{{ __('messages.export') }}</span>
                                </a>
                            </div>

                            {{-- زر الإضافة --}}
                            <div>
                                <button data-modal-target="addEmployeeModal" type="button"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" data-lucide="plus"
                                        class="lucide lucide-plus inline-block size-4">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5v14"></path>
                                    </svg>
                                    <span class="align-middle">{{ __('messages.add_employee') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- الجدول --}}
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-zink-600 border-b border-slate-200 dark:border-zink-500">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.no') }}</th>
                                    <th hidden>ID</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.employee_id') }}</th>
                                    <th hidden>Photo</th>
                                    <th hidden>Location</th>
                                    <th hidden>Join Date</th>
                                    <th hidden>Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.name') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.email') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.phone') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.experience') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.join_date') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.last_login') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.role') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.designation') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.department') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.status') }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.action') }}</th>
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
                                        
                                        // حساب تاريخ الانضمام بدقة (بدون كسور)
                                        $joinDate = \Carbon\Carbon::parse($employee->join_date);
                                        $now = \Carbon\Carbon::now();
                                        
                                        // حساب الفرق بالسنوات (عدد صحيح)
                                        $years = $now->year - $joinDate->year;
                                        
                                        // حساب الفرق بالأشهر
                                        $months = $now->month - $joinDate->month;
                                        
                                        // حساب الفرق بالأيام
                                        $days = $now->day - $joinDate->day;
                                        
                                        // تعديل القيم إذا كانت سلبية
                                        if ($days < 0) {
                                            $months--;
                                            $days += $joinDate->copy()->addYears($years)->addMonths($months)->daysInMonth;
                                        }
                                        
                                        if ($months < 0) {
                                            $years--;
                                            $months += 12;
                                        }
                                        
                                        $joinDateText = '';
                                        
                                        if ($years > 0) {
                                            $joinDateText .= $years . ' ' . ($years == 1 ? __('messages.year') : __('messages.years'));
                                        }
                                        
                                        if ($months > 0) {
                                            if ($joinDateText) $joinDateText .= ' ' . __('messages.and') . ' ';
                                            $joinDateText .= $months . ' ' . ($months == 1 ? __('messages.month') : __('messages.months'));
                                        }
                                        
                                        if ($days > 0 && $years == 0) {
                                            if ($joinDateText) $joinDateText .= ' ' . __('messages.and') . ' ';
                                            $joinDateText .= $days . ' ' . ($days == 1 ? __('messages.day') : __('messages.days'));
                                        }
                                        
                                        if (empty($joinDateText)) {
                                            $joinDateText = __('messages.today');
                                        }
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
                                                    <p class="position text-xs text-slate-400 dark:text-zink-300">{{ $employee->position }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 email">{{ $employee->email }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 phone_number">{{ $employee->phone_number }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 experience">{{ $employee->experience }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200">{{ $joinDateText }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200">{{ \Carbon\Carbon::parse($employee->last_login)->diffForHumans() }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 role_name">{{ $employee->role_name }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 designation">{{ $employee->designation }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 department">{{ $employee->department }}</td>
                                        <td class="px-4 py-3">
                                            @if ($employee->status == 'Active')
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400">
                                                    <i data-lucide="check-circle" class="inline size-3 mr-1"></i>
                                                    {{ __('messages.active') }}
                                                </span>
                                            @elseif ($employee->status == 'Inactive')
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-500 dark:bg-slate-500/20 dark:text-slate-400">
                                                    <i data-lucide="loader" class="inline size-3 mr-1"></i>
                                                    {{ __('messages.inactive') }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-500 dark:bg-red-500/20 dark:text-red-400">
                                                    <i data-lucide="x" class="inline size-3 mr-1"></i>
                                                    {{ __('messages.disable') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                <a class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-7 bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500"
                                                    href="{{ url('page/account/' . $employee->user_id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        data-lucide="eye" class="size-3.5">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10 7-10-7Z"></path>
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
                                                        data-lucide="trash-2" class="size-3.5">
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
         ADD EMPLOYEE MODAL
    ══════════════════════════════════════════════════════════════ --}}
    <div id="addEmployeeModal" modal-center=""
        class="fixed flex flex-col transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show
               {{ $errors->create->any() || session('open_add_modal') ? '' : 'hidden' }}">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">

            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16">{{ __('messages.add_employee') }}</h5>
                <button data-modal-close="addEmployeeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form id="create-form" action="{{ route('hr/employee/save') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $employeeId }}">

                    @if ($errors->create->any())
                        <div class="px-4 py-3 mb-4 text-sm text-red-500 border border-red-200 rounded-md bg-red-50 dark:bg-red-500/20">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->create->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                        <div class="xl:col-span-12">
                            <div class="relative mx-auto mb-4 rounded-full shadow-md size-24 bg-slate-100 profile-user dark:bg-zink-500">
                                <img id="add-user-profile-image" src="{{ URL::to('assets/images/user.png') }}"
                                    alt="" class="object-cover w-full h-full rounded-full">
                                <div class="absolute bottom-0 flex items-center justify-center rounded-full size-8 ltr:right-0 rtl:left-0 profile-photo-edit">
                                    <input id="add-profile-img-file-input" name="avatar" type="file"
                                        class="hidden profile-img-file-input">
                                    <label for="add-profile-img-file-input"
                                        class="flex items-center justify-center bg-white rounded-full shadow-lg cursor-pointer size-8 dark:bg-zink-600 profile-photo-edit">
                                        <i data-lucide="image-plus"
                                            class="size-4 text-slate-500 fill-slate-200 dark:text-zink-200 dark:fill-zink-500"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_id') }}</label>
                            <input type="text"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                value="{{ $employeeId }}" disabled>
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_name') }}</label>
                            <input type="text" name="name"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_employee_name') }}" value="{{ old('name') }}">
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.email') }}</label>
                            <input type="email" name="email"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_email') }}" value="{{ old('email') }}">
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.password') }}</label>
                            <div class="relative">
                                <input type="password" name="password" id="add_password"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 pr-10"
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePassword('add_password', 'add_eye')"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-zink-200">
                                    <i id="add_eye" data-lucide="eye" class="size-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.new_password') }}</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="add_password_confirmation"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 pr-10"
                                    placeholder="••••••••">
                                <button type="button"
                                    onclick="togglePassword('add_password_confirmation', 'add_eye_confirm')"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-zink-200">
                                    <i id="add_eye_confirm" data-lucide="eye" class="size-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.position') }}</label>
                            <select name="position"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800">
                                <option value="">{{ __('messages.select_position') }}</option>
                                @foreach ($position as $value)
                                    <option value="{{ $value->position }}"
                                        {{ old('position') == $value->position ? 'selected' : '' }}>
                                        {{ $value->position }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.department') }}</label>
                            <select name="department"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800">
                                <option value="">{{ __('messages.select_department') }}</option>
                                @foreach ($department as $dept)
                                    <option value="{{ $dept->department }}"
                                        {{ old('department') == $dept->department ? 'selected' : '' }}>
                                        {{ $dept->department }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.role_name') }}</label>
                            <select name="role_name"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800">
                                <option value="">{{ __('messages.select_role') }}</option>
                                @foreach ($roleName as $value)
                                    <option value="{{ $value->role_type }}"
                                        {{ old('role_name') == $value->role_type ? 'selected' : '' }}>
                                        {{ $value->role_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.status') }}</label>
                            <select name="status"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800">
                                <option value="">{{ __('messages.select_status') }}</option>
                                @foreach ($statusUser as $value)
                                    <option value="{{ $value->type_name }}"
                                        {{ old('status') == $value->type_name ? 'selected' : '' }}>
                                        {{ $value->type_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.phone_number') }}</label>
                            <input type="tel" name="phone_number"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_phone') }}" value="{{ old('phone_number') }}">
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.location') }}</label>
                            <input type="text" name="location"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_location') }}" value="{{ old('location') }}">
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.joining_date') }}</label>
                            <input type="text" name="join_date"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.select_date') }}" data-provider="flatpickr"
                                data-date-format="d M, Y" value="{{ old('join_date') }}">
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.experience_years') }}</label>
                            <input type="number" name="experience" step="0.1"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="0.0" value="{{ old('experience') }}">
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.designation') }}</label>
                            <select name="designation"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800">
                                <option value="">{{ __('messages.designation_select') }}</option>
                                @foreach ($position as $value)
                                    <option value="{{ $value->position }}"
                                        {{ old('designation') == $value->position ? 'selected' : '' }}>
                                        {{ $value->position }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="reset" data-modal-close="addEmployeeModal"
                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
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
        class="fixed flex flex-col transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show
               {{ $errors->update->any() || session('open_edit_modal') ? '' : 'hidden' }}">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">

            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16">{{ __('messages.edit_employee') }}</h5>
                <button data-modal-close="editEmployeeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form id="edit-form" action="{{ route('hr/employee/update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="e_id"
                        value="{{ old('id', session('open_edit_modal') ? old('id') : '') }}">
                    <input type="hidden" name="old_avatar" id="old_avatar" value="{{ old('old_avatar') }}">

                    @if ($errors->update->any())
                        <div class="px-4 py-3 mb-4 text-sm text-red-500 border border-red-200 rounded-md bg-red-50 dark:bg-red-500/20">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->update->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                        <div class="xl:col-span-12">
                            <div class="relative mx-auto mb-4 rounded-full shadow-md size-24 bg-slate-100 profile-user dark:bg-zink-500">
                                <img id="e_avatar_edit" src="{{ URL::to('assets/images/user.png') }}"
                                    alt="" class="object-cover w-full h-full rounded-full">
                                <div class="absolute bottom-0 flex items-center justify-center rounded-full size-8 ltr:right-0 rtl:left-0 profile-photo-edit">
                                    <input id="edit-profile-img-file-input" name="avatar" type="file"
                                        class="hidden edit-profile-img-file-input">
                                    <label for="edit-profile-img-file-input"
                                        class="flex items-center justify-center bg-white rounded-full shadow-lg cursor-pointer size-8 dark:bg-zink-600 profile-photo-edit">
                                        <i data-lucide="image-plus"
                                            class="size-4 text-slate-500 fill-slate-200 dark:text-zink-200 dark:fill-zink-500"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_id') }}</label>
                            <input type="text" name="employee_id" id="e_employee_id"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800"
                                readonly value="{{ old('employee_id') }}">
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_name') }}</label>
                            <input type="text" name="name" id="e_name"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_employee_name') }}" value="{{ old('name') }}">
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.email') }}</label>
                            <input type="email" name="email" id="e_email"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_email') }}" value="{{ old('email') }}">
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.new_password') }}</label>
                            <div class="relative">
                                <input type="password" name="password" id="e_password"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 pr-10"
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePassword('e_password', 'e_eye')"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-zink-200">
                                    <i id="e_eye" data-lucide="eye" class="size-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.password_confirmation') }}</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="e_password_confirmation"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 pr-10"
                                    placeholder="••••••••">
                                <button type="button"
                                    onclick="togglePassword('e_password_confirmation', 'e_eye_confirm')"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-zink-200">
                                    <i id="e_eye_confirm" data-lucide="eye" class="size-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.position') }}</label>
                            <select name="position" id="e_position" class="form-input border-slate-200 dark:border-zink-500">
                                <option value="">{{ __('messages.select_position') }}</option>
                                @foreach ($position as $value)
                                    <option value="{{ $value->position }}">{{ $value->position }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.department') }}</label>
                            <select name="department" id="e_department" class="form-input border-slate-200 dark:border-zink-500">
                                <option value="">{{ __('messages.select_department') }}</option>
                                @foreach ($department as $dept)
                                    <option value="{{ $dept->department }}">{{ $dept->department }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.role_name') }}</label>
                            <select name="role_name" id="e_role_name" class="form-input border-slate-200 dark:border-zink-500">
                                <option value="">{{ __('messages.select_role') }}</option>
                                @foreach ($roleName as $value)
                                    <option value="{{ $value->role_type }}">{{ $value->role_type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.status') }}</label>
                            <select name="status" id="e_status" class="form-input border-slate-200 dark:border-zink-500">
                                <option value="">{{ __('messages.select_status') }}</option>
                                @foreach ($statusUser as $value)
                                    <option value="{{ $value->type_name }}">{{ $value->type_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.phone_number') }}</label>
                            <input type="tel" name="phone_number" id="e_phone_number"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_phone') }}" value="{{ old('phone_number') }}">
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.location') }}</label>
                            <input type="text" name="location" id="e_location"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_location') }}" value="{{ old('location') }}">
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.joining_date') }}</label>
                            <input type="text" name="join_date" id="e_join_date"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.select_date') }}" data-provider="flatpickr"
                                data-date-format="d M, Y" value="{{ old('join_date') }}">
                        </div>

                        <div class="xl:col-span-6">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.experience_years') }}</label>
                            <input type="number" name="experience" id="e_experience" step="0.1"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="0.0" value="{{ old('experience') }}">
                        </div>

                        <div class="xl:col-span-12">
                            <label class="inline-block mb-2 text-base font-medium">{{ __('messages.designation') }}</label>
                            <select name="designation" id="e_designation" class="form-input border-slate-200 dark:border-zink-500">
                                <option value="">{{ __('messages.designation_select') }}</option>
                                @foreach ($position as $value)
                                    <option value="{{ $value->position }}">{{ $value->position }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="reset" data-modal-close="editEmployeeModal"
                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                            {{ __('messages.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         DELETE MODAL
    ══════════════════════════════════════════════════════════════ --}}
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
                <img src="data:image/png;base64,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"
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
    @if($errors->create->any() || session('open_add_modal'))
        $('#addEmployeeModal').removeClass('hidden');
    @endif

    @if($errors->update->any() || session('open_edit_modal'))
        $('#editEmployeeModal').removeClass('hidden');
    @endif

    $('[data-modal-close]').on('click', function() {
        var modalId = $(this).attr('data-modal-close');
        $('#' + modalId).addClass('hidden');
        $('#' + modalId + ' form')[0]?.reset();
        if (modalId === 'addEmployeeModal') {
            $('#add-user-profile-image').attr('src', "{{ URL::to('assets/images/user.png') }}");
        }
        if (modalId === 'editEmployeeModal') {
            $('#e_avatar_edit').attr('src', "{{ URL::to('assets/images/user.png') }}");
        }
    });

    $('[data-modal-target="addEmployeeModal"]').on('click', function() {
        $('#addEmployeeModal').removeClass('hidden');
    });

    let searchTimeout;
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

$(document).on('click', '#editEmployee', function() {
    var row = $(this).closest('tr');
    var avatar = row.find('.photo').text().trim();
    var avatarUrl = avatar ? "{{ asset('assets/images/user') }}/" + avatar : "{{ asset('assets/images/user.png') }}";
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
    $('#e_position').val(row.find('.position').text().trim());
    $('#e_department').val(row.find('.department').text().trim());
    $('#e_role_name').val(row.find('.role_name').text().trim());
    $('#e_status').val(row.find('.statuss').text().trim());
    $('#e_designation').val(row.find('.designation').text().trim());
    $('#e_password').val('');
    $('#e_password_confirmation').val('');
    $('#editEmployeeModal').removeClass('hidden');
});

$(document).on('click', '#deleteRecord', function() {
    var row = $(this).closest('tr');
    $('#e_idDelete').val(row.find('.id').text().trim());
    $('#del_avatar').val(row.find('.photo').text().trim());
    $('#deleteModal').removeClass('hidden');
});

document.getElementById('add-profile-img-file-input')?.addEventListener('change', function() {
    var preview = document.getElementById('add-user-profile-image');
    var reader = new FileReader();
    reader.addEventListener('load', function() { preview.src = reader.result; });
    if (this.files[0]) reader.readAsDataURL(this.files[0]);
});

document.getElementById('edit-profile-img-file-input')?.addEventListener('change', function() {
    var preview = document.getElementById('e_avatar_edit');
    var reader = new FileReader();
    reader.addEventListener('load', function() { preview.src = reader.result; });
    if (this.files[0]) reader.readAsDataURL(this.files[0]);
});

if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endsection
