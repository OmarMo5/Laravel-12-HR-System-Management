@extends('layouts.master')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Page Header -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">{{ __('messages.my_attendance_dashboard') }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400">
                        <a href="#!" class="text-slate-400">{{ __('messages.attendance') }}</a>
                    </li>
                    <li class="text-slate-700">{{ __('messages.hr_management') }}</li>
                </ul>
            </div>

            <!-- User Info Card -->
            <div class="grid grid-cols-1 gap-5 mb-5 lg:grid-cols-4">
                <div class="lg:col-span-4">
                    <div class="card bg-gradient-to-r from-slate-700 to-slate-800">
                        <div class="card-body">
                            <div class="flex items-center gap-4">
                                <div class="size-16 rounded-full border-2 border-white overflow-hidden">
                                    <img src="{{ $user && $user->avatar ? asset('assets/images/user/' . $user->avatar) : asset('assets/images/profile.png') }}"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="text-xl text-white mb-1">{{ $user->name ?? 'Employee' }}</h4>
                                    <p class="text-white/80 text-sm">{{ $user->user_id ?? '—' }} •
                                        {{ $user->position ?? 'Employee' }}</p>
                                    <div class="flex gap-3 mt-2">
                                        <span class="text-xs text-white/60">
                                            <i data-lucide="mail" class="inline-block size-3 mr-1"></i>
                                            {{ $user->email ?? '—' }}
                                        </span>
                                        <span class="text-xs text-white/60">
                                            <i data-lucide="phone" class="inline-block size-3 mr-1"></i>
                                            {{ $user->phone_number ?? '—' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Server Time Card -->
            <div class="grid grid-cols-1 gap-5 mb-5 lg:grid-cols-3">
                <div class="lg:col-span-3">
                    <div class="card overflow-hidden bg-gradient-to-r from-custom-500 to-custom-600">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h6 class="mb-2 text-15 text-white/80">{{ __('messages.server_time') }}</h6>
                                    <h2 class="mb-1 text-3xl text-white" id="server-time">
                                        {{ $currentTime->format('h:i:s A') }}</h2>
                                    <p class="text-white/80">{{ $currentTime->format('l, d F Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="inline-block p-4 rounded-full bg-white/10">
                                        <i data-lucide="clock" class="size-12 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Check In/Out Cards Containers -->
            <div class="grid grid-cols-1 gap-5 mb-5 lg:grid-cols-2">
                <div id="checkInCardContainer" class="col-span-1">
                    @include('HR.Attendance.partials.check-in-card', ['checkedIn' => $checkedIn, 'todayAttendance' => $todayAttendance, 'checkedOut' => $checkedOut])
                </div>
                <div id="checkOutCardContainer" class="col-span-1">
                    @include('HR.Attendance.partials.check-out-card', ['checkedIn' => $checkedIn, 'checkedOut' => $checkedOut, 'todayAttendance' => $todayAttendance])
                </div>
            </div>

            <!-- Monthly Stats Container -->
            <div id="statsCardsContainer">
                @include('HR.Attendance.partials.monthly-stats-cards', ['monthlyStats' => $monthlyStats])
            </div>

            <!-- Recent Attendance Container -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-15">{{ __('messages.recent_attendance') }}</h6>
                        <a href="{{ route('employee/attendance/history') }}" class="text-custom-500 hover:underline">
                            {{ __('messages.view_all') }} <i data-lucide="arrow-right" class="inline-block size-4"></i>
                        </a>
                    </div>
                    <div id="recentAttendanceContainer">
                        @include('HR.Attendance.partials.recent-attendance-table', ['recentAttendance' => $recentAttendance])
                    </div>
                </div>
            </div>

            <!-- Modals (Check In & Check Out) - نفس الكود اللي عندك من قبل -->
            <div id="checkInModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300" style="display: none;">
                <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl dark:bg-zink-700 transform transition-all duration-300 scale-100">
                    <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-zink-500 bg-gradient-to-r from-custom-500 to-custom-600 rounded-t-2xl sticky top-0 z-10">
                        <div class="flex items-center gap-3">
                            <div class="size-14 rounded-full bg-white/20 flex items-center justify-center">
                                <i data-lucide="log-in" class="size-7 text-white"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl font-semibold text-white">{{ __('messages.check_in_confirmation') }}</h5>
                                <p class="text-sm text-white/80">{{ __('messages.please_confirm_check_in') }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-white/80 hover:text-white transition-colors" data-modal-close="checkInModal">
                            <i data-lucide="x" class="size-7"></i>
                        </button>
                    </div>
                    <div class="p-8">
                        <div class="mb-8 p-8 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-500/10 dark:to-indigo-500/10 rounded-xl border-2 border-blue-200 dark:border-blue-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-medium text-blue-600 dark:text-blue-400 mb-2">{{ __('messages.check_in_time') }}</p>
                                    <h2 class="text-5xl font-bold text-blue-700 dark:text-blue-300 font-mono" id="checkInTimeDisplay">{{ $currentTime->format('h:i:s A') }}</h2>
                                    <p class="text-base text-blue-500 dark:text-blue-400 mt-3">
                                        <i data-lucide="calendar" class="inline-block size-4 mr-1"></i>
                                        {{ $currentTime->format('l, d F Y') }}
                                    </p>
                                </div>
                                <div class="size-24 rounded-full bg-blue-500/20 flex items-center justify-center animate-pulse">
                                    <i data-lucide="clock" class="size-12 text-blue-600 dark:text-blue-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block mb-3 text-base font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.notes_optional') }}</label>
                            <textarea id="checkInNotes" rows="4" class="w-full px-5 py-4 text-base border-2 border-slate-200 dark:border-zink-500 rounded-xl focus:border-custom-500 dark:focus:border-custom-800 focus:ring-2 focus:ring-custom-500/20 dark:focus:ring-custom-800/20 outline-none transition-all resize-none bg-white dark:bg-zink-600" placeholder="{{ __('messages.add_remarks') }}"></textarea>
                        </div>
                        <div class="p-5 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-start gap-3">
                                <div class="size-10 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center shrink-0">
                                    <i data-lucide="info" class="size-5 text-amber-600 dark:text-amber-400"></i>
                                </div>
                                <div>
                                    <h6 class="text-base font-semibold text-amber-700 dark:text-amber-300 mb-1">{{ __('messages.server_time_used') }}</h6>
                                    <p class="text-base text-amber-600 dark:text-amber-400">{{ __('messages.server_time_info') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 p-6 border-t border-slate-200 dark:border-zink-500 bg-slate-50 dark:bg-zink-800 rounded-b-2xl sticky bottom-0">
                        <button type="button" data-modal-close="checkInModal" class="px-8 py-3 text-base font-medium text-slate-700 bg-white border-2 border-slate-300 rounded-xl hover:bg-slate-50 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-500 dark:hover:bg-zink-500 transition-all duration-200 hover:scale-105">{{ __('messages.cancel') }}</button>
                        <button type="button" id="confirmCheckIn" class="px-10 py-3 text-base font-medium text-white bg-gradient-to-r from-custom-500 to-custom-600 rounded-xl hover:from-custom-600 hover:to-custom-700 transition-all duration-200 hover:scale-105 shadow-lg shadow-custom-500/30"><i data-lucide="check-circle" class="inline-block size-5 mr-2"></i>{{ __('messages.confirm_check_in') }}</button>
                    </div>
                </div>
            </div>

            <div id="checkOutModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300" style="display: none;">
                <div class="relative w-full max-w-2xl max-h-[50vh] overflow-y-auto bg-white rounded-2xl shadow-2xl dark:bg-zink-700 transform transition-all duration-300 scale-100">
                    <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500 bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-2xl sticky top-0 z-10">
                        <div class="flex items-center gap-3">
                            <div class="size-14 rounded-full bg-white/20 flex items-center justify-center">
                                <i data-lucide="log-out" class="size-7 text-white"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl font-semibold text-white">{{ __('messages.check_out_confirmation') }}</h5>
                                <p class="text-sm text-white/80">{{ __('messages.please_confirm_check_out') }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-white/80 hover:text-white transition-colors" data-modal-close="checkOutModal">
                            <i data-lucide="x" class="size-7"></i>
                        </button>
                    </div>
                    <div class="p-8">
                        <div class="mb-8 p-4 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-500/10 dark:to-orange-500/10 rounded-xl border-2 border-amber-200 dark:border-amber-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-medium text-amber-600 dark:text-amber-400 mb-2">{{ __('messages.check_out_time') }}</p>
                                    <h2 class="text-5xl font-bold text-amber-700 dark:text-amber-300 font-mono" id="checkOutTimeDisplay">{{ $currentTime->format('h:i:s A') }}</h2>
                                    <p class="text-base text-amber-500 dark:text-amber-400 mt-3">
                                        <i data-lucide="calendar" class="inline-block size-4 mr-1"></i>
                                        {{ $currentTime->format('l, d F Y') }}
                                    </p>
                                </div>
                                <div class="size-24 rounded-full bg-amber-500/20 flex items-center justify-center animate-pulse">
                                    <i data-lucide="clock" class="size-12 text-amber-600 dark:text-amber-400"></i>
                                </div>
                            </div>
                        </div>
                        @if ($checkedIn && !$checkedOut)
                            <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 rounded-xl border border-green-200 dark:border-green-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-base font-medium text-green-600 dark:text-green-400 mb-1">{{ __('messages.todays_working_hours') }}</p>
                                        <h3 class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $todayAttendance ? \Carbon\Carbon::parse($todayAttendance->check_in)->diffInHours(\Carbon\Carbon::now()) : 0 }} {{ __('messages.hrs') }}</h3>
                                    </div>
                                    <i data-lucide="trending-up" class="size-10 text-green-500"></i>
                                </div>
                            </div>
                        @endif
                        <div class="mb-6">
                            <label class="block mb-3 text-base font-semibold text-slate-700 dark:text-zink-200">{{ __('messages.notes_optional') }}</label>
                            <textarea id="checkOutNotes" rows="4" class="w-full px-5 py-4 text-base border-2 border-slate-200 dark:border-zink-500 rounded-xl focus:border-amber-500 dark:focus:border-amber-800 focus:ring-2 focus:ring-amber-500/20 dark:focus:ring-amber-800/20 outline-none transition-all resize-none bg-white dark:bg-zink-600" placeholder="{{ __('messages.add_remarks') }}"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 p-6 border-t border-slate-200 dark:border-zink-500 bg-slate-50 dark:bg-zink-800 rounded-b-2xl sticky bottom-0">
                        <button type="button" data-modal-close="checkOutModal" class="px-8 py-3 text-base font-medium text-slate-700 bg-white border-2 border-slate-300 rounded-xl hover:bg-slate-50 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-500 dark:hover:bg-zink-500 transition-all duration-200 hover:scale-105">{{ __('messages.cancel') }}</button>
                        <button type="button" id="confirmCheckOut" class="px-10 py-3 text-base font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 hover:scale-105 shadow-lg shadow-amber-500/30"><i data-lucide="check-circle" class="inline-block size-5 mr-2"></i>{{ __('messages.confirm_check_out') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <style>
        [id$="Modal"] { animation: fadeIn 0.3s ease; }
        [id$="Modal"]>div { animation: slideIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideIn { from { transform: translateY(-20px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
        [id$="Modal"] { z-index: 99999 !important; }
    </style>
    <script>
        function updateServerTime() {
            let now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            let ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            $('#server-time').text(hours + ':' + minutes + ':' + seconds + ' ' + ampm);
        }
        setInterval(updateServerTime, 1000);

        $('#checkInBtn').on('click', function() {
            let now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            let ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            $('#checkInTimeDisplay').text(hours + ':' + minutes + ':' + seconds + ' ' + ampm);
            openModal('checkInModal');
        });

        $('#checkOutBtn').on('click', function() {
            let now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            let ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            $('#checkOutTimeDisplay').text(hours + ':' + minutes + ':' + seconds + ' ' + ampm);
            openModal('checkOutModal');
        });

        $('#confirmCheckIn').on('click', function() {
            let notes = $('#checkInNotes').val();
            let button = $(this);
            button.prop('disabled', true).html('<i class="animate-spin inline-block size-4 mr-2">⏳</i> {{ __('messages.processing') }}');
            $.ajax({
                url: '{{ route('employee/attendance/check-in') }}',
                type: 'POST',
                data: { notes: notes, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        closeModal('checkInModal');
                        if (response.html) {
                            if (response.html.recentAttendance) $('#recentAttendanceContainer').html(response.html.recentAttendance);
                            if (response.html.statsCards) $('#statsCardsContainer').html(response.html.statsCards);
                            if (response.html.checkInCard) $('#checkInCardContainer').html(response.html.checkInCard);
                            if (response.html.checkOutCard) $('#checkOutCardContainer').html(response.html.checkOutCard);
                            $('#checkInBtn').off('click').on('click', function() {
                                let now = new Date();
                                let hours = now.getHours();
                                let minutes = now.getMinutes().toString().padStart(2, '0');
                                let seconds = now.getSeconds().toString().padStart(2, '0');
                                let ampm = hours >= 12 ? 'PM' : 'AM';
                                hours = hours % 12;
                                hours = hours ? hours : 12;
                                $('#checkInTimeDisplay').text(hours + ':' + minutes + ':' + seconds + ' ' + ampm);
                                openModal('checkInModal');
                            });
                            $('#checkOutBtn').off('click').on('click', function() {
                                let now = new Date();
                                let hours = now.getHours();
                                let minutes = now.getMinutes().toString().padStart(2, '0');
                                let seconds = now.getSeconds().toString().padStart(2, '0');
                                let ampm = hours >= 12 ? 'PM' : 'AM';
                                hours = hours % 12;
                                hours = hours ? hours : 12;
                                $('#checkOutTimeDisplay').text(hours + ':' + minutes + ':' + seconds + ' ' + ampm);
                                openModal('checkOutModal');
                            });
                        }
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                        Swal.fire({ icon: 'success', title: '{{ __('messages.success') }}', text: response.message, timer: 1500, showConfirmButton: false });
                    }
                },
                error: function(xhr) {
                    button.prop('disabled', false).html('<i data-lucide="check-circle" class="inline-block size-5 mr-2"></i> {{ __('messages.confirm_check_in') }}');
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    Swal.fire({ icon: 'error', title: '{{ __('messages.error') }}', text: xhr.responseJSON?.message || 'Check-in failed!' });
                }
            });
        });

        $('#confirmCheckOut').on('click', function() {
            let notes = $('#checkOutNotes').val();
            let button = $(this);
            button.prop('disabled', true).html('<i class="animate-spin inline-block size-4 mr-2">⏳</i> {{ __('messages.processing') }}');
            $.ajax({
                url: '{{ route('employee/attendance/check-out') }}',
                type: 'POST',
                data: { notes: notes, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        closeModal('checkOutModal');
                        if (response.html) {
                            if (response.html.recentAttendance) $('#recentAttendanceContainer').html(response.html.recentAttendance);
                            if (response.html.statsCards) $('#statsCardsContainer').html(response.html.statsCards);
                            if (response.html.checkInCard) $('#checkInCardContainer').html(response.html.checkInCard);
                            if (response.html.checkOutCard) $('#checkOutCardContainer').html(response.html.checkOutCard);
                        }
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                        Swal.fire({ icon: 'success', title: '{{ __('messages.success') }}', text: response.message, timer: 1500, showConfirmButton: false });
                    }
                },
                error: function(xhr) {
                    button.prop('disabled', false).html('<i data-lucide="check-circle" class="inline-block size-5 mr-2"></i> {{ __('messages.confirm_check_out') }}');
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    Swal.fire({ icon: 'error', title: '{{ __('messages.error') }}', text: xhr.responseJSON?.message || 'Check-out failed!' });
                }
            });
        });

        function openModal(modalId) { $('#' + modalId).removeClass('hidden').css('display', 'flex'); $('body').css('overflow', 'hidden'); }
        function closeModal(modalId) { $('#' + modalId).addClass('hidden').css('display', 'none'); $('#' + modalId + ' textarea').val(''); $('body').css('overflow', ''); }
        $(document).on('click', function(e) { if ($(e.target).is('[id$="Modal"]')) closeModal($(e.target).attr('id')); });
        $(document).on('keydown', function(e) { if (e.key === 'Escape') $('[id$="Modal"]:visible').each(function() { closeModal($(this).attr('id')); }); });
        $('[data-modal-close]').on('click', function() { closeModal($(this).closest('[id$="Modal"]').attr('id')); });
    </script>
    @endsection
@endsection