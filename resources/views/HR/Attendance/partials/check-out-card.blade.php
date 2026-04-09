<div class="card {{ $checkedOut ? 'border-l-4 border-red-500' : '' }}">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <h6 class="text-15">{{ __('messages.check_out') }}</h6>
            @if ($checkedOut)
                <span class="px-2.5 py-0.5 text-xs font-medium rounded bg-red-100 text-red-500">
                    <i data-lucide="check" class="inline-block size-3"></i>
                    {{ __('messages.checked_out') }}
                </span>
            @endif
        </div>

        <div class="text-center">
            @if ($checkedOut)
                <div class="mb-4">
                    <div class="inline-block p-4 rounded-full bg-red-100">
                        <i data-lucide="log-out" class="size-12 text-red-500"></i>
                    </div>
                    <h4 class="mt-3 text-2xl">
                        {{ date('h:i A', strtotime($todayAttendance->check_out)) }}
                    </h4>
                    <p class="text-slate-500">{{ __('messages.your_check_out_time') }}</p>
                    <div class="flex items-center justify-center gap-4 mt-3">
                        <span class="text-sm">
                            <span class="font-semibold">{{ $todayAttendance->working_hours }}</span>
                            {{ __('messages.hrs') }} {{ __('messages.hours_worked') }}
                        </span>
                        @if ($todayAttendance->overtime_hours > 0)
                            <span class="text-sm text-green-600">
                                +{{ $todayAttendance->overtime_hours }} {{ __('messages.hrs') }}
                                {{ __('messages.ot') }}
                            </span>
                        @endif
                    </div>
                </div>
            @elseif($checkedIn)
                <div class="mb-4">
                    <div class="inline-block p-4 rounded-full bg-yellow-100">
                        <i data-lucide="log-out" class="size-12 text-yellow-500"></i>
                    </div>
                    <h4 class="mt-3 text-2xl">— : — : —</h4>
                    <p class="text-slate-500">{{ __('messages.waiting_for_check_out') }}</p>
                </div>

                <button type="button" id="checkOutBtn"
                    class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600 w-full py-3 text-16">
                    <i data-lucide="log-out" class="inline-block size-5 mr-2"></i>
                    {{ __('messages.check_out_now') }}
                </button>
            @else
                <div class="mb-4">
                    <div class="inline-block p-4 rounded-full bg-slate-100">
                        <i data-lucide="log-out" class="size-12 text-slate-400"></i>
                    </div>
                    <h4 class="mt-3 text-2xl">— : — : —</h4>
                    <p class="text-slate-500">{{ __('messages.check_in_first') }}</p>
                </div>

                <button type="button" disabled
                    class="text-white btn bg-slate-300 border-slate-300 cursor-not-allowed w-full py-3 text-16">
                    <i data-lucide="log-out" class="inline-block size-5 mr-2"></i>
                    {{ __('messages.check_out_disabled') }}
                </button>
            @endif
        </div>
    </div>
</div>