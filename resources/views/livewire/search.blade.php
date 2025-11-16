<?php

use Livewire\WithPagination;
use function Livewire\Volt\{state, mount, computed, uses};

uses([WithPagination::class]);

state(['gender', 'age', 'marital_status', 'religion', 'min_height', 'max_height', 'education', 'profession', 'district', 'city', 'min_income', 'max_income', 'body_type', 'complexion', 'showAdvanced' => false]);

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
    $this->resetPage();
};

$goToPage = function ($page) {
    $this->setPage($page);
};

$profiles = computed(function () {
    return \App\Models\User::query()
        ->whereHas('basicInfo', function ($query) {
            $query->when($this->gender, function ($query) {
                $query->where('gender', strtoupper($this->gender));
            });

            $query->when($this->age, function ($query) {
                //get age from birthday
                $ex = explode('-', $this->age);

                if (count($ex) != 2) {
                    return $query;
                }

                $query->whereBetween('dob', [now()->subYears($ex[1] - 1), now()->subYears($ex[0] + 1)]);
            });

            $query->when($this->marital_status, function ($query) {
                $query->where('marital_status', strtoupper($this->marital_status));
            });

            $query->when($this->religion, function ($query) {
                $query->where('religion', 'LIKE', '%' . $this->religion . '%');
            });
        })
        ->when($this->min_height || $this->max_height, function ($query) {
            $query->whereHas('physical_attr', function ($q) {
                $q->when($this->min_height, function ($q) {
                    $q->where('height', '>=', $this->min_height);
                });
                $q->when($this->max_height, function ($q) {
                    $q->where('height', '<=', $this->max_height);
                });
            });
        })
        ->when($this->body_type, function ($query) {
            $query->whereHas('physical_attr', function ($q) {
                $q->where('body_type', 'LIKE', '%' . $this->body_type . '%');
            });
        })
        ->when($this->complexion, function ($query) {
            $query->whereHas('physical_attr', function ($q) {
                $q->where('complexion', 'LIKE', '%' . $this->complexion . '%');
            });
        })
        ->when($this->education, function ($query) {
            $query->whereHas('education', function ($q) {
                $q->where('education', 'LIKE', '%' . $this->education . '%');
            });
        })
        ->when($this->profession, function ($query) {
            $query->whereHas('education', function ($q) {
                $q->where('profession', 'LIKE', '%' . $this->profession . '%');
            });
        })
        ->when($this->min_income || $this->max_income, function ($query) {
            $query->whereHas('education', function ($q) {
                $q->when($this->min_income, function ($q) {
                    $q->where('monthly_income', '>=', $this->min_income);
                });
                $q->when($this->max_income, function ($q) {
                    $q->where('monthly_income', '<=', $this->max_income);
                });
            });
        })
        ->when($this->district, function ($query) {
            $query->whereHas('location', function ($q) {
                $q->where('district', 'LIKE', '%' . $this->district . '%');
            });
        })
        ->when($this->city, function ($query) {
            $query->whereHas('location', function ($q) {
                $q->where('city', 'LIKE', '%' . $this->city . '%');
            });
        })
        ->paginate(3);
});

?>


<div class="max-w-6xl mx-auto mt-20">
    <!-- Search Filters -->
    <div class="container mx-auto px-6 py-10">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-xl">
            <h2 class="text-xl font-bold text-custom-red text-center mb-4">
                Refine Your Search
            </h2>
            <form method="GET" action="">
                <!-- Basic Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <select wire:model.live="gender" name="gender"
                        class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                        <option value="">I'm looking for</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                    <select wire:model.live="marital_status" name="marital_status"
                        class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                        <option value="">Marital Status</option>
                        @foreach (['UNMARRIED', 'MARRIED', 'DIVORCED', 'WIDOWED'] as $maritalStatus)
                            <option>{{ $maritalStatus }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="age" name="age"
                        class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                        <option value="">Select Age</option>
                        <option>18-25</option>
                        <option>26-35</option>
                        <option>36-45</option>
                        <option>46-55</option>
                        <option>56-65</option>
                    </select>
                    <select wire:model.live="religion" name="religion"
                        class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                        <option value="">Religion</option>
                        <option>Islam</option>
                        <option>Hinduism</option>
                        <option>Buddhism</option>
                        <option>Christianity</option>
                        <option>Other</option>
                    </select>
                </div>

                <!-- Advanced Filters Toggle -->
                <div class="mt-6 text-center">
                    <button type="button" wire:click="$toggle('showAdvanced')"
                        class="text-custom-pink font-semibold hover:text-custom-red transition-colors flex items-center justify-center mx-auto gap-2">
                        <svg class="w-5 h-5 transform transition-transform duration-200"
                            :class="{ 'rotate-180': @js($showAdvanced) }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        {{ $showAdvanced ? 'Hide Advanced Filters' : 'Show Advanced Filters' }}
                    </button>
                </div>

                <!-- Advanced Filters Section -->
                @if ($showAdvanced)
                    <div class="mt-6 pt-6 border-t-2 border-gray-200 animate-fadeIn">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Advanced Filters</h3>

                        <!-- Physical Attributes -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Physical
                                Attributes</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <input type="number" wire:model.live="min_height" name="min_height"
                                    placeholder="Min Height (cm)"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                                <input type="number" wire:model.live="max_height" name="max_height"
                                    placeholder="Max Height (cm)"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                                <select wire:model.live="body_type" name="body_type"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                                    <option value="">Body Type</option>
                                    <option>Slim</option>
                                    <option>Athletic</option>
                                    <option>Average</option>
                                    <option>Heavy</option>
                                </select>
                                <select wire:model.live="complexion" name="complexion"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                                    <option value="">Complexion</option>
                                    <option>Fair</option>
                                    <option>Wheatish</option>
                                    <option>Dark</option>
                                </select>
                            </div>
                        </div>

                        <!-- Education & Career -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Education &
                                Career</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <input type="text" wire:model.live="education" name="education"
                                    placeholder="Education (e.g., Bachelor's)"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                                <input type="text" wire:model.live="profession" name="profession"
                                    placeholder="Profession (e.g., Engineer)"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                                <input type="number" wire:model.live="min_income" name="min_income"
                                    placeholder="Min Monthly Income"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                                <input type="number" wire:model.live="max_income" name="max_income"
                                    placeholder="Max Monthly Income"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Location</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" wire:model.live="district" name="district"
                                    placeholder="District (e.g., Dhaka)"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                                <input type="text" wire:model.live="city" name="city"
                                    placeholder="City (e.g., Mirpur)"
                                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Search & Clear Buttons -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="button" wire:click="clearFilters"
                        class="w-full bg-gray-500 text-white p-3 rounded-lg hover:bg-gray-600 transition-all font-semibold text-lg shadow-lg hover:shadow-xl">
                        <i class="fas fa-times-circle mr-2"></i> Clear Filters
                    </button>
                    <button type="submit"
                        class="w-full bg-custom-pink text-white p-3 rounded-lg hover:bg-opacity-90 transition-all font-semibold text-lg shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i> Search Profiles
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Results -->
    <div class="container mx-auto px-6 pb-10">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold">Matched Profiles</h2>
            <div class="bg-custom-pink text-white px-4 py-2 rounded-lg font-semibold">
                <i class="fas fa-users mr-2"></i>{{ $this->profiles->total() }} Profile(s) Found
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->

            @forelse ($this->profiles as $profile)
                <x-single-profile :profile="$profile" />
            @empty
                <div class="col-span-3 text-center">
                    <p class="text-gray-500">No profiles found.</p>
                </div>
            @endforelse



        </div>

        <!-- Pagination Controls -->
        @if ($this->profiles->hasPages())
            <div class="mt-12 flex justify-center">
                <nav class="flex items-center gap-2" role="navigation" aria-label="Pagination Navigation">
                    {{-- Previous Button --}}
                    @if ($this->profiles->onFirstPage())
                        <span
                            class="px-4 py-3 bg-gray-200 text-gray-400 rounded-lg cursor-not-allowed font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </span>
                    @else
                        <button wire:click="previousPage" wire:loading.attr="disabled"
                            class="px-4 py-3 bg-white border-2 border-custom-pink text-custom-pink rounded-lg hover:bg-custom-pink hover:text-white transition-all duration-300 font-semibold flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>
                    @endif

                    {{-- Page Numbers --}}
                    <div class="flex items-center gap-2">
                        @foreach ($this->profiles->getUrlRange(1, $this->profiles->lastPage()) as $page => $url)
                            @if ($page == $this->profiles->currentPage())
                                <span
                                    class="px-5 py-3 bg-custom-pink text-white rounded-lg font-bold shadow-lg transform scale-110 border-2 border-custom-pink">
                                    {{ $page }}
                                </span>
                            @else
                                <button wire:click="goToPage({{ $page }})"
                                    class="px-5 py-3 bg-white text-gray-700 rounded-lg hover:bg-custom-pink hover:text-white transition-all duration-300 font-semibold border-2 border-gray-200 hover:border-custom-pink shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    </div>

                    {{-- Next Button --}}
                    @if ($this->profiles->hasMorePages())
                        <button wire:click="nextPage" wire:loading.attr="disabled"
                            class="px-4 py-3 bg-white border-2 border-custom-pink text-custom-pink rounded-lg hover:bg-custom-pink hover:text-white transition-all duration-300 font-semibold flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            Next
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @else
                        <span
                            class="px-4 py-3 bg-gray-200 text-gray-400 rounded-lg cursor-not-allowed font-semibold flex items-center gap-2">
                            Next
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </nav>
            </div>

            {{-- Page Info --}}
            <div class="mt-4 text-center text-gray-600 text-sm">
                Showing <span class="font-semibold text-custom-pink">{{ $this->profiles->firstItem() }}</span> to
                <span class="font-semibold text-custom-pink">{{ $this->profiles->lastItem() }}</span> of
                <span class="font-semibold text-custom-pink">{{ $this->profiles->total() }}</span> profiles
            </div>
        @endif

    </div>
</div>
