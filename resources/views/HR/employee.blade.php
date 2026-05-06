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

                            {{-- Import --}}
                            <div>
                                <button data-modal-target="importEmployeeModal" type="button"
                                    class="text-white btn bg-green-500 border-green-500 hover:text-white hover:bg-green-600 hover:border-green-600 focus:text-white focus:bg-green-600 focus:border-green-600 focus:ring focus:ring-green-100 active:text-white active:bg-green-600 active:border-green-600 active:ring active:ring-green-100 dark:ring-green-400/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-upload-cloud inline-block size-4 mr-1">
                                        <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"></path>
                                        <path d="M12 12v9"></path>
                                        <path d="m8 16 4-4 4 4"></path>
                                    </svg>
                                    <span class="align-middle">{{ __('messages.import') }}</span>
                                </button>
                            </div>

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
                                <tr class="bg-slate-100 dark:bg-zink-600 border-b border-slate-200 dark:border-zink-500">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.no') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.employee_id') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.name') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.email') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.role') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.phone') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.designation') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.department') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.join_date') }}</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.gender') }}</th>
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
                                    @endphp
                                    <tr class="border-b border-slate-200 dark:border-zink-500 hover:bg-slate-50 dark:hover:bg-zink-600 transition-colors">
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200">{{ $employeeList->firstItem() + $key }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ url('page/account/' . $employee->user_id) }}"
                                                class="transition-all duration-150 ease-linear text-custom-500 hover:text-custom-600 user_id">
                                                {{ $employee->user_id }}
                                            </a>
                                        </td>
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
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 email">{{ $employee->email }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 role_name">
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-md bg-slate-100 text-slate-600 dark:bg-zink-500/20 dark:text-zink-200">
                                                {{ $employee->role_name }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 phone">{{ $employee->phone_number }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 designation">
                                            {{ $employee->jobInfo->jobTitle->position ?? $employee->designation }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 department">
                                            {{ $employee->jobInfo->department->department ?? $employee->department }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-zink-200 join_date">
                                            {{ \Carbon\Carbon::parse($employee->hiringInfo->join_date ?? $employee->join_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $gender = $employee->profile->gender ?? 'Male';
                                            @endphp
                                            @if ($gender === 'Male')
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">{{ __('messages.male') }}</span>
                                            @elseif ($gender === 'Female')
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-600 dark:bg-pink-500/20 dark:text-pink-400">{{ __('messages.female') }}</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-500 dark:bg-slate-500/20 dark:text-slate-400">{{ $gender }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button type="button" class="show-employee flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-7 bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500 cursor-pointer"
                                                   data-modal-target="viewEmployeeModal"
                                                   data-employee="{{ json_encode([
                                                        'id' => $employee->id,
                                                        'user_id' => $employee->user_id,
                                                        'name' => $employee->name,
                                                        'email' => $employee->email,
                                                        'phone_number' => $employee->phone_number,
                                                        'role_name' => $employee->role_name,
                                                        'department' => $employee->jobInfo?->department?->department ?? $employee->department,
                                                        'designation' => $employee->jobInfo?->jobTitle?->position ?? $employee->designation,
                                                        'position' => $employee->position,
                                                        'join_date' => $employee->join_date,
                                                        'avatar' => $employee->avatar,
                                                        'profile' => $employee->profile,
                                                        'job_info' => $employee->jobInfo,
                                                        'hiring_info' => $employee->hiringInfo,
                                                        'salary' => $employee->salary,
                                                        'insurance' => $employee->insurance,
                                                        'documents' => $employee->documents,
                                                        'evaluations' => $employee->evaluations->first(),
                                                   ]) }}">
                                                    <i data-lucide="eye" class="size-3.5"></i>
                                                </button>
                                                <button type="button" class="edit-employee flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-7 text-slate-500 bg-slate-100 hover:text-white hover:bg-slate-500 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-white dark:hover:bg-zink-500 cursor-pointer"
                                                    data-modal-target="editEmployeeModal"
                                                    data-employee="{{ json_encode([
                                                        'id' => $employee->id,
                                                        'user_id' => $employee->user_id,
                                                        'name' => $employee->name,
                                                        'email' => $employee->email,
                                                        'phone_number' => $employee->phone_number,
                                                        'role_id' => $employee->role_id,
                                                        'avatar' => $employee->avatar,
                                                        'profile' => $employee->profile,
                                                        'job_info' => $employee->jobInfo,
                                                        'hiring_info' => $employee->hiringInfo,
                                                        'salary' => $employee->salary,
                                                        'insurance' => $employee->insurance,
                                                        'documents' => $employee->documents,
                                                        'evaluations' => $employee->evaluations->first(),
                                                        'designation' => $employee->jobInfo?->jobTitle?->position ?? $employee->designation,
                                                    ]) }}">
                                                    <i data-lucide="pencil" class="size-3.5"></i>
                                                </button>
                                                <button type="button" class="delete-record flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-7 bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-red-500 dark:hover:bg-red-500/20 cursor-pointer"
                                                    data-modal-target="deleteModal"
                                                    data-id="{{ $employee->id }}" data-avatar="{{ $employee->avatar }}">
                                                    <i data-lucide="trash-2" class="size-3.5"></i>
                                                </button>
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

    {{-- ================================================================
         ADD EMPLOYEE MODAL WITH STEPPER (9 STEPS)
    ================================================================ --}}
    <div id="addEmployeeModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[45rem] bg-white shadow rounded-md dark:bg-zink-600 flex flex-col h-[80vh]">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500 shrink-0">
                <h5 class="text-16 font-bold text-slate-700 dark:text-zink-100">{{ __('messages.add_employee') }}</h5>
                <button data-modal-close="addEmployeeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <div class="flex flex-col h-full overflow-hidden">
                {{-- Stepper Progress Bar --}}
                <div class="p-4 bg-slate-50 dark:bg-zink-700 border-b dark:border-zink-500 shrink-0">
                    <div class="flex justify-between mb-2">
                        @for ($i = 1; $i <= 9; $i++)
                            <div class="flex flex-col items-center flex-1 step-header-add cursor-pointer transition-all hover:opacity-80" data-step="{{ $i }}">
                                <div class="size-8 rounded-full flex items-center justify-center border-2 mb-1 step-circle-add {{ $i == 1 ? 'border-custom-500 bg-custom-500 text-white' : 'border-slate-300 text-slate-500 dark:text-zink-200' }}">
                                    {{ $i }}
                                </div>
                                <span class="text-[9px] hidden lg:block uppercase font-bold {{ $i == 1 ? 'text-custom-500' : 'text-slate-400' }}">
                                    @switch($i)
                                        @case(1) {{ __('messages.basic_info') }} @break
                                        @case(2) {{ __('messages.job_info') }} @break
                                        @case(3) {{ __('messages.hiring_info') }} @break
                                        @case(4) {{ __('messages.system_access') }} @break
                                        @case(5) {{ __('messages.salary') }} @break
                                        @case(6) {{ __('messages.insurance') }} @break
                                        @case(7) {{ __('messages.additional_info') }} @break
                                        @case(8) {{ __('messages.manager_eval') }} @break
                                        @case(9) {{ __('messages.documents') }} @break
                                    @endswitch
                                </span>
                            </div>
                        @endfor
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-zink-600 rounded-full h-1">
                        <div id="add-stepper-progress" class="bg-custom-500 h-1 rounded-full transition-all duration-500" style="width: 11.11%"></div>
                    </div>
                </div>

                <form action="{{ route('hr/employee/save') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden" id="addEmployeeForm">
                    @csrf
                    
                    <div class="flex-1 overflow-y-auto p-6 scroll-smooth">
                        
                        {{-- Step 1: Basic Information --}}
                        <div class="step-content-add block" data-step="1">
                            <div class="flex justify-center mb-6">
                                <div class="relative inline-block size-24 rounded-full shadow-md profile-user bg-slate-100 dark:bg-zink-500">
                                    <img src="{{ URL::to('assets/images/user.png') }}" alt="" id="add-user-profile-image"
                                        class="size-full object-cover border-0 rounded-full img-thumbnail">
                                    <div class="absolute bottom-0 flex items-center justify-center rounded-full size-8 ltr:right-0 rtl:left-0">
                                        <input id="add-profile-img-file-input" name="avatar" type="file" class="hidden profile-img-file-input" accept="image/*">
                                        <label for="add-profile-img-file-input"
                                            class="flex items-center justify-center bg-white dark:bg-zink-600 rounded-full shadow-lg cursor-pointer size-8 profile-photo-edit">
                                            <i data-lucide="image-plus" class="size-4 text-slate-500 dark:text-zink-200"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.full_name') }} <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 @error('name') border-red-500 @enderror" 
                                           name="name" value="{{ old('name') }}" placeholder="{{ __('messages.enter_full_name') }}" required>
                                    @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.phone_number') }} <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 @error('phone_number') border-red-500 @enderror" 
                                           name="phone_number" value="{{ old('phone_number') }}" placeholder="{{ __('messages.enter_phone_number') }}" required>
                                    @error('phone_number') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.email') }} <span class="text-red-500">*</span></label>
                                    <input type="email" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 @error('email') border-red-500 @enderror" 
                                           name="email" value="{{ old('email') }}" placeholder="{{ __('messages.enter_email') }}" required>
                                    @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.national_id') }}</label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="national_id" value="{{ old('national_id') }}" placeholder="{{ __('messages.enter_national_id') }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.address') }}</label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="address" value="{{ old('address') }}" placeholder="{{ __('messages.enter_address') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Job Information --}}
                        <div class="step-content-add hidden" data-step="2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.job_title') }} <span class="text-red-500">*</span></label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="job_title_id" required>
                                        <option value="">{{ __('messages.select_job_title') }}</option>
                                        @foreach ($position as $pos)
                                            <option value="{{ $pos->id }}" {{ old('job_title_id') == $pos->id ? 'selected' : '' }}>{{ $pos->position }}</option>
                                        @endforeach
                                    </select>
                                    @error('job_title_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.department') }} <span class="text-red-500">*</span></label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="department_id" required>
                                        <option value="">{{ __('messages.select_department') }}</option>
                                        @foreach ($department as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.manager') }}</label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="manager_id">
                                        <option value="">{{ __('messages.select_manager') }}</option>
                                        @foreach ($managers as $mgr)
                                            <option value="{{ $mgr->id }}" {{ old('manager_id') == $mgr->id ? 'selected' : '' }}>{{ $mgr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.work_type') }} <span class="text-red-500">*</span></label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="work_type" required>
                                        <option value="Full-Time Onsite" {{ old('work_type') == 'Full-Time Onsite' ? 'selected' : '' }}>{{ __('messages.full_time') }}</option>
                                        <option value="Part-Time" {{ old('work_type') == 'Part-Time' ? 'selected' : '' }}>{{ __('messages.part_time') }}</option>
                                        <option value="Remote" {{ old('work_type') == 'Remote' ? 'selected' : '' }}>{{ __('messages.remote') }}</option>
                                        <option value="Hybrid Work" {{ old('work_type') == 'Hybrid Work' ? 'selected' : '' }}>{{ __('messages.hybrid') }}</option>
                                        <option value="Contractor" {{ old('work_type') == 'Contractor' ? 'selected' : '' }}>{{ __('messages.contractor') }}</option>
                                    </select>
                                    @error('work_type') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.work_location') }}</label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="work_location_job" value="{{ old('work_location_job') }}" placeholder="{{ __('messages.enter_work_location') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Hiring Information --}}
                        <div class="step-content-add hidden" data-step="3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.joining_date') }} <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 datepicker" 
                                           name="join_date" value="{{ old('join_date') }}" placeholder="{{ __('messages.select_date') }}" required>
                                    @error('join_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.contract_type') }} <span class="text-red-500">*</span></label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="contract_type" required>
                                        <option value="permanent" {{ old('contract_type') == 'permanent' ? 'selected' : '' }}>{{ __('messages.permanent') }}</option>
                                        <option value="temporary" {{ old('contract_type') == 'temporary' ? 'selected' : '' }}>{{ __('messages.temporary') }}</option>
                                        <option value="freelance" {{ old('contract_type') == 'freelance' ? 'selected' : '' }}>{{ __('messages.freelance') }}</option>
                                        <option value="consultant" {{ old('contract_type') == 'consultant' ? 'selected' : '' }}>{{ __('messages.consultant') }}</option>
                                        <option value="fixed" {{ old('contract_type') == 'fixed' ? 'selected' : '' }}>{{ __('messages.fixed_term') }}</option>
                                    </select>
                                    @error('contract_type') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Step 4: System Access --}}
                        <div class="step-content-add hidden" data-step="4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.role') }} <span class="text-red-500">*</span></label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="role_id" required>
                                        <option value="">{{ __('messages.select_role') }}</option>
                                        @foreach ($roleName as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->role_type }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_id') }}</label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 bg-slate-100 dark:bg-zink-700" 
                                           value="{{ $employeeId }}" disabled>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.password') }} <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="password" id="add_password" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                                   name="password" placeholder="{{ __('messages.enter_password') }}" required>
                                            <button type="button" onclick="togglePassword('add_password', 'add_pass_icon')" 
                                                    class="absolute top-2.5 ltr:right-4 rtl:left-4 text-slate-500">
                                                <i data-lucide="eye" id="add_pass_icon" class="size-4"></i>
                                            </button>
                                        </div>
                                        @error('password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.confirm_password') }} <span class="text-red-500">*</span></label>
                                        <input type="password" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                               name="password_confirmation" placeholder="{{ __('messages.confirm_password') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 5: Salary --}}
                        <div class="step-content-add hidden" data-step="5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.base_salary') }} <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 add-salary-input" 
                                           name="base_salary" value="{{ old('base_salary', 0) }}" required>
                                    @error('base_salary') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.allowances') }}</label>
                                    <input type="number" step="0.01" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 add-salary-input" 
                                           name="allowances" value="{{ old('allowances', 0) }}">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.overtime') }}</label>
                                    <input type="number" step="0.01" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 add-salary-input" 
                                           name="overtime" value="{{ old('overtime', 0) }}">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.deductions') }}</label>
                                    <input type="number" step="0.01" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 add-salary-input" 
                                           name="deductions" value="{{ old('deductions', 0) }}">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.advances') }}</label>
                                    <input type="number" step="0.01" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 add-salary-input" 
                                           name="advances" value="{{ old('advances', 0) }}">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.total_salary') }}</label>
                                    <input type="text" id="add-total-salary" class="form-input border-slate-200 dark:border-zink-500 bg-slate-100 dark:bg-zink-700" 
                                           value="0.00" readonly>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.payment_type') }} <span class="text-red-500">*</span></label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="payment_type" required>
                                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payment_type') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                    @error('payment_type') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Step 6: Insurance --}}
                        <div class="step-content-add hidden" data-step="6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.insurance_number') }}</label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="insurance_number" value="{{ old('insurance_number') }}">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.insurance_start_date') }}</label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 datepicker" 
                                           name="insurance_start_date" value="{{ old('insurance_start_date') }}">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.insurance_status') }} <span class="text-red-500">*</span></label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="insurance_status" required>
                                        <option value="insured" {{ old('insurance_status') == 'insured' ? 'selected' : '' }}>Insured</option>
                                        <option value="not_insured" {{ old('insurance_status') == 'not_insured' ? 'selected' : '' }}>Not Insured</option>
                                        <option value="willing" {{ old('insurance_status') == 'willing' ? 'selected' : '' }}>Willing</option>
                                        <option value="not_willing" {{ old('insurance_status') == 'not_willing' ? 'selected' : '' }}>Not Willing</option>
                                    </select>
                                    @error('insurance_status') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Step 7: Additional Info --}}
                        <div class="step-content-add hidden" data-step="7">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.location') }}</label>
                                    <input type="text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="location" value="{{ old('location') }}" placeholder="{{ __('messages.enter_location') }}">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.experience_years') }}</label>
                                    <input type="number" step="0.1" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="experience_years" value="{{ old('experience_years') }}">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.gender') }}</label>
                                    <select class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="gender">
                                        <option value="">{{ __('messages.select_gender') }}</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Step 8: Manager Evaluation --}}
                        <div class="step-content-add hidden" data-step="8">
                            @if (in_array(Auth::user()->role_name, ['Admin', 'Manager', 'HR']))
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.manager_rating') }} (1-10)</label>
                                    <input type="number" min="1" max="10" step="1" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="manager_rating" value="{{ old('manager_rating') }}">
                                    <p class="mt-1 text-xs text-slate-400">{{ __('messages.manager_rating_hint') }}</p>
                                </div>
                            @else
                                <div class="p-4 bg-slate-100 dark:bg-zink-700 rounded text-center">
                                    <p class="text-slate-500 dark:text-zink-200">{{ __('messages.restricted_manager_rating') }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Step 9: Documents --}}
                        <div class="step-content-add hidden" data-step="9">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.cv_file') }}</label>
                                    <input type="file" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="cv_file" accept=".pdf,.doc,.docx">
                                    <p class="mt-1 text-xs text-slate-400">{{ __('messages.cv_file_hint') }}</p>
                                    @error('cv_file') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Form Footer --}}
                    <div class="p-4 border-t dark:border-zink-500 flex justify-between bg-slate-50 dark:bg-zink-700 shrink-0">
                        <button type="button" id="add-prev-btn" class="hidden text-slate-500 btn bg-white border-slate-200 hover:bg-slate-50">
                            {{ __('messages.previous') }}
                        </button>
                        <div class="flex-1 text-right">
                            <button type="button" id="add-next-btn" class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600">
                                {{ __('messages.next') }}
                            </button>
                            <button type="submit" id="add-submit-btn" class="hidden text-white btn bg-green-500 border-green-500 hover:bg-green-600">
                                {{ __('messages.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================================================================
         EDIT EMPLOYEE MODAL WITH STEPPER (9 STEPS)
    ================================================================ --}}
    <div id="editEmployeeModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[45rem] bg-white shadow rounded-md dark:bg-zink-600 flex flex-col h-[80vh]">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500 shrink-0">
                <h5 class="text-16 font-bold text-slate-700 dark:text-zink-100">{{ __('messages.edit_employee') }}</h5>
                <button data-modal-close="editEmployeeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <div class="flex flex-col h-full overflow-hidden">
                {{-- Stepper Progress Bar --}}
                <div class="p-4 bg-slate-50 dark:bg-zink-700 border-b dark:border-zink-500 shrink-0">
                    <div class="flex justify-between mb-2">
                        @for ($i = 1; $i <= 9; $i++)
                            <div class="flex flex-col items-center flex-1 step-header-edit cursor-pointer transition-all hover:opacity-80" data-step="{{ $i }}">
                                <div class="size-8 rounded-full flex items-center justify-center border-2 mb-1 step-circle-edit {{ $i == 1 ? 'border-custom-500 bg-custom-500 text-white' : 'border-slate-300 text-slate-500 dark:text-zink-200' }}">
                                    {{ $i }}
                                </div>
                                <span class="text-[9px] hidden lg:block uppercase font-bold {{ $i == 1 ? 'text-custom-500' : 'text-slate-400' }}">
                                    @switch($i)
                                        @case(1) {{ __('messages.basic_info') }} @break
                                        @case(2) {{ __('messages.job_info') }} @break
                                        @case(3) {{ __('messages.hiring_info') }} @break
                                        @case(4) {{ __('messages.system_access') }} @break
                                        @case(5) {{ __('messages.salary') }} @break
                                        @case(6) {{ __('messages.insurance') }} @break
                                        @case(7) {{ __('messages.additional_info') }} @break
                                        @case(8) {{ __('messages.manager_eval') }} @break
                                        @case(9) {{ __('messages.documents') }} @break
                                    @endswitch
                                </span>
                            </div>
                        @endfor
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-zink-600 rounded-full h-1">
                        <div id="edit-stepper-progress" class="bg-custom-500 h-1 rounded-full transition-all duration-500" style="width: 11.11%"></div>
                    </div>
                </div>

                <form action="{{ route('hr/employee/update') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden" id="editEmployeeForm">
                    @csrf
                    <input type="hidden" name="id" id="e_id">
                    <input type="hidden" name="old_avatar" id="old_avatar">
                    
                    <div class="flex-1 overflow-y-auto p-6 scroll-smooth">
                        
                        {{-- Step 1: Basic Information --}}
                        <div class="step-content-edit block" data-step="1">
                            <div class="flex justify-center mb-6">
                                <div class="relative inline-block size-24 rounded-full shadow-md profile-user bg-slate-100 dark:bg-zink-500">
                                    <img src="{{ URL::to('assets/images/user.png') }}" alt="" id="e_avatar_edit"
                                        class="size-full object-cover border-0 rounded-full img-thumbnail">
                                    <div class="absolute bottom-0 flex items-center justify-center rounded-full size-8 ltr:right-0 rtl:left-0">
                                        <input id="edit-profile-img-file-input" name="avatar" type="file" class="hidden profile-img-file-input" accept="image/*">
                                        <label for="edit-profile-img-file-input"
                                            class="flex items-center justify-center bg-white dark:bg-zink-600 rounded-full shadow-lg cursor-pointer size-8 profile-photo-edit">
                                            <i data-lucide="image-plus" class="size-4 text-slate-500 dark:text-zink-200"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.full_name') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="e_name" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="name" required>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.phone_number') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="e_phone_number" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="phone_number" required>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.email') }} <span class="text-red-500">*</span></label>
                                    <input type="email" id="e_email" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="email" required>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.national_id') }}</label>
                                    <input type="text" id="e_national_id" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="national_id">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.address') }}</label>
                                    <input type="text" id="e_address" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="address">
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Job Information --}}
                        <div class="step-content-edit hidden" data-step="2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.job_title') }} <span class="text-red-500">*</span></label>
                                    <select id="e_job_title_id" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="job_title_id" required>
                                        <option value="">{{ __('messages.select_job_title') }}</option>
                                        @foreach ($position as $pos)
                                            <option value="{{ $pos->id }}">{{ $pos->position }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.department') }} <span class="text-red-500">*</span></label>
                                    <select id="e_department_id" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="department_id" required>
                                        <option value="">{{ __('messages.select_department') }}</option>
                                        @foreach ($department as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->department }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.manager') }}</label>
                                    <select id="e_manager_id" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="manager_id">
                                        <option value="">{{ __('messages.select_manager') }}</option>
                                        @foreach ($managers as $mgr)
                                            <option value="{{ $mgr->id }}">{{ $mgr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.work_type') }} <span class="text-red-500">*</span></label>
                                    <select id="e_work_type" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="work_type" required>
                                        <option value="Full-Time Onsite">{{ __('messages.full_time') }}</option>
                                        <option value="Part-Time">{{ __('messages.part_time') }}</option>
                                        <option value="Remote">{{ __('messages.remote') }}</option>
                                        <option value="Hybrid Work">{{ __('messages.hybrid') }}</option>
                                        <option value="Contractor">{{ __('messages.contractor') }}</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.work_location') }}</label>
                                    <input type="text" id="e_work_location_job" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="work_location_job">
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Hiring Information --}}
                        <div class="step-content-edit hidden" data-step="3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.joining_date') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="e_join_date" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 datepicker" 
                                           name="join_date" required>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.contract_type') }} <span class="text-red-500">*</span></label>
                                    <select id="e_contract_type" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="contract_type" required>
                                        <option value="permanent">{{ __('messages.permanent') }}</option>
                                        <option value="temporary">{{ __('messages.temporary') }}</option>
                                        <option value="freelance">{{ __('messages.freelance') }}</option>
                                        <option value="consultant">{{ __('messages.consultant') }}</option>
                                        <option value="fixed">{{ __('messages.fixed_term') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Step 4: System Access --}}
                        <div class="step-content-edit hidden" data-step="4">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.role') }} <span class="text-red-500">*</span></label>
                                    <select id="e_role_id" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="role_id" required>
                                        <option value="">{{ __('messages.select_role') }}</option>
                                        @foreach ($roleName as $role)
                                            <option value="{{ $role->id }}">{{ $role->role_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.employee_id') }}</label>
                                    <input type="text" id="e_employee_id" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 bg-slate-100 dark:bg-zink-700" 
                                           readonly>
                                </div>
                                <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 p-3 rounded">
                                    <p class="text-xs text-amber-700 dark:text-amber-400">{{ __('messages.leave_password_empty') }}</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.new_password') }}</label>
                                        <div class="relative">
                                            <input type="password" id="e_password" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                                   name="password" placeholder="••••••">
                                            <button type="button" onclick="togglePassword('e_password', 'e_pass_icon')" 
                                                    class="absolute top-2.5 ltr:right-4 rtl:left-4 text-slate-500">
                                                <i data-lucide="eye" id="e_pass_icon" class="size-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.confirm_password') }}</label>
                                        <input type="password" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                               name="password_confirmation" placeholder="••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 5: Salary --}}
                        <div class="step-content-edit hidden" data-step="5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.base_salary') }} <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" id="e_base_salary" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 edit-salary-input" 
                                           name="base_salary" required>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.allowances') }}</label>
                                    <input type="number" step="0.01" id="e_allowances" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 edit-salary-input" 
                                           name="allowances">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.overtime') }}</label>
                                    <input type="number" step="0.01" id="e_overtime" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 edit-salary-input" 
                                           name="overtime">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.deductions') }}</label>
                                    <input type="number" step="0.01" id="e_deductions" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 edit-salary-input" 
                                           name="deductions">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.advances') }}</label>
                                    <input type="number" step="0.01" id="e_advances" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 edit-salary-input" 
                                           name="advances">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.total_salary') }}</label>
                                    <input type="text" id="edit-total-salary" class="form-input border-slate-200 dark:border-zink-500 bg-slate-100 dark:bg-zink-700" 
                                           readonly>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.payment_type') }} <span class="text-red-500">*</span></label>
                                    <select id="e_payment_type" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="payment_type" required>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Step 6: Insurance --}}
                        <div class="step-content-edit hidden" data-step="6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.insurance_number') }}</label>
                                    <input type="text" id="e_insurance_number" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="insurance_number">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.insurance_start_date') }}</label>
                                    <input type="text" id="e_insurance_start_date" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 datepicker" 
                                           name="insurance_start_date">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.insurance_status') }} <span class="text-red-500">*</span></label>
                                    <select id="e_insurance_status" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="insurance_status" required>
                                        <option value="insured">Insured</option>
                                        <option value="not_insured">Not Insured</option>
                                        <option value="willing">Willing</option>
                                        <option value="not_willing">Not Willing</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Step 7: Additional Info --}}
                        <div class="step-content-edit hidden" data-step="7">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.location') }}</label>
                                    <input type="text" id="e_location_extra" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="location">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.experience_years') }}</label>
                                    <input type="number" step="0.1" id="e_experience_extra" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="experience_years">
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.gender') }}</label>
                                    <select id="e_gender" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" name="gender">
                                        <option value="">{{ __('messages.select_gender') }}</option>
                                        <option value="Male">{{ __('messages.male') }}</option>
                                        <option value="Female">{{ __('messages.female') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Step 8: Manager Evaluation --}}
                        <div class="step-content-edit hidden" data-step="8">
                            @if (in_array(Auth::user()->role_name, ['Admin', 'Manager', 'HR']))
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.manager_rating') }} (1-10)</label>
                                    <input type="number" min="1" max="10" step="1" id="e_manager_rating" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="manager_rating">
                                </div>
                            @else
                                <div class="p-4 bg-slate-100 dark:bg-zink-700 rounded text-center">
                                    <p class="text-slate-500 dark:text-zink-200">{{ __('messages.restricted_manager_rating') }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Step 9: Documents --}}
                        <div class="step-content-edit hidden" data-step="9">
                            <div class="grid grid-cols-1 gap-4">
                                <div id="e_current_cv" class="hidden mb-4 p-3 bg-slate-50 dark:bg-zink-700 rounded flex justify-between items-center">
                                    <span class="text-sm font-medium text-slate-600 dark:text-zink-200">{{ __('messages.current_cv') }}</span>
                                    <a href="#" id="e_cv_link" target="_blank" class="text-custom-500 hover:underline text-sm">{{ __('messages.view_cv') }}</a>
                                </div>
                                <div>
                                    <label class="inline-block mb-2 text-base font-medium">{{ __('messages.update_cv_file') }}</label>
                                    <input type="file" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" 
                                           name="cv_file" accept=".pdf,.doc,.docx">
                                    <p class="mt-1 text-xs text-slate-400">{{ __('messages.cv_file_hint') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Form Footer --}}
                    <div class="p-4 border-t dark:border-zink-500 flex justify-between bg-slate-50 dark:bg-zink-700 shrink-0">
                        <button type="button" id="edit-prev-btn" class="hidden text-slate-500 btn bg-white border-slate-200 hover:bg-slate-50">
                            {{ __('messages.previous') }}
                        </button>
                        <div class="flex-1 text-right">
                            <button type="button" id="edit-next-btn" class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600">
                                {{ __('messages.next') }}
                            </button>
                            <button type="submit" id="edit-submit-btn" class="hidden text-white btn bg-green-500 border-green-500 hover:bg-green-600">
                                {{ __('messages.update') }}
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         DELETE MODAL
    ================================================================ --}}
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
                <form id="deleteForm">
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

    {{-- ================================================================
         VIEW EMPLOYEE MODAL (Professional Design)
    ================================================================ --}}
    <div id="viewEmployeeModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[60rem] bg-white shadow-2xl rounded-2xl dark:bg-zink-600 flex flex-col h-[90vh] overflow-hidden border border-slate-200 dark:border-zink-500">
            <!-- Premium Header with Gradient -->
            <div class="relative h-28 bg-gradient-to-r from-custom-600 to-custom-400 shrink-0">
                <!-- Close Button -->
                <button type="button" data-modal-close="viewEmployeeModal" class="absolute top-4 right-4 z-20 text-white/70 hover:text-white bg-black/10 hover:bg-black/20 rounded-full p-1.5 transition-all">
                    <i data-lucide="x" class="size-5"></i>
                </button>
                
                <!-- Avatar Overlay -->
                <div class="absolute -bottom-10 left-10 p-1 bg-white dark:bg-zink-600 rounded-full shadow-lg border-4 border-white dark:border-zink-500">
                    <img id="v_avatar" src="{{ asset('assets/images/user.png') }}" alt="" class="size-20 rounded-full object-cover">
                </div>
            </div>

            <!-- Content Area -->
            <div class="pt-12 px-10 pb-10 flex-1 overflow-y-auto">
                <!-- Header Title Section -->
                <div class="flex flex-wrap items-end justify-between gap-6 mb-10 pb-6 border-b border-slate-100 dark:border-zink-500">
                    <div>
                        <h2 id="v_name" class="text-3xl font-extrabold text-slate-800 dark:text-zink-50 mb-2">---</h2>
                        <div class="flex items-center gap-4 text-slate-500 dark:text-zink-200">
                            <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-zink-700 px-3 py-1 rounded-lg">
                                <i data-lucide="briefcase" class="size-4 text-custom-500"></i>
                                <span id="v_designation" class="font-semibold text-sm">---</span>
                            </div>
                            <span class="text-slate-300">|</span>
                            <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-zink-700 px-3 py-1 rounded-lg">
                                <i data-lucide="hash" class="size-4 text-custom-500"></i>
                                <span id="v_user_id" class="font-bold text-sm">---</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span id="v_role" class="px-4 py-2 text-sm font-bold rounded-xl bg-custom-50 text-custom-600 border border-custom-100 dark:bg-custom-500/10 dark:text-custom-400 dark:border-custom-500/20">---</span>
                        <span id="v_work_type" class="px-4 py-2 text-sm font-bold rounded-xl bg-blue-50 text-blue-600 border border-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20">---</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Details -->
                    <div class="lg:col-span-8 space-y-8">
                        <!-- Contact & Professional Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Contact Info -->
                            <div class="bg-slate-50 dark:bg-zink-700/50 p-6 rounded-2xl border border-slate-100 dark:border-zink-500">
                                <div class="flex items-center gap-2 mb-5">
                                    <i data-lucide="contact" class="size-5 text-custom-500"></i>
                                    <h4 class="font-bold text-slate-700 dark:text-zink-50 uppercase tracking-wider text-xs">{{ __('messages.contact_info') }}</h4>
                                </div>
                                <div class="space-y-5">
                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-white dark:bg-zink-600 rounded-xl shadow-sm"><i data-lucide="mail" class="size-4 text-blue-500"></i></div>
                                        <div class="overflow-hidden"><p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('messages.email') }}</p><p id="v_email" class="text-sm font-medium text-slate-700 dark:text-zink-100 truncate">---</p></div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-white dark:bg-zink-600 rounded-xl shadow-sm"><i data-lucide="phone" class="size-4 text-green-500"></i></div>
                                        <div><p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('messages.phone') }}</p><p id="v_phone" class="text-sm font-medium text-slate-700 dark:text-zink-100">---</p></div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-white dark:bg-zink-600 rounded-xl shadow-sm"><i data-lucide="map-pin" class="size-4 text-orange-500"></i></div>
                                        <div><p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('messages.address') }}</p><p id="v_address" class="text-sm font-medium text-slate-700 dark:text-zink-100">---</p></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Employment Info -->
                            <div class="bg-slate-50 dark:bg-zink-700/50 p-6 rounded-2xl border border-slate-100 dark:border-zink-500">
                                <div class="flex items-center gap-2 mb-5">
                                    <i data-lucide="layers" class="size-5 text-custom-500"></i>
                                    <h4 class="font-bold text-slate-700 dark:text-zink-50 uppercase tracking-wider text-xs">{{ __('messages.employment_details') }}</h4>
                                </div>
                                <div class="space-y-5">
                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-white dark:bg-zink-600 rounded-xl shadow-sm"><i data-lucide="building-2" class="size-4 text-purple-500"></i></div>
                                        <div><p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('messages.department') }}</p><p id="v_department" class="text-sm font-medium text-slate-700 dark:text-zink-100">---</p></div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-white dark:bg-zink-600 rounded-xl shadow-sm"><i data-lucide="calendar" class="size-4 text-indigo-500"></i></div>
                                        <div><p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('messages.join_date') }}</p><p id="v_join_date" class="text-sm font-medium text-slate-700 dark:text-zink-100">---</p></div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-white dark:bg-zink-600 rounded-xl shadow-sm"><i data-lucide="award" class="size-4 text-yellow-500"></i></div>
                                        <div><p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('messages.experience') }}</p><p id="v_experience" class="text-sm font-medium text-slate-700 dark:text-zink-100">---</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Salary Information Section -->
                        <div class="bg-white dark:bg-zink-700 p-8 rounded-3xl border border-slate-200 dark:border-zink-500 shadow-sm relative overflow-hidden">
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-6">
                                    <i data-lucide="banknote" class="size-5 text-custom-500"></i>
                                    <h4 class="font-bold text-slate-700 dark:text-zink-50 uppercase tracking-wider text-xs">{{ __('messages.salary_info') }}</h4>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                    <div class="space-y-1">
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ __('messages.base_salary') }}</p>
                                        <div class="flex items-baseline gap-1 text-3xl font-black text-slate-800 dark:text-white">
                                            <span id="v_base_salary">0.00</span>
                                            <span class="text-sm text-slate-400 font-normal">EGP</span>
                                        </div>
                                    </div>
                                    <div class="space-y-1 border-l sm:border-l border-slate-100 dark:border-zink-500 pl-0 sm:pl-8">
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ __('messages.total_salary') }}</p>
                                        <div class="flex items-baseline gap-1 text-3xl font-black text-custom-600 dark:text-custom-400">
                                            <span id="v_total_salary">0.00</span>
                                            <span class="text-sm text-slate-400 font-normal">EGP</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-8 flex items-center gap-3 p-3 bg-slate-50 dark:bg-zink-600 rounded-2xl w-fit">
                                    <i data-lucide="credit-card" class="size-4 text-slate-400"></i>
                                    <span class="text-xs text-slate-500 font-bold uppercase">{{ __('messages.payment_type') }}:</span>
                                    <span id="v_payment_type" class="text-xs font-black text-slate-800 dark:text-zink-100 uppercase">---</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Documents & Evaluation -->
                    <div class="lg:col-span-4 space-y-8">
                        <!-- Rating Section -->
                        <div class="bg-white dark:bg-zink-700 p-6 rounded-3xl border border-slate-200 dark:border-zink-500 shadow-sm relative overflow-hidden">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('messages.manager_rating') }}</h4>
                                <div class="p-2 bg-yellow-50 dark:bg-yellow-500/10 rounded-lg">
                                    <i data-lucide="star" class="size-5 text-yellow-500 fill-yellow-500"></i>
                                </div>
                            </div>
                            <div class="text-center py-4 relative z-10">
                                <div id="v_rating" class="text-7xl font-black mb-2 text-slate-800 dark:text-zink-50">---</div>
                                <div class="text-xs text-slate-400 font-bold uppercase tracking-widest">Out of 10 Points</div>
                            </div>
                            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-zink-500 flex items-center justify-between">
                                <span class="text-xs text-slate-400 font-bold uppercase">Performance Level</span>
                                <span id="v_performance_level" class="text-[10px] font-black uppercase px-2 py-1 bg-custom-50 text-custom-600 dark:bg-custom-500/10 dark:text-custom-400 rounded-md">Standard</span>
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="bg-white dark:bg-zink-700 p-6 rounded-3xl border border-slate-200 dark:border-zink-500">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-5">{{ __('messages.documents_insurance') }}</h4>
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zink-600 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><i data-lucide="shield-check" class="size-5"></i></div>
                                        <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">{{ __('messages.insurance') }}</p><p id="v_insurance_status" class="text-xs font-bold text-slate-700 dark:text-zink-100 uppercase">---</p></div>
                                    </div>
                                </div>

                                <div id="v_cv_section" class="p-4 rounded-2xl border-2 border-dashed border-slate-100 dark:border-zink-500 flex items-center justify-between group hover:border-custom-300 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center"><i data-lucide="file-text" class="size-5"></i></div>
                                        <div class="overflow-hidden w-24 md:w-auto"><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">CV File</p><p class="text-xs font-bold text-slate-700 dark:text-zink-100 truncate">Document.pdf</p></div>
                                    </div>
                                    <a id="v_cv_link" href="#" target="_blank" download class="size-10 rounded-xl bg-white dark:bg-zink-500 shadow-sm border border-slate-100 dark:border-zink-400 flex items-center justify-center text-slate-500 hover:text-custom-500 hover:border-custom-200 transition-all">
                                        <i data-lucide="download" class="size-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-slate-50 dark:bg-zink-700/30 p-6 rounded-3xl border border-dashed border-slate-200 dark:border-zink-500">
                             <div class="flex items-center gap-4">
                                <div class="size-12 rounded-full bg-slate-200 dark:bg-zink-600 flex items-center justify-center"><i data-lucide="user-check" class="size-6 text-slate-500"></i></div>
                                <div><p class="text-xs font-bold text-slate-700 dark:text-zink-50">Profile Status</p><p class="text-[10px] text-green-500 font-black uppercase">Active Member</p></div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         IMPORT EMPLOYEE MODAL
    ================================================================ --}}
    <div id="importEmployeeModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[35rem] bg-white shadow rounded-md dark:bg-zink-600 flex flex-col">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500 shrink-0">
                <h5 class="text-16 font-bold text-slate-700 dark:text-zink-100">{{ __('messages.import_employees') }}</h5>
                <button data-modal-close="importEmployeeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('hr/employee/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-zink-100">{{ __('messages.choose_file') }} (CSV)</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer border-slate-300 bg-slate-50 hover:bg-slate-100 dark:border-zink-500 dark:bg-zink-700 dark:hover:bg-zink-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i data-lucide="file-up" class="size-8 mb-3 text-slate-400"></i>
                                    <p class="mb-2 text-sm text-slate-500 dark:text-zink-200"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-slate-400">CSV, XLS, XLSX (MAX. 5MB)</p>
                                </div>
                                <input type="file" name="import_file" class="hidden" required accept=".csv,.xls,.xlsx" />
                            </label>
                        </div>
                    </div>
                    
                    <div class="p-4 mb-6 rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20">
                        <div class="flex items-start gap-3">
                            <i data-lucide="info" class="size-5 text-blue-500 shrink-0 mt-0.5"></i>
                            <div>
                                <h6 class="text-sm font-bold text-blue-700 dark:text-blue-400 mb-1">Instruction</h6>
                                <p class="text-xs text-blue-600 dark:text-blue-300 leading-relaxed">
                                    Please use our official template to ensure all data is correctly mapped to the system. 
                                    Columns must match the template format exactly.
                                </p>
                                <a href="{{ route('hr/employee/import-template') }}" class="inline-flex items-center gap-1 mt-3 text-xs font-bold text-blue-700 hover:underline">
                                    <i data-lucide="download-cloud" class="size-3.5"></i>
                                    {{ __('messages.download_template') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" data-modal-close="importEmployeeModal" class="bg-white text-slate-500 btn border-slate-200 hover:text-slate-600 hover:bg-slate-100 focus:text-slate-600 focus:bg-slate-100 dark:bg-zink-700 dark:text-zink-100 dark:border-zink-500 dark:hover:bg-zink-600">Cancel</button>
                        <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600 focus:bg-custom-600">
                            {{ __('messages.import') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // ── Global State ─────────────────────────────────────────────────────────────
    let avatarChanged = false;
    let cvChanged = false;

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
        // ========== STEPPER FUNCTION ==========
        function initStepper(modalId, stepContentClass, stepHeaderClass, progressId, nextBtnId, prevBtnId, submitBtnId) {
            let currentStep = 1;
            const totalSteps = 9;

            function updateStepper() {
                $(`#${modalId} .${stepContentClass}`).addClass('hidden').removeClass('block');
                $(`#${modalId} .${stepContentClass}[data-step="${currentStep}"]`).removeClass('hidden').addClass('block');

                $(`#${modalId} .${stepHeaderClass}`).each(function() {
                    const stepNum = $(this).data('step');
                    const circle = $(this).find('.step-circle-add, .step-circle-edit');
                    const text = $(this).find('span');

                    if (stepNum < currentStep) {
                        circle.addClass('bg-green-500 border-green-500 text-white').removeClass('bg-custom-500 border-custom-500 border-slate-300 text-slate-500');
                        text.addClass('text-green-500').removeClass('text-custom-500 text-slate-400');
                    } else if (stepNum === currentStep) {
                        circle.addClass('bg-custom-500 border-custom-500 text-white').removeClass('bg-green-500 border-green-500 border-slate-300 text-slate-500');
                        text.addClass('text-custom-500').removeClass('text-green-500 text-slate-400');
                    } else {
                        circle.addClass('border-slate-300 text-slate-500').removeClass('bg-custom-500 border-custom-500 bg-green-500 border-green-500 text-white');
                        text.addClass('text-slate-400').removeClass('text-custom-500 text-green-500');
                    }
                });

                const progressWidth = (currentStep / totalSteps) * 100;
                $(`#${progressId}`).css('width', progressWidth + '%');

                if (currentStep === 1) {
                    $(`#${prevBtnId}`).addClass('hidden');
                } else {
                    $(`#${prevBtnId}`).removeClass('hidden');
                }

                if (currentStep === totalSteps) {
                    $(`#${nextBtnId}`).addClass('hidden');
                    $(`#${submitBtnId}`).removeClass('hidden');
                } else {
                    $(`#${nextBtnId}`).removeClass('hidden');
                    $(`#${submitBtnId}`).addClass('hidden');
                }
                
                $(`#${modalId} .overflow-y-auto`).scrollTop(0);
            }

            $(`#${nextBtnId}`).off('click').on('click', function() {
                const currentStepEl = $(`#${modalId} .${stepContentClass}[data-step="${currentStep}"]`);
                const requiredInputs = currentStepEl.find(':input[required]');
                let isValid = true;
                
                requiredInputs.each(function() {
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        isValid = false;
                        return false;
                    }
                });

                if (!isValid) return;

                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepper();
                }
            });

            $(`#${prevBtnId}`).off('click').on('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepper();
                }
            });

            $(`#${modalId} .${stepHeaderClass}`).off('click').on('click', function() {
                const targetStep = parseInt($(this).data('step'));
                
                if (targetStep > currentStep) {
                    const currentStepEl = $(`#${modalId} .${stepContentClass}[data-step="${currentStep}"]`);
                    const requiredInputs = currentStepEl.find(':input[required]');
                    let isValid = true;
                    
                    requiredInputs.each(function() {
                        if (!this.checkValidity()) {
                            this.reportValidity();
                            isValid = false;
                            return false;
                        }
                    });
                    if (!isValid) return;
                }

                currentStep = targetStep;
                updateStepper();
            });

            return function reset() {
                currentStep = 1;
                updateStepper();
            };
        }

        const resetAddStepper = initStepper('addEmployeeModal', 'step-content-add', 'step-header-add', 'add-stepper-progress', 'add-next-btn', 'add-prev-btn', 'add-submit-btn');
        const resetEditStepper = initStepper('editEmployeeModal', 'step-content-edit', 'step-header-edit', 'edit-stepper-progress', 'edit-next-btn', 'edit-prev-btn', 'edit-submit-btn');

        // ========== SALARY CALCULATION ==========
        function calculateTotalSalary(inputClass, totalId) {
            function update() {
                let base = parseFloat($(`.${inputClass}[name="base_salary"]`).val()) || 0;
                let allowances = parseFloat($(`.${inputClass}[name="allowances"]`).val()) || 0;
                let overtime = parseFloat($(`.${inputClass}[name="overtime"]`).val()) || 0;
                let deductions = parseFloat($(`.${inputClass}[name="deductions"]`).val()) || 0;
                let advances = parseFloat($(`.${inputClass}[name="advances"]`).val()) || 0;

                let total = base + allowances + overtime - deductions - advances;
                $(`#${totalId}`).val(total.toFixed(2));
            }
            $(document).on('input', `.${inputClass}`, update);
        }

        calculateTotalSalary('add-salary-input', 'add-total-salary');
        calculateTotalSalary('edit-salary-input', 'edit-total-salary');

        // ========== OPEN/CLOSE MODALS ==========
        $('[data-modal-target="addEmployeeModal"]').on('click', function() {
            resetAddStepper();
            $('#addEmployeeForm')[0].reset();
            $('#add-user-profile-image').attr('src', "{{ URL::to('assets/images/user.png') }}");
            $('#addEmployeeModal').removeClass('hidden');
        });

        $('[data-modal-close]').on('click', function() {
            var modalId = $(this).attr('data-modal-close');
            $(`#${modalId}`).addClass('hidden');
        });

        // ========== AJAX SUBMISSION HELPER ==========
        function submitFormViaAjax(formId, url, modalId, successMessage) {
            $(`#${formId}`).on('submit', function(e) {
                e.preventDefault();
                
                // For Edit form: check for changes
                if (formId === 'editEmployeeForm') {
                    const currentData = $(this).serialize();
                    // files are not in serialize(), so check flags
                    if (currentData === $(this).data('initial-state') && !avatarChanged && !cvChanged) {
                        Swal.fire({
                            icon: 'info',
                            title: '{{ __("messages.no_changes") }}',
                            text: 'لم يتم إجراء أي تغييرات على البيانات.',
                            confirmButtonColor: '#3085d6',
                        });
                        return;
                    }
                }

                const formData = new FormData(this);
                const submitBtn = $(this).find('button[type="submit"]');
                const originalBtnHtml = submitBtn.html();
                
                // Clear previous errors
                $(`#${modalId} .invalid-feedback`).remove();
                $(`#${modalId} .is-invalid`).removeClass('is-invalid');

                submitBtn.prop('disabled', true).html('<i class="size-4 mr-2 animate-spin border-2 border-white border-t-transparent rounded-full inline-block"></i> {{ __("messages.processing") }}');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`#${modalId}`).addClass('hidden');
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __("messages.success") }}',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(originalBtnHtml);
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let firstErrorStep = null;

                            Object.keys(errors).forEach(key => {
                                const input = $(`#${modalId} [name="${key}"]`);
                                input.addClass('is-invalid');
                                input.after(`<div class="invalid-feedback text-xs mt-1 font-medium text-red-500">${errors[key][0]}</div>`);
                                
                                // Find which step the error is in
                                if (!firstErrorStep) {
                                    const stepContent = input.closest('[data-step]');
                                    if (stepContent.length) {
                                        firstErrorStep = stepContent.data('step');
                                    }
                                }
                            });

                            if (firstErrorStep) {
                                // Jump to the step with the first error
                                $(`#${modalId} .step-header-${modalId.includes('add') ? 'add' : 'edit'}[data-step="${firstErrorStep}"]`).click();
                            }

                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("messages.validation_error") }}',
                                text: 'يرجى مراجعة الحقول المطلوبة والتأكد من صحتها.',
                                confirmButtonColor: '#3085d6',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("messages.error") }}',
                                text: xhr.responseJSON?.message || 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.',
                                confirmButtonColor: '#3085d6',
                            });
                        }
                    }
                });
            });
        }

        submitFormViaAjax('addEmployeeForm', '{{ route("hr/employee/save") }}', 'addEmployeeModal');
        submitFormViaAjax('editEmployeeForm', '{{ route("hr/employee/update") }}', 'editEmployeeModal');
        
        // Delete Form AJAX
        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.post('{{ route("hr/employee/delete") }}', formData, function(response) {
                $('#deleteModal').addClass('hidden');
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحذف!',
                    text: 'تم حذف سجل الموظف بنجاح.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            });
        });

        // ========== SHOW DETAILS MODAL LOGIC ==========
        $(document).on('click', '.show-employee', function() {
            let emp = $(this).data('employee');
            
            // Safer parsing if data() fails due to complexity
            if (typeof emp === 'string') {
                try { emp = JSON.parse(emp); } catch(e) { console.error("Error parsing employee data", e); return; }
            }
            
            if (!emp) return;

            // Header & Avatar
            $('#v_avatar').attr('src', emp.avatar ? `{{ asset('assets/images/user') }}/${emp.avatar}` : `{{ asset('assets/images/user.png') }}`);
            $('#v_name').text(emp.name || '---');
            $('#v_user_id').text(emp.user_id || '---');
            $('#v_designation').text(emp.job_info?.job_title?.position || emp.designation || '---');
            $('#v_role').text(emp.role_name || '---');
            $('#v_work_type').text(emp.job_info?.work_type || emp.position || '---');
            
            // Contact
            $('#v_email').text(emp.email || '---');
            $('#v_phone').text(emp.phone_number || '---');
            $('#v_address').text(emp.profile?.address || '---');
            
            // Employment
            $('#v_department').text(emp.job_info?.department?.department || emp.department || '---');
            $('#v_join_date').text(emp.hiring_info?.join_date ? new Date(emp.hiring_info.join_date).toLocaleDateString('en-GB') : '---');
            $('#v_experience').text(emp.profile?.experience_years ? emp.profile.experience_years + ' {{ __("messages.years") }}' : '---');
            
            // System/Personal
            $('#v_gender').text(emp.profile?.gender || '---');
            $('#v_national_id').text(emp.profile?.national_id || '---');
            
            // Evaluation (Get latest rating from array)
            let latestRating = '---';
            if (emp.evaluations && Array.isArray(emp.evaluations) && emp.evaluations.length > 0) {
                // Assuming the last one is the latest
                latestRating = emp.evaluations[emp.evaluations.length - 1].rating;
            } else if (emp.evaluations && typeof emp.evaluations === 'object' && emp.evaluations.rating) {
                latestRating = emp.evaluations.rating;
            }
            $('#v_rating').text(latestRating);
            
            // Performance Level Logic
            let perfLevel = '---';
            let perfClass = 'bg-slate-100 text-slate-600';
            if (latestRating !== '---') {
                let r = parseFloat(latestRating);
                if (r >= 8) {
                    perfLevel = '{{ __("messages.excellent") }}';
                    perfClass = 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400';
                } else if (r >= 5) {
                    perfLevel = '{{ __("messages.good") }}';
                    perfClass = 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400';
                } else {
                    perfLevel = '{{ __("messages.low") }}';
                    perfClass = 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400';
                }
            }
            $('#v_performance_level').text(perfLevel).attr('class', `text-[10px] font-black uppercase px-2 py-1 rounded-md ${perfClass}`);
            
            // Salary
            $('#v_base_salary').text(emp.salary?.base_salary ? parseFloat(emp.salary.base_salary).toLocaleString(undefined, {minimumFractionDigits: 2}) : '0.00');
            $('#v_total_salary').text(emp.salary?.total_salary ? parseFloat(emp.salary.total_salary).toLocaleString(undefined, {minimumFractionDigits: 2}) : '0.00');
            $('#v_payment_type').text(emp.salary?.payment_type || '---');
            
            // Insurance & CV
            $('#v_insurance_status').text(emp.insurance?.insurance_status || '---');
            
            if (emp.documents?.cv_file_path) {
                $('#v_cv_section').removeClass('hidden').addClass('flex');
                let cvPath = emp.documents.cv_file_path;
                $('#v_cv_link').attr('href', `{{ url('hr/employee/download-cv') }}/${emp.id}`);
                
                // Set filename in UI
                let fileName = cvPath.split('/').pop();
                $('#v_cv_section p.truncate').text(fileName);
            } else {
                $('#v_cv_section').addClass('hidden').removeClass('flex');
            }

            $('#viewEmployeeModal').removeClass('hidden').addClass('show');
            
            // Re-initialize Lucide icons for the new modal content
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            console.log('Viewing employee:', emp.name);
        });

        // Helper to set select values (handles standard & Choices.js)
        function setSelectValue(selector, value) {
            const el = $(selector);
            if (!el.length || value === null || value === undefined) return;
            
            const valStr = String(value);
            el.val(valStr).trigger('change');
            
            const domEl = el[0];
            if (domEl.choices) {
                domEl.choices.setChoiceByValue(valStr);
            }
        }

        // ========== POPULATE EDIT MODAL ==========
        $(document).on('click', '.edit-employee', function() {
            const employee = $(this).data('employee');
            const form = $('#editEmployeeForm');
            resetEditStepper();
            
            // Reset file change flags
            avatarChanged = false;
            cvChanged = false;
            
            // Clear previous errors
            form.find('.invalid-feedback').remove();
            form.find('.is-invalid').removeClass('is-invalid');

            // Step 1: Basic
            $('#e_id').val(employee.id);
            $('#e_avatar_edit').attr('src', employee.avatar ? `{{ asset('assets/images/user') }}/${employee.avatar}` : `{{ asset('assets/images/user.png') }}`);
            $('#old_avatar').val(employee.avatar);
            $('#e_employee_id').val(employee.user_id);
            $('#e_name').val(employee.name);
            $('#e_email').val(employee.email);
            $('#e_phone_number').val(employee.phone_number);
            
            if(employee.profile) {
                $('#e_national_id').val(employee.profile.national_id);
                $('#e_address').val(employee.profile.address);
                setSelectValue('#e_gender', employee.profile.gender);
                $('#e_location_extra').val(employee.profile.location);
                $('#e_experience_extra').val(employee.profile.experience_years);
            }

            // Step 2: Job
            if(employee.job_info) {
                setSelectValue('#e_job_title_id', employee.job_info.job_title_id);
                setSelectValue('#e_department_id', employee.job_info.department_id);
                setSelectValue('#e_manager_id', employee.job_info.manager_id);
                setSelectValue('#e_work_type', employee.job_info.work_type);
                $('#e_work_location_job').val(employee.job_info.work_location);
            }

            // Step 3: Hiring
            if(employee.hiring_info) {
                $('#e_join_date').val(employee.hiring_info.join_date);
                setSelectValue('#e_contract_type', employee.hiring_info.contract_type);
            }

            // Step 4: System
            setSelectValue('#e_role_id', employee.role_id);
            $('#e_password').val('');
            $('#e_password_confirmation').val('');

            // Step 5: Salary
            if(employee.salary) {
                $('#e_base_salary').val(employee.salary.base_salary);
                $('#e_allowances').val(employee.salary.allowances);
                $('#e_overtime').val(employee.salary.overtime);
                $('#e_deductions').val(employee.salary.deductions);
                $('#e_advances').val(employee.salary.advances);
                setSelectValue('#e_payment_type', employee.salary.payment_type);
                $('.edit-salary-input').trigger('input');
            }

            // Step 6: Insurance
            if(employee.insurance) {
                $('#e_insurance_number').val(employee.insurance.insurance_number);
                $('#e_insurance_start_date').val(employee.insurance.insurance_start_date);
                setSelectValue('#e_insurance_status', employee.insurance.insurance_status);
            }

            // Step 8: Rating
            if (employee.evaluations && employee.evaluations.rating) {
                $('#e_manager_rating').val(employee.evaluations.rating);
            }

            // Step 9: Documents
            if(employee.documents && employee.documents.cv_file_path) {
                $('#e_current_cv').removeClass('hidden');
                $('#e_cv_link').attr('href', `{{ asset('storage') }}/${employee.documents.cv_file_path}`);
            } else {
                $('#e_current_cv').addClass('hidden');
            }

            // Capture initial state for "No Changes" check
            setTimeout(() => {
                form.data('initial-state', form.serialize());
            }, 300);

            $('#editEmployeeModal').removeClass('hidden').addClass('show');
        });

        // ========== DELETE ==========
        $(document).on('click', '.delete-record', function() {
            const id = $(this).data('id');
            const avatar = $(this).data('avatar');
            $('#e_idDelete').val(id);
            $('#del_avatar').val(avatar);
            $('#deleteModal').removeClass('hidden');
        });

        // ========== AVATAR PREVIEWS ==========
        document.getElementById('add-profile-img-file-input')?.addEventListener('change', function() {
            var preview = document.getElementById('add-user-profile-image');
            var reader = new FileReader();
            reader.addEventListener('load', function() { preview.src = reader.result; });
            if (this.files[0]) reader.readAsDataURL(this.files[0]);
        });

        document.getElementById('edit-profile-img-file-input')?.addEventListener('change', function() {
            avatarChanged = true;
            var preview = document.getElementById('e_avatar_edit');
            var reader = new FileReader();
            reader.addEventListener('load', function() { preview.src = reader.result; });
            if (this.files[0]) reader.readAsDataURL(this.files[0]);
        });

        // Track CV file changes
        $(document).on('change', 'input[name="cv_file"]', function() {
            cvChanged = true;
        });

        // ========== SEARCH ==========
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

        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>

{{-- Flatpickr for date fields --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.datepicker').forEach(function(input) {
            flatpickr(input, {
                dateFormat: "d M, Y",
                allowInput: true
            });
        });
    });
</script>
@endsection