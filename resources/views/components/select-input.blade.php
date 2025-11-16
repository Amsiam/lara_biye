@props([
    'icon' => null,
    'placeholder' => 'Select an option',
    'options' => [],
    'wireModel' => null,
    'name' => null,
])

<div class="relative">
    @if ($icon)
        <div class="absolute top-1/2 -translate-y-1/2 left-0 pl-3 flex items-center pointer-events-none">
            {!! $icon !!}
        </div>
    @endif

    <select
        @if($wireModel) wire:model.live="{{ $wireModel }}" @endif
        @if($name) name="{{ $name }}" @endif
        {{ $attributes->merge(['class' => 'w-full ' . ($icon ? 'pl-14' : 'pl-4') . ' pr-10 py-3 border-2 border-gray-300 rounded-lg text-gray-700 bg-white focus:ring-2 focus:ring-custom-pink focus:border-custom-pink transition-all duration-200 hover:border-custom-pink cursor-pointer appearance-none font-medium shadow-sm hover:shadow-md']) }}
    >
        <option value="" class="text-gray-400">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" class="text-gray-700">{{ $label }}</option>
        @endforeach
        {{ $slot }}
    </select>

    <div class="absolute top-1/2 -translate-y-1/2 right-0 pr-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>
</div>
