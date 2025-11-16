@props([
    'profile' => null,
])

<div
    class="bg-white p-5 rounded-lg shadow-lg text-black hover:shadow-2xl  hover:bg-gray-100 transform transition-all duration-300 hover:scale-105 flex flex-col items-center text-center">
    <img src="{{ asset($profile->basicInfo?->image) }}" class="w-full h-60 object-contain rounded-lg"
        alt="Profile Picture" />
    <div class="mt-3 flex items-center justify-center gap-2">
        @if (auth()->user()->isConnected($profile->id))
            <h3 class="text-lg font-bold text-center">{{ $profile->name }}</h3>
        @else
            <h3 class="text-lg font-bold text-center h-8"></h3>
        @endif

        @if ($profile->isProfileVerified())
            <span class="inline-flex items-center" title="Profile Verified by Admin">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif
    </div>
    <p class="text-gray-700 flex items-center gap-1">
        👤 Age: {{ floor(-1 * now()->diffInYears($profile->basicInfo?->dob)) }} | 🕌 Religion:
        {{ $profile?->basicInfo?->religion }}
    </p>
    <a href="{{ route('profile', $profile->id) }}"
        class="mt-3 p-2 w-full bg-custom-pink text-white font-bold rounded hover:bg-opacity-90">
        View Profile
    </a>
</div>
