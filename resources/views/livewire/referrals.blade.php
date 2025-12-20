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

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">My Referrals</h2>
                <p class="text-gray-600 mt-1">Invite friends and earn free connections!</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Referral Code Card -->
            <div
                class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <h3 class="text-lg font-semibold opacity-90 relative z-10">Your Referral Code</h3>
                <div class="mt-4 flex items-center gap-3 bg-white/10 p-1.5 rounded-xl backdrop-blur-md border border-white/20 relative z-10"
                    x-data="{ copied: false }">
                    <code
                        class="text-2xl font-mono font-bold tracking-wider flex-1 text-center py-2">{{ $referralCode }}</code>
                    <button
                        @click="navigator.clipboard.writeText('{{ $referralCode }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="p-2.5 bg-white/20 hover:bg-white/30 rounded-lg transition-colors focus:outline-none backdrop-blur-sm"
                        title="Copy Code">
                        <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <svg x-show="copied" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
                <p class="mt-3 text-sm text-indigo-100 relative z-10">Share this code with friends to earn standard
                    rewards.</p>
            </div>

            <!-- Total Referrals -->
            <div
                class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>

                <div class="relative z-10 flex flex-col items-center text-center">
                    <div
                        class="p-3 bg-white/10 rounded-full text-white mb-3 backdrop-blur-sm shadow-inner ring-1 ring-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-4xl font-extrabold">{{ $totalReferrals }}</h3>
                    <p class="text-blue-100 font-medium mt-1">Successful Referrals</p>
                </div>
            </div>

            <!-- Total Earned -->
            <div
                class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>

                <div class="relative z-10 flex flex-col items-center text-center">
                    <div
                        class="p-3 bg-white/10 rounded-full text-white mb-3 backdrop-blur-sm shadow-inner ring-1 ring-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-4xl font-extrabold">{{ $earnedConnections }}</h3>
                    <p class="text-emerald-100 font-medium mt-1">Connections Earned</p>
                </div>
            </div>
        </div>

        <!-- Referrals List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Referral History</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-semibold">User Name</th>
                            <th class="px-6 py-4 font-semibold">Joined At</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($referrals as $referral)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-custom-pink/10 flex items-center justify-center text-custom-pink font-bold text-xs">
                                            {{ $referral->initials() }}
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $referral->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $referral->created_at->format('M d, Y') }}
                                    <span
                                        class="text-xs text-gray-400 block">{{ $referral->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p>No referrals yet. Share your code to get started!</p>
                                    </div>
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