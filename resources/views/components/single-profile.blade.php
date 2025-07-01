@props([
    'profile' => null,
])

<div
    class="bg-white p-5 rounded-lg shadow-lg text-black hover:shadow-2xl  hover:bg-gray-100 transform transition-all duration-300 hover:scale-105 flex flex-col items-center text-center">
    <img src="{{ asset($profile->basicInfo?->image) }}" class="w-full h-60 object-contain rounded-lg"
        alt="Profile Picture" />
    @if (auth()->user()->isConnected($profile->id))
        <h3 class="text-lg font-bold mt-3 text-center">{{ $profile->name }}</h3>
    @else
        <h3 class="text-lg font-bold mt-3 text-center h-8"></h3>
    @endif
    <p class="text-gray-700 flex items-center gap-1">
        👤 Age: {{ floor(-1 * now()->diffInYears($profile->basicInfo?->dob)) }} | 🕌 Religion:
        {{ $profile?->basicInfo?->religion }}
    </p>
    <a href="{{ route('profile', $profile->id) }}"
        class="mt-3 p-2 w-full bg-custom-pink text-white font-bold rounded hover:bg-opacity-90">
        View Profile
    </a>
</div>
