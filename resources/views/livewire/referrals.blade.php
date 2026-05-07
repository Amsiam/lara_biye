<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\Setting;

new #[Layout('components.layouts.app')] class extends Component {
    public function with(): array
    {
        $user = Auth::user();
        return [
            'referralCode' => $user->referral_code,
            'referrals' => $user->referrals()->latest()->paginate(20),
            'totalReferrals' => $user->referrals()->count(),
            'earnedConnections' => $user->connectionHistory()->where('type', 'referral_bonus')->sum('amount'),
        ];
    }
}; ?>

<div class="py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div>
            <h2 class="text-2xl font-bold text-custom-red flex items-center gap-2">
                <i class="ph-bold ph-share-network text-custom-pink"></i>
                My Referrals
            </h2>
            <p class="text-gray-500 text-sm mt-1">Invite friends and earn free connections!</p>
        </div>

        <!-- Referral Code Hero -->
        <div class="bg-gradient-to-br from-custom-pink to-custom-red rounded-2xl p-6 text-white shadow-xl relative overflow-hidden"
            x-data="{ copied: false }">
            <div class="absolute right-0 top-0 w-40 h-40 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl pointer-events-none"></div>
            <div class="absolute left-0 bottom-0 w-28 h-28 bg-black/10 rounded-full -ml-10 -mb-10 blur-xl pointer-events-none"></div>

            <div class="relative z-10">
                <p class="text-white/80 text-sm font-semibold uppercase tracking-widest mb-3 flex items-center gap-1.5">
                    <i class="ph-bold ph-ticket text-base"></i>
                    Your Referral Code
                </p>
                <div class="flex items-center gap-3 bg-white/15 backdrop-blur-sm border border-white/25 rounded-xl p-2">
                    <code class="text-2xl sm:text-3xl font-mono font-bold tracking-widest flex-1 text-center py-1">{{ $referralCode }}</code>
                    <button
                        @click="navigator.clipboard.writeText('{{ $referralCode }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="p-2.5 bg-white/20 hover:bg-white/30 rounded-lg transition-all focus:outline-none shrink-0"
                        :title="copied ? 'Copied!' : 'Copy Code'">
                        <i x-show="!copied" class="ph-bold ph-copy text-lg"></i>
                        <i x-show="copied" class="ph-bold ph-check text-lg" style="display:none"></i>
                    </button>
                </div>
                <p x-show="copied" class="text-white/90 text-xs mt-2 font-medium" style="display:none">
                    <i class="ph-bold ph-check-circle"></i> Copied to clipboard!
                </p>
                <p x-show="!copied" class="text-white/70 text-xs mt-2">Share this code — your friend gets a bonus and so do you.</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-pink-50 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-users text-2xl text-custom-pink"></i>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $totalReferrals }}</p>
                    <p class="text-sm text-gray-500 font-medium">Successful Referrals</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-pink-50 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-handshake text-2xl text-custom-pink"></i>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $earnedConnections }}</p>
                    <p class="text-sm text-gray-500 font-medium">Connections Earned</p>
                </div>
            </div>
        </div>

        <!-- Referral History Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ph-bold ph-clock-clockwise text-custom-pink text-lg"></i>
                <h3 class="text-base font-bold text-gray-800">Referral History</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-semibold">User</th>
                            <th class="px-6 py-3 font-semibold">Joined At</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($referrals as $referral)
                            <tr class="hover:bg-pink-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-custom-pink font-bold text-xs shrink-0">
                                            {{ $referral->initials() }}
                                        </div>
                                        <span class="font-medium text-gray-800 text-sm">{{ $referral->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $referral->created_at->local()->format('M d, Y') }}
                                    <span class="text-xs text-gray-400 block">{{ $referral->created_at->local()->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                        <i class="ph-bold ph-check-circle"></i> Completed
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-14 text-center">
                                    <i class="ph-bold ph-users text-5xl text-gray-300 block mb-3"></i>
                                    <p class="text-gray-500 font-semibold">No referrals yet</p>
                                    <p class="text-gray-400 text-sm mt-1">Share your code above to get started!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($referrals->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $referrals->links() }}
                </div>
            @endif
        </div>

    </div>
</div>