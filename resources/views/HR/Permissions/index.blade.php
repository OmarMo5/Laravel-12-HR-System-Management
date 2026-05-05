@extends('layouts.master')
@section('content')
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
                                    <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.from_time') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.to_time') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.date') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.status') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold">{{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                    <tr class="border-b border-slate-200 dark:border-zink-500">
                                        <td class="px-3.5 py-2.5">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $permission->user->avatar ? asset('assets/images/user/' . $permission->user->avatar) : asset('assets/images/profile.png') }}" class="size-8 rounded-full">
                                                <span>{{ $permission->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5">{{ __('messages.' . $permission->type) }}</td>
                                        <td class="px-3.5 py-2.5">{{ date('h:i A', strtotime($permission->from_time)) }}</td>
                                        <td class="px-3.5 py-2.5">{{ date('h:i A', strtotime($permission->to_time)) }}</td>
                                        <td class="px-3.5 py-2.5">{{ $permission->date }}</td>
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
                                                <button type="button" @click="$dispatch('open-modal', {id: 'statusModal{{ $permission->id }}'})" class="text-custom-500 hover:text-custom-600">
                                                    <i data-lucide="edit-3" class="size-4"></i>
                                                </button>
                                            </div>

                                            <!-- Status Update Modal (Simple form for now) -->
                                            <div id="statusModal{{ $permission->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" x-show="open">
                                                <div class="flex items-center justify-center min-h-screen px-4">
                                                    <div class="fixed inset-0 transition-opacity bg-slate-900/50"></div>
                                                    <div class="relative w-full max-w-md p-6 bg-white rounded-lg dark:bg-zink-700">
                                                        <h5 class="mb-4 text-16">{{ __('messages.update_leave') }}</h5>
                                                        <form action="{{ route('permissions.update-status', $permission->id) }}" method="POST">
                                                            @csrf
                                                            <div class="mb-4">
                                                                <label class="block mb-2 font-medium">{{ __('messages.status') }}</label>
                                                                <select name="status" class="form-select w-full border-slate-200 dark:border-zink-500">
                                                                    <option value="pending" {{ $permission->status == 'pending' ? 'selected' : '' }}>{{ __('messages.Pending') }}</option>
                                                                    <option value="approved" {{ $permission->status == 'approved' ? 'selected' : '' }}>{{ __('messages.Approved') }}</option>
                                                                    <option value="rejected" {{ $permission->status == 'rejected' ? 'selected' : '' }}>{{ __('messages.Rejected') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label class="block mb-2 font-medium">{{ __('messages.notes_optional') }}</label>
                                                                <textarea name="admin_notes" class="form-input w-full border-slate-200 dark:border-zink-500">{{ $permission->admin_notes }}</textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" @click="open = false" class="text-slate-500 btn bg-slate-100 border-slate-200">{{ __('messages.cancel') }}</button>
                                                                <button type="submit" class="text-white btn bg-custom-500 border-custom-500">{{ __('messages.update') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
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
@endsection
