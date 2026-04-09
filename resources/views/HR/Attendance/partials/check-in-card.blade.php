<div class="card {{ $checkedIn ? 'border-l-4 border-green-500' : '' }}">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <h6 class="text-15">{{ __('messages.check_in') }}</h6>
            @if ($checkedIn)
                <span class="px-2.5 py-0.5 text-xs font-medium rounded bg-green-100 text-green-500">
                    <i data-lucide="check" class="inline-block size-3"></i>
                    {{ __('messages.checked_in') }}
                </span>
            @endif
        </div>

        <div class="text-center">
            @if ($checkedIn)
                <div class="mb-4">
                    <div class="inline-block p-4 rounded-full bg-green-100">
                        <i data-lucide="log-in" class="size-12 text-green-500"></i>
                    </div>
                    <h4 class="mt-3 text-2xl">
                        {{ $todayAttendance ? date('h:i A', strtotime($todayAttendance->check_in)) : '—' }}
                    </h4>
                    <p class="text-slate-500">{{ __('messages.your_check_in_time') }}</p>
                    @if ($todayAttendance && $todayAttendance->late_minutes > 0)
                        <span class="inline-block px-2.5 py-1 mt-2 text-xs font-medium rounded bg-yellow-100 text-yellow-600">
                            {{ __('messages.late_by') }} {{ round($todayAttendance->late_minutes) }}
                            {{ __('messages.minutes') }}
                        </span>
                    @endif
                </div>
            @else
                <div class="mb-4">
                    <div class="inline-block p-4 rounded-full bg-custom-100 animate-pulse">
                        <i data-lucide="log-in" class="size-12 text-custom-500"></i>
                    </div>
                    <h4 class="mt-3 text-2xl">— : — : —</h4>
                    <p class="text-slate-500">{{ __('messages.you_havent_checked_in') }}</p>
                </div>

                <button type="button" id="checkInBtn"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 w-full py-3 text-16">
                    <i data-lucide="log-in" class="inline-block size-5 mr-2"></i>
                    {{ __('messages.check_in_now') }}
                </button>
            @endif
        </div>
    </div>
</div>