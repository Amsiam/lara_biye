<?php
use App\Models\Purchase;
use function Livewire\Volt\{computed};

$purchases = computed(function () {
    return Purchase::where('user_id', auth()->id())
        ->with('package')
        ->orderBy('created_at', 'desc')
        ->get();
});
?>

@php
    $stageMap = [
        'initiated' => ['label' => 'Initiated', 'bg' => 'bg-gray-100',   'color' => 'text-gray-700',  'icon' => 'ph-clock'],
        'pending'   => ['label' => 'Pending',   'bg' => 'bg-yellow-50',  'color' => 'text-yellow-700','icon' => 'ph-hourglass-medium'],
        'completed' => ['label' => 'Completed', 'bg' => 'bg-green-50',   'color' => 'text-green-700', 'icon' => 'ph-check-circle'],
        'failed'    => ['label' => 'Failed',    'bg' => 'bg-red-50',     'color' => 'text-red-700',   'icon' => 'ph-x-circle'],
        'refunded'  => ['label' => 'Refunded',  'bg' => 'bg-purple-50',  'color' => 'text-purple-700','icon' => 'ph-arrow-u-up-left'],
        'cancelled' => ['label' => 'Cancelled', 'bg' => 'bg-gray-100',   'color' => 'text-gray-600',  'icon' => 'ph-prohibit'],
    ];
@endphp

<div class="py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-custom-red flex items-center gap-2">
                <i class="ph-bold ph-receipt text-custom-pink"></i>
                Payment History
            </h1>
            <p class="text-gray-500 text-sm mt-1">All your package purchases and transactions</p>
        </div>

        @if ($this->purchases->count() > 0)

            <!-- Summary Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center shrink-0">
                        <i class="ph-bold ph-currency-circle-dollar text-xl text-custom-pink"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Spent</p>
                        <p class="text-lg font-extrabold text-gray-900">৳{{ number_format($this->purchases->where('payment_stage', 'completed')->sum('amount'), 0) }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                        <i class="ph-bold ph-check-circle text-xl text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Completed</p>
                        <p class="text-lg font-extrabold text-gray-900">{{ $this->purchases->where('payment_stage', 'completed')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center shrink-0">
                        <i class="ph-bold ph-hourglass-medium text-xl text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Pending</p>
                        <p class="text-lg font-extrabold text-gray-900">{{ $this->purchases->whereIn('payment_stage', ['initiated', 'pending'])->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center shrink-0">
                        <i class="ph-bold ph-handshake text-xl text-custom-pink"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Connections</p>
                        <p class="text-lg font-extrabold text-gray-900">{{ $this->purchases->where('connections_applied', true)->sum('connections_purchased') }}</p>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Date</th>
                            <th class="px-5 py-3 font-semibold">Package</th>
                            <th class="px-5 py-3 font-semibold">Connections</th>
                            <th class="px-5 py-3 font-semibold">Amount</th>
                            <th class="px-5 py-3 font-semibold">Stage</th>
                            <th class="px-5 py-3 font-semibold">Transaction ID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($this->purchases as $purchase)
                            @php $stage = $stageMap[$purchase->payment_stage] ?? ['label' => ucfirst($purchase->payment_stage), 'bg' => 'bg-gray-100', 'color' => 'text-gray-700', 'icon' => 'ph-question']; @endphp
                            <tr class="hover:bg-pink-50/20 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $purchase->created_at->local()->format('M d, Y') }}
                                    <span class="text-xs text-gray-400 block">{{ $purchase->created_at->local()->format('h:i A') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $purchase->package->name }}</p>
                                    @if($purchase->package->description)
                                        <p class="text-xs text-gray-500">{{ $purchase->package->description }}</p>
                                    @endif
                                    @if($purchase->is_refunded)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 mt-1">
                                            <i class="ph-bold ph-arrow-u-up-left"></i> Refunded
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-sm">
                                    <span class="font-semibold text-gray-900">{{ $purchase->connections_purchased }}</span>
                                    @if($purchase->connections_applied)
                                        <span class="text-xs text-green-600 flex items-center gap-1 mt-0.5">
                                            <i class="ph-bold ph-check-circle"></i> Applied
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 block mt-0.5">Not applied</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">৳{{ number_format($purchase->amount, 2) }}</span>
                                    @if($purchase->invoice_number)
                                        <span class="text-xs text-gray-400 block">{{ $purchase->invoice_number }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $stage['bg'] }} {{ $stage['color'] }}">
                                        <i class="ph-bold {{ $stage['icon'] }}"></i>
                                        {{ $stage['label'] }}
                                    </span>
                                    @if($purchase->error_message)
                                        <span class="text-xs text-red-500 block mt-1">Error occurred</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">
                                    {{ $purchase->transaction_id ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-3">
                @foreach ($this->purchases as $purchase)
                    @php $stage = $stageMap[$purchase->payment_stage] ?? ['label' => ucfirst($purchase->payment_stage), 'bg' => 'bg-gray-100', 'color' => 'text-gray-700', 'icon' => 'ph-question']; @endphp
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-bold text-gray-900 text-sm">{{ $purchase->package->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $purchase->created_at->local()->format('M d, Y · h:i A') }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $stage['bg'] }} {{ $stage['color'] }} shrink-0">
                                <i class="ph-bold {{ $stage['icon'] }}"></i>
                                {{ $stage['label'] }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <p class="text-xs text-gray-400">Amount</p>
                                <p class="font-bold text-gray-900">৳{{ number_format($purchase->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Connections</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $purchase->connections_purchased }}
                                    @if($purchase->connections_applied)
                                        <span class="text-xs text-green-600 font-medium">✓ Applied</span>
                                    @endif
                                </p>
                            </div>
                            @if($purchase->transaction_id)
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-400">Transaction ID</p>
                                    <p class="font-mono text-xs text-gray-600 truncate">{{ $purchase->transaction_id }}</p>
                                </div>
                            @endif
                        </div>
                        @if($purchase->is_refunded)
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <span class="inline-flex items-center gap-1 text-xs text-red-600 font-semibold">
                                    <i class="ph-bold ph-arrow-u-up-left"></i> Refunded
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-16 text-center">
                <i class="ph-bold ph-receipt text-5xl text-gray-300 block mb-3"></i>
                <p class="text-gray-700 font-semibold text-lg">No payment history</p>
                <p class="text-gray-400 text-sm mt-1">You haven't made any purchases yet.</p>
                <a href="{{ route('packages') }}"
                    class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-custom-pink text-white rounded-lg font-semibold hover:bg-custom-red transition-colors text-sm">
                    <i class="ph-bold ph-shopping-cart"></i>
                    Browse Packages
                </a>
            </div>
        @endif

    </div>
</div>
