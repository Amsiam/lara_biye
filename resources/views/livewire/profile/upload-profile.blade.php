<?php
use function Livewire\Volt\{state, rules, uses};
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

uses(WithFileUploads::class);

state([
    'photo' => null,
    'previewUrl' => 'default.png',
    'success' => false,
    'uploading' => false,
    'user',
    'errorMessage' => null,
    'imageVersion' => 0,
]);

rules([
    'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120|dimensions:min_width=200,min_height=200', // Max 5MB, min 200x200
]);

$deleteOldImage = function ($imagePath) {
    if (!$imagePath || $imagePath === 'default.png') {
        return;
    }

    // Convert path to absolute path
    $fullPath = str_starts_with($imagePath, '/storage/') ? public_path($imagePath) : storage_path('app/public/' . $imagePath);

    // Delete file if it exists
    if (file_exists($fullPath)) {
        @unlink($fullPath);
    }
};

$uploadPhoto = function () {
    if (!$this->photo) {
        $this->errorMessage = 'Please select a photo first.';
        return;
    }

    $this->uploading = true;
    $this->errorMessage = null;
    $this->success = false;

    try {
        $this->validate();

        // Delete old image if exists
        $oldImage = auth()->user()->basicInfo?->image;
        if ($oldImage && $oldImage !== 'default.png') {
            $this->deleteOldImage($oldImage);
        }

        // Process and optimize the image
        $manager = new ImageManager(new Driver());
        $image = $manager->read($this->photo->getRealPath());

        // Resize if too large (max 800x800 maintaining aspect ratio)
        if ($image->width() > 800 || $image->height() > 800) {
            $image->scale(width: 800, height: 800);
        }

        // Save optimized image
        $filename = 'profile_' . auth()->id() . '_' . time() . '.jpg';
        $path = storage_path('app/public/photos/' . $filename);

        // Ensure directory exists
        if (!file_exists(storage_path('app/public/photos'))) {
            mkdir(storage_path('app/public/photos'), 0755, true);
        }

        $image->save($path, quality: 85);

        // Update user's basicInfo with the image path
        auth()
            ->user()
            ->basicInfo()
            ->update(['image' => '/storage/photos/' . $filename]);

        // Reload user data to get fresh image
        $this->user = auth()
            ->user()
            ->fresh(['basicInfo']);

        // Reset and show success
        $this->reset(['photo', 'previewUrl']);
        $this->success = true;
        $this->uploading = false;
        $this->imageVersion++;

        // Dispatch event with timestamp for cache busting
        $this->dispatch('photo-uploaded', timestamp: time());

        // Reload page to show new image
        $this->dispatch('refresh-page');
    } catch (\Illuminate\Validation\ValidationException $e) {
        $this->errorMessage = $e->validator->errors()->first('photo');
        $this->uploading = false;
    } catch (\Exception $e) {
        $this->errorMessage = 'Failed to upload image: ' . $e->getMessage();
        $this->uploading = false;
    }
};

$updatedPhoto = function () {
    if ($this->photo) {
        try {
            $this->previewUrl = $this->photo->temporaryUrl();
            $this->success = false;
            $this->errorMessage = null;
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to load preview.';
            $this->photo = null;
        }
    }
};

$removePhoto = function () {
    // Delete current image from storage
    $currentImage = auth()->user()->basicInfo?->image;
    if ($currentImage && $currentImage !== 'default.png') {
        $this->deleteOldImage($currentImage);
    }

    // Set to default image
    auth()
        ->user()
        ->basicInfo()
        ->update(['image' => 'default.png']);

    // Reload user data
    $this->user = auth()
        ->user()
        ->fresh(['basicInfo']);

    $this->reset(['photo', 'previewUrl', 'success', 'errorMessage']);
    $this->imageVersion++;

    // Dispatch event with timestamp for cache busting
    $this->dispatch('photo-uploaded', timestamp: time());

    // Reload page to show default image
    $this->dispatch('refresh-page');
};
?>

<div class="space-y-3">
    <!-- Profile Image Preview -->
    <div class="relative inline-block group">
        <div
            class="w-32 h-32 mx-auto border-4 border-custom-pink rounded-full overflow-hidden flex items-center justify-center bg-gray-50 shadow-lg">
            @if ($photo)
                <img class="w-full h-full object-cover" src="{{ $previewUrl }}" alt="Profile Preview" key="preview">
            @else
                <img class="w-full h-full object-cover profile-image"
                    src="{{ route('profile.image', $user->id) }}?v={{ $imageVersion }}" alt="Profile Image"
                    onerror="this.src='{{ asset('default.png') }}'" key="current">
            @endif

            @if ($uploading)
                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="text-white text-center">
                        <i class="ph-bold ph-circle-notch animate-spin text-2xl block mx-auto mb-1"></i>
                        <p class="text-xs font-semibold">Uploading...</p>
                    </div>
                </div>
            @endif
        </div>

        @auth
            @if (auth()->user()?->id == $this->user->id)
                <!-- Camera Icon Overlay -->
                <div class="absolute bottom-0 right-1/2 transform translate-x-16 bg-custom-pink text-white rounded-full p-2 shadow-lg cursor-pointer hover:bg-opacity-90 transition-all duration-200 hover:scale-110"
                    wire:click="$dispatch('open-file-input')" title="Change Photo">
                    <i class="ph-bold ph-camera text-sm"></i>
                </div>
            @endif
        @endauth
    </div>

    @auth
        @if (auth()->user()?->id == $this->user->id)
            <!-- Hidden File Input -->
            <input type="file" wire:model="photo" accept="image/jpeg,image/png,image/jpg" class="hidden" x-data
                x-ref="fileInput" x-on:open-file-input.window="$refs.fileInput.click()"
                id="photo-input-{{ $user->id }}">

            <!-- Upload Controls -->
            @if ($photo)
                <div class="flex flex-col gap-2 mt-3" style="position: relative; z-index: 10;">
                    <button wire:click="uploadPhoto" type="button"
                        class="w-full px-4 py-2 bg-custom-pink text-white rounded-md hover:bg-pink-600 font-semibold text-sm cursor-pointer"
                        style="pointer-events: auto;" wire:loading.attr="disabled" wire:target="photo">
                        <span wire:loading.remove wire:target="uploadPhoto" class="flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i>
                            Save Photo
                        </span>
                        <span wire:loading wire:target="uploadPhoto" class="inline-flex items-center">
                            <i class="ph-bold ph-circle-notch animate-spin -ml-1 mr-2"></i>
                            Saving...
                        </span>
                    </button>

                    <button wire:click="$set('photo', null)" type="button"
                        class="w-full px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 font-semibold text-sm cursor-pointer"
                        style="pointer-events: auto;">
                        ✕ Cancel
                    </button>
                </div>
            @else
                <div class="mt-2 text-xs text-white text-center">
                    Click the camera icon to select a photo
                </div>
            @endif

            <!-- Remove Photo Button (only if not default) -->
            @if (!$photo && auth()->user()->basicInfo?->image && auth()->user()->basicInfo->image !== 'default.png')
                <div class="mt-2">
                    <button wire:click="removePhoto" type="button"
                        wire:confirm="Are you sure you want to remove your profile photo?"
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 font-medium text-sm transition-all duration-200">
                        <i class="ph-bold ph-trash mr-1"></i> Remove Photo
                    </button>
                </div>
            @endif

            <!-- Success Message -->
            @if ($success)
                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded-md text-sm flex items-center gap-2">
                    <i class="ph-bold ph-check"></i>
                    Photo uploaded successfully!
                </div>
            @endif

            <!-- Error Message -->
            @if ($errorMessage)
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-md text-sm">
                    ✕ {{ $errorMessage }}
                </div>
            @endif

            <!-- Upload Guidelines -->
            @if (!$photo)
                <div class="bg-blue-50 border border-blue-200 rounded-md p-2 mt-2">
                    <p class="text-xs font-semibold text-blue-900 mb-1">Image Guidelines:</p>
                    <ul class="text-xs text-blue-800 space-y-0.5 ml-3">
                        <li>• JPEG, PNG, JPG only</li>
                        <li>• Max size: 5 MB</li>
                        <li>• Min: 200x200 pixels</li>
                        <li>• Auto-optimized</li>
                    </ul>
                </div>
            @endif
        @endif
    @endauth
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('photo-uploaded', (event) => {
            // Force all profile images to refresh with cache busting
            const timestamp = event.timestamp || new Date().getTime();
            const images = document.querySelectorAll('img[src*="profile.image"], img.profile-image');

            images.forEach(img => {
                const currentSrc = img.src;
                const baseSrc = currentSrc.split('?')[0];
                img.src = baseSrc + '?v=' + timestamp;
            });
        });

        Livewire.on('refresh-page', () => {
            // Wait a moment for the upload to complete, then reload
            setTimeout(() => {
                window.location.reload();
            }, 500);
        });
    });
</script>
