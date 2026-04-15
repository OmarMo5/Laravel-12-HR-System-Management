@extends('layouts.master')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.my_leaves') }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ __('messages.leaves_manage') }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">{{ __('messages.my_leaves') }}</li>
                </ul>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-x-5 md:grid-cols-2 xl:grid-cols-4">
                <div class="col-span-1">
                    <div class="card">
                        <div class="flex items-center gap-3 card-body">
                            <div
                                class="flex items-center justify-center rounded-md size-12 text-15 bg-custom-100 text-custom-500 dark:bg-custom-500/20 shrink-0">
                                <i data-lucide="calendar"></i>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value"
                                        data-target="{{ $totalLeaves }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">{{ __('messages.total_leaves') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div class="card">
                        <div class="flex items-center gap-3 card-body">
                            <div
                                class="flex items-center justify-center text-green-500 bg-green-100 rounded-md size-12 text-15 dark:bg-green-500/20 shrink-0">
                                <i data-lucide="check-circle"></i>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value"
                                        data-target="{{ $approvedLeaves }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">{{ __('messages.approved') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div class="card">
                        <div class="flex items-center gap-3 card-body">
                            <div
                                class="flex items-center justify-center text-yellow-500 bg-yellow-100 rounded-md size-12 text-15 dark:bg-yellow-500/20 shrink-0">
                                <i data-lucide="clock"></i>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value"
                                        data-target="{{ $pendingLeaves }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">{{ __('messages.pending') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <div class="card">
                        <div class="flex items-center gap-3 card-body">
                            <div
                                class="flex items-center justify-center text-red-500 bg-red-100 rounded-md size-12 text-15 dark:bg-red-500/20 shrink-0">
                                <i data-lucide="x-circle"></i>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value"
                                        data-target="{{ $rejectedLeaves }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">{{ __('messages.rejected') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Add Button -->
            <div class="card">
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-4 mb-5 lg:grid-cols-2 xl:grid-cols-12">
                        <div class="xl:col-span-9">
                            <form method="GET" action="{{ route('hr/leave/employee/page') }}"
                                class="flex flex-wrap gap-3">
                                <div class="flex-1 min-w-[200px]">
                                    <input type="text" name="search" value="{{ $search ?? '' }}"
                                        class="form-input border-slate-200 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100 focus:outline-none focus:border-custom-500"
                                        placeholder="{{ __('messages.search_by_leave_type') }}">
                                </div>
                                <button type="submit" class="text-white btn bg-custom-500 border-custom-500">
                                    <i data-lucide="search" class="inline-block size-4"></i> {{ __('messages.search') }}
                                </button>
                                <a href="{{ route('hr/leave/employee/page') }}"
                                    class="btn bg-slate-200 text-slate-800 hover:bg-slate-300">
                                    <i data-lucide="x" class="inline-block size-4"></i> {{ __('messages.clear') }}
                                </a>
                            </form>
                        </div>
                        <div class="xl:col-span-3">
                            <div class="ltr:lg:text-right rtl:lg:text-left">
                                <a href="{{ route('hr/create/leave/employee/page') }}" type="button"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600">
                                    <i data-lucide="plus" class="inline-block size-4"></i>
                                    <span class="align-middle">{{ __('messages.apply_leave') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Leaves Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">#
                                    </th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.leave_type') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.reason') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.days') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.from') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.to') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.status') }}</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        {{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leave as $index => $item)
                                    <tr>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $leave->firstItem() + $index }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $item->leave_type }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ Str::limit($item->reason, 30) }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $item->number_of_day }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $item->date_from }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $item->date_to }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            @if ($item->status == 'Approved')
                                                <span
                                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-green-100 border-green-100 text-green-500">
                                                    {{ __('messages.approved') }}
                                                </span>
                                            @elseif($item->status == 'Pending')
                                                <span
                                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-yellow-100 border-yellow-100 text-yellow-500">
                                                    {{ __('messages.pending') }}
                                                </span>
                                            @elseif($item->status == 'Rejected')
                                                <span
                                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-red-100 border-red-100 text-red-500">
                                                    {{ __('messages.rejected') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            <div class="flex gap-2">
                                                <a href="{{ route('hr/view/detail/leave', $item->id) }}"
                                                    class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 text-slate-500 bg-slate-100 hover:text-white hover:bg-slate-500"
                                                    title="{{ __('messages.view_details') }}">
                                                    <i data-lucide="eye" class="size-4"></i>
                                                </a>
                                                
                                                @if($item->status == 'Pending')
                                                    <a href="{{ route('hr/leave/edit', $item->id) }}"
                                                        class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 text-custom-500 bg-custom-100 hover:text-white hover:bg-custom-500"
                                                        title="{{ __('messages.edit') }}">
                                                        <i data-lucide="edit" class="size-4"></i>
                                                    </a>
                                                    
                                                    <button type="button" onclick="confirmDelete({{ $item->id }})"
                                                        class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500"
                                                        title="{{ __('messages.delete') }}">
                                                        <i data-lucide="trash-2" class="size-4"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-3.5 py-2.5 text-center border-y border-slate-200 dark:border-zink-500">
                                            {{ __('messages.no_leaves_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $leave->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '{{ __('messages.delete_leave') ?? 'Delete Leave' }}',
                text: '{{ __('messages.delete_confirmation_message') ?? 'Are you sure you want to delete this leave? This action cannot be undone!' }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#64748b',
                confirmButtonText: '{{ __('messages.yes_delete') }}',
                cancelButtonText: '{{ __('messages.cancel') }}',
                backdrop: true,
                allowOutsideClick: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '{{ __('messages.processing') }}',
                        text: '{{ __('messages.deleting_please_wait') }}',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.ajax({
                        url: "{{ route('hr/delete/leave') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: '{{ __('messages.deleted') }}',
                                    text: response.message || '{{ __('messages.record_deleted') }}',
                                    icon: 'success',
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: '{{ __('messages.ok') }}',
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: '{{ __('messages.error') }}',
                                    text: response.message || '{{ __('messages.delete_failed') }}',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = xhr.responseJSON?.message || '{{ __('messages.delete_failed') }}';
                            Swal.fire({
                                title: '{{ __('messages.error') }}',
                                text: errorMsg,
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection