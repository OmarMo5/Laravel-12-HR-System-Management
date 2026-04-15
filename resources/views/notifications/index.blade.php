@extends('layouts.master')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-zink-100">{{ __('messages.notifications') }}</h2>
        <form action="{{ route('notifications.mark-all-read') }}" method="POST" id="markAllReadForm">
            @csrf
            <button type="submit" class="text-sm text-custom-500 hover:text-custom-600">
                {{ __('messages.mark_all_read') }}
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-zink-700 rounded-lg shadow overflow-hidden">
        @forelse($notifications as $notification)
            <a href="{{ route('notifications.show', $notification->id) }}" 
               class="flex items-start gap-4 p-4 border-b border-slate-200 dark:border-zink-600 hover:bg-slate-50 dark:hover:bg-zink-600 transition-colors {{ !$notification->is_read ? 'bg-blue-50 dark:bg-zink-600/50' : '' }}">
                <div class="shrink-0">
                    @if($notification->type == 'leave_approved')
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                            <i data-lucide="check-circle" class="size-5 text-green-500"></i>
                        </div>
                    @elseif($notification->type == 'leave_rejected')
                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
                            <i data-lucide="x-circle" class="size-5 text-red-500"></i>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-custom-100 dark:bg-custom-500/20 flex items-center justify-center">
                            <i data-lucide="bell" class="size-5 text-custom-500"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800 dark:text-zink-100">{{ $notification->title }}</h4>
                    <p class="text-sm text-slate-600 dark:text-zink-300 mt-1">{{ $notification->message }}</p>
                    <p class="text-xs text-slate-400 dark:text-zink-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notification->is_read)
                    <div class="shrink-0">
                        <span class="inline-block w-2 h-2 rounded-full bg-custom-500"></span>
                    </div>
                @endif
            </a>
        @empty
            <div class="p-8 text-center">
                <i data-lucide="bell-off" class="size-12 mx-auto text-slate-400 dark:text-zink-400"></i>
                <p class="mt-3 text-slate-500 dark:text-zink-300">{{ __('messages.no_notifications') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection