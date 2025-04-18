<?php

use function Livewire\Volt\{state, mount, computed};

state(['gender', 'age', 'marital_status']);

mount(function () {
    $this->gender = request()->get('gender');
    $this->age = request()->get('age');
    $this->marital_status = request()->get('marital_status');
});

$profiles = computed(function () {
    return \App\Models\User::query()
        ->whereHas('basicInfo', function ($query) {
            $query->when($this->gender, function ($query) {
                $query->where('gender', strtoupper($this->gender));
            });

            $query->when($this->age, function ($query) {
                //get age from birthday
                $ex = explode('-', $this->age);

                $query->whereBetween('dob', [now()->subYears($ex[1] - 1), now()->subYears($ex[0] + 1)]);
            });

            $query->when($this->marital_status, function ($query) {
                $query->where('marital_status', strtoupper($this->marital_status));
            });
        })
        ->get();
});

?>


<div class="max-w-6xl mx-auto mt-20">
    <!-- Search Filters -->
    <div class="container mx-auto px-6 py-10">
        <div
            class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-xl transform transition-smooth duration-300 hover:scale-105">
            <h2 class="text-xl font-bold text-custom-red text-center mb-4">
                Refine Your Search
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <select wire:model.live="gender"
                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                    <option>I'm looking for</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
                <select wire:model.live="marital_status"
                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                    <option>Marital Status</option>
                    @foreach (['UNMARRIED', 'MARRIED', 'DIVORCED', 'WIDOWED'] as $maritalStatus)
                        <option>{{ $maritalStatus }}</option>
                    @endforeach
                </select>
                <select wire:model.live="age"
                    class="w-full p-3 border-2 border-gray-300 rounded-lg text-black focus:ring-2 focus:ring-custom-pink">
                    <option>Select Age</option>
                    <option>18-25</option>
                    <option>26-35</option>
                    <option>36-45</option>
                </select>
                <button class="w-full bg-custom-pink text-white  p-3 rounded-lg hover:bg-opacity-90 transition-all">
                    Search <i class="fas fa-search ml-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="container mx-auto px-6 pb-10">
        <h2 class="text-xl font-bold text-center mb-8">Matched Profiles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->

            @foreach ($this->profiles as $profile)
                <div
                    class="bg-white p-5 rounded-lg shadow-lg text-black hover:shadow-2xl  hover:bg-gray-100 transform transition-all duration-300 hover:scale-105 flex flex-col items-center text-center">
                    <img src="./assets/user1.jpg" class="w-full h-60 object-contain rounded-lg" alt="Profile Picture" />
                    <h3 class="text-lg font-bold mt-3 text-center">{{ $profile->name }}</h3>
                    <p class="text-gray-700 flex items-center gap-1">
                        👤 Age: 28 | 🕌 Religion: {{ $profile?->basicInfo?->religion }}
                    </p>
                    <button class="mt-3 p-2 w-full bg-custom-pink text-white font-bold rounded hover:bg-opacity-90">
                        View Profile
                    </button>
                </div>
            @endforeach



        </div>

        <!-- Pagination Controls -->
        <div class="flex justify-center mt-8 space-x-2 ">
            <button id="prev-btn"
                class="px-4 py-2 border border-custom-pink text-white font-bold rounded hover:bg-opacity-90 hover:bg-white hover:text-custom-pink">
                <i class="fas fa-arrow-left mr-2"></i> Previous
            </button>
            <span id="page-indicator" class="px-4 py-2 bg-white text-custom-red font-bold rounded">1</span>
            <button id="next-btn"
                class="px-4 py-2 border border-custom-pink text-white font-bold rounded hover:bg-opacity-90 hover:bg-white hover:text-custom-pink">
                Next<i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>

    </div>
</div>
