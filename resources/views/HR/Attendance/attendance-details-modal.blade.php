@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="grid grid-cols-1 gap-4">
                <div class="text-center">
                    <div class="mx-auto rounded-full size-16 bg-slate-100 dark:bg-zink-600 mb-3">
                        <img src="{{ $attendance->user && $attendance->user->avatar ? asset('assets/images/user/' . $attendance->user->avatar) : asset('assets/images/profile.png') }}"
                            alt="" class="rounded-full">
                    </div>
                    <h6 class="text-15">{{ $attendance->user->name ?? __('messages.na') }}</h6>
                    <p class="text-slate-500 dark:text-zink-200">{{ $attendance->user->user_id ?? __('messages.na') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <tr>
                            <td class="px-3 py-2 font-semibold">{{ __('messages.date') }}:</td>
                            <td class="px-3 py-2">{{ date('l, d F Y', strtotime($attendance->date)) }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-semibold">{{ __('messages.check_in') }}:</td>
                            <td class="px-3 py-2">
                                {{ $attendance->check_in ? date('h:i:s A', strtotime($attendance->check_in)) : '—' }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-semibold">{{ __('messages.check_out') }}:</td>
                            <td class="px-3 py-2">
                                {{ $attendance->check_out ? date('h:i:s A', strtotime($attendance->check_out)) : '—' }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-semibold">{{ __('messages.working_hours') }}:</td>
                            <td class="px-3 py-2">
                                @php
                                    $wh_modal = abs($attendance->working_hours);
                                    $wh_modal_h = floor($wh_modal);
                                    $wh_modal_m = round(($wh_modal - $wh_modal_h) * 60);
                                @endphp
                                {{ sprintf('%02d:%02d', $wh_modal_h, $wh_modal_m) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 font-semibold">{{ __('messages.overtime') }}:</td>
                            <td class="px-3 py-2">
                                @php
                                    $oh_modal = abs($attendance->overtime_hours);
                                    $oh_modal_h = floor($oh_modal);
                                    $oh_modal_m = round(($oh_modal - $oh_modal_h) * 60);
                                @endphp
                                {{ sprintf('%02d:%02d', $oh_modal_h, $oh_modal_m) }}
                            </td>
                        </tr>
                        @if ($attendance->late_minutes > 0)
                            <tr>
                                <td class="px-3 py-2 font-semibold">{{ __('messages.late_by') }}:</td>
                                <td class="px-3 py-2 text-yellow-600">{{ round($attendance->late_minutes) }}
                                    {{ __('messages.minutes') }}</td>
                            </tr>
                        @endif
                        @if ($attendance->early_departure_minutes > 0)
                            <tr>
                                <td class="px-3 py-2 font-semibold">{{ __('messages.left_early_by') }}:</td>
                                <td class="px-3 py-2 text-orange-600">{{ round($attendance->early_departure_minutes) }}
                                    {{ __('messages.minutes') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="px-3 py-2 font-semibold">{{ __('messages.status') }}:</td>
                            <td class="px-3 py-2">
                                @php
                                    $statusColors = [
                                        'present' => 'bg-green-100 text-green-500',
                                        'late' => 'bg-yellow-100 text-yellow-500',
                                        'early_departure' => 'bg-orange-100 text-orange-500',
                                        'late_early' => 'bg-red-100 text-red-500',
                                        'absent' => 'bg-slate-100 text-slate-500',
                                        'approved' => 'bg-green-100 text-green-500',
                                        'rejected' => 'bg-red-100 text-red-500',
                                        'pending' => 'bg-yellow-100 text-yellow-500',
                                    ];
                                    $statusText = [
                                        'present' => __('messages.present'),
                                        'late' => __('messages.late'),
                                        'early_departure' => __('messages.early_departure'),
                                        'late_early' => __('messages.late_early'),
                                        'absent' => __('messages.absent'),
                                        'approved' => __('messages.approved'),
                                        'rejected' => __('messages.rejected'),
                                        'pending' => __('messages.pending'),
                                    ];
                                @endphp
                                <span
                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded {{ $statusColors[$attendance->status] ?? 'bg-slate-100 text-slate-500' }}">
                                    {{ $statusText[$attendance->status] ?? ucfirst($attendance->status) }}
                                </span>
                            </td>
                        </tr>
                        @if ($attendance->notes)
                            <tr>
                                <td class="px-3 py-2 font-semibold">{{ __('messages.notes') }}:</td>
                                <td class="px-3 py-2">{{ $attendance->notes }}</td>
                            </tr>
                        @endif
                        @if ($attendance->approved_by)
                            <tr>
                                <td class="px-3 py-2 font-semibold">{{ __('messages.approved_rejected_by') }}:</td>
                                <td class="px-3 py-2">{{ $attendance->approved_by }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
