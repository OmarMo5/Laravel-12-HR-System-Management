@extends('layouts.master')
@section('content')
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <div x-data="{ showPermissionModal: false }">
        <!-- Request Permission Modal -->
        <div x-show="showPermissionModal" 
             class="fixed inset-0 z-[9999] flex items-center justify-center" 
             style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; margin: 0 !important; z-index: 99999 !important;"
             x-cloak>
            <!-- Backdrop -->
            <div x-show="showPermissionModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="showPermissionModal = false" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                 style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; margin: 0 !important;"></div>

            <!-- Modal Content -->
            <div x-show="showPermissionModal" 
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
                            <i data-lucide="clock" class="size-5"></i>
                        </div>
                        <h5 class="text-16 font-bold text-slate-800 dark:text-zink-50">{{ __('messages.request_permission') }}</h5>
                    </div>
                    <button @click="showPermissionModal = false" class="transition-all duration-200 text-slate-400 hover:text-red-500 dark:hover:text-red-400">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('permissions.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-5">
                        <!-- Permission Type -->
                        <div>
                            <label for="type" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.permission_type') }}</label>
                            <select name="type" id="type" class="form-select w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5" required>
                                <option value="">{{ __('messages.select_leave_type') }}</option>
                                <option value="early_departure">{{ __('messages.early_departure') }}</option>
                                <option value="late_arrival">{{ __('messages.late_arrival') }}</option>
                                <option value="mid_day_outing">{{ __('messages.mid_day_outing') }}</option>
                            </select>
                        </div>

                        <!-- Reason -->
                        <div>
                            <label for="reason" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.permission_reason') }}</label>
                            <select name="reason" id="reason" class="form-select w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5" required>
                                <option value="">{{ __('messages.select_status') }}</option>
                                <option value="personal">{{ __('messages.personal_reason') }}</option>
                                <option value="work">{{ __('messages.work_reason') }}</option>
                                <option value="both">{{ __('messages.both_reasons') }}</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- From Time -->
                            <div>
                                <label for="from_time" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.from_time') }}</label>
                                <div class="relative">
                                    <input type="time" name="from_time" id="from_time" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5" required>
                                </div>
                            </div>

                            <!-- To Time -->
                            <div>
                                <label for="to_time" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.to_time') }}</label>
                                <div class="relative">
                                    <input type="time" name="to_time" id="to_time" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5" required>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.date') }}</label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-input w-full border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 rounded-md py-2.5" required>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-3 mt-8">
                        <button type="button" @click="showPermissionModal = false" class="px-6 py-2.5 text-slate-500 bg-slate-100 hover:bg-slate-200 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-zink-500 rounded-md transition-all font-medium">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-white bg-custom-500 border border-custom-500 hover:bg-custom-600 focus:bg-custom-600 rounded-md transition-all font-medium shadow-lg shadow-custom-500/20">
                            {{ __('messages.add') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
            <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
                <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                    <div class="grow">
                        <h5 class="text-16">{{ __('messages.my_permissions') }}</h5>
                    </div>
                    <div class="shrink-0">
                        <button @click="showPermissionModal = true" class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600 focus:bg-custom-600" {{ $monthlyCount >= 2 ? 'disabled' : '' }}>
                            <i data-lucide="plus" class="inline-block size-4 mr-1"></i> {{ __('messages.request_permission') }}
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                    <div class="xl:col-span-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Monthly Count Alert -->
                                <div class="flex items-center gap-3 px-4 py-3 mb-5 border rounded-md {{ $monthlyCount >= 2 ? 'bg-red-50 border-red-100 text-red-600 dark:bg-red-500/10 dark:border-red-500/20' : 'bg-blue-50 border-blue-100 text-blue-600 dark:bg-blue-500/10 dark:border-blue-500/20' }}">
                                    <div class="flex items-center justify-center rounded-full size-10 {{ $monthlyCount >= 2 ? 'bg-red-100 dark:bg-red-500/20' : 'bg-blue-100 dark:bg-blue-500/20' }}">
                                        <i data-lucide="{{ $monthlyCount >= 2 ? 'alert-octagon' : 'info' }}" class="size-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0.5 text-sm font-bold">{{ __('messages.permissions_count_alert', ['count' => 2 - $monthlyCount]) }}</h6>
                                        <p class="text-xs opacity-80">{{ __('messages.remaining_permissions') }}</p>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="ltr:text-left rtl:text-right">
                                            <tr class="border-b border-slate-200 dark:border-zink-500 text-slate-500 dark:text-zink-200">
                                                <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.date') }}</th>
                                                <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.permission_type') }}</th>
                                                <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.from_time') }}</th>
                                                <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.to_time') }}</th>
                                                <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.permission_reason') }}</th>
                                                <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($permissions as $permission)
                                                <tr class="border-b border-slate-200 dark:border-zink-500 last:border-0">
                                                    <td class="px-3.5 py-3.5 font-bold">{{ $permission->date }}</td>
                                                    <td class="px-3.5 py-3.5">{{ __('messages.' . $permission->type) }}</td>
                                                    <td class="px-3.5 py-3.5 font-mono text-slate-500">{{ date('h:i A', strtotime($permission->from_time)) }}</td>
                                                    <td class="px-3.5 py-3.5 font-mono text-slate-500">{{ date('h:i A', strtotime($permission->to_time)) }}</td>
                                                    <td class="px-3.5 py-3.5">{{ __('messages.' . $permission->reason . '_reason') }}</td>
                                                    <td class="px-3.5 py-3.5">
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
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="px-3.5 py-10 text-center text-slate-500 dark:text-zink-200">
                                                        <i data-lucide="info" class="inline-block size-8 mb-2 opacity-20"></i>
                                                        <p>{{ __('messages.no_records_found') }}</p>
                                                    </td>
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
        </div>
    </div>
@endsection
