<div class="grid grid-cols-1 gap-5 lg:grid-cols-4">
    <div class="col-span-1">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-md size-12 text-green-500 bg-green-100">
                        <i data-lucide="check-circle" class="size-6"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-xl">{{ $monthlyStats['present'] }}</h5>
                        <p class="text-slate-500">{{ __('messages.present_days') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-span-1">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-md size-12 text-yellow-500 bg-yellow-100">
                        <i data-lucide="alert-circle" class="size-6"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-xl">{{ $monthlyStats['late'] }}</h5>
                        <p class="text-slate-500">{{ __('messages.late_days') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-1">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-md size-12 text-red-500 bg-red-100">
                        <i data-lucide="x-circle" class="size-6"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-xl">{{ $monthlyStats['absent'] }}</h5>
                        <p class="text-slate-500">{{ __('messages.absent_days') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-span-1">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-md size-12 text-purple-500 bg-purple-100">
                        <i data-lucide="clock" class="size-6"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-xl">{{ number_format($monthlyStats['total_hours'], 1) }}</h5>
                        <p class="text-slate-500">{{ __('messages.total_hours') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>