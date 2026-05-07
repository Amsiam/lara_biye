<?php

use Livewire\WithPagination;
use function Livewire\Volt\{state, mount, computed, uses};

uses([WithPagination::class]);

state([
    'gender', 'age', 'marital_status', 'religion',
    'min_height', 'max_height', 'education', 'profession',
    'district', 'city', 'min_income', 'max_income',
    'body_type', 'complexion',
    'verified_only' => false,
    'sort' => 'newest',
    'showAdvanced' => false,
]);

mount(function () {
    $this->gender = request()->get('gender');
    $this->age = request()->get('age');
    $this->marital_status = request()->get('marital_status');
    $this->religion = request()->get('religion');
    $this->min_height = request()->get('min_height');
    $this->max_height = request()->get('max_height');
    $this->education = request()->get('education');
    $this->profession = request()->get('profession');
    $this->district = request()->get('district');
    $this->city = request()->get('city');
    $this->min_income = request()->get('min_income');
    $this->max_income = request()->get('max_income');
    $this->body_type = request()->get('body_type');
    $this->complexion = request()->get('complexion');
    $this->verified_only = (bool) request()->get('verified_only', false);
    $this->sort = request()->get('sort', 'newest');
});

$clearFilters = function () {
    $this->gender = null;
    $this->age = null;
    $this->marital_status = null;
    $this->religion = null;
    $this->min_height = null;
    $this->max_height = null;
    $this->education = null;
    $this->profession = null;
    $this->district = null;
    $this->city = null;
    $this->min_income = null;
    $this->max_income = null;
    $this->body_type = null;
    $this->complexion = null;
    $this->verified_only = false;
    $this->sort = 'newest';
    $this->resetPage();
};

$goToPage = function ($page) {
    $this->setPage($page);
};

$activeFilterCount = computed(function () {
    $filters = [
        $this->min_height, $this->max_height,
        $this->body_type, $this->complexion,
        $this->education, $this->profession,
        $this->min_income, $this->max_income,
        $this->district, $this->city,
    ];
    return count(array_filter($filters, fn($v) => $v !== null && $v !== ''));
});

$profiles = computed(function () {
    $query = \App\Models\User::query()
        ->where('is_admin', false)
        ->where('hide_from_search', false)
        ->when(auth()->check(), function ($query) {
            $query->where('id', '!=', auth()->id());
        })
        ->when($this->verified_only, function ($query) {
            $query->whereNotNull('profile_verified_at');
        })
        ->whereHas('basicInfo', function ($query) {
            $query->when($this->gender, function ($query) {
                $query->where('gender', strtoupper($this->gender));
            });

            $query->when($this->age, function ($query) {
                $ex = explode('-', $this->age);
                if (count($ex) != 2) return $query;
                $minAge = (int) $ex[0];
                $maxAge = (int) $ex[1];
                $currentYear = now()->year;
                $query->whereYear('dob', '>=', $currentYear - $maxAge)
                      ->whereYear('dob', '<=', $currentYear - $minAge);
            });

            $query->when($this->marital_status, function ($query) {
                $query->where('marital_status', strtoupper($this->marital_status));
            });

            $query->when($this->religion, function ($query) {
                $query->where('religion', 'LIKE', '%' . $this->religion . '%');
            });
        })
        ->when($this->min_height || $this->max_height, function ($query) {
            $query->whereHas('basicInfo', function ($q) {
                $q->when($this->min_height, fn($q) => $q->where('height', '>=', $this->min_height));
                $q->when($this->max_height, fn($q) => $q->where('height', '<=', $this->max_height));
            });
        })
        ->when($this->body_type, function ($query) {
            $query->whereHas('physical_attr', fn($q) => $q->where('body_type', 'LIKE', '%' . $this->body_type . '%'));
        })
        ->when($this->complexion, function ($query) {
            $query->whereHas('physical_attr', fn($q) => $q->where('complexion', 'LIKE', '%' . $this->complexion . '%'));
        })
        ->when($this->education, function ($query) {
            $query->whereHas('education', fn($q) => $q->where('highest_education', 'LIKE', '%' . $this->education . '%'));
        })
        ->when($this->profession, function ($query) {
            $query->whereHas('education', fn($q) => $q->where('occupation', 'LIKE', '%' . $this->profession . '%'));
        })
        ->when($this->min_income || $this->max_income, function ($query) {
            $query->whereHas('education', function ($q) {
                $q->when($this->min_income, fn($q) => $q->where('annual_income', '>=', $this->min_income * 12));
                $q->when($this->max_income, fn($q) => $q->where('annual_income', '<=', $this->max_income * 12));
            });
        })
        ->when($this->district, function ($query) {
            $query->whereHas('location', fn($q) => $q->where('district', 'LIKE', '%' . $this->district . '%'));
        })
        ->when($this->city, function ($query) {
            $query->whereHas('location', fn($q) => $q->where('upazilla', 'LIKE', '%' . $this->city . '%'));
        });

    match ($this->sort) {
        'oldest'  => $query->oldest(),
        'random'  => $query->inRandomOrder(),
        default   => $query->latest(),
    };

    return $query->paginate(20);
});

?>


<div class="max-w-6xl mx-auto mt-20">
    <!-- Search Filters -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-10">
        <div class="max-w-6xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-xl">
            <h2 class="text-xl font-bold text-custom-red text-center mb-4 flex items-center justify-center gap-2">
                <i class="ph-bold ph-funnel text-custom-pink"></i>
                Refine Your Search
            </h2>

            <!-- Basic Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-select-input wire-model="gender" name="gender" placeholder="I'm looking for"
                    :options="['Male' => 'Male', 'Female' => 'Female']"
                    :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\' /></svg>'" />

                <x-select-input wire-model="marital_status" name="marital_status" placeholder="Marital Status"
                    :options="[
                        'UNMARRIED' => 'Unmarried',
                        'MARRIED' => 'Married',
                        'DIVORCED' => 'Divorced',
                        'WIDOWED' => 'Widowed',
                    ]"
                    :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z\' /></svg>'" />

                <x-select-input wire-model="age" name="age" placeholder="Select Age"
                    :options="[
                        '18-25' => '18–25 years',
                        '26-35' => '26–35 years',
                        '36-45' => '36–45 years',
                        '46-55' => '46–55 years',
                        '56-65' => '56–65 years',
                    ]"
                    :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\' /></svg>'" />

                <x-select-input wire-model="religion" name="religion" placeholder="Religion"
                    :options="[
                        'Islam' => 'Islam',
                        'Hinduism' => 'Hinduism',
                        'Buddhism' => 'Buddhism',
                        'Christianity' => 'Christianity',
                        'Other' => 'Other',
                    ]"
                    :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253\' /></svg>'" />
            </div>

            <!-- Quick Toggles -->
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <!-- Verified Only -->
                <button type="button" wire:click="$toggle('verified_only')"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold border-2 transition-all duration-200 {{ $verified_only ? 'bg-custom-pink text-white border-custom-pink' : 'bg-white text-gray-600 border-gray-300 hover:border-custom-pink hover:text-custom-pink' }}">
                    <i class="ph-fill ph-seal-check text-base"></i>
                    Verified Only
                </button>

                <!-- Active filter pills -->
                @if ($gender)
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-pink-50 text-pink-700 rounded-full text-sm font-medium border border-pink-200">
                        <i class="ph-bold ph-user text-xs"></i>
                        {{ ucfirst(strtolower($gender)) }}
                        <button wire:click="$set('gender', null)" class="ml-1 hover:text-pink-900"><i class="ph-bold ph-x text-xs"></i></button>
                    </span>
                @endif
                @if ($marital_status)
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-pink-50 text-pink-700 rounded-full text-sm font-medium border border-pink-200">
                        <i class="ph-bold ph-heart text-xs"></i>
                        {{ ucfirst(strtolower($marital_status)) }}
                        <button wire:click="$set('marital_status', null)" class="ml-1 hover:text-pink-900"><i class="ph-bold ph-x text-xs"></i></button>
                    </span>
                @endif
                @if ($age)
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-pink-50 text-pink-700 rounded-full text-sm font-medium border border-pink-200">
                        <i class="ph-bold ph-calendar text-xs"></i>
                        {{ $age }} yrs
                        <button wire:click="$set('age', null)" class="ml-1 hover:text-pink-900"><i class="ph-bold ph-x text-xs"></i></button>
                    </span>
                @endif
                @if ($religion)
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-pink-50 text-pink-700 rounded-full text-sm font-medium border border-pink-200">
                        <i class="ph-bold ph-book-open text-xs"></i>
                        {{ $religion }}
                        <button wire:click="$set('religion', null)" class="ml-1 hover:text-pink-900"><i class="ph-bold ph-x text-xs"></i></button>
                    </span>
                @endif
            </div>

            <!-- Advanced Filters Toggle -->
            <div class="mt-5 text-center">
                <button type="button" wire:click="$toggle('showAdvanced')"
                    class="text-custom-pink font-semibold hover:text-custom-red transition-colors inline-flex items-center gap-2">
                    <i class="ph-bold ph-caret-down text-base transition-transform duration-200 {{ $showAdvanced ? 'rotate-180' : '' }}"></i>
                    {{ $showAdvanced ? 'Hide Advanced Filters' : 'Show Advanced Filters' }}
                    @if ($this->activeFilterCount > 0)
                        <span class="bg-custom-pink text-white text-xs font-bold rounded-full px-2 py-0.5 ml-1">{{ $this->activeFilterCount }}</span>
                    @endif
                </button>
            </div>

            <!-- Advanced Filters Section -->
            @if ($showAdvanced)
                <div class="mt-5 pt-5 border-t-2 border-gray-100">
                    <!-- Physical Attributes -->
                    <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="ph-bold ph-person text-custom-pink"></i> Physical Attributes
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                        <input type="number" wire:model.live="min_height" name="min_height"
                            placeholder="Min Height (cm)"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all hover:border-custom-pink text-sm">
                        <input type="number" wire:model.live="max_height" name="max_height"
                            placeholder="Max Height (cm)"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all hover:border-custom-pink text-sm">
                        <x-select-input wire-model="body_type" name="body_type" placeholder="Body Type"
                            :options="['Slim' => 'Slim', 'Athletic' => 'Athletic', 'Average' => 'Average', 'Heavy' => 'Heavy']" />
                        <x-select-input wire-model="complexion" name="complexion" placeholder="Complexion"
                            :options="['Fair' => 'Fair', 'Wheatish' => 'Wheatish', 'Dark' => 'Dark']" />
                    </div>

                    <!-- Education & Career -->
                    <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="ph-bold ph-graduation-cap text-custom-pink"></i> Education & Career
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                        <input type="text" wire:model.live="education" name="education"
                            placeholder="Education (e.g., BSc)"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all hover:border-custom-pink text-sm">
                        <input type="text" wire:model.live="profession" name="profession"
                            placeholder="Profession (e.g., Engineer)"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all hover:border-custom-pink text-sm">
                        <input type="number" wire:model.live="min_income" name="min_income"
                            placeholder="Min Monthly Income"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all hover:border-custom-pink text-sm">
                        <input type="number" wire:model.live="max_income" name="max_income"
                            placeholder="Max Monthly Income"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all hover:border-custom-pink text-sm">
                    </div>

                    <!-- Location -->
                    <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="ph-bold ph-map-pin text-custom-pink"></i> Location
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" wire:model.live="district" name="district"
                            placeholder="District (e.g., Dhaka)"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all hover:border-custom-pink text-sm">
                        <input type="text" wire:model.live="city" name="city"
                            placeholder="Upazilla (e.g., Mirpur)"
                            class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all hover:border-custom-pink text-sm">
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button type="button" wire:click="clearFilters"
                    class="w-full bg-gray-100 text-gray-700 p-3 rounded-lg hover:bg-gray-200 transition-all font-semibold flex items-center justify-center gap-2">
                    <i class="ph-bold ph-x-circle text-lg"></i>
                    Clear Filters
                </button>
                <button type="button" wire:click="$refresh"
                    class="w-full bg-custom-pink text-white p-3 rounded-lg hover:bg-pink-600 transition-all font-semibold flex items-center justify-center gap-2 shadow-md">
                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    Search Profiles
                </button>
            </div>
        </div>
    </div>

    <!-- Results Header -->
    <div class="container mx-auto px-4 sm:px-6 pb-4">
        <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Matched Profiles</h2>
                <span class="bg-custom-pink text-white px-3 py-1 rounded-full text-sm font-semibold">
                    <i class="ph-bold ph-users mr-1"></i>{{ $this->profiles->total() }}
                </span>
            </div>

            <!-- Sort -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-500 font-medium hidden sm:block">Sort:</label>
                <select wire:model.live="sort"
                    class="text-sm border-2 border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 focus:ring-2 focus:ring-custom-pink focus:border-custom-pink bg-white font-medium">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="random">Random</option>
                </select>
            </div>
        </div>

        <!-- Loading overlay -->
        <div wire:loading class="flex justify-center items-center py-6">
            <div class="flex items-center gap-2 text-custom-pink font-semibold">
                <i class="ph-bold ph-circle-notch animate-spin text-2xl"></i>
                Searching...
            </div>
        </div>

        <div wire:loading.remove>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($this->profiles as $profile)
                    <x-single-profile :profile="$profile" />
                @empty
                    <div class="col-span-3 py-16 text-center">
                        <i class="ph-bold ph-magnifying-glass text-5xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500 font-semibold text-lg">No profiles found</p>
                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                        <button wire:click="clearFilters" class="mt-4 text-custom-pink font-semibold hover:underline text-sm">
                            Clear all filters
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($this->profiles->hasPages())
                @php
                    $current = $this->profiles->currentPage();
                    $last = $this->profiles->lastPage();
                    $window = collect(range(max(1, $current - 2), min($last, $current + 2)));
                @endphp
                <div class="mt-10 flex flex-col items-center gap-3">
                    <nav class="flex items-center gap-1.5 flex-wrap justify-center" role="navigation" aria-label="Pagination">
                        {{-- Previous --}}
                        @if ($this->profiles->onFirstPage())
                            <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed font-semibold flex items-center gap-1 text-sm">
                                <i class="ph-bold ph-caret-left"></i>
                                <span class="hidden sm:inline">Previous</span>
                            </span>
                        @else
                            <button wire:click="previousPage" wire:loading.attr="disabled"
                                class="px-3 py-2 bg-white border-2 border-custom-pink text-custom-pink rounded-lg hover:bg-custom-pink hover:text-white transition-all font-semibold flex items-center gap-1 text-sm shadow-sm">
                                <i class="ph-bold ph-caret-left"></i>
                                <span class="hidden sm:inline">Previous</span>
                            </button>
                        @endif

                        {{-- First page + ellipsis --}}
                        @if ($window->first() > 1)
                            <button wire:click="goToPage(1)" class="px-3 py-2 bg-white text-gray-700 rounded-lg hover:bg-custom-pink hover:text-white transition-all font-semibold border-2 border-gray-200 hover:border-custom-pink text-sm">1</button>
                            @if ($window->first() > 2)
                                <span class="px-2 text-gray-400">…</span>
                            @endif
                        @endif

                        {{-- Window pages --}}
                        @foreach ($window as $page)
                            @if ($page == $current)
                                <span class="px-3 py-2 bg-custom-pink text-white rounded-lg font-bold border-2 border-custom-pink text-sm shadow-md">{{ $page }}</span>
                            @else
                                <button wire:click="goToPage({{ $page }})"
                                    class="px-3 py-2 bg-white text-gray-700 rounded-lg hover:bg-custom-pink hover:text-white transition-all font-semibold border-2 border-gray-200 hover:border-custom-pink text-sm">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach

                        {{-- Last page + ellipsis --}}
                        @if ($window->last() < $last)
                            @if ($window->last() < $last - 1)
                                <span class="px-2 text-gray-400">…</span>
                            @endif
                            <button wire:click="goToPage({{ $last }})" class="px-3 py-2 bg-white text-gray-700 rounded-lg hover:bg-custom-pink hover:text-white transition-all font-semibold border-2 border-gray-200 hover:border-custom-pink text-sm">{{ $last }}</button>
                        @endif

                        {{-- Next --}}
                        @if ($this->profiles->hasMorePages())
                            <button wire:click="nextPage" wire:loading.attr="disabled"
                                class="px-3 py-2 bg-white border-2 border-custom-pink text-custom-pink rounded-lg hover:bg-custom-pink hover:text-white transition-all font-semibold flex items-center gap-1 text-sm shadow-sm">
                                <span class="hidden sm:inline">Next</span>
                                <i class="ph-bold ph-caret-right"></i>
                            </button>
                        @else
                            <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed font-semibold flex items-center gap-1 text-sm">
                                <span class="hidden sm:inline">Next</span>
                                <i class="ph-bold ph-caret-right"></i>
                            </span>
                        @endif
                    </nav>

                    <p class="text-gray-500 text-xs">
                        Showing {{ $this->profiles->firstItem() }}–{{ $this->profiles->lastItem() }} of {{ $this->profiles->total() }} profiles
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
