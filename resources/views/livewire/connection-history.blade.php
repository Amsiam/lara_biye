<?php
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public function with(): array
    {
        return [
            'histories' => Auth::user()->connectionHistory()->latest()->paginate(20),
            'currentBalance' => Auth::user()->connection->connection ?? 0,
        ];
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Connection History</h2>
                <p class="text-gray-600 mt-1">Track your connection usage and credits.</p>
            </div>
            <div
                class="bg-gradient-to-r from-custom-pink to-pink-600 px-6 py-4 rounded-2xl shadow-lg flex items-center gap-4 relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="absolute left-0 bottom-0 w-24 h-24 bg-black/10 rounded-full -ml-10 -mb-10 blur-xl"></div>
                
                <div class="relative flex justify-center items-center h-12 w-12 text-custom-pink bg-white rounded-full shadow-md shrink-0">
                    <i class="ph-bold ph-ticket text-xl"></i>
                </div>
                <div class="relative">
                    <p class="text-xs text-white/80 font-medium uppercase tracking-wider">Current Balance</p>
                    <p class="text-2xl font-bold text-white">{{ $currentBalance }} Connections</p>
                </div>
                </div>
                </div>

        <!-- History Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Date</th>
                            <th class="px-6 py-4 font-semibold">Type</th>
                            <th class="px-6 py-4 font-semibold">Description</th>
                            <th class="px-6 py-4 font-semibold text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($histories as $history)
                            @php
                                $typeMap = [
                                    'connection_request_sent'     => ['label' => 'Request Sent',     'icon' => 'ph-paper-plane-tilt', 'bg' => 'bg-blue-50',   'color' => 'text-blue-600'],
                                    'connection_request_accepted'  => ['label' => 'Request Accepted', 'icon' => 'ph-handshake',         'bg' => 'bg-green-50',  'color' => 'text-green-600'],
                                    'purchase'                     => ['label' => 'Purchase',          'icon' => 'ph-shopping-cart',     'bg' => 'bg-purple-50', 'color' => 'text-purple-600'],
                                ];
                                $meta = $typeMap[$history->type] ?? ['label' => ucwords(str_replace('_', ' ', $history->type)), 'icon' => 'ph-clock-clockwise', 'bg' => 'bg-gray-50', 'color' => 'text-gray-500'];
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $history->created_at->format('M d, Y') }}
                                    <span class="text-xs text-gray-400 block">{{ $history->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $meta['bg'] }} {{ $meta['color'] }}">
                                        <i class="ph-bold {{ $meta['icon'] }}"></i>
                                        {{ $meta['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $history->description }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $history->amount > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $history->amount > 0 ? '+' : '' }}{{ $history->amount }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="ph-bold ph-clock-clockwise text-4xl text-gray-300"></i>
                                        <p>No history records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($histories->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $histories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
