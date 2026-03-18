<?php
/**
 * Dynamic Image Generator
 * Adds personalized text overlay to images using saved configurations
 *
 * Usage: index.php?id={image_id}&t={text}
 * Example: index.php?id=1&t=Hello+World
 */

// Configuration
$imagesDir = __DIR__ . '/images/';
$configFile = __DIR__ . '/configs.json';
$fontsDir = __DIR__ . '/fonts/';

// Default configuration
$defaultConfig = [
    'fontFamily' => 'Arial',
    'fontSize' => 40,
    'fontWeight' => 'normal',
    'fontColor' => '#FFFFFF',
    'shadowColor' => '#000000',
    'posXRel' => 50, // Relative position (percentage)
    'posYRel' => 50,
];

// Get parameters
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$text = isset($_GET['t']) ? urldecode($_GET['t']) : '';

// Validate ID
if ($id < 1) {
    $id = 1;
}

// Load configuration for this image
$config = $defaultConfig;
if (file_exists($configFile)) {
    $allConfigs = json_decode(file_get_contents($configFile), true);
    if (isset($allConfigs[$id])) {
        $config = array_merge($defaultConfig, $allConfigs[$id]);
    }
}

// Construct image path
$imagePath = $imagesDir . $id . '.jpg';
if (!file_exists($imagePath)) {
    $imagePath = $imagesDir . $id . '.png';
}
if (!file_exists($imagePath)) {
    $imagePath = $imagesDir . 'default.jpg';
}

// If no image found, create a simple placeholder
if (!file_exists($imagePath)) {
    createPlaceholderImage($config, $text);
    exit;
}

// Load image
$imageInfo = getimagesize($imagePath);
if (!$imageInfo) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('Unable to load image');
}

$imageType = $imageInfo[2];
$image = null;

switch ($imageType) {
    case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($imagePath);
        break;
    case IMAGETYPE_PNG:
        $image = imagecreatefrompng($imagePath);
        imagesavealpha($image, true);
        break;
    case IMAGETYPE_GIF:
        $image = imagecreatefromgif($imagePath);
        break;
    default:
        header('HTTP/1.1 415 Unsupported Media Type');
        exit('Unsupported image type');
}

if (!$image) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('Unable to create image resource');
}

// Get image dimensions
$imageWidth = imagesx($image);
$imageHeight = imagesy($image);

// Scale font size based on actual image vs canvas dimensions
// If canvas was 800px but actual image is 1920px, scale font by 2.4x
$canvasWidth = $config['canvasWidth'] ?? 800;
$actualImageWidth = $config['originalImageWidth'] ?? $imageWidth;
$fontScale = ($imageWidth / $canvasWidth) * 0.75;  // Scale down 25% for better match
$scaledFontSize = (int)($config['fontSize'] * $fontScale);

// Calculate text position from relative percentage
$textX = ($config['posXRel'] / 100) * $imageWidth;
$textY = ($config['posYRel'] / 100) * $imageHeight;

// Parse colors
list($fr, $fg, $fb) = sscanf($config['fontColor'], '#%02x%02x%02x');
list($sr, $sg, $sb) = sscanf($config['shadowColor'], '#%02x%02x%02x');
$fontColor = imagecolorallocate($image, $fr, $fg, $fb);
$shadowColor = imagecolorallocate($image, $sr, $sg, $sb);

// Add text if provided
if ($text) {
    // Map font family names to actual font files
    $fontMap = [
        'opensans' => 'OpenSans.ttf',
        'roboto' => 'Roboto.ttf',
        'caveat' => 'Caveat.ttf',
        'gloriahallelujah' => 'GloriaHallelujah.ttf',
    ];

    $fontKey = strtolower(str_replace(' ', '', $config['fontFamily']));
    $fontFile = $fontMap[$fontKey] ?? 'OpenSans.ttf';
    $fontPath = $fontsDir . $fontFile;

    if (file_exists($fontPath)) {
        // Use TrueType font
        $angle = 0;

        // Convert pixels to points for GD library
        $fontSizeInPoints = $scaledFontSize * 0.9;
        $bbox = imagettfbbox($fontSizeInPoints, $angle, $fontPath, $text);

        // Calculate text dimensions for centering (matching canvas textAlign='center' and textBaseline='middle')
        $textWidth = $bbox[2] - $bbox[0];

        // Center horizontally: position is at center, so subtract half width
        // The bbox[0] is the left edge offset from the baseline origin
        $x = $textX - $textWidth / 2 - $bbox[0];

        // Center vertically: use baseline + half font size - offset
        $y = $textY + $scaledFontSize / 2 - 18;

        // Add shadow
        imagettftext($image, $fontSizeInPoints, $angle, $x + 2, $y + 2, $shadowColor, $fontPath, $text);

        // Add main text
        imagettftext($image, $fontSizeInPoints, $angle, $x, $y, $fontColor, $fontPath, $text);
    } else {
        // Font not found - show error in image
        $errorColor = imagecolorallocate($image, 255, 0, 0);
        imagestring($image, 5, 10, 10, "Font not found: $fontFile", $errorColor);
    }
}

// Set cache headers
header('Content-Type: image/jpeg');
header('Cache-Control: public, max-age=3600'); // Cache for 1 hour
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

imagejpeg($image, null, 90);
imagedestroy($image);

/**
 * Create a placeholder image when no image file exists
 */
function createPlaceholderImage($config, $text) {
    $width = 800;
    $height = 400;

    $image = imagecreatetruecolor($width, $height);

    // Create gradient background
    for ($y = 0; $y < $height; $y++) {
        $r = 102 + ($y / $height) * 20;
        $g = 126 + ($y / $height) * 40;
        $b = 234 + ($y / $height) * 60;
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $y, $width, $y, $color);
    }

    // Add decorative border
    $borderColor = imagecolorallocate($image, 255, 255, 255);
    imagerectangle($image, 10, 10, $width - 10, $height - 10, $borderColor);

    // Calculate text position
    $textX = ($config['posXRel'] / 100) * $width;
    $textY = ($config['posYRel'] / 100) * $height;

    // Scale font size (placeholder is 800px wide)
    $canvasWidth = $config['canvasWidth'] ?? 800;
    $fontScale = ($width / $canvasWidth) * 0.75;  // Scale down 25% for better match
    $scaledFontSize = (int)($config['fontSize'] * $fontScale);

    // Parse colors
    list($fr, $fg, $fb) = sscanf($config['fontColor'], '#%02x%02x%02x');
    list($sr, $sg, $sb) = sscanf($config['shadowColor'], '#%02x%02x%02x');
    $fontColor = imagecolorallocate($image, $fr, $fg, $fb);
    $shadowColor = imagecolorallocate($image, $sr, $sg, $sb);

    // Add text
    if ($text) {
        $fontsDir = __DIR__ . '/fonts/';
        $fontMap = [
            'opensans' => 'OpenSans.ttf',
            'roboto' => 'Roboto.ttf',
            'caveat' => 'Caveat.ttf',
            'gloriahallelujah' => 'GloriaHallelujah.ttf',
        ];

        $fontKey = strtolower(str_replace(' ', '', $config['fontFamily']));
        $fontFile = $fontMap[$fontKey] ?? 'OpenSans.ttf';
        $fontPath = $fontsDir . $fontFile;

        if (file_exists($fontPath)) {
            $angle = 0;

            // Convert pixels to points for GD library
            $fontSizeInPoints = $scaledFontSize * 0.9;
            $bbox = imagettfbbox($fontSizeInPoints, $angle, $fontPath, $text);

            // Calculate text dimensions for centering (matching canvas textAlign='center' and textBaseline='middle')
            $textWidth = $bbox[2] - $bbox[0];
            $x = $textX - $textWidth / 2 - $bbox[0];

            // Center vertically: use baseline + half font size - offset
            $y = $textY + $scaledFontSize / 2 - 18;

            imagettftext($image, $fontSizeInPoints, $angle, $x + 2, $y + 2, $shadowColor, $fontPath, $text);
            imagettftext($image, $fontSizeInPoints, $angle, $x, $y, $fontColor, $fontPath, $text);
        } else {
            // Fallback to built-in font if TTF not found
            $fontVariant = 5;
            $x = $textX - (strlen($text) * imagefontwidth($fontVariant)) / 2;
            $y = $textY - imagefontheight($fontVariant) / 2;
            imagestring($image, $fontVariant, $x + 2, $y + 2, $text, $shadowColor);
            imagestring($image, $fontVariant, $x, $y, $text, $fontColor);
        }
    }

    // Output image
    header('Content-Type: image/jpeg');
    header('Cache-Control: public, max-age=3600');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

    imagejpeg($image, null, 90);
    imagedestroy($image);
}
