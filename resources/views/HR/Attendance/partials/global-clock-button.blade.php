<div class="relative flex items-center h-header">
    @if(!$globalCheckedIn)
        <button type="button" 
            onclick="openCheckInModal()"
            style="background-color: #10b981 !important; color: #ffffff !important; border: none !important;"
            class="group flex items-center gap-2 px-5 py-2 text-sm font-bold rounded-full shadow-md hover:opacity-90 transition-all hover:-translate-y-0.5">
            <i data-lucide="play-circle" style="width: 18px; height: 18px; stroke-width: 3px;"></i>
            <span class="hidden lg:inline">{{ __('messages.check_in') }}</span>
        </button>
    @elseif(!$globalCheckedOut)
        <button type="button" 
            onclick="openCheckOutModal()"
            style="background-color: #ef4444 !important; color: #ffffff !important; border: none !important;"
            class="group flex items-center gap-2 px-5 py-2 text-sm font-bold rounded-full shadow-md hover:opacity-90 transition-all hover:-translate-y-0.5">
            <i data-lucide="stop-circle" style="width: 18px; height: 18px; stroke-width: 3px;"></i>
            <span class="hidden lg:inline">{{ __('messages.check_out') }}</span>
        </button>
    @else
        <div style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important; color: #6b7280 !important;"
            class="flex items-center gap-2 px-5 py-2 text-sm font-bold rounded-full shadow-inner">
            <i data-lucide="check-circle-2" style="width: 18px; height: 18px; stroke-width: 3px; color: #10b981;"></i>
            <span class="hidden lg:inline">{{ __('messages.checked_out') }}</span>
        </div>
    @endif
</div>

<!-- Modals (Global) -->
@include('HR.Attendance.partials.attendance-modals')

<script>
    // Ensure Lucide icons are rendered for this specific partial
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
