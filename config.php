<?php
/**
 * Configuration for Dynamic Image Generator
 */

// Image settings
return [
    // Path to images folder (relative to index.php)
    'images_dir' => __DIR__ . '/images/',
    
    // Default image ID if none specified
    'default_id' => 1,
    
    // Text settings
    'font_size' => 40,          // Text size (for built-in fonts: 1-5)
    'font_color' => '#FFFFFF',  // Text color (hex)
    'shadow_color' => '#000000', // Text shadow color (hex)
    
    // Built-in GD font (1-5), or path to TTF file
    'font' => 5,
    
    // Padding from image edges (pixels)
    'padding' => 20,
    
    // Image quality (0-100, for JPEG output)
    'jpeg_quality' => 90,
    
    // Text position (percentage of image width/height)
    // 0.5 = centered, 0.1 = top/left, 0.9 = bottom/right
    'position_x' => 0.5,
    'position_y' => 0.5,
];
