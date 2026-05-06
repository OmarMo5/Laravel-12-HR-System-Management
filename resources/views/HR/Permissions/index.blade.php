@extends('layouts.master')
@section('content')
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ 
        showDeleteModal: false,
        showReasonModal: false,
        currentReason: '',
        deleteRoute: ''
    }">
        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-[9999] flex items-center justify-center" 
             style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; margin: 0 !important; z-index: 99999 !important;"
             x-cloak>
            <!-- Backdrop -->
            <div x-show="showDeleteModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="showDeleteModal = false" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                 style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; margin: 0 !important;"></div>

            <!-- Modal Content -->
            <div x-show="showDeleteModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative p-8 overflow-hidden transition-all transform bg-white rounded-xl shadow-2xl dark:bg-zink-700 text-center"
                 style="width: 400px !important; max-width: 95vw !important; margin: auto !important;">
                
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center rounded-full size-16 bg-red-100 text-red-500 dark:bg-red-500/10 mb-5">
                        <i data-lucide="alert-triangle" class="size-8"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800 dark:text-zink-50 mb-2">{{ __('messages.confirm_delete') }}</h4>
                    <p class="text-slate-500 dark:text-zink-200 mb-8">{{ __('messages.confirm_delete_msg') ?? 'This action cannot be undone. Are you sure you want to delete this permission?' }}</p>
                </div>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" @click="showDeleteModal = false" class="px-6 py-2.5 text-slate-500 bg-slate-100 hover:bg-slate-200 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-zink-500 rounded-md transition-all font-medium">
                        {{ __('messages.cancel') }}
                    </button>
                    <form :action="deleteRoute" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 text-white bg-red-500 border border-red-500 hover:bg-red-600 rounded-md transition-all font-medium shadow-lg shadow-red-500/20">
                            {{ __('messages.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reason Detail Modal -->
        <div x-show="showReasonModal" 
             class="fixed inset-0 z-[9999] flex items-center justify-center" 
             style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; margin: 0 !important; z-index: 99999 !important;"
             x-cloak>
            <!-- Backdrop -->
            <div x-show="showReasonModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="showReasonModal = false" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                 style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; margin: 0 !important;"></div>

            <!-- Modal Content -->
            <div x-show="showReasonModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative p-0 overflow-hidden transition-all transform bg-white rounded-xl shadow-2xl dark:bg-zink-700 ltr:text-left rtl:text-right"
                 style="width: 450px !important; max-width: 95vw !important; margin: auto !important;">
                
                <!-- Header -->
                <div class="flex items-center justify-between p-5 border-b border-slate-200 dark:border-zink-600 bg-slate-50/50 dark:bg-zink-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center rounded-lg size-10 bg-custom-100 text-custom-500 dark:bg-custom-500/10">
                            <i data-lucide="info" class="size-5"></i>
                        </div>
                        <h5 class="text-16 font-bold text-slate-800 dark:text-zink-50">{{ __('messages.personal_reason_details') }}</h5>
                    </div>
                    <button @click="showReasonModal = false" class="transition-all duration-200 text-slate-400 hover:text-red-500 dark:hover:text-red-400">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 text-center">
                    <p class="text-slate-600 dark:text-zink-200 leading-relaxed break-words" x-text="currentReason"></p>
                    
                    <div class="flex items-center justify-center mt-8">
                        <button type="button" @click="showReasonModal = false" class="px-6 py-2.5 text-white bg-custom-500 border border-custom-500 hover:bg-custom-600 focus:bg-custom-600 rounded-md transition-all font-medium shadow-lg shadow-custom-500/20">
                            {{ __('messages.ok') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
            <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
                <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                    <div class="grow">
                        <h5 class="text-16">{{ __('messages.manage_permissions') }}</h5>
                    </div>
                    <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                        <li class="relative before:content-['\ea67'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:before:text-zink-200">
                            <a href="{{ route('home') }}" class="text-slate-400 dark:text-zink-200">{{ __('messages.dashboards') }}</a>
                        </li>
                        <li class="text-slate-700 dark:text-zink-100">{{ __('messages.manage_permissions') }}</li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('permissions.index') }}" method="GET" class="grid grid-cols-1 gap-4 mb-5 md:grid-cols-4">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-input ltr:pl-8 rtl:pr-8 border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500" placeholder="{{ __('messages.search_by_employee') }}">
                                <i data-lucide="search" class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500"></i>
                            </div>
                            <div>
                                <select name="user_id" class="form-select border-slate-200 dark:border-zink-500">
                                    <option value="">{{ __('messages.all_employees') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="date" name="date" value="{{ request('date') }}" class="form-input border-slate-200 dark:border-zink-500">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600">{{ __('messages.filter') }}</button>
                                <a href="{{ route('permissions.index') }}" class="text-slate-500 btn bg-slate-100 border-slate-200 hover:bg-slate-200">{{ __('messages.clear') }}</a>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="ltr:text-left rtl:text-right">
                                    <tr class="border-b border-slate-200 dark:border-zink-500">
                                        <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.employee_name') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.permission_type') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.date') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.manager_status') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.status') }}</th>
                                        <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permissions as $permission)
                                        <tr class="border-b border-slate-200 dark:border-zink-500 hover:bg-slate-50/50 dark:hover:bg-zink-600/50 transition-all">
                                            <td class="px-3.5 py-2.5">
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $permission->user->avatar ? asset('assets/images/user/' . $permission->user->avatar) : asset('assets/images/profile.png') }}" class="size-8 rounded-full border border-slate-200">
                                                    <span class="font-medium">{{ $permission->user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-2.5">
                                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600 dark:bg-zink-500 dark:text-zink-100">
                                                    {{ __('messages.' . $permission->type) }}
                                                </span>
                                                <div class="text-[11px] text-slate-400 mt-1">{{ date('h:i A', strtotime($permission->from_time)) }} - {{ date('h:i A', strtotime($permission->to_time)) }}</div>
                                                @if($permission->personal_reason)
                                                    <div class="text-[11px] text-custom-500 mt-1 font-medium italic cursor-pointer hover:text-custom-600 transition-colors"
                                                         @click="currentReason = @js($permission->personal_reason); showReasonModal = true"
                                                         title="{{ __('messages.view_details') ?? 'Click to view full reason' }}">
                                                        {{ __('messages.personal_reason_details') }}: {{ \Illuminate\Support\Str::limit($permission->personal_reason, 40) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-3.5 py-2.5 font-medium">{{ $permission->date }}</td>
                                            <td class="px-3.5 py-2.5">
                                                @php
                                                    $mStatusClass = [
                                                        'Pending' => 'bg-yellow-100 text-yellow-500 dark:bg-yellow-500/10',
                                                        'Approved' => 'bg-blue-100 text-blue-500 dark:bg-blue-500/10',
                                                        'Rejected' => 'bg-red-100 text-red-500 dark:bg-red-500/10',
                                                    ];
                                                @endphp
                                                <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded {{ $mStatusClass[$permission->manager_status] ?? 'bg-slate-100 text-slate-500' }}">
                                                    {{ __('messages.' . $permission->manager_status) }}
                                                </span>
                                            </td>
                                            <td class="px-3.5 py-2.5">
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'bg-yellow-100 text-yellow-500 dark:bg-yellow-500/10',
                                                        'approved' => 'bg-green-100 text-green-500 dark:bg-green-500/10',
                                                        'rejected' => 'bg-red-100 text-red-500 dark:bg-red-500/10',
                                                    ];
                                                @endphp
                                                <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded {{ $statusClass[$permission->status] ?? 'bg-slate-100 text-slate-500' }}">
                                                    {{ __('messages.' . ucfirst($permission->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-3.5 py-2.5">
                                                <div class="flex gap-2">
                                                    @php
                                                        $userRole = Auth::user()->role_name;
                                                        $canApprove = false;
                                                        if ($userRole === 'Manager' && $permission->manager_status === 'Pending' && $permission->status === 'pending') {
                                                            $canApprove = true;
                                                        } elseif (($userRole === 'HR' || $userRole === 'Admin') && $permission->manager_status === 'Approved' && $permission->status === 'pending') {
                                                            $canApprove = true;
                                                        }
                                                    @endphp

                                                    @if($canApprove)
                                                        <form action="{{ route('permissions.update-status', $permission->id) }}" method="POST" class="inline-flex gap-1">
                                                            @csrf
                                                            <input type="hidden" name="status" value="Approved">
                                                            <button type="submit" class="p-1.5 text-green-500 bg-green-100 hover:bg-green-200 rounded-md transition-all dark:bg-green-500/10 dark:hover:bg-green-500/20" title="{{ __('messages.Approve') }}">
                                                                <i data-lucide="check-circle" class="size-4"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('permissions.update-status', $permission->id) }}" method="POST" class="inline-flex gap-1">
                                                            @csrf
                                                            <input type="hidden" name="status" value="Rejected">
                                                            <button type="submit" class="p-1.5 text-red-500 bg-red-100 hover:bg-red-200 rounded-md transition-all dark:bg-red-500/10 dark:hover:bg-red-500/20" title="{{ __('messages.Reject') }}">
                                                                <i data-lucide="x-circle" class="size-4"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs text-slate-400 italic">{{ __('messages.no_action_needed') }}</span>
                                                    @endif
                                                    
                                                    @if(Auth::user()->hasAnyRole(['Admin', 'HR']))
                                                        <button @click="deleteRoute = '{{ route('permissions.destroy', $permission->id) }}'; showDeleteModal = true" class="p-1.5 text-red-500 bg-red-100 hover:bg-red-200 rounded-md transition-all dark:bg-red-500/10 dark:hover:bg-red-500/20" title="{{ __('messages.delete') }}">
                                                            <i data-lucide="trash-2" class="size-4"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-3.5 py-5 text-center text-slate-500">{{ __('messages.no_records_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $permissions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
