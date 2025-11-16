<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileImageController extends Controller
{
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $currentUser = auth()->user();

        // Get the image path
        $imagePath = $user->basicInfo?->image ?? 'default.png';

        // Check if user is connected or viewing own profile
        $isConnected = $currentUser && ($currentUser->isConnected($userId) || $currentUser->id == $userId);

        // If image is default.png, serve it directly
        if ($imagePath === 'default.png') {
            return response()->file(public_path('default.png'), [
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        // Get full path
        $fullPath = str_starts_with($imagePath, '/storage/')
            ? public_path($imagePath)
            : storage_path('app/public/' . $imagePath);

        // If file doesn't exist, serve default
        if (!file_exists($fullPath)) {
            return response()->file(public_path('default.png'), [
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        // If connected, serve original image with minimal caching
        if ($isConnected) {
            return response()->file($fullPath, [
                'Cache-Control' => 'public, max-age=300', // 5 minutes cache for faster loads
                'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($fullPath)) . ' GMT',
            ]);
        }

        // If not connected, serve blurred version
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullPath);

            // Apply heavy blur
            $image->blur(90);

            // Return blurred image
            return response($image->encode())
                ->header('Content-Type', 'image/jpeg')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        } catch (\Exception $e) {
            // If image processing fails, return default
            Log::info($e->getMessage());
            return response()->file(public_path('default.png'));
        }
    }
}
