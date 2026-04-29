<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>
    <button @click="open = !open" class="relative flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-9 text-slate-500 hover:text-slate-700 dark:text-zink-200 dark:hover:text-zink-100">
        <i data-lucide="bell" class="size-5"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 border-2 border-white rounded-full dark:border-zink-700 shadow-sm animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="absolute right-0 z-50 w-80 mt-2 origin-top-right bg-white rounded-md shadow-lg dark:bg-zink-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
        
        <div class="p-3 border-b border-slate-200 dark:border-zink-500">
            <div class="flex items-center justify-between">
                <h6 class="text-sm font-semibold text-slate-700 dark:text-zink-100">{{ __('messages.notifications') }}</h6>
                <button onclick="markAllNotificationsRead()" class="text-xs text-custom-500 hover:text-custom-600">
                    {{ __('messages.mark_all_read') }}
                </button>
            </div>
        </div>
        
        <div class="max-h-[400px] overflow-y-auto custom-scrollbar" data-simplebar>
            @forelse($notifications as $notification)
                <a href="{{ route('notifications.show', $notification->id) }}" 
                   class="flex items-start gap-3 p-3 transition-colors duration-200 border-b border-slate-100 hover:bg-slate-50 dark:border-zink-600 dark:hover:bg-zink-600 {{ !$notification->is_read ? 'bg-blue-50 dark:bg-zink-600/50' : '' }}">
                    <div class="shrink-0">
                        @if($notification->type == 'leave_approved')
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-500/20">
                                <i data-lucide="check-circle" class="size-4 text-green-500"></i>
                            </div>
                        @elseif($notification->type == 'leave_rejected')
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-500/20">
                                <i data-lucide="x-circle" class="size-4 text-red-500"></i>
                            </div>
                        @else
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-custom-100 dark:bg-custom-500/20">
                                <i data-lucide="bell" class="size-4 text-custom-500"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 dark:text-zink-100">
                            {{ str_starts_with($notification->title, 'messages.') ? __($notification->title) : $notification->title }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-zink-300 line-clamp-2">
                            {{ str_starts_with($notification->message, 'messages.') ? __($notification->message) : $notification->message }}
                        </p>
                        <p class="mt-1 text-xs text-slate-400 dark:text-zink-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @if(!$notification->is_read)
                        <div class="shrink-0">
                            <span class="inline-block w-2 h-2 rounded-full bg-custom-500"></span>
                        </div>
                    @endif
                </a>
            @empty
                <div class="py-8 text-center">
                    <i data-lucide="bell-off" class="size-8 mx-auto text-slate-400 dark:text-zink-400"></i>
                    <p class="mt-2 text-sm text-slate-500 dark:text-zink-300">{{ __('messages.no_notifications') }}</p>
                </div>
            @endforelse
        </div>
        
        <div class="p-2 border-t border-slate-200 dark:border-zink-500">
            <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-custom-500 hover:text-custom-600">
                {{ __('messages.view_all_notifications') }}
            </a>
        </div>
    </div>
</div>

<script>
function markAllNotificationsRead() {
    fetch('{{ route("notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              location.reload();
          }
      });
}
</script>