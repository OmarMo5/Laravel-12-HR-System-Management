<!-- Check In Modal -->
<div id="checkInModal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
    <div class="relative bg-white dark:bg-zink-700 rounded-2xl shadow-2xl overflow-hidden border border-slate-200 dark:border-zink-600" style="width: 350px; max-width: 95%;">
        <div class="p-6 text-center">
            <div class="size-12 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="play-circle" class="size-6 text-emerald-600"></i>
            </div>
            
            <h5 class="text-lg font-bold text-slate-800 dark:text-zink-50 mb-1">{{ __('messages.check_in_confirmation') }}</h5>
            <p class="text-xs text-slate-500 dark:text-zink-400 mb-6">{{ __('messages.please_confirm_check_in') }}</p>

            <div class="mb-6 p-4 bg-slate-50 dark:bg-zink-600/50 rounded-xl border border-slate-100 dark:border-zink-500">
                <h2 class="text-2xl font-black text-slate-800 dark:text-zink-50 tracking-tight" id="checkInTimeDisplay">--:--</h2>
                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest" id="checkInDateDisplay">----</p>
            </div>

            <div class="mb-6">
                <textarea id="checkInNotes" rows="2" class="w-full px-4 py-3 text-sm font-semibold bg-white dark:bg-zink-700 border border-slate-200 dark:border-zink-500 rounded-xl focus:border-emerald-500 outline-none resize-none placeholder:text-slate-300" placeholder="{{ __('messages.notes_optional') }}"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal('checkInModal')" 
                    class="flex-1 px-4 py-2.5 text-xs font-bold text-slate-500 bg-slate-100 dark:bg-zink-600 rounded-lg hover:bg-slate-200 transition-all">
                    {{ __('messages.cancel') }}
                </button>
                <button type="button" id="confirmCheckIn" onclick="submitCheckIn()" 
                    style="background-color: #dcfce7 !important; color: #15803d !important; border: 1px solid #bdf0d2 !important;"
                    class="flex-1 px-4 py-2.5 text-xs font-bold rounded-lg hover:opacity-80 transition-all">
                    {{ __('messages.confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Check Out Modal -->
<div id="checkOutModal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
    <div class="relative bg-white dark:bg-zink-700 rounded-2xl shadow-2xl overflow-hidden border border-slate-200 dark:border-zink-600" style="width: 350px; max-width: 95%;">
        <div class="p-6 text-center">
            <div class="size-12 rounded-full bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="stop-circle" class="size-6 text-rose-600"></i>
            </div>
            
            <h5 class="text-lg font-bold text-slate-800 dark:text-zink-50 mb-1">{{ __('messages.check_out_confirmation') }}</h5>
            <p class="text-xs text-slate-500 dark:text-zink-400 mb-6">{{ __('messages.please_confirm_check_out') }}</p>

            <div class="mb-6 p-4 bg-slate-50 dark:bg-zink-600/50 rounded-xl border border-slate-100 dark:border-zink-500">
                <h2 class="text-2xl font-black text-slate-800 dark:text-zink-50 tracking-tight" id="checkOutTimeDisplay">--:--</h2>
                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest" id="checkOutDateDisplay">----</p>
            </div>

            <div class="mb-6">
                <textarea id="checkOutNotes" rows="2" class="w-full px-4 py-3 text-sm font-semibold bg-white dark:bg-zink-700 border border-slate-200 dark:border-zink-500 rounded-xl focus:border-rose-500 outline-none resize-none placeholder:text-slate-300" placeholder="{{ __('messages.notes_optional') }}"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal('checkOutModal')" 
                    class="flex-1 px-4 py-2.5 text-xs font-bold text-slate-500 bg-slate-100 dark:bg-zink-600 rounded-lg hover:bg-slate-200 transition-all">
                    {{ __('messages.cancel') }}
                </button>
                <button type="button" id="confirmCheckOut" onclick="submitCheckOut()" 
                    style="background-color: #fee2e2 !important; color: #b91c1c !important; border: 1px solid #fecaca !important;"
                    class="flex-1 px-4 py-2.5 text-xs font-bold rounded-lg hover:opacity-80 transition-all">
                    {{ __('messages.confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openCheckInModal() {
        let now = new Date();
        $('#checkInTimeDisplay').text(formatTime(now));
        $('#checkInDateDisplay').text(formatDate(now));
        openModal('checkInModal');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function openCheckOutModal() {
        let now = new Date();
        $('#checkOutTimeDisplay').text(formatTime(now));
        $('#checkOutDateDisplay').text(formatDate(now));
        openModal('checkOutModal');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function formatTime(date) {
        let hours = date.getHours();
        let minutes = date.getMinutes().toString().padStart(2, '0');
        let ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        return hours + ':' + minutes + ' ' + ampm;
    }

    function formatDate(date) {
        const options = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' };
        return date.toLocaleDateString(undefined, options);
    }

    function openModal(modalId) {
        $('#' + modalId).removeClass('hidden').css('display', 'flex');
        $('body').css('overflow', 'hidden');
    }

    function closeModal(modalId) {
        $('#' + modalId).addClass('hidden').css('display', 'none');
        $('#' + modalId + ' textarea').val('');
        $('body').css('overflow', '');
    }

    function submitCheckIn() {
        let notes = $('#checkInNotes').val();
        let button = $('#confirmCheckIn');
        button.prop('disabled', true).html('<i class="animate-spin inline-block size-3 mr-1">⏳</i>');
        
        $.ajax({
            url: '{{ route('employee/attendance/check-in') }}',
            type: 'POST',
            data: { notes: notes, _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    closeModal('checkInModal');
                    Swal.fire({ 
                        icon: 'success', title: 'Success', text: response.message, 
                        timer: 1500, showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl border-none shadow-xl' }
                    }).then(() => { location.reload(); });
                }
            },
            error: function(xhr) {
                button.prop('disabled', false).html('{{ __('messages.confirm') }}');
                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Check-in failed!' });
            }
        });
    }

    function submitCheckOut() {
        let notes = $('#checkOutNotes').val();
        let button = $('#confirmCheckOut');
        button.prop('disabled', true).html('<i class="animate-spin inline-block size-3 mr-1">⏳</i>');
        
        $.ajax({
            url: '{{ route('employee/attendance/check-out') }}',
            type: 'POST',
            data: { notes: notes, _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    closeModal('checkOutModal');
                    Swal.fire({ 
                        icon: 'success', title: 'Success', text: response.message, 
                        timer: 1500, showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl border-none shadow-xl' }
                    }).then(() => { location.reload(); });
                }
            },
            error: function(xhr) {
                button.prop('disabled', false).html('{{ __('messages.confirm') }}');
                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Check-out failed!' });
            }
        });
    }
</script>
