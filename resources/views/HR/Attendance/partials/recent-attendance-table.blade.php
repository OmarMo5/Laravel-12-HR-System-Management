<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-slate-100">
            <tr>
                <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.date') }}</th>
                <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.check_in') }}</th>
                <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.check_out') }}</th>
                <!-- <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.status') }}</th> -->
                <th class="px-3.5 py-2.5 font-semibold text-left">{{ __('messages.hours') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentAttendance as $attendance)
                <tr>
                    <td class="px-3.5 py-2.5 border-y">
                        {{ date('d M, Y', strtotime($attendance->date)) }}
                        <span class="px-2 py-0.5 text-xs rounded bg-slate-100 ltr:ml-2">
                            {{ date('D', strtotime($attendance->date)) }}
                        </span>
                    </td>
                    <td class="px-3.5 py-2.5 border-y">
                        {{ $attendance->check_in_display ?? ($attendance->check_in ? date('h:i A', strtotime($attendance->check_in)) : '—') }}
                    </td>
                    <td class="px-3.5 py-2.5 border-y">
                        {{ $attendance->check_out_display ?? ($attendance->check_out ? date('h:i A', strtotime($attendance->check_out)) : '—') }}
                    </td>
                    <!-- <td class="px-3.5 py-2.5 border-y">
                        @php
                            $statusColors = [
                                'present' => 'bg-green-100 text-green-500',
                                'late' => 'bg-yellow-100 text-yellow-500',
                                'early_departure' => 'bg-orange-100 text-orange-500',
                                'late_early' => 'bg-red-100 text-red-500',
                                'absent' => 'bg-slate-100 text-slate-500',
                            ];
                            $statusText = [
                                'present' => __('messages.present'),
                                'late' => __('messages.late'),
                                'early_departure' => __('messages.early'),
                                'late_early' => __('messages.late_early'),
                                'absent' => __('messages.absent'),
                            ];
                        @endphp
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded {{ $statusColors[$attendance->status] ?? 'bg-slate-100' }}">
                            {{ $statusText[$attendance->status] ?? ucfirst($attendance->status) }}
                        </span>
                    </td> -->
                    <td class="px-3.5 py-2.5 border-y">
                        {{ $attendance->working_hours ? number_format($attendance->working_hours, 1) . ' ' . __('messages.hrs') : '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-3.5 py-2.5 text-center border-y">
                        {{ __('messages.no_records') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>