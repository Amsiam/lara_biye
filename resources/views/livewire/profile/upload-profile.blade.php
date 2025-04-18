<?php
use function Livewire\Volt\{state, rules, uses};
use Livewire\WithFileUploads;

uses(WithFileUploads::class);

state([
    'photo' => null,
    'previewUrl' => './assets/UserProfile.png',
    'success' => false,
    'uploading' => false,
    'user',
]);

rules([
    'photo' => 'nullable|image|max:2048', // Max 2MB
]);

$uploadPhoto = function () {
    if ($this->photo) {
        $this->uploading = true;
        $this->validate();

        // Store the file in storage/app/public/photos
        $path = $this->photo->store('photos', 'public');
        $this->previewUrl = asset('/storage//' . $path);

        // Update user's basicInfo with the image path
        auth()
            ->user()
            ->basicInfo()
            ->update(['image' => '/storage//' . $path]);

        // Reset the photo input and set success
        $this->reset('photo');
        $this->success = true;
        $this->uploading = false;

        // Dispatch event for success
        $this->dispatch('photo-uploaded');
    }
};

$updatePreview = function () {
    if ($this->photo) {
        // Update preview with temporary URL
        $this->previewUrl = $this->photo->temporaryUrl();
        $this->success = false;
    }
};
?>

<div>
    <div class="w-32 h-32 mx-auto border border-gray-300 rounded-full overflow-hidden flex items-center justify-center cursor-pointer"
        wire:click="$dispatch('open-file-input')">
        <img class="w-full h-full object-cover" src="{{ $previewUrl }}" alt="Profile Image">
    </div>
    @auth
        @if (auth()->user()?->id == $this->user->id)
            <input type="file" wire:model.live="photo" accept="image/*" class="hidden" x-data x-ref="fileInput"
                x-on:open-file-input.window="$refs.fileInput.click()" @change="$wire.call('updatePreview')">
            @error('photo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            @if ($success)
                <p class="text-green-500 text-sm mt-2">Photo uploaded successfully!</p>
            @endif

            <button wire:click="uploadPhoto"
                class="mt-2 px-2 py-1 hover:bg-custom-pink hover:opacity-50  text-white rounded bg-custom-pink"
                wire:loading.attr="disabled">
                <span wire:loading wire:target="uploadPhoto">Uploading...</span>
                <span wire:loading.remove wire:target="uploadPhoto">Save Photo</span>
            </button>
        @endif
    @endauth
</div>
