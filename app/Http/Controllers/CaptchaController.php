<?php

namespace App\Http\Controllers;

class CaptchaController extends Controller
{
    public function generate()
    {
        // Get the code from the session (secure)
        $code = session('captcha_code', 'ABCDEF');

        // Image dimensions
        $width = 200;
        $height = 70;

        // Create image
        $image = imagecreate($width, $height);

        // Allocate colors
        $bgColor = imagecolorallocate($image, 226, 49, 131); // custom-pink
        $textColor = imagecolorallocate($image, 255, 255, 255); // white
        $lineColor = imagecolorallocate($image, 73, 11, 34); // custom-red
        $noiseColor = imagecolorallocate($image, 200, 200, 200);

        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Add random lines for noise
        for ($i = 0; $i < 5; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }

        // Add random dots for noise
        for ($i = 0; $i < 100; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $height), $noiseColor);
        }

        // Add text with random positions and angles
        $characters = str_split($code);
        $charWidth = $width / count($characters);

        foreach ($characters as $index => $char) {
            $x = ($index * $charWidth) + rand(5, 15);
            $y = rand(45, 55);
            $angle = rand(-15, 15);
            $fontSize = rand(20, 28);

            // Use imagestring if imagettftext is not available
            if (function_exists('imagettftext')) {
                // Try to use a built-in font - using PHP's default font
                imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $this->getFont(), $char);
            } else {
                // Fallback to imagestring
                $fontId = rand(3, 5); // Use built-in fonts 3, 4, or 5
                imagestring($image, $fontId, $x, $y - 20, $char, $textColor);
            }
        }

        // Output image
        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        imagepng($image);
        imagedestroy($image);
    }

    private function getFont()
    {
        // Try to find a system font
        $possibleFonts = [
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
            'C:\Windows\Fonts\Arial.ttf',
        ];

        foreach ($possibleFonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }

        // If no TrueType font found, return null to use imagestring instead
        return null;
    }
}
