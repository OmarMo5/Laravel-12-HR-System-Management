@php
    $userRole = Session::get('role_name');
@endphp

<div>
    <ul class="group-data-[layout=horizontal]:flex group-data-[layout=horizontal]:flex-col group-data-[layout=horizontal]:md:flex-row"
        id="navbar-nav">

        <!-- Menu Header -->
        <li
            class="px-4 py-1 text-vertical-menu-item group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern uppercase font-medium text-[11px] cursor-default tracking-wider group-data-[sidebar-size=sm]:hidden group-data-[layout=horizontal]:hidden inline-block group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:underline group-data-[sidebar-size=md]:text-center group-data-[sidebar=dark]:dark:text-zink-200">
            <span data-key="t-menu">{{ __('messages.menu') }}</span>
        </li>

        <!-- Dashboard Link (الكل يشوفه إلا الـ Employee) -->
        @if(in_array($userRole, ['Admin', 'HR']))
        <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
            <a href="{{ route('home') }}"
                class="{{ set_active(['home']) }} relative flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=brand]:[&.active]:bg-vertical-menu-item-bg-active-brand group-data-[sidebar=brand]:[&.active]:text-vertical-menu-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar=modern]:[&.active]:bg-vertical-menu-item-bg-active-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-item-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200"
                data-key="t-hr">
                <span
                    class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center">
                    <i data-lucide="monitor-dot"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition-all duration-300 group-hover/menu-link:rotate-12 group-hover/menu-link:scale-110 group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                </span>
                <span
                    class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                    data-key="t-hr-management">{{ __('messages.hr_dashboard') }}
                </span>
            </a>
        </li>
        @endif

        <!-- HR Management Section -->
        <li
            class="px-4 py-1 text-vertical-menu-item group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern uppercase font-medium text-[11px] cursor-default tracking-wider group-data-[sidebar-size=sm]:hidden group-data-[layout=horizontal]:hidden inline-block group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:underline group-data-[sidebar-size=md]:text-center group-data-[sidebar=dark]:dark:text-zink-200">
            <span data-key="t-hr-management">{{ __('messages.hr_management') }}</span>
        </li>

        <!-- ============================================================ -->
        <!-- Employee Management Dropdown - يظهر لـ Admin و HR فقط (يخفي عن Employee و Manager) -->
        <!-- ============================================================ 
        @if(in_array($userRole, ['Admin', 'HR']))
        <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
            <a class="{{ request()->routeIs('hr/employee/list') || request()->routeIs('employee/attendance/dashboard')
                ? 'active show'
                : '' }} dropdown-button flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=sm]:group-hover/sm:w-[calc(theme('spacing.vertical-menu-sm')_*_3.63)] group-data-[sidebar-size=sm]:group-hover/sm:bg-vertical-menu group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:group-hover/sm:bg-vertical-menu-dark group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:group-hover/sm:bg-vertical-menu-border-modern group-data-[sidebar-size=sm]:group-data-[sidebar=brand]:group-hover/sm:bg-vertical-menu-brand group-data-[sidebar-size=sm]:my-0 group-data-[sidebar-size=sm]:rounded-b-none group-data-[layout=horizontal]:m-0 group-data-[layout=horizontal]:ltr:pr-8 group-data-[layout=horizontal]:rtl:pl-8 group-data-[layout=horizontal]:hover:bg-transparent [&.dropdown-button]:before:absolute [&.dropdown-button]:before:content-['\ea6e'] [&.dropdown-button]:before:font-remix ltr:[&.dropdown-button]:before:right-2 rtl:[&.dropdown-button]:before:left-2 [&.dropdown-button]:before:text-16 group-data-[sidebar-size=sm]:[&.dropdown-button]:before:hidden group-data-[sidebar-size=md]:[&.dropdown-button]:before:hidden group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200 rtl:[&.dropdown-button]:before:rotate-180"
                href="#!">
                <span
                    class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center">
                    <i data-lucide="users"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition-all duration-300 group-hover/menu-link:rotate-12 group-hover/menu-link:scale-110 group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                </span>
                <span
                    class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                    data-key="t-employee">{{ __('messages.employee') }}</span>
            </a>

            <div
                class="dropdown-content {{ request()->routeIs('hr/employee/list') || request()->routeIs('employee/attendance/dashboard') ? 'show' : '' }} group-data-[sidebar-size=sm]:ltr:left-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:right-vertical-menu-sm group-data-[sidebar-size=sm]:w-[calc(theme('spacing.vertical-menu-sm')_*_2.8)] group-data-[sidebar-size=sm]:absolute group-data-[sidebar-size=sm]:rounded-b-sm bg-vertical-menu group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar=dark]:dark:bg-zink-700 group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar=modern]:bg-transparent group-data-[layout=horizontal]:md:absolute group-data-[layout=horizontal]:top-full group-data-[layout=horizontal]:md:w-44 group-data-[layout=horizontal]:py-2 group-data-[layout=horizontal]:rounded-b-md group-data-[layout=horizontal]:md:shadow-lg group-data-[layout=horizontal]:md:shadow-slate-500/10 group-data-[layout=horizontal]:dark:bg-zink-700 group-data-[layout=horizontal]:dark:md:shadow-zink-600/20 {{ request()->routeIs('hr/employee/list') || request()->routeIs('employee/attendance/dashboard')
                    ? ''
                    : 'hidden' }} group-data-[sidebar-size=sm]:{{ request()->routeIs('hr/employee/list') || request()->routeIs('employee/attendance/dashboard')
                    ? 'block'
                    : 'hidden' }} group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:rounded-br-md group-data-[sidebar-size=sm]:rtl:rounded-br-md group-data-[sidebar-size=sm]:shadow-lg group-data-[sidebar-size=sm]:shadow-slate-700/10">
                <ul
                    class="ltr:pl-[1.75rem] rtl:pr-[1.75rem] group-data-[sidebar-size=md]:ltr:pl-0 group-data-[sidebar-size=md]:rtl:pr-0 group-data-[sidebar-size=sm]:ltr:pl-0 group-data-[sidebar-size=sm]:rtl:pr-0 group-data-[sidebar-size=sm]:py-2 group-data-[layout=horizontal]:ltr:pl-0 group-data-[layout=horizontal]:rtl:pr-0">
                    
                    <li>
                        <a href="{{ route('employee/attendance/dashboard') }}"
                            class="{{ set_active(['employee/attendance/dashboard']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden"
                            data-key="t-my-attendance">
                            <i data-lucide="clock" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.my_attendance') }}
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif-->

        {{-- @if(in_array($userRole, ['Admin', 'HR']))
            <li>
                <a href="{{ route('hr/employee/list') }}"
                    class="{{ set_active(['hr/employee/list']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden"
                    data-key="t-employees-list">
                    <i data-lucide="list" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                    {{ __('messages.employees_list') }}
                </a>
            </li>
        @endif --}}
        @if(in_array($userRole, ['Admin', 'HR']))
            <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
                <a href="{{ route('hr/employee/list') }}"
                    class="{{ set_active(['hr/employee/list']) }} relative flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=brand]:[&.active]:bg-vertical-menu-item-bg-active-brand group-data-[sidebar=brand]:[&.active]:text-vertical-menu-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar=modern]:[&.active]:bg-vertical-menu-item-bg-active-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-item-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200"
                    data-key="t-employees_list">
                    <span
                        class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center">
                        <i data-lucide="list"
                            class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition-all duration-300 group-hover/menu-link:rotate-12 group-hover/menu-link:scale-110 group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                    </span>
                    <span
                        class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                        data-key="t-employees_list">{{ __('messages.employees_list') }}</span>
                </a>
            </li>
        @endif
        
        <!-- ============================================================ -->
        <!-- Attendance Management Dropdown - يظهر للكل لكن بمحتوى مختلف -->
        <!-- ============================================================ -->
        <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
            <a class="{{ request()->routeIs('hr/attendance/page') ||
            request()->routeIs('employee/attendance/history') ||
            request()->routeIs('hr/attendance/main/page')
                ? 'active show'
                : '' }} dropdown-button flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=sm]:group-hover/sm:w-[calc(theme('spacing.vertical-menu-sm')_*_3.63)] group-data-[sidebar-size=sm]:group-hover/sm:bg-vertical-menu group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:group-hover/sm:bg-vertical-menu-dark group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:group-hover/sm:bg-vertical-menu-border-modern group-data-[sidebar-size=sm]:group-data-[sidebar=brand]:group-hover/sm:bg-vertical-menu-brand group-data-[sidebar-size=sm]:my-0 group-data-[sidebar-size=sm]:rounded-b-none group-data-[layout=horizontal]:m-0 group-data-[layout=horizontal]:ltr:pr-8 group-data-[layout=horizontal]:rtl:pl-8 group-data-[layout=horizontal]:hover:bg-transparent [&.dropdown-button]:before:absolute [&.dropdown-button]:before:content-['\ea6e'] [&.dropdown-button]:before:font-remix ltr:[&.dropdown-button]:before:right-2 rtl:[&.dropdown-button]:before:left-2 [&.dropdown-button]:before:text-16 group-data-[sidebar-size=sm]:[&.dropdown-button]:before:hidden group-data-[sidebar-size=md]:[&.dropdown-button]:before:hidden group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200 rtl:[&.dropdown-button]:before:rotate-180"
                href="#!">
                <span
                    class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center">
                    <i data-lucide="calendar-check"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition-all duration-300 group-hover/menu-link:rotate-12 group-hover/menu-link:scale-110 group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                </span>
                <span
                    class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                    data-key="t-attendance">{{ __('messages.attendance') }}</span>
            </a>

            <!-- Dropdown Content -->
            <div
                class="dropdown-content {{ request()->routeIs('hr/attendance/page') ||
                request()->routeIs('employee/attendance/history') ||
                request()->routeIs('hr/attendance/main/page')
                    ? 'show'
                    : '' }} group-data-[sidebar-size=sm]:ltr:left-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:right-vertical-menu-sm group-data-[sidebar-size=sm]:w-[calc(theme('spacing.vertical-menu-sm')_*_2.8)] group-data-[sidebar-size=sm]:absolute group-data-[sidebar-size=sm]:rounded-b-sm bg-vertical-menu group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar=dark]:dark:bg-zink-700 group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar=modern]:bg-transparent group-data-[layout=horizontal]:md:absolute group-data-[layout=horizontal]:top-full group-data-[layout=horizontal]:md:w-44 group-data-[layout=horizontal]:py-2 group-data-[layout=horizontal]:rounded-b-md group-data-[layout=horizontal]:md:shadow-lg group-data-[layout=horizontal]:md:shadow-slate-500/10 group-data-[layout=horizontal]:dark:bg-zink-700 group-data-[layout=horizontal]:dark:md:shadow-zink-600/20 {{ request()->routeIs('hr/attendance/page') ||
                request()->routeIs('employee/attendance/history') ||
                request()->routeIs('hr/attendance/main/page')
                    ? ''
                    : 'hidden' }} group-data-[sidebar-size=sm]:{{ request()->routeIs('hr/attendance/page') ||
                request()->routeIs('employee/attendance/history') ||
                request()->routeIs('hr/attendance/main/page')
                    ? 'block'
                    : 'hidden' }} group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:rounded-br-md group-data-[sidebar-size=sm]:rtl:rounded-br-md group-data-[sidebar-size=sm]:shadow-lg group-data-[sidebar-size=sm]:shadow-slate-700/10">
                <ul
                    class="ltr:pl-[1.75rem] rtl:pr-[1.75rem] group-data-[sidebar-size=md]:ltr:pl-0 group-data-[sidebar-size=md]:rtl:pr-0 group-data-[sidebar-size=sm]:ltr:pl-0 group-data-[sidebar-size=sm]:rtl:pr-0 group-data-[sidebar-size=sm]:py-2 group-data-[layout=horizontal]:ltr:pl-0 group-data-[layout=horizontal]:rtl:pr-0">
                    
                    <!-- Attendance HR - يظهر لـ Admin, HR, Manager فقط -->
                    @if(in_array($userRole, ['Admin', 'HR', 'Manager']))
                    <li>
                        <a href="{{ route('hr/attendance/page') }}"
                            class="{{ set_active(['hr/attendance/page']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden">
                            <i data-lucide="clipboard-list" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.attendance_hr') }}
                        </a>
                    </li>
                    @endif
                    
                    @if(in_array($userRole, ['Employee']))
                    <!-- My Attendance - يظهر للكل -->
                    <li>
                        <a href="{{ route('employee/attendance/dashboard') }}"
                            class="{{ set_active(['employee/attendance/dashboard']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden">
                            <i data-lucide="clock" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.my_attendance') }}
                        </a>
                    </li>
                    <!-- My History - يظهر للكل 
                    <li>
                        <a href="{{ route('employee/attendance/history') }}"
                            class="{{ set_active(['employee/attendance/history']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden">
                            <i data-lucide="history" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.attendance') }}
                        </a>
                    </li>-->
                    @endif
                    
                    <!-- Main Attendance - يظهر لـ Admin, HR, Manager فقط -->
                    @if(in_array($userRole, ['Admin', 'HR', 'Manager']))
                    <li>
                        <a href="{{ route('hr/attendance/main/page') }}"
                            class="{{ set_active(['hr/attendance/main/page']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden">
                            <i data-lucide="layout-dashboard" class="size-4 mr-2"></i>
                            {{ __('messages.main_attendance') }}
                        </a>
                    </li>
                    @endif

                    @if(in_array($userRole, ['Admin', 'HR', 'Manager']))
                    <li>
                        <a href="{{ route('employee/attendance/dashboard') }}"
                            class="{{ set_active(['employee/attendance/dashboard']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden"
                            data-key="t-my-attendance">
                            <i data-lucide="clock" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.my_attendance') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li>

        <!-- ============================================================ -->
        <!-- Leaves Management Dropdown - الكل يشوفه (لكن المحتوى يختلف حسب الـ Role) -->
        <!-- ============================================================ -->
        <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
            <a class="{{ request()->routeIs('hr/leave/employee/page') ||
            request()->routeIs('hr/create/leave/employee/page') ||
            request()->routeIs('hr/leave/hr/page') ||
            request()->routeIs('hr/create/leave/hr/page') ||
            request()->is('hr/view/detail/leave/employee/*')
                ? 'active show'
                : '' }} dropdown-button flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=sm]:group-hover/sm:w-[calc(theme('spacing.vertical-menu-sm')_*_3.63)] group-data-[sidebar-size=sm]:group-hover/sm:bg-vertical-menu group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:group-hover/sm:bg-vertical-menu-dark group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:group-hover/sm:bg-vertical-menu-border-modern group-data-[sidebar-size=sm]:group-data-[sidebar=brand]:group-hover/sm:bg-vertical-menu-brand group-data-[sidebar-size=sm]:my-0 group-data-[sidebar-size=sm]:rounded-b-none group-data-[layout=horizontal]:m-0 group-data-[layout=horizontal]:ltr:pr-8 group-data-[layout=horizontal]:rtl:pl-8 group-data-[layout=horizontal]:hover:bg-transparent [&.dropdown-button]:before:absolute [&.dropdown-button]:before:content-['\ea6e'] [&.dropdown-button]:before:font-remix ltr:[&.dropdown-button]:before:right-2 rtl:[&.dropdown-button]:before:left-2 [&.dropdown-button]:before:text-16 group-data-[sidebar-size=sm]:[&.dropdown-button]:before:hidden group-data-[sidebar-size=md]:[&.dropdown-button]:before:hidden group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200 rtl:[&.dropdown-button]:before:rotate-180"
                href="#!">
                <span
                    class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center">
                    <i data-lucide="calendar-off"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition-all duration-300 group-hover/menu-link:rotate-12 group-hover/menu-link:scale-110 group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                </span>
                <span
                    class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                    data-key="t-leaves">{{ __('messages.leaves') }}</span>
            </a>

            <!-- Dropdown Content -->
            <div
                class="dropdown-content {{ request()->routeIs('hr/leave/employee/page') ||
                request()->routeIs('hr/create/leave/employee/page') ||
                request()->routeIs('hr/leave/hr/page') ||
                request()->routeIs('hr/create/leave/hr/page') ||
                request()->is('hr/view/detail/leave/employee/*')
                    ? 'show'
                    : '' }} group-data-[sidebar-size=sm]:ltr:left-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:right-vertical-menu-sm group-data-[sidebar-size=sm]:w-[calc(theme('spacing.vertical-menu-sm')_*_2.8)] group-data-[sidebar-size=sm]:absolute group-data-[sidebar-size=sm]:rounded-b-sm bg-vertical-menu group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar=dark]:dark:bg-zink-700 group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar=modern]:bg-transparent group-data-[layout=horizontal]:md:absolute group-data-[layout=horizontal]:top-full group-data-[layout=horizontal]:md:w-44 group-data-[layout=horizontal]:py-2 group-data-[layout=horizontal]:rounded-b-md group-data-[layout=horizontal]:md:shadow-lg group-data-[layout=horizontal]:md:shadow-slate-500/10 group-data-[layout=horizontal]:dark:bg-zink-700 group-data-[layout=horizontal]:dark:md:shadow-zink-600/20 {{ request()->routeIs('hr/leave/employee/page') ||
                request()->routeIs('hr/create/leave/employee/page') ||
                request()->routeIs('hr/leave/hr/page') ||
                request()->routeIs('hr/create/leave/hr/page') ||
                request()->is('hr/view/detail/leave/employee/*')
                    ? ''
                    : 'hidden' }} group-data-[sidebar-size=sm]:{{ request()->routeIs('hr/leave/employee/page') ||
                request()->routeIs('hr/create/leave/employee/page') ||
                request()->routeIs('hr/leave/hr/page') ||
                request()->routeIs('hr/create/leave/hr/page') ||
                request()->is('hr/view/detail/leave/employee/*')
                    ? 'block'
                    : 'hidden' }} group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:rounded-br-md group-data-[sidebar-size=sm]:rtl:rounded-br-md group-data-[sidebar-size=sm]:shadow-lg group-data-[sidebar-size=sm]:shadow-slate-700/10">
                <ul
                    class="ltr:pl-[1.75rem] rtl:pr-[1.75rem] group-data-[sidebar-size=md]:ltr:pl-0 group-data-[sidebar-size=md]:rtl:pr-0 group-data-[sidebar-size=sm]:ltr:pl-0 group-data-[sidebar-size=sm]:rtl:pr-0 group-data-[sidebar-size=sm]:py-2 group-data-[layout=horizontal]:ltr:pl-0 group-data-[layout=horizontal]:rtl:pr-0">
                    <!-- By Employee (الكل يشوفه) -->
                    <li>
                        <a href="{{ route('hr/leave/employee/page') }}"
                            class="{{ set_active(['hr/leave/employee/page']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden">
                            <i data-lucide="eye" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.by_employee') }}
                        </a>
                    </li>
                    <!-- Add Leave Employee (الكل يشوفه) -->
                    <li>
                        <a href="{{ route('hr/create/leave/employee/page') }}"
                            class="{{ set_active(['hr/create/leave/employee/page']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden">
                            <i data-lucide="plus-circle" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.add_leave_employee') }}
                        </a>
                    </li>
                    
                    <!-- ============================================================ -->
                    <!-- الأقسام التالية تظهر لـ Admin, HR, Manager فقط (تخفي عن Employee) -->
                    <!-- ============================================================ -->
                    @if(in_array($userRole, ['Admin', 'HR', 'Manager']))
                    <!-- By HR -->
                    <li>
                        <a href="{{ route('hr/leave/hr/page') }}"
                            class="{{ set_active(['hr/leave/hr/page']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden">
                            <i data-lucide="users" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.by_hr') }}
                        </a>
                    </li>
                    <!-- Add Leave HR -->
                    <li>
                        <a href="{{ route('hr/create/leave/hr/page') }}"
                            class="{{ set_active(['hr/create/leave/hr/page']) }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover hover:translate-x-1 [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden">
                            <i data-lucide="user-plus" class="size-4 mr-2 transition-all group-hover:scale-110"></i>
                            {{ __('messages.add_leave_hr') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li>

        <!-- ============================================================ -->
        <!-- Holidays Link - يظهر للكل (Admin, HR, Manager, Employee) -->
        <!-- ============================================================ -->
        <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
            <a href="{{ route('hr/holidays/page') }}"
                class="{{ set_active(['hr/holidays/page']) }} relative flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=brand]:[&.active]:bg-vertical-menu-item-bg-active-brand group-data-[sidebar=brand]:[&.active]:text-vertical-menu-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar=modern]:[&.active]:bg-vertical-menu-item-bg-active-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-item-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200"
                data-key="t-holidays">
                <span
                    class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center">
                    <i data-lucide="gift"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition-all duration-300 group-hover/menu-link:rotate-12 group-hover/menu-link:scale-110 group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                </span>
                <span
                    class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                    data-key="t-holidays">{{ __('messages.holidays') }}</span>
            </a>
        </li>


        <!-- ============================================================ -->
        <!-- Department Link - يظهر لـ Admin و HR فقط (يخفي عن Employee و Manager) -->
        <!-- ============================================================ -->
        @if(in_array($userRole, ['Admin', 'HR']))
        <!-- <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
            <a href="{{ route('hr/department/org-chart') }}"
                class="{{ set_active(['hr/department/org-chart']) }} relative flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=brand]:[&.active]:bg-vertical-menu-item-bg-active-brand group-data-[sidebar=brand]:[&.active]:text-vertical-menu-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar=modern]:[&.active]:bg-vertical-menu-item-bg-active-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-item-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200"
                data-key="t-department">
                <span
                    class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center">
                    <i data-lucide="building-2"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition-all duration-300 group-hover/menu-link:rotate-12 group-hover/menu-link:scale-110 group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                </span>
                <span
                    class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                    data-key="t-department">{{ __('messages.org_chart') }}</span>
            </a>
        </li> -->
        @endif
        @if(in_array($userRole, ['Admin', 'HR']))
        <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
            <a href="{{ route('hr/department/page') }}"
                class="{{ set_active(['hr/department/page']) }} relative flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=brand]:[&.active]:bg-vertical-menu-item-bg-active-brand group-data-[sidebar=brand]:[&.active]:text-vertical-menu-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar=modern]:[&.active]:bg-vertical-menu-item-bg-active-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-item-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200"
                data-key="t-department">
                <span
                    class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center">
                    <i data-lucide="building-2"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition-all duration-300 group-hover/menu-link:rotate-12 group-hover/menu-link:scale-110 group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                </span>
                <span
                    class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                    data-key="t-department">{{ __('messages.department') }}</span>
            </a>
        </li>
        @endif

        <!-- Pages Section Header (الكل يشوفه) -->
        <li
            class="px-4 py-1 text-vertical-menu-item group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern uppercase font-medium text-[11px] cursor-default tracking-wider group-data-[sidebar-size=sm]:hidden group-data-[layout=horizontal]:hidden inline-block group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:underline group-data-[sidebar-size=md]:text-center">
            <span data-key="t-components">{{ __('messages.pages') }}</span>
        </li>

        <!-- Pages Dropdown (الكل يشوفه) -->
        <li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
            <a class="{{ request()->is('page/account/*') ? 'active' : '' }} {{ request()->is('page/account/*') ? 'show' : '' }} relative dropdown-button flex items-center ltr:pl-3 rtl:pr-3 ltr:pr-5 rtl:pl-5 mx-3 my-1 group/menu-link text-vertical-menu-item-font-size font-normal transition-all duration-75 ease-linear rounded-md py-2.5 text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=brand]:[&.active]:bg-vertical-menu-item-bg-active-brand group-data-[sidebar=brand]:[&.active]:text-vertical-menu-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar=modern]:[&.active]:bg-vertical-menu-item-bg-active-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-item-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=sm]:group-hover/sm:w-[calc(theme('spacing.vertical-menu-sm')_*_3.63)] group-data-[sidebar-size=sm]:group-hover/sm:bg-vertical-menu group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:group-hover/sm:bg-vertical-menu-dark group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:group-hover/sm:bg-vertical-menu-border-modern group-data-[sidebar-size=sm]:group-data-[sidebar=brand]:group-hover/sm:bg-vertical-menu-brand group-data-[sidebar-size=sm]:my-0 group-data-[sidebar-size=sm]:rounded-b-none group-data-[layout=horizontal]:m-0 group-data-[layout=horizontal]:ltr:pr-8 group-data-[layout=horizontal]:rtl:pl-8 group-data-[layout=horizontal]:hover:bg-transparent group-data-[layout=horizontal]:[&.active]:bg-transparent [&.dropdown-button]:before:absolute [&.dropdown-button]:[&.show]:before:content-['\ea4e'] [&.dropdown-button]:before:content-['\ea6e'] [&.dropdown-button]:before:font-remix ltr:[&.dropdown-button]:before:right-2 rtl:[&.dropdown-button]:before:left-2 [&.dropdown-button]:before:text-16 group-data-[sidebar-size=sm]:[&.dropdown-button]:before:hidden group-data-[sidebar-size=md]:[&.dropdown-button]:before:hidden group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200 group-data-[sidebar=dark]:[&.active]:dark:bg-zink-600 group-data-[layout=horizontal]:dark:[&.active]:text-custom-500 rtl:[&.dropdown-button]:before:rotate-180 group-data-[layout=horizontal]:[&.dropdown-button]:before:rotate-90 group-data-[layout=horizontal]:[&.dropdown-button]:[&.show]:before:rotate-0 rtl:[&.dropdown-button]:[&.show]:before:rotate-0"
                href="#!">
                <span
                    class="min-w-[1.75rem] group-data-[sidebar-size=sm]:h-[1.75rem] inline-block text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:items-center"><i
                        data-lucide="codesandbox"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i></span>
                <span
                    class="group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10 align-middle group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:hidden"
                    data-key="t-pages">{{ __('messages.pages') }}</span>
            </a>
            <div
                class="dropdown-content group-data-[sidebar-size=sm]:ltr:left-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:right-vertical-menu-sm group-data-[sidebar-size=sm]:w-[calc(theme('spacing.vertical-menu-sm')_*_2.8)] group-data-[sidebar-size=sm]:absolute group-data-[sidebar-size=sm]:rounded-b-sm bg-vertical-menu group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar=dark]:dark:bg-zink-700 group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar=modern]:bg-transparent group-data-[layout=horizontal]:md:absolute group-data-[layout=horizontal]:top-full group-data-[layout=horizontal]:md:w-44 group-data-[layout=horizontal]:py-2 group-data-[layout=horizontal]:rounded-b-md group-data-[layout=horizontal]:md:shadow-lg group-data-[layout=horizontal]:md:shadow-slate-500/10 group-data-[layout=horizontal]:dark:bg-zink-700 group-data-[layout=horizontal]:dark:md:shadow-zink-600/20 hidden group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:rounded-br-md group-data-[sidebar-size=sm]:rtl:rounded-br-md group-data-[sidebar-size=sm]:shadow-lg group-data-[sidebar-size=sm]:shadow-slate-700/10 group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:group-hover/sm:to-vertical-menu-to-modern group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:group-hover/sm:from-vertical-menu-from-modern group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:group-hover/sm:bg-gradient-to-tr">
                <ul
                    class="ltr:pl-[1.75rem] rtl:pr-[1.75rem] group-data-[sidebar-size=md]:ltr:pl-0 group-data-[sidebar-size=md]:rtl:pr-0 group-data-[sidebar-size=sm]:ltr:pl-0 group-data-[sidebar-size=sm]:rtl:pr-0 group-data-[sidebar-size=sm]:py-2 group-data-[layout=horizontal]:ltr:pl-0 group-data-[layout=horizontal]:rtl:pr-0">
                    <li>
                        <a href="{{ url('page/account/' . Session::get('user_id')) }}"
                            class="{{ request()->is('page/account/*') ? 'active' : '' }} relative flex items-center px-6 py-2 text-vertical-menu-item-font-size transition-all duration-75 ease-linear text-vertical-menu-sub-item hover:text-vertical-menu-sub-item-hover [&.active]:text-vertical-menu-sub-item-active before:absolute ltr:before:left-1.5 rtl:before:right-1.5 before:top-4 before:w-1 before:h-1 before:rounded before:transition-all before:duration-75 before:ease-linear before:bg-vertical-menu-sub-item hover:before:bg-vertical-menu-sub-item-hover [&.active]:before:bg-vertical-menu-sub-item-active group-data-[sidebar=dark]:text-vertical-menu-sub-item-dark group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:text-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:text-custom-500 group-data-[layout=horizontal]:dark:[&.active]:text-custom-500 group-data-[sidebar=dark]:before:bg-vertical-menu-sub-item-dark/50 group-data-[sidebar=dark]:hover:before:bg-vertical-menu-sub-item-hover-dark group-data-[sidebar=dark]:dark:hover:before:bg-custom-500 group-data-[sidebar=dark]:[&.active]:before:bg-vertical-menu-sub-item-active-dark group-data-[sidebar=dark]:dark:[&.active]:before:bg-custom-500 group-data-[sidebar=brand]:text-vertical-menu-sub-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-sub-item-hover-brand group-data-[sidebar=brand]:before:bg-vertical-menu-sub-item-brand/80 group-data-[sidebar=brand]:hover:before:bg-vertical-menu-sub-item-hover-brand/80 group-data-[sidebar=brand]:[&.active]:before:bg-vertical-menu-sub-item-active-brand/80 group-data-[sidebar=brand]:[&.active]:text-vertical-menu-sub-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-sub-item-modern group-data-[sidebar=modern]:before:bg-vertical-menu-sub-item-modern/70 group-data-[sidebar=modern]:hover:text-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:before:hover:bg-vertical-menu-sub-item-hover-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar=modern]:before:[&.active]:text-vertical-menu-sub-item-active-modern group-data-[sidebar-size=md]:before:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:before:hidden group-data-[layout=horizontal]:before:left-[1.4rem] group-data-[layout=horizontal]:md:before:hidden group-data-[layout=horizontal]:ltr:pl-10 group-data-[layout=horizontal]:rtl:pr-10 group-data-[layout=horizontal]:ltr:pr-5 group-data-[layout=horizontal]:rtl:pl-5 group-data-[layout=horizontal]:md:!px-5"
                            data-key="t-account">{{ __('messages.account') }}</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>

<style>
    /* Custom animations for sidebar items */
    @keyframes pulse-icon {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }

    .group/menu-link:hover i {
        animation: pulse-icon 0.5s ease-in-out;
    }

    /* Active state indicator */
    [class*="active"] i {
        color: rgb(59 130 246 / var(--tw-text-opacity));
    }

    /* Smooth transitions */
    .group/menu-link,
    .group/menu-link i,
    .group/menu-link span {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Dropdown submenu hover effect */
    .dropdown-content li a:hover {
        transform: translateX(4px);
    }

    /* Icon container hover effect */
    .group/menu-link:hover .min-w-\[1\.75rem\] {
        transform: scale(1.05);
    }

    /* Active dropdown styles */
    .dropdown-button.active {
        background-color: rgba(59, 130, 246, 0.1);
        color: rgb(59 130 246);
    }

    .dropdown-button.active i {
        fill: rgba(59, 130, 246, 0.2);
    }

    /* Show dropdown when active */
    .dropdown-content.show {
        display: block !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dropdown-content.show {
            position: static !important;
            box-shadow: none !important;
            margin-left: 1rem;
        }
    }
</style>