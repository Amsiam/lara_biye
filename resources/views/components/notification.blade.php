@php
    $notifications = auth()->user()->notifications()->where('read', false)->latest()->get();
@endphp

<div class="relative" x-data="{ open: false }" x-cloak>
    <button @click="open = !open" @click.outside="open = false"
        class="relative p-2 text-gray-500 hover:text-custom-pink hover:bg-pink-50 rounded-lg transition-colors focus:outline-none"
        type="button">
        <i class="ph-bold ph-bell text-xl"></i>
        @if ($notifications->count() > 0)
            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
        @endif
    </button>

    <div x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-[200] origin-top-right"
        @click.outside="open = false">

        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <span class="font-semibold text-gray-800 text-sm">Notifications</span>
            @if ($notifications->count() > 0)
                <span class="text-xs bg-pink-100 text-custom-pink font-bold px-2 py-0.5 rounded-full">
                    {{ $notifications->count() }}
                </span>
            @endif
        </div>

        <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
            @forelse ($notifications as $notification)
                <a href="{{ route('notifications.show', $notification->id) }}"
                    class="flex items-start gap-3 px-4 py-3 hover:bg-pink-50/50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="ph-fill ph-bell text-sm text-custom-pink"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 leading-snug">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <div class="py-8 text-center">
                    <i class="ph-bold ph-bell-slash text-3xl text-gray-300 block mb-2"></i>
                    <p class="text-sm text-gray-400">No new notifications</p>
                </div>
            @endforelse
        </div>

        @if ($notifications->count() > 0)
            <div class="border-t border-gray-100">
                <a href="{{ route('notifications.markAllAsRead') }}"
                    class="flex items-center justify-center gap-1.5 py-3 text-sm font-semibold text-custom-pink hover:bg-pink-50 rounded-b-xl transition-colors">
                    <i class="ph-bold ph-checks"></i>
                    Mark all as read
                </a>
            </div>
        @endif
    </div>
</div>
