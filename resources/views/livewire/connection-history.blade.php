<?php
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{computed};

$connections = computed(function () {
    // Get all accepted connections (both sent and received)
    return DB::table('connected')
        ->where(function ($query) {
            $query->where('user_id', auth()->id())->orWhere('connected_user_id', auth()->id());
        })
        ->where('status', 'ACCEPTED')
        ->get()
        ->map(function ($connection) {
            // Determine which user is the connected user
            $connectedUserId = $connection->user_id == auth()->id() ? $connection->connected_user_id : $connection->user_id;

            $connectedUser = \App\Models\User::with('basicInfo', 'location', 'education')->find($connectedUserId);

            return (object) [
                'user' => $connectedUser,
                'connected_at' => now(), // Pivot table doesn't have timestamps
            ];
        });
});

?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Connection History</h1>
            <p class="text-gray-600 mt-2">View all your accepted connections</p>
        </div>

        @if ($this->connections->count() > 0)
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-custom-pink" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Connections</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $this->connections->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-custom-pink" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Available Connections</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ auth()->user()?->connection()?->first()?->connection ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Connections Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($this->connections as $item)
                    @if ($item->user)
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <a href="{{ route('profile', ['profileId' => $item->user->id]) }}" class="block">
                                <!-- Profile Image -->
                                <div class="aspect-w-1 aspect-h-1 w-full bg-gray-200">
                                    @if ($item->user->basicInfo?->profile_image)
                                        <img src="{{ asset('storage/' . $item->user->basicInfo->profile_image) }}"
                                            alt="{{ $item->user->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                                            <svg class="h-20 w-20 text-gray-400" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Profile Info -->
                                <div class="p-4">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate">
                                            {{ $item->user->basicInfo?->first_name ?? $item->user->name }}
                                        </h3>
                                        @if ($item->user->isProfileVerified())
                                            <span class="inline-flex items-center flex-shrink-0" title="Profile Verified by Admin">
                                                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                                        @if ($item->user->basicInfo?->age)
                                            <p class="flex items-center">
                                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $item->user->basicInfo->age }} years old
                                            </p>
                                        @endif

                                        @if ($item->user->location)
                                            <p class="flex items-center">
                                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $item->user->location->district ?? 'N/A' }}
                                            </p>
                                        @endif

                                        @if ($item->user->education?->education)
                                            <p class="flex items-center">
                                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                                    <path
                                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                                </svg>
                                                {{ $item->user->education->education }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Connection Status -->
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-green-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Connected
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white py-10 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No connections yet</h3>
                <p class="mt-2 text-sm text-gray-500">Start searching for your perfect match and send connection
                    requests.</p>
                <div class="mt-6 flex justify-center gap-4">
                    <a href="{{ route('search') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-custom-pink hover:bg-opacity-90">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search Profiles
                    </a>
                    <a href="{{ route('packages') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buy Connections
                    </a>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="/dashboard" class="text-custom-pink hover:text-custom-red font-semibold">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</div>
