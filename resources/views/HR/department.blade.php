@extends('layouts.master')
@section('content')
    <style>
        /* Custom Pagination Styles - Light & Dark Mode */
        .custom-pagination nav {
            display: flex;
            justify-content: center;
        }

        .custom-pagination .flex {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .custom-pagination .relative span,
        .custom-pagination .relative a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        /* Light Mode */
        .custom-pagination .relative a {
            background-color: #f1f5f9;
            color: #1e293b;
            border: 1px solid #e2e8f0;
        }

        .custom-pagination .relative a:hover {
            background-color: #e2e8f0;
            border-color: #cbd5e1;
        }

        .custom-pagination .relative span[aria-current="page"] {
            background-color: #4f46e5;
            color: white;
            border: 1px solid #4f46e5;
        }

        .custom-pagination .relative span:not([aria-current="page"]) {
            background-color: #f1f5f9;
            color: #94a3b8;
            border: 1px solid #e2e8f0;
        }

        /* Dark Mode */
        .dark .custom-pagination .relative a {
            background-color: #1e293b;
            color: #cbd5e1;
            border: 1px solid #334155;
        }

        .dark .custom-pagination .relative a:hover {
            background-color: #334155;
            border-color: #475569;
            color: #f1f5f9;
        }

        .dark .custom-pagination .relative span[aria-current="page"] {
            background-color: #6366f1;
            color: white;
            border: 1px solid #6366f1;
        }

        .dark .custom-pagination .relative span:not([aria-current="page"]) {
            background-color: #1e293b;
            color: #64748b;
            border: 1px solid #334155;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .custom-pagination .flex {
                gap: 0.125rem;
            }

            .custom-pagination .relative span,
            .custom-pagination .relative a {
                min-width: 2rem;
                height: 2rem;
                padding: 0 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Select Box Dark Mode */
        select.form-select {
            background-color: #ffffff;
            color: #1e293b;
            border-color: #e2e8f0;
        }

        .dark select.form-select {
            background-color: #1e293b;
            color: #cbd5e1;
            border-color: #334155;
        }

        .dark select.form-select option {
            background-color: #1e293b;
            color: #cbd5e1;
        }

        /* Table Dark Mode */
        .dark table tbody tr:hover {
            background-color: rgba(51, 65, 85, 0.5);
        }

        /* Modal Dark Mode */
        .dark .modal-content {
            background-color: #1e293b;
        }
    </style>

    <!-- Page-content -->
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.departments_list') }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.hr_management') }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        {{ __('messages.departments_list') }}
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-15 grow">{{ __('messages.departments') }}</h6>
                        <div class="shrink-0 flex gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-slate-500 dark:text-zink-200">عرض:</span>
                                <select id="perPageSelect"
                                    class="form-select border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 dark:focus:border-custom-800 w-20 py-1.5 rounded-md focus:outline-none focus:border-custom-500">
                                    <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page', 5) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page', 5) == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>
                            <form method="GET" action="{{ route('hr/department/page') }}" class="flex items-center gap-2">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by department or head..."
                                    class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 
                                    focus:outline-none focus:border-custom-500 px-3 py-1.5 rounded-md w-58">

                                {{-- مهم نحافظ على per_page --}}
                                <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">

                                <button type="submit"
                                    class="btn bg-custom-500 text-white px-3 py-1.5 rounded-md hover:bg-custom-600">
                                    Search
                                </button>
                            </form>
                            <button data-modal-target="addDepartmentModal" type="button"
                                class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" data-lucide="plus"
                                    class="lucide lucide-plus inline-block size-4">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                <span class="align-middle">{{ __('messages.add_department') }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- عرض رسائل الخطأ العامة --}}
                    @if ($errors->any())
                        <div
                            class="alert alert-danger mb-4 p-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-3.5 py-2.5 font-semibold border border-slate-200 dark:border-zink-500 dark:text-zink-100">
                                        {{ __('messages.no') }}</th>
                                    <th hidden>ID</th>
                                    <th
                                        class="px-3.5 py-2.5 font-semibold border border-slate-200 dark:border-zink-500 dark:text-zink-100">
                                        {{ __('messages.department_name') }}</th>
                                    <th
                                        class="px-3.5 py-2.5 font-semibold border border-slate-200 dark:border-zink-500 dark:text-zink-100">
                                        {{ __('messages.head_of_department') }}</th>
                                    <th
                                        class="px-3.5 py-2.5 font-semibold border border-slate-200 dark:border-zink-500 dark:text-zink-100">
                                        {{ __('messages.phone_number') }}</th>
                                    <th
                                        class="px-3.5 py-2.5 font-semibold border border-slate-200 dark:border-zink-500 dark:text-zink-100">
                                        {{ __('messages.email') }}</th>
                                    <th
                                        class="px-3.5 py-2.5 font-semibold border border-slate-200 dark:border-zink-500 dark:text-zink-100">
                                        {{ __('messages.total_employee') }}</th>
                                    <th
                                        class="px-3.5 py-2.5 font-semibold border border-slate-200 dark:border-zink-500 dark:text-zink-100">
                                        {{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($departmentList as $key => $value)
                                    <tr>
                                        <td
                                            class="px-3.5 py-2.5 border border-slate-200 dark:border-zink-500 dark:text-zink-200">
                                            {{ ($departmentList->currentPage() - 1) * $departmentList->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="id_update" hidden>{{ $value->id }}</td>
                                        <td
                                            class="department-name px-3.5 py-2.5 border border-slate-200 dark:border-zink-500 dark:text-zink-200">
                                            {{ $value->department }}
                                        </td>
                                        <td
                                            class="head-of-name px-3.5 py-2.5 border border-slate-200 dark:border-zink-500 dark:text-zink-200">
                                            {{ $value->head_of }}
                                        </td>
                                        <td
                                            class="phone-number px-3.5 py-2.5 border border-slate-200 dark:border-zink-500 dark:text-zink-200">
                                            {{ $value->phone_number }}
                                        </td>
                                        <td
                                            class="email-address px-3.5 py-2.5 border border-slate-200 dark:border-zink-500 dark:text-zink-200">
                                            {{ $value->email }}
                                        </td>
                                        <td
                                            class="total-employee px-3.5 py-2.5 border border-slate-200 dark:border-zink-500 dark:text-zink-200">
                                            {{ $value->users_count ?? 0 }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border border-slate-200 dark:border-zink-500">
                                            <div class="flex gap-2">
                                                <a href="javascript:void(0);" data-modal-target="editDepartmentModal"
                                                    class="update-record-btn flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 bg-slate-100 dark:bg-zink-600 dark:text-zink-200 text-slate-500 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-100 dark:hover:bg-custom-500/20"><i
                                                        data-lucide="pencil" class="size-4"></i></a>
                                                <a href="javascript:void(0);" data-modal-target="deleteModal"
                                                    class="delete-record-btn flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 bg-slate-100 dark:bg-zink-600 dark:text-zink-200 text-slate-500 hover:text-red-500 dark:hover:text-red-500 hover:bg-red-100 dark:hover:bg-red-500/20"><i
                                                        data-lucide="trash-2" class="size-4"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-3.5 py-2.5 text-center border border-slate-200 dark:border-zink-500 dark:text-zink-200">
                                            {{ __('messages.no_records') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="flex flex-wrap items-center justify-between mt-4 pagination-container">
                        <div class="mb-3 text-sm text-slate-500 dark:text-zink-200 md:mb-0">
                            {{-- عرض المعلومات --}}
                            @if ($departmentList->total() > 0)
                                {{ __('messages.showing_entries', [
                                    'from' => $departmentList->firstItem(),
                                    'to' => $departmentList->lastItem(),
                                    'total' => $departmentList->total(),
                                ]) }}
                            @else
                                {{ __('messages.no_entries_found') }}
                            @endif
                        </div>
                        <div>
                            {{ $departmentList->appends(['search' => request('search')])->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    {{-- روابط التصفح (Pagination) --}}
                    {{-- @if ($departmentList->hasPages())
                        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-slate-500 dark:text-zink-200">
                                {{ __('messages.depar_from') }}
                                <span class="font-medium">{{ $departmentList->firstItem() ?? 0 }}</span>
                                {{ __('messages.depar_to') }}
                                <span class="font-medium">{{ $departmentList->lastItem() ?? 0 }}</span>
                                {{ __('messages.depar_tota') }}
                                <span class="font-medium">{{ $departmentList->total() }}</span>
                                {{ __('messages.depar_dep') }}
                            </div>
                            <div class="custom-pagination">
                                {{ $departmentList->appends(['per_page' => request('per_page', 5), 'search' => request('search')])->links('pagination::tailwind') }}
                            </div>
                        </div>
                    @endif --}}
                    {{--  @if ($departmentList->hasPages())
                        <div class="mt-6 flex justify-center">
                            <div class="custom-pagination">
                                {{ $departmentList->appends(['per_page' => request('per_page', 5), 'search' => request('search')])->links() }}
                            </div>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->

    <!-- add department modal -->
    <div id="addDepartmentModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16 dark:text-zink-100">{{ __('messages.add_department') }}</h5>
                <button type="button" data-modal-close="addDepartmentModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x"
                        class="w-5 h-5"></i></button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form action="{{ route('hr/department/save') }}" method="POST" id="addDepartmentForm">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                        <div class="xl:col-span-12">
                            <label for="departmentInput"
                                class="inline-block mb-2 text-base font-medium dark:text-zink-100">{{ __('messages.department_name') }}</label>
                            <input type="text" name="department" id="departmentInput"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('department') is-invalid @enderror"
                                placeholder="{{ __('messages.enter_department_name') }}" value="{{ old('department') }}"
                                required>
                            @error('department')
                                <span class="text-red-500 text-sm mt-1 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="headOfInput"
                                class="inline-block mb-2 text-base font-medium dark:text-zink-100">{{ __('messages.head_of_department') }}</label>
                            <select name="head_of" id="headOfInput"
                                class="form-select border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 dark:focus:border-custom-800 focus:outline-none focus:border-custom-500 @error('head_of') is-invalid @enderror"
                                required>
                                <option value="">{{ __('messages.select_manager') }}</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->name }}"
                                        {{ old('head_of') == $manager->name ? 'selected' : '' }}>
                                        {{ $manager->name }} ({{ $manager->user_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('head_of')
                                <span class="text-red-500 text-sm mt-1 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if ($managers->isEmpty())
                                <span class="text-yellow-500 text-sm mt-1 block">
                                    <strong>⚠️ لا يوجد مدراء في النظام. قم بإضافة موظف بدور Manager أولاً.</strong>
                                </span>
                            @endif
                        </div>
                        <div class="xl:col-span-12">
                            <label for="phoneNumberInput"
                                class="inline-block mb-2 text-base font-medium dark:text-zink-100">{{ __('messages.phone_number') }}</label>
                            <input type="text" name="phone_number" id="phoneNumberInput"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('phone_number') is-invalid @enderror"
                                placeholder="{{ __('messages.enter_phone') }}" value="{{ old('phone_number') }}"
                                required>
                            @error('phone_number')
                                <span class="text-red-500 text-sm mt-1 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="emailInput"
                                class="inline-block mb-2 text-base font-medium dark:text-zink-100">{{ __('messages.email') }}</label>
                            <input type="email" name="email" id="emailInput"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('email') is-invalid @enderror"
                                placeholder="{{ __('messages.enter_email') }}" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="text-red-500 text-sm mt-1 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" data-modal-close="addDepartmentModal"
                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10 dark:text-red-400">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">{{ __('messages.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end add department-->

    <!-- edit department modal -->
    <div id="editDepartmentModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16 dark:text-zink-100">{{ __('messages.edit_department') }}</h5>
                <button type="button" data-modal-close="editDepartmentModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x"
                        class="w-5 h-5"></i></button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form action="{{ route('hr/department/save') }}" method="POST" id="editDepartmentForm">
                    @csrf
                    <input type="hidden" name="id_update" id="e_id_update" value="" />
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                        <div class="xl:col-span-12">
                            <label for="e_department_input"
                                class="inline-block mb-2 text-base font-medium dark:text-zink-100">{{ __('messages.department_name') }}</label>
                            <input type="text" name="department" id="e_department_input"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_department_name') }}" required>
                            @error('department')
                                <span class="text-red-500 text-sm mt-1 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="e_head_of_input"
                                class="inline-block mb-2 text-base font-medium dark:text-zink-100">{{ __('messages.head_of_department') }}</label>
                            <select name="head_of" id="e_head_of_input"
                                class="form-select border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 dark:focus:border-custom-800 focus:outline-none focus:border-custom-500"
                                required>
                                <option value="">{{ __('messages.select_manager') }}</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->name }}">
                                        {{ $manager->name }} ({{ $manager->user_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('head_of')
                                <span class="text-red-500 text-sm mt-1 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="e_phone_number_input"
                                class="inline-block mb-2 text-base font-medium dark:text-zink-100">{{ __('messages.phone_number') }}</label>
                            <input type="text" name="phone_number" id="e_phone_number_input"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_phone') }}" required>
                            @error('phone_number')
                                <span class="text-red-500 text-sm mt-1 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="e_email_input"
                                class="inline-block mb-2 text-base font-medium dark:text-zink-100">{{ __('messages.email') }}</label>
                            <input type="email" name="email" id="e_email_input"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="{{ __('messages.enter_email') }}" required>
                            @error('email')
                                <span class="text-red-500 text-sm mt-1 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" data-modal-close="editDepartmentModal"
                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10 dark:text-red-400">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">{{ __('messages.update_department') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end edit department-->

    <!--delete modal-->
    <div id="deleteModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                <div class="float-right">
                    <button type="button" data-modal-close="deleteModal"
                        class="transition-all duration-200 ease-linear text-slate-500 hover:text-red-500">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <img src="data:image/png;base64,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"
                    alt="" class="block h-12 mx-auto">
                <form action="{{ route('hr/department/delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_delete" id="e_id_delete" value="">
                    <div class="mt-5 text-center">
                        <h5 class="mb-1 dark:text-zink-100">{{ __('messages.are_you_sure') }}</h5>
                        <p class="text-slate-500 dark:text-zink-200">{{ __('messages.confirm_delete') }}</p>
                        <div class="flex justify-center gap-2 mt-6">
                            <button type="button" data-modal-close="deleteModal"
                                class="bg-white text-slate-500 btn hover:text-slate-500 hover:bg-slate-100 focus:text-slate-500 focus:bg-slate-100 active:text-slate-500 active:bg-slate-100 dark:bg-zink-600 dark:hover:bg-slate-500/10 dark:focus:bg-slate-500/10 dark:active:bg-slate-500/10 dark:text-zink-200">{{ __('messages.cancel') }}</button>
                            <button type="submit"
                                class="text-white bg-red-500 border-red-500 btn hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 dark:ring-custom-400/20">{{ __('messages.yes_delete') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end delete modal-->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Per Page Change
            $('#perPageSelect').on('change', function() {
                var perPage = $(this).val();
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('per_page', perPage);
                currentUrl.searchParams.set('page', 1);
                window.location.href = currentUrl.toString();
            });

            // Update record
            $(document).on('click', '.update-record-btn', function() {
                var _this = $(this).closest('tr');

                var id = _this.find('.id_update').text().trim();
                var department = _this.find('.department-name').text().trim();
                var headOf = _this.find('.head-of-name').text().trim();
                var phoneNumber = _this.find('.phone-number').text().trim();
                var email = _this.find('.email-address').text().trim();

                $('#e_id_update').val(id);
                $('#e_department_input').val(department);
                $('#e_head_of_input').val(headOf);
                $('#e_phone_number_input').val(phoneNumber);
                $('#e_email_input').val(email);

                const modalElement = document.getElementById('editDepartmentModal');
                if (modalElement) {
                    modalElement.classList.remove('hidden');
                }
            });

            // Delete record
            $(document).on('click', '.delete-record-btn', function() {
                var _this = $(this).closest('tr');
                var id = _this.find('.id_update').text().trim();

                $('#e_id_delete').val(id);

                const modalElement = document.getElementById('deleteModal');
                if (modalElement) {
                    modalElement.classList.remove('hidden');
                }
            });

            // Close modal
            $('[data-modal-close]').on('click', function() {
                var modalId = $(this).data('modal-close');
                $('#' + modalId).addClass('hidden');
            });

            // Keep modals open if there are validation errors
            @if (session('open_modal_add') || $errors->hasBag('create') || ($errors->any() && !old('id_update')))
                $('#addDepartmentModal').removeClass('hidden');
            @endif

            @if (session('open_modal_edit') || $errors->hasBag('update') || (old('id_update') && $errors->any()))
                $('#editDepartmentModal').removeClass('hidden');
            @endif
        });
    </script>
@endsection
