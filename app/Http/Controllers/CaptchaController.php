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

            // Check if TrueType font is available
            $font = $this->getFont();

            if (function_exists('imagettftext') && $font !== null) {
                // Use TrueType font if available
                imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $font, $char);
            } else {
                // Fallback to built-in bitmap font
                $fontId = 5; // Use largest built-in font (5)
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
        // Try to find a system font (common paths on various servers)
        $possibleFonts = [
            // Linux/cPanel common paths
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
            '/usr/share/fonts/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/liberation/LiberationSans-Regular.ttf',
            '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf',
            '/usr/share/fonts/truetype/freefont/FreeSans.ttf',
            // Alternative Linux paths
            '/usr/share/fonts/TTF/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/TTF/DejaVuSans.ttf',
            // macOS paths
            '/System/Library/Fonts/Helvetica.ttc',
            '/Library/Fonts/Arial.ttf',
            // Windows paths
            'C:\Windows\Fonts\Arial.ttf',
            'C:\Windows\Fonts\arialbd.ttf',
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
