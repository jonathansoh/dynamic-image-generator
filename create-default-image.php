<?php
/**
 * Create a default placeholder image
 * Run this script: php create-default-image.php
 */

$width = 800;
$height = 400;

// Create image
$image = imagecreatetruecolor($width, $height);

// Gradient background (blue to dark blue)
for ($y = 0; $y < $height; $y++) {
    $r = 30 + ($y / $height) * 20;
    $g = 60 + ($y / $height) * 40;
    $b = 120 + ($y / $height) * 60;
    $color = imagecolorallocate($image, $r, $g, $b);
    imageline($image, 0, $y, $width, $y, $color);
}

// Add decorative border
$borderColor = imagecolorallocate($image, 255, 255, 255);
imagerectangle($image, 10, 10, $width - 10, $height - 10, $borderColor);

// Save as default.jpg
$savePath = __DIR__ . '/images/default.jpg';
imagejpeg($image, $savePath, 90);

imagedestroy($image);

echo "Default image created at: $savePath\n";
