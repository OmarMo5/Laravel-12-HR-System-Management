@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div x-data="{ 
        showEditModal: false,
        profileData: @js([
            'name' => $profileDetail->name,
            'phone_number' => $profileDetail->phone_number,
            'address' => $profileDetail->profile->address ?? '',
            'gender' => $profileDetail->profile->gender ?? '',
            'experience_years' => $profileDetail->profile->experience_years ?? 0,
            'location' => $profileDetail->profile->location ?? '',
        ])
    }"
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        
        <!-- Success/Error Alerts -->
        @if(session('success'))
            <div class="fixed top-20 right-4 z-[9999] bg-green-500 text-white px-6 py-3 rounded-lg shadow-xl animate-bounce">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="size-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="fixed top-20 right-4 z-[9999] bg-red-500 text-white px-6 py-3 rounded-lg shadow-xl animate-shake">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="size-5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="mt-1 -ml-3 -mr-3 rounded-none card">
                <div class="card-body !px-2.5">
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12 2xl:grid-cols-12">
                        <div class="lg:col-span-2 2xl:col-span-1">
                            @if (!empty($profileDetail->name))
                                @php
                                    $fullName = $profileDetail->name;
                                    $parts = explode(' ', $fullName);
                                    $initials = '';
                                    foreach ($parts as $part) {
                                        $initials .= strtoupper(substr($part, 0, 1));
                                    }
                                @endphp
                            @endif
                            <div class="relative inline-block rounded-full shadow-lg size-20 bg-slate-100 profile-user xl:size-28 border-4 border-white dark:border-zink-500 overflow-hidden group">
                                @if ($profileDetail && !empty($profileDetail->avatar) && file_exists(public_path('assets/images/user/' . $profileDetail->avatar)))
                                    <img id="user-profile-img" src="{{ asset('assets/images/user/' . $profileDetail->avatar) }}" alt=""
                                        class="size-full object-cover rounded-full user-profile-image">
                                @else
                                    <div id="user-profile-placeholder" class="flex items-center justify-center font-bold text-2xl rounded-full size-full bg-custom-100 text-custom-500 dark:bg-custom-500/20 uppercase">
                                        {{ $initials ?? '?' }}
                                    </div>
                                    <img id="user-profile-img" src="" alt="" class="size-full object-cover rounded-full user-profile-image hidden">
                                @endif
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    <label for="profile-img-file-input" class="cursor-pointer">
                                        <i data-lucide="camera" class="size-6 text-white"></i>
                                    </label>
                                </div>
                                <input id="profile-img-file-input" type="file" class="hidden profile-img-file-input" accept="image/*">
                            </div>
                        </div>
                        <!--end col-->
                        <div class="lg:col-span-10 2xl:col-span-9">
                            <h5 class="mb-1">
                                @if (!empty($profileDetail->name))
                                    {{ $profileDetail->name }}
                                @else
                                    {{ Session::get('name') }}
                                @endif
                                <i data-lucide="badge-check"
                                    class="inline-block size-4 text-sky-500 fill-sky-100 dark:fill-custom-500/20"></i>
                            </h5>
                            <div class="flex gap-3 mb-4">
                                <p class="text-slate-500 dark:text-zink-200">
                                    <i data-lucide="user-circle"
                                        class="inline-block size-4 ltr:mr-1 rtl:ml-1 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-500"></i>

                                    @if ($profileDetail && !empty($profileDetail->position))
                                        @switch($profileDetail->position)
                                            @case('Full-Time Onsite') {{ __('messages.full_time') }} @break
                                            @case('Part-Time') {{ __('messages.part_time') }} @break
                                            @case('Remote') {{ __('messages.remote') }} @break
                                            @case('Hybrid Work') {{ __('messages.hybrid') }} @break
                                            @case('Contractor') {{ __('messages.contractor') }} @break
                                            @default {{ $profileDetail->position }}
                                        @endswitch
                                    @elseif($profileDetail && $profileDetail->position === null)
                                        N/A
                                    @else
                                        {{ Session::get('name') }}
                                    @endif
                                </p>

                                <p class="text-slate-500 dark:text-zink-200">
                                    <i data-lucide="map-pin"
                                        class="inline-block size-4 ltr:mr-1 rtl:ml-1 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-500"></i>

                                    @if ($profileDetail && !empty($profileDetail->location))
                                        {{ $profileDetail->location }}
                                    @elseif($profileDetail && $profileDetail->location === null)
                                        N/A
                                    @else
                                        {{ Session::get('location') }}
                                    @endif
                                </p>
                            </div>
                            <ul
                                class="flex flex-wrap gap-3 mt-4 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                <li class="px-5">
                                    <h5 class="text-15">{{ $profileDetail->jobInfo->department->department ?? $profileDetail->department ?? 'N/A' }}</h5>
                                    <p class="text-slate-500 dark:text-zink-200">{{ __('messages.department') }}</p>
                                </li>
                                <li class="px-5">
                                    <h5 class="text-15">{{ $profileDetail->jobInfo->jobTitle->position ?? $profileDetail->designation ?? 'N/A' }}</h5>
                                    <p class="text-slate-500 dark:text-zink-200">{{ __('messages.designation') }}</p>
                                </li>
                                <li class="px-5">
                                    <h5 class="text-15">{{ $profileDetail->user_id }}</h5>
                                    <p class="text-slate-500 dark:text-zink-200">{{ __('messages.employee_id') }}</p>
                                </li>
                            </ul>
                            <p class="mt-4 text-slate-500 dark:text-zink-200">
                                @if($profileDetail->documents && $profileDetail->documents->cv_file_path)
                                    <span class="flex items-center gap-2 text-custom-500 font-bold bg-custom-50 dark:bg-custom-500/10 px-3 py-1 rounded-lg w-fit">
                                        <i data-lucide="file-check" class="size-4"></i>
                                        {{ __('messages.yes_cv_available') }}
                                    </span>
                                @else
                                    {{ $profileDetail->bio ?? __('messages.no_bio_available') }}
                                @endif
                            </p>
                            {{-- Social links removed for internal HR system --}}
                        </div>
                        <div class="lg:col-span-12 2xl:col-span-2">
                            <div class="flex gap-2 2xl:justify-end">
                                <a href="mailto:{{ $profileDetail->email }}"
                                    class="flex items-center justify-center size-[37.5px] p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20"
                                    title="{{ __('messages.send_email') }}">
                                    <i data-lucide="mail" class="size-4"></i>
                                </a>

                                @if(auth()->user()->id == $profileDetail->id || auth()->user()->hasAnyRole(['Admin', 'HR']))
                                    <button @click="showEditModal = true" class="flex items-center justify-center gap-2 px-4 py-2 text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600 focus:bg-custom-600 transition-all shadow-lg shadow-custom-500/20">
                                        <i data-lucide="edit-3" class="size-4"></i>
                                        <span class="hidden sm:inline">{{ __('messages.edit_profile') }}</span>
                                    </button>
                                @endif

                                <div class="relative dropdown">
                                    <button
                                        class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20"
                                        id="accountSettings" data-bs-toggle="dropdown"><i data-lucide="more-horizontal"
                                            class="size-4"></i></button>
                                    <ul class="absolute z-50 hidden py-2 mt-1 ltr:text-left rtl:text-right list-none bg-white dark:bg-zink-600 rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                        aria-labelledby="accountSettings">
                                        <li class="px-3 mb-2 text-sm text-slate-500">
                                            Payments
                                        </li>
                                        <li>
                                            <a class="block px-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                href="#!">Create Invoice</a>
                                        </li>
                                        <li>
                                            <a class="block px-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                href="#!">Pending Billing</a>
                                        </li>
                                        <li>
                                            <a class="block px-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                href="#!">Genarate Bill</a>
                                        </li>
                                        <li>
                                            <a class="block px-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                href="#!">Subscription</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end grid-->
                </div>
                <div class="card-body !px-2.5 !py-0">
                    <ul class="flex flex-wrap w-full text-sm font-medium text-center nav-tabs">
                        <li class="group active">
                            <a href="javascript:void(0);" data-tab-toggle="" data-target="overviewTabs"
                                class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 dark:group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">{{ __('messages.overview') }}</a>
                        </li>
                        <li class="group">
                            <a href="javascript:void(0);" data-tab-toggle="" data-target="documentsTabs"
                                class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 dark:group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">{{ __('messages.documents') }}</a>
                        </li>
                        <li class="group">
                            <a href="javascript:void(0);" data-tab-toggle="" data-target="projectsTabs"
                                class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 dark:group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">{{ __('messages.projects') }}</a>
                        </li>
                        <li class="group">
                            <a href="javascript:void(0);" data-tab-toggle="" data-target="followersTabs"
                                class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 dark:group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">{{ __('messages.followers') }}</a>
                        </li>
                        <li class="group">
                            <a href="javascript:void(0);" data-tab-toggle="" data-target="financialTabs"
                                class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 dark:group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">{{ __('messages.financial_transactions') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!--end card-->

            <div class="tab-content">
                <div class="block tab-pane" id="overviewTabs">
                    <div class="grid grid-cols-1 gap-x-5 2xl:grid-cols-12">
                        <div class="2xl:col-span-9">
                            <div class="grid grid-cols-1 gap-x-5 xl:grid-cols-12">
                                <div class="xl:col-span-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3 text-15">Recent Statistics</h6>
                                            <div id="recentStatistics" class="apex-charts"
                                                data-chart-colors='["bg-custom-500", "bg-purple-500"]' dir="ltr">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="text-center card bg-custom-500 xl:col-span-3">
                                    <div class="flex flex-col h-full card-body">
                                        <img src="{{ URL::to('assets/images/medal.png') }}" alt=""
                                            class="w-2/6 mx-auto">
                                        <div class="mt-5 mb-auto">
                                            <h5 class="mb-1 text-white">{{ __('messages.congratulations') }} {{ $profileDetail->name }}</h5>
                                            <p class="text-custom-200">{{ __('messages.achievement_message') }}</p>
                                        </div>
                                        <div class="p-3 mt-5 rounded-md bg-custom-600">
                                            <h2 class="mb-1 text-white">1054</h2>
                                            <p class="text-custom-200">It's very easy to convert your points to cash now.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end grid-->
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3 text-15">{{ __('messages.overview') }}</h6>
                                    <p class="mb-2 text-slate-500 dark:text-zink-200">
                                        {{ __('messages.profile_welcome', ['name' => $profileDetail->name]) }}
                                    </p>
                                    <p class="text-slate-500 dark:text-zink-200">
                                        {{ __('messages.track_attendance_records') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="2xl:col-span-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-4 text-15">Personal Information</h6>
                                    <div class="overflow-x-auto">
                                        <table class="w-full ltr:text-left rtl:ext-right">
                                            <tbody>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.employee_id') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        {{ $profileDetail->user_id }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.designation') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        {{ $profileDetail->jobInfo->jobTitle->position ?? $profileDetail->designation ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.department') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        {{ $profileDetail->jobInfo->department->department ?? $profileDetail->department ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.role') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        {{ $profileDetail->role_name ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.phone_number') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        {{ $profileDetail->phone_number ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.email') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        {{ $profileDetail->email ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.gender') }}</th>
                                                    <td class="py-2 text-right">
                                                        @php
                                                            $gender = $profileDetail->profile->gender ?? $profileDetail->status;
                                                        @endphp
                                                        @if ($gender === 'Male')
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">{{ __('messages.male') }}</span>
                                                        @elseif ($gender === 'Female')
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-600 dark:bg-pink-500/20 dark:text-pink-400">{{ __('messages.female') }}</span>
                                                        @else
                                                            <span class="text-slate-500 dark:text-zink-200">{{ $gender ?? 'N/A' }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.employment_type') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        @php
                                                            $workType = $profileDetail->jobInfo->work_type ?? $profileDetail->position;
                                                        @endphp
                                                        @switch($workType)
                                                            @case('Full-Time Onsite') {{ __('messages.full_time') }} @break
                                                            @case('Part-Time') {{ __('messages.part_time') }} @break
                                                            @case('Remote') {{ __('messages.remote') }} @break
                                                            @case('Hybrid Work') {{ __('messages.hybrid') }} @break
                                                            @case('Contractor') {{ __('messages.contractor') }} @break
                                                            @default {{ $workType ?? 'N/A' }}
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.location') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        {{ $profileDetail->profile->location ?? $profileDetail->location ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.cv_status') }}</th>
                                                    <td class="py-2 text-right">
                                                        @if($profileDetail->documents && $profileDetail->documents->cv_file_path)
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400">
                                                                <i data-lucide="check-circle" class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
                                                                {{ __('messages.yes_cv_available') }}
                                                            </span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                                                                <i data-lucide="x-circle" class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
                                                                {{ __('messages.not_uploaded') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="py-2 font-semibold ps-0" scope="row">{{ __('messages.experience') }}</th>
                                                    <td class="py-2 text-right text-slate-500 dark:text-zink-200">
                                                        {{ $profileDetail->profile->experience_years ?? $profileDetail->experience ?? '0' }} {{ __('messages.years') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="pt-2 font-semibold ps-0" scope="row">{{ __('messages.joining_date') }}</th>
                                                    <td class="pt-2 text-right text-slate-500 dark:text-zink-200">
                                                        @php
                                                            $joinDate = $profileDetail->hiringInfo->join_date ?? $profileDetail->join_date;
                                                        @endphp
                                                        {{ !empty($joinDate) ? \Carbon\Carbon::parse($joinDate)->format('d/m/Y') : 'N/A' }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end grid-->
                </div>
                
                <!-- Tab pane Documents -->
                <div class="hidden tab-pane" id="documentsTabs">
                    <div class="flex items-center gap-3 mb-4">
                        <h5 class="underline grow">{{ __('messages.documents') }}</h5>
                    </div>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                        @if($profileDetail->documents && $profileDetail->documents->cv_file_path)
                        <div class="card">
                            <div class="card-body">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center size-12 rounded-lg bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                                        <i data-lucide="file-text" class="size-6"></i>
                                    </div>
                                    <div class="grow">
                                        <h6 class="mb-1 text-15">{{ __('messages.cv_document') }}</h6>
                                        <p class="text-slate-500 dark:text-zink-200 text-xs">{{ basename($profileDetail->documents->cv_file_path) }}</p>
                                    </div>
                                    <div class="shrink-0">
                                        <a href="{{ url('hr/employee/download-cv/' . $profileDetail->id) }}" class="flex items-center justify-center transition-all duration-150 ease-linear rounded-md size-8 bg-slate-100 hover:bg-slate-200 dark:bg-zink-600 dark:hover:bg-zink-500" title="{{ __('messages.download') }}">
                                            <i data-lucide="download" class="size-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-span-full py-10 text-center">
                            <i data-lucide="folder-open" class="size-12 mx-auto text-slate-300 mb-3"></i>
                            <p class="text-slate-500 dark:text-zink-200">{{ __('messages.no_documents_found') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Financial Transactions Tab -->
                <div class="hidden tab-pane" id="financialTabs">
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                        <!-- Salary Details Card -->
                        <div class="card shadow-md border-none bg-white dark:bg-zink-700">
                            <div class="card-body p-5">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="flex items-center justify-center size-10 rounded-lg bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400">
                                        <i data-lucide="banknote" class="size-5"></i>
                                    </div>
                                    <h6 class="text-16 font-bold">{{ __('messages.salary_details') }}</h6>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zink-600">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.base_salary') }}</span>
                                        <span class="font-bold text-slate-800 dark:text-zink-50">{{ number_format($profileDetail->salary->base_salary ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zink-600">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.allowances') }}</span>
                                        <span class="font-bold text-slate-800 dark:text-zink-50">{{ number_format($profileDetail->salary->allowances ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zink-600">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.overtime') }}</span>
                                        <span class="font-bold text-green-600">{{ number_format($profileDetail->salary->overtime ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zink-600">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.deductions') }}</span>
                                        <span class="font-bold text-red-600">{{ number_format($profileDetail->salary->deductions ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zink-600">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.advances') }}</span>
                                        <span class="font-bold text-orange-600">{{ number_format($profileDetail->salary->advances ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 bg-slate-50 dark:bg-zink-800 px-3 rounded-lg mt-4">
                                        <span class="text-slate-800 dark:text-zink-50 font-bold text-15">{{ __('messages.total_salary') }}</span>
                                        <span class="font-black text-custom-500 text-lg">{{ number_format($profileDetail->salary->total_salary ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-3">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.payment_type') }}</span>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600 dark:bg-zink-600 dark:text-zink-200">
                                            @if(($profileDetail->salary->payment_type ?? '') == 'Cash')
                                                {{ __('messages.cash') }}
                                            @elseif(($profileDetail->salary->payment_type ?? '') == 'Bank Transfer')
                                                {{ __('messages.bank_transfer') }}
                                            @else
                                                {{ $profileDetail->salary->payment_type ?? 'N/A' }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Insurance Details Card -->
                        <div class="card shadow-md border-none bg-white dark:bg-zink-700">
                            <div class="card-body p-5">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="flex items-center justify-center size-10 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">
                                        <i data-lucide="shield-check" class="size-5"></i>
                                    </div>
                                    <h6 class="text-16 font-bold">{{ __('messages.insurance_details') }}</h6>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zink-600">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.insurance_number') }}</span>
                                        <span class="font-bold text-slate-800 dark:text-zink-50">{{ $profileDetail->insurance->insurance_number ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zink-600">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.insurance_start_date') }}</span>
                                        <span class="font-bold text-slate-800 dark:text-zink-50">
                                            {{ isset($profileDetail->insurance->insurance_start_date) ? \Carbon\Carbon::parse($profileDetail->insurance->insurance_start_date)->format('d/m/Y') : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-slate-500 dark:text-zink-200 font-medium">{{ __('messages.insurance_status') }}</span>
                                        @php
                                            $status = $profileDetail->insurance->insurance_status ?? '';
                                            $statusClass = 'bg-slate-100 text-slate-600';
                                            $statusLabel = $status ?: 'N/A';
                                            
                                            if($status == 'Insured') {
                                                $statusClass = 'bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400';
                                                $statusLabel = __('messages.insured');
                                            } elseif($status == 'Not Insured') {
                                                $statusClass = 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400';
                                                $statusLabel = __('messages.not_insured');
                                            } elseif($status == 'Willing') {
                                                $statusClass = 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400';
                                                $statusLabel = __('messages.willing');
                                            }
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end tab pane Projects-->
                <!-- <div class="hidden tab-pane" id="projectsTabs">
                    <div class="flex items-center gap-3 mb-4">
                        <h5 class="underline grow">Projects</h5>
                        <div class="shrink-0">
                            <button type="button"
                                class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add
                                Project</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-x-5 md:grid-cols-2 2xl:grid-cols-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="flex">
                                    <div class="grow">
                                        <img src="{{ URL::to('assets/images/adwords.png') }}" alt=""
                                            class="h-11">
                                    </div>
                                    <div class="shrink-0">
                                        <div class="relative dropdown">
                                            <button
                                                class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50"
                                                id="projectDropdownmenu1" data-bs-toggle="dropdown"><i
                                                    data-lucide="more-horizontal" class="size-4"></i></button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                                aria-labelledby="projectDropdownmenu1">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="eye"
                                                            class="inline-block mr-1 size-3"></i> Overview</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="file-edit"
                                                            class="inline-block mr-1 size-3"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="trash-2"
                                                            class="inline-block mr-1 size-3"></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-1 text-16"><a href="#!">Chat App</a></h6>
                                    <p class="text-slate-500 dark:text-zink-200">Allows you to communicate with your
                                        customers in web chat rooms.</p>
                                </div>
                                <div
                                    class="flex w-full gap-3 mt-6 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">16 July, 2023</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Due Date</p>
                                    </div>
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">$8,740.00</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Budget</p>
                                    </div>
                                </div>
                                <div class="w-full h-1.5 mt-6 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <div class="h-1.5 rounded-full bg-custom-500" style="width: 25%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="flex">
                                    <div class="grow">
                                        <img src="{{ URL::to('assets/images/app-store.png') }}" alt=""
                                            class="h-11">
                                    </div>
                                    <div class="shrink-0">
                                        <div class="relative dropdown">
                                            <button
                                                class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50"
                                                id="projectDropdownmenu2" data-bs-toggle="dropdown"><i
                                                    data-lucide="more-horizontal" class="size-4"></i></button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                                aria-labelledby="projectDropdownmenu2">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="eye"
                                                            class="inline-block mr-1 size-3"></i> Overview</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="file-edit"
                                                            class="inline-block mr-1 size-3"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="trash-2"
                                                            class="inline-block mr-1 size-3"></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-1 text-16"><a href="#!">Business Template - UI/UX design</a></h6>
                                    <p class="text-slate-500 dark:text-zink-200">UX design process is iterative and
                                        non-linear, includes a lot of research.</p>
                                </div>
                                <div
                                    class="flex w-full gap-3 mt-6 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">28 Nov, 2023</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Due Date</p>
                                    </div>
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">$10,254.00</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Budget</p>
                                    </div>
                                </div>
                                <div class="w-full h-1.5 mt-6 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <div class="h-1.5 rounded-full bg-sky-500" style="width: 61%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="flex">
                                    <div class="grow">
                                        <img src="{{ URL::to('assets/images/profile.png') }}" alt=""
                                            class="w-12 h-12 rounded">
                                    </div>
                                    <div class="shrink-0">
                                        <div class="relative dropdown">
                                            <button
                                                class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50"
                                                id="projectDropdownmenu3" data-bs-toggle="dropdown"><i
                                                    data-lucide="more-horizontal" class="size-4"></i></button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                                aria-labelledby="projectDropdownmenu3">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="eye"
                                                            class="inline-block mr-1 size-3"></i> Overview</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="file-edit"
                                                            class="inline-block mr-1 size-3"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="trash-2"
                                                            class="inline-block mr-1 size-3"></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-1 text-16"><a href="#!">ABC Project Customization</a></h6>
                                    <p class="text-slate-500 dark:text-zink-200">The process of tailoring the overall
                                        project delivery process to meet the requirements.</p>
                                </div>
                                <div
                                    class="flex w-full gap-3 mt-6 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">20 Oct, 2023</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Due Date</p>
                                    </div>
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">$9,832.00</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Budget</p>
                                    </div>
                                </div>
                                <div class="w-full h-1.5 mt-6 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <div class="h-1.5 rounded-full bg-green-500" style="width: 87%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="flex">
                                    <div class="grow">
                                        <img src="{{ URL::to('assets/images/profile.png') }}" alt=""
                                            class="w-12 h-12 rounded">
                                    </div>
                                    <div class="shrink-0">
                                        <div class="relative dropdown">
                                            <button
                                                class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50"
                                                id="projectDropdownmenu4" data-bs-toggle="dropdown"><i
                                                    data-lucide="more-horizontal" class="size-4"></i></button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                                aria-labelledby="projectDropdownmenu4">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="eye"
                                                            class="inline-block mr-1 size-3"></i> Overview</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="file-edit"
                                                            class="inline-block mr-1 size-3"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="trash-2"
                                                            class="inline-block mr-1 size-3"></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-1 text-16"><a href="#!">starcode Design</a></h6>
                                    <p class="text-slate-500 dark:text-zink-200">Drawing created with Microsoft Expression
                                        Design, a drawing and design program for Windows.</p>
                                </div>
                                <div
                                    class="flex w-full gap-3 mt-6 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">07 Dec, 2023</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Due Date</p>
                                    </div>
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">$11,971.00</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Budget</p>
                                    </div>
                                </div>
                                <div class="w-full h-1.5 mt-6 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <div class="h-1.5 bg-purple-500 rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="flex">
                                    <div class="grow">
                                        <img src="{{ URL::to('assets/images/profile.png') }}" alt=""
                                            class="w-12 h-12 rounded">
                                    </div>
                                    <div class="shrink-0">
                                        <div class="relative dropdown">
                                            <button
                                                class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50"
                                                id="projectDropdownmenu5" data-bs-toggle="dropdown"><i
                                                    data-lucide="more-horizontal" class="size-4"></i></button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                                aria-labelledby="projectDropdownmenu5">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="eye"
                                                            class="inline-block mr-1 size-3"></i> Overview</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="file-edit"
                                                            class="inline-block mr-1 size-3"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="trash-2"
                                                            class="inline-block mr-1 size-3"></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-1 text-16"><a href="#!">HR Management</a></h6>
                                    <p class="text-slate-500 dark:text-zink-200">The strategic approach to nurturing and
                                        supporting employees and ensuring a positive.</p>
                                </div>
                                <div
                                    class="flex w-full gap-3 mt-6 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">02 Jan, 2024</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Due Date</p>
                                    </div>
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">$7,546.00</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Budget</p>
                                    </div>
                                </div>
                                <div class="w-full h-1.5 mt-6 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <div class="h-1.5 bg-purple-500 rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="flex">
                                    <div class="grow">
                                        <img src="{{ URL::to('assets/images/meta.png') }}" alt=""
                                            class="h-11">
                                    </div>
                                    <div class="shrink-0">
                                        <div class="relative dropdown">
                                            <button
                                                class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50"
                                                id="projectDropdownmenu6" data-bs-toggle="dropdown"><i
                                                    data-lucide="more-horizontal" class="size-4"></i></button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                                aria-labelledby="projectDropdownmenu6">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="eye"
                                                            class="inline-block mr-1 size-3"></i> Overview</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="file-edit"
                                                            class="inline-block mr-1 size-3"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="trash-2"
                                                            class="inline-block mr-1 size-3"></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-1 text-16"><a href="#!">Finance Apps</a></h6>
                                    <p class="text-slate-500 dark:text-zink-200">A personal budget app is a technology
                                        solution that is connected.</p>
                                </div>
                                <div
                                    class="flex w-full gap-3 mt-6 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">10 Feb, 2024</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Due Date</p>
                                    </div>
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">$13,745.00</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Budget</p>
                                    </div>
                                </div>
                                <div class="w-full h-1.5 mt-6 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <div class="h-1.5 bg-purple-500 rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="flex">
                                    <div class="grow">
                                        <img src="{{ URL::to('assets/images/search.png') }}" alt=""
                                            class="h-11">
                                    </div>
                                    <div class="shrink-0">
                                        <div class="relative dropdown">
                                            <button
                                                class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50"
                                                id="projectDropdownmenu7" data-bs-toggle="dropdown"><i
                                                    data-lucide="more-horizontal" class="size-4"></i></button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                                aria-labelledby="projectDropdownmenu7">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="eye"
                                                            class="inline-block mr-1 size-3"></i> Overview</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="file-edit"
                                                            class="inline-block mr-1 size-3"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="trash-2"
                                                            class="inline-block mr-1 size-3"></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-1 text-16"><a href="#!">Mailbox Design</a></h6>
                                    <p class="text-slate-500 dark:text-zink-200">An email template is an HTML preformatted
                                        email that you can use to create your own.</p>
                                </div>
                                <div
                                    class="flex w-full gap-3 mt-6 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">19 Feb, 2024</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Due Date</p>
                                    </div>
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">$9,120.00</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Budget</p>
                                    </div>
                                </div>
                                <div class="w-full h-1.5 mt-6 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <div class="h-1.5 bg-purple-500 rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="flex">
                                    <div class="grow">
                                        <img src="{{ URL::to('assets/images/profile.png') }}" alt=""
                                            class="w-12 h-12 rounded">
                                    </div>
                                    <div class="shrink-0">
                                        <div class="relative dropdown">
                                            <button
                                                class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50"
                                                id="projectDropdownmenu8" data-bs-toggle="dropdown"><i
                                                    data-lucide="more-horizontal" class="size-4"></i></button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]"
                                                aria-labelledby="projectDropdownmenu8">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="eye"
                                                            class="inline-block mr-1 size-3"></i> Overview</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="file-edit"
                                                            class="inline-block mr-1 size-3"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500"
                                                        href="#!"><i data-lucide="trash-2"
                                                            class="inline-block mr-1 size-3"></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-1 text-16"><a href="#!">Banking Management</a></h6>
                                    <p class="text-slate-500 dark:text-zink-200">Bank management refers to the process of
                                        managing the Bank's statutory activity.</p>
                                </div>
                                <div
                                    class="flex w-full gap-3 mt-6 text-center divide-x divide-slate-200 dark:divide-zink-500 rtl:divide-x-reverse">
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">01 March, 2024</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Due Date</p>
                                    </div>
                                    <div class="px-3 grow">
                                        <h6 class="mb-1">$24,863.00</h6>
                                        <p class="text-slate-500 dark:text-zink-200">Budget</p>
                                    </div>
                                </div>
                                <div class="w-full h-1.5 mt-6 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <div class="h-1.5 bg-purple-500 rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-4 mt-2 mb-4 md:flex-row">
                        <div class="grow">
                            <p class="text-slate-500 dark:text-zink-200">Showing <b>8</b> of <b>30</b> Results</p>
                        </div>
                        <ul class="flex flex-wrap items-center gap-2 shrink-0">
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto"><i
                                        class="size-4 rtl:rotate-180" data-lucide="chevrons-left"></i></a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto"><i
                                        class="size-4 rtl:rotate-180" data-lucide="chevron-left"></i></a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">1</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">2</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto active">3</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">4</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">5</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">6</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto"><i
                                        class="size-4 rtl:rotate-180" data-lucide="chevron-right"></i></a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto"><i
                                        class="size-4 rtl:rotate-180" data-lucide="chevrons-right"></i></a>
                            </li>
                        </ul>
                    </div>
                </div> -->

                <!--end tab pane Followers-->
                <!-- <div class="hidden tab-pane" id="followersTabs">
                    <h5 class="mb-4 underline">Followers</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-4 gap-x-5">
                        <div class="relative card">
                            <div class="card-body">
                                <p
                                    class="absolute inline-block px-5 py-1 text-xs ltr:left-0 rtl:right-0 text-custom-600 bg-custom-100 dark:bg-custom-500/20 top-5 ltr:rounded-e rtl:rounded-l">
                                    Executive Operations</p>
                                <div class="flex items-center justify-end">
                                    <p class="text-slate-500 dark:text-zink-200">Doj : 15 Jan, 2023</p>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="overflow-hidden rounded-full size-20 bg-slate-100">
                                            <img src="{{ URL::to('assets/images/avatar-3.png') }}" alt=""
                                                class="">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h4 class="mt-4 mb-2 font-semibold text-16">Ralaphe Flores </h4>
                                    </a>
                                    <div class="text-slate-500 dark:text-zink-200">
                                        <p class="mb-1">floral12@starcode.com</p>
                                        <p>+213 617 219 6245</p>
                                        <p
                                            class="inline-block px-3 py-1 my-4 font-semibold rounded-md text-slate-600 bg-slate-100 dark:bg-zink-600 dark:text-zink-200">
                                            Exp. : 1.5 years</p>
                                        <h4 class="text-15 text-custom-500">Salary : $463.42 <span
                                                class="text-xs font-normal text-slate-500 dark:text-zink-200">/
                                                Month<span></span></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative card">
                            <div class="card-body">
                                <p
                                    class="absolute inline-block px-5 py-1 text-xs text-green-600 bg-green-100 ltr:left-0 rtl:right-0 dark:bg-green-500/20 top-5 ltr:rounded-e rtl:rounded-l">
                                    Project Manager</p>
                                <div class="flex items-center justify-end">
                                    <p class="text-slate-500 dark:text-zink-200">Doj : 29 Feb, 2023</p>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="overflow-hidden rounded-full size-20 bg-slate-100">
                                            <img src="{{ URL::to('assets/images/avatar-2.png') }}" alt=""
                                                class="">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h4 class="mt-4 mb-2 font-semibold text-16">James Lash </h4>
                                    </a>
                                    <div class="text-slate-500 dark:text-zink-200">
                                        <p class="mb-1">jameslash@starcode.com</p>
                                        <p>+210 85 383 2388</p>
                                        <p
                                            class="inline-block px-3 py-1 my-4 font-semibold rounded-md text-slate-600 bg-slate-100 dark:bg-zink-600 dark:text-zink-200">
                                            Exp. : 0.5 years</p>
                                        <h4 class="text-15 text-custom-500">Salary : $701.77 <span
                                                class="text-xs font-normal text-slate-500 dark:text-zink-200">/
                                                Month<span></span></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative card">
                            <div class="card-body">
                                <p
                                    class="absolute inline-block px-5 py-1 text-xs ltr:left-0 rtl:right-0 text-sky-600 bg-sky-100 dark:bg-sky-500/20 top-5 ltr:rounded-e rtl:rounded-l">
                                    React Developer</p>
                                <div class="flex items-center justify-end">
                                    <p class="text-slate-500 dark:text-zink-200">Doj : 04 March, 2023</p>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="overflow-hidden rounded-full size-20 bg-slate-100">
                                            <img src="{{ URL::to('assets/images/avatar-4.png') }}" alt=""
                                                class="">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h4 class="mt-4 mb-2 font-semibold text-16">Angus Garnsey</h4>
                                    </a>
                                    <div class="text-slate-500 dark:text-zink-200">
                                        <p class="mb-1">angusgarnsey@starcode.com</p>
                                        <p>+210 41521 1325</p>
                                        <p
                                            class="inline-block px-3 py-1 my-4 font-semibold rounded-md text-slate-600 bg-slate-100 dark:bg-zink-600 dark:text-zink-200">
                                            Exp. : 0.7 years</p>
                                        <h4 class="text-15 text-custom-500">Salary : $478.32 <span
                                                class="text-xs font-normal text-slate-500 dark:text-zink-200">/
                                                Month<span></span></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative card">
                            <div class="card-body">
                                <p
                                    class="absolute inline-block px-5 py-1 text-xs text-yellow-600 bg-yellow-100 ltr:left-0 rtl:right-0 dark:bg-yellow-500/20 top-5 ltr:rounded-e rtl:rounded-l">
                                    Shopify Developer</p>
                                <div class="flex items-center justify-end">
                                    <p class="text-slate-500 dark:text-zink-200">Doj : 11 March, 2023</p>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="overflow-hidden rounded-full size-20 bg-slate-100">
                                            <img src="{{ URL::to('assets/images/avatar-5.png') }}" alt=""
                                                class="">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h4 class="mt-4 mb-2 font-semibold text-16">Matilda Marston</h4>
                                    </a>
                                    <div class="text-slate-500 dark:text-zink-200">
                                        <p class="mb-1">matildamarston@starcode.com</p>
                                        <p>+210 082 288 1065</p>
                                        <p
                                            class="inline-block px-3 py-1 my-4 font-semibold rounded-md text-slate-600 bg-slate-100 dark:bg-zink-600 dark:text-zink-200">
                                            Exp. : 1 years</p>
                                        <h4 class="text-15 text-custom-500">Salary : $120.37 <span
                                                class="text-xs font-normal text-slate-500 dark:text-zink-200">/
                                                Month<span></span></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative card">
                            <div class="card-body">
                                <p
                                    class="absolute inline-block px-5 py-1 text-xs text-red-600 bg-red-100 ltr:left-0 rtl:right-0 dark:bg-red-500/20 top-5 ltr:rounded-e rtl:rounded-l">
                                    Angular Developer</p>
                                <div class="flex items-center justify-end">
                                    <p class="text-slate-500 dark:text-zink-200">Doj : 22 March, 2023</p>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="overflow-hidden rounded-full size-20 bg-slate-100">
                                            <img src="{{ URL::to('assets/images/avatar-6.png') }}" alt=""
                                                class="">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h4 class="mt-4 mb-2 font-semibold text-16">Zachary Benjamin</h4>
                                    </a>
                                    <div class="text-slate-500 dark:text-zink-200">
                                        <p class="mb-1">zacharybenjamin@starcode.com</p>
                                        <p>+120 348 9730 237</p>
                                        <p
                                            class="inline-block px-3 py-1 my-4 font-semibold rounded-md text-slate-600 bg-slate-100 dark:bg-zink-600 dark:text-zink-200">
                                            Exp. : 0 years</p>
                                        <h4 class="text-15 text-custom-500">Salary : $89.99 <span
                                                class="text-xs font-normal text-slate-500 dark:text-zink-200">/
                                                Month<span></span></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative card">
                            <div class="card-body">
                                <p
                                    class="absolute inline-block px-5 py-1 text-xs text-purple-600 bg-purple-100 ltr:left-0 rtl:right-0 dark:bg-purple-500/20 top-5 ltr:rounded-e rtl:rounded-l">
                                    Graphic Designer</p>
                                <div class="flex items-center justify-end">
                                    <p class="text-slate-500 dark:text-zink-200">Doj : 09 June, 2023</p>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="overflow-hidden rounded-full size-20 bg-slate-100">
                                            <img src="{{ URL::to('assets/images/avatar-7.png') }}" alt=""
                                                class="">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h4 class="mt-4 mb-2 font-semibold text-16">Ruby Chomley</h4>
                                    </a>
                                    <div class="text-slate-500 dark:text-zink-200">
                                        <p class="mb-1">rubychomley@starcode.com</p>
                                        <p>+120 1234 56789</p>
                                        <p
                                            class="inline-block px-3 py-1 my-4 font-semibold rounded-md text-slate-600 bg-slate-100 dark:bg-zink-600 dark:text-zink-200">
                                            Exp. : 0.2 years</p>
                                        <h4 class="text-15 text-custom-500">Salary : $214.82 <span
                                                class="text-xs font-normal text-slate-500 dark:text-zink-200">/
                                                Month<span></span></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative card">
                            <div class="card-body">
                                <p
                                    class="absolute inline-block px-5 py-1 text-xs text-yellow-600 bg-yellow-100 ltr:left-0 rtl:right-0 dark:bg-yellow-500/20 top-5 ltr:rounded-e rtl:rounded-l">
                                    Shopify Developer</p>
                                <div class="flex items-center justify-end">
                                    <p class="text-slate-500 dark:text-zink-200">Doj : 27 June, 2023</p>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="overflow-hidden rounded-full size-20 bg-slate-100">
                                            <img src="{{ URL::to('assets/images/avatar-8.png') }}" alt=""
                                                class="">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h4 class="mt-4 mb-2 font-semibold text-16">Jesse Edouardy</h4>
                                    </a>
                                    <div class="text-slate-500 dark:text-zink-200">
                                        <p class="mb-1">jessedouard@starcode.com</p>
                                        <p>+87 044 017 3869</p>
                                        <p
                                            class="inline-block px-3 py-1 my-4 font-semibold rounded-md text-slate-600 bg-slate-100 dark:bg-zink-600 dark:text-zink-200">
                                            Exp. : 1.7 years</p>
                                        <h4 class="text-15 text-custom-500">Salary : $278.96 <span
                                                class="text-xs font-normal text-slate-500 dark:text-zink-200">/
                                                Month<span></span></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative card">
                            <div class="card-body">
                                <p
                                    class="absolute inline-block px-5 py-1 text-xs text-orange-600 bg-orange-100 ltr:left-0 rtl:right-0 dark:bg-orange-500/20 top-5 ltr:rounded-e rtl:rounded-l">
                                    Team Leader</p>
                                <div class="flex items-center justify-end">
                                    <p class="text-slate-500 dark:text-zink-200">Doj : 15 July, 2023</p>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="overflow-hidden rounded-full size-20 bg-slate-100">
                                            <img src="{{ URL::to('assets/images/avatar-9.png') }}" alt=""
                                                class="">
                                        </div>
                                    </div>
                                    <a href="#!">
                                        <h4 class="mt-4 mb-2 font-semibold text-16">Xavier Bower</h4>
                                    </a>
                                    <div class="text-slate-500 dark:text-zink-200">
                                        <p class="mb-1">xavierbower@starcode.com</p>
                                        <p>+159 98765 32451</p>
                                        <p
                                            class="inline-block px-3 py-1 my-4 font-semibold rounded-md text-slate-600 bg-slate-100 dark:bg-zink-600 dark:text-zink-200">
                                            Exp. : 6.7 years</p>
                                        <h4 class="text-15 text-custom-500">Salary : $901.94 <span
                                                class="text-xs font-normal text-slate-500 dark:text-zink-200">/
                                                Month<span></span></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-4 mb-4 md:flex-row">
                        <div class="grow">
                            <p class="text-slate-500 dark:text-zink-200">Showing <b>8</b> of <b>18</b> Results</p>
                        </div>
                        <ul class="flex flex-wrap items-center gap-2">
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto"><i
                                        class="size-4 rtl:rotate-180" data-lucide="chevron-left"></i></a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">1</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">2</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto active">3</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">4</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">5</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">6</a>
                            </li>
                            <li>
                                <a href="#!"
                                    class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border border-slate-200 dark:border-zink-500 rounded text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-50 dark:[&.active]:text-custom-50 [&.active]:bg-custom-500 dark:[&.active]:bg-custom-500 [&.active]:border-custom-500 dark:[&.active]:border-custom-500 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto"><i
                                        class="size-4 rtl:rotate-180" data-lucide="chevron-right"></i></a>
                            </li>
                        </ul>
                    </div>
                </div> -->

                <!--end tab pane-->
            </div>
            <!--end tab content-->
        </div>
        <!-- container-fluid -->
        
        <!-- Edit Profile Modal -->
        <div x-show="showEditModal" 
             class="fixed inset-0 z-[9999] flex items-center justify-center" 
             style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; margin: 0 !important; z-index: 99999 !important;"
             x-cloak>
            <!-- Backdrop -->
            <div x-show="showEditModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="showEditModal = false" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                 style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; margin: 0 !important;"></div>

            <!-- Modal Content -->
            <div x-show="showEditModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative p-0 overflow-hidden transition-all transform bg-white rounded-xl shadow-2xl dark:bg-zink-700 ltr:text-left rtl:text-right"
                 style="width: 500px !important; max-width: 95vw !important; margin: auto !important;">
                
                <!-- Header -->
                <div class="flex items-center justify-between p-5 border-b border-slate-200 dark:border-zink-600 bg-slate-50/50 dark:bg-zink-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center rounded-lg size-10 bg-custom-100 text-custom-500 dark:bg-custom-500/10">
                            <i data-lucide="user-cog" class="size-5"></i>
                        </div>
                        <h5 class="text-16 font-bold text-slate-800 dark:text-zink-50">{{ __('messages.edit_profile') }}</h5>
                    </div>
                    <button @click="showEditModal = false" class="transition-all duration-200 text-slate-400 hover:text-red-500 dark:hover:text-red-400">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('profile/update') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $profileDetail->user_id }}">
                    
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.full_name') }}</label>
                            <input type="text" name="name" x-model="profileData.name" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Phone -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.phone_number') }}</label>
                                <input type="text" name="phone_number" x-model="profileData.phone_number" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.gender') }}</label>
                                <select name="gender" x-model="profileData.gender" class="form-select w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5">
                                    <option value="">{{ __('messages.select_gender') }}</option>
                                    <option value="Male">{{ __('messages.male') }}</option>
                                    <option value="Female">{{ __('messages.female') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Experience -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.experience_years') }}</label>
                                <input type="number" name="experience_years" x-model="profileData.experience_years" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5">
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.location') }}</label>
                                <input type="text" name="location" x-model="profileData.location" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5">
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.address') }}</label>
                            <textarea name="address" x-model="profileData.address" rows="2" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5"></textarea>
                        </div>

                        <hr class="border-slate-200 dark:border-zink-600 my-2">

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.new_password') }}</label>
                                <input type="password" name="password" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5" placeholder="********">
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.confirm_password') }}</label>
                                <input type="password" name="password_confirmation" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5" placeholder="********">
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-3 mt-8">
                        <button type="button" @click="showEditModal = false" class="px-6 py-2.5 text-slate-500 bg-slate-100 hover:bg-slate-200 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-zink-500 rounded-md transition-all font-medium">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-white bg-custom-500 border border-custom-500 hover:bg-custom-600 rounded-md transition-all font-medium shadow-lg shadow-custom-500/20">
                            {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- Close Alpine Wrapper -->

    <style>
        [x-cloak] { display: none !important; }
        .animate-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
@endsection

@section('script')
    <!-- pages-account init js-->
    <script src="{{ URL::to('assets/js/pages/pages-account.init.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#profile-img-file-input').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('user_id', '{{ $profileDetail->user_id }}');
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route("profile/update-avatar") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#user-profile-img').attr('src', response.avatar_url).removeClass('hidden');
                            $('#user-profile-placeholder').addClass('hidden');
                            
                            if ('{{ auth()->user()->user_id }}' === '{{ $profileDetail->user_id }}') {
                                $('.user-profile-image').attr('src', response.avatar_url);
                            }

                            Swal.fire({
                                icon: 'success',
                                title: '{{ __("messages.success") }}',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("messages.error") }}',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("messages.error") }}',
                            text: xhr.responseJSON?.message || 'Something went wrong!'
                        });
                    }
                });
            });
        });
    </script>
@endsection
