@props(['profile' => null])

@php
    $isConnected = auth()->check() && auth()->user()->isConnected($profile->id);
    $age = $profile->basicInfo?->dob ? \Carbon\Carbon::parse($profile->basicInfo->dob)->age : null;
    $gender = $profile->basicInfo?->gender;
    $genderColor = $gender === 'FEMALE' ? 'text-pink-500 bg-pink-50' : 'text-blue-500 bg-blue-50';
    $religion = $profile->basicInfo?->religion;
@endphp

<div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col overflow-hidden group">
    <!-- Image -->
    <div class="relative overflow-hidden">
        <img src="{{ route('profile.image', $profile->id) }}"
             class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500"
             alt="Profile Picture"
             onerror="this.src='{{ asset('default.png') }}'" />

        @if ($profile->isProfileVerified())
            <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center gap-1 text-xs font-semibold text-pink-500 shadow-sm">
                <i class="ph-fill ph-seal-check text-base"></i>
                Verified
            </span>
        @endif

        @if ($gender)
            <span class="absolute top-2 left-2 rounded-full w-7 h-7 flex items-center justify-center shadow-sm {{ $genderColor }}">
                <i class="ph-bold {{ $gender === 'FEMALE' ? 'ph-gender-female' : 'ph-gender-male' }} text-base"></i>
            </span>
        @endif
    </div>

    <!-- Info -->
    <div class="p-4 flex flex-col flex-1">
        <div class="mb-3">
            @if ($isConnected)
                <h3 class="text-base font-bold text-gray-900 truncate">{{ $profile->name }}</h3>
            @else
                <h3 class="text-base font-bold text-gray-400 italic">Profile Hidden</h3>
            @endif
        </div>

        <div class="flex flex-wrap gap-2 text-xs text-gray-600 mb-4">
            @if ($age)
                <span class="bg-gray-100 px-2 py-1 rounded-full">{{ $age }} yrs</span>
            @endif
            @if ($religion)
                <span class="bg-gray-100 px-2 py-1 rounded-full">{{ ucfirst(strtolower($religion)) }}</span>
            @endif
        </div>

        <a href="{{ route('profile', $profile->id) }}"
           class="mt-auto w-full text-center py-2 bg-custom-pink text-white text-sm font-semibold rounded-lg hover:bg-custom-red transition-colors duration-200">
            View Profile
        </a>
    </div>
</div>
