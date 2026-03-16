<?php
/**
 * Auto-download Free Font Script
 * Downloads Open Sans font (GPL licensed) for use with GD library
 *
 * Usage: Open setup-fonts.php in your browser
 */

$fontsDir = __DIR__ . '/fonts/';

// Ensure fonts directory exists
if (!file_exists($fontsDir)) {
    mkdir($fontsDir, 0755, true);
}

// Open Sans font URLs (from GitHub - free and GPL licensed)
$fonts = [
    'opensans.ttf' => 'https://github.com/googlefonts/noto-fonts/raw/main/hinted/ttf/NotoSans-Regular.ttf',
    'opensansbd.ttf' => 'https://github.com/googlefonts/noto-fonts/raw/main/hinted/ttf/NotoSans-Bold.ttf',
    'arial.ttf' => 'https://github.com/googlefonts/roboto/raw/main/src/hinted/Roboto-Regular.ttf',
];

echo "<h2>🔤 Font Auto-Download Setup</h2>";
echo "<pre>";

foreach ($fonts as $filename => $url) {
    $destination = $fontsDir . $filename;

    if (file_exists($destination)) {
        echo "✅ Already exists: $filename\n";
        continue;
    }

    echo "⬇️  Downloading: $filename ... ";

    $ctx = stream_context_create([
        'http' => [
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0'
        ]
    ]);

    $fontData = @file_get_contents($url, false, $ctx);

    if ($fontData === false) {
        echo "❌ Failed\n";
        echo "   URL: $url\n";
        continue;
    }

    if (file_put_contents($destination, $fontData)) {
        echo "✅ Success\n";
    } else {
        echo "❌ Could not save to: $destination\n";
    }
}

echo "\n📁 Fonts directory contents:\n";
$files = glob($fontsDir . '*.ttf');
if (empty($files)) {
    echo "   No TTF fonts found.\n";
} else {
    foreach ($files as $file) {
        $size = filesize($file);
        echo "   ✅ " . basename($file) . " ($size bytes)\n";
    }
}

echo "\n✨ Setup complete!";
echo "\n\n<a href='admin.html'>Go to Admin Panel</a>";
echo "</pre>";
echo "<style>body{font-family:sans-serif;max-width:600px;margin:50px auto;padding:20px;background:#f5f5f5;}pre{background:#fff;padding:20px;border-radius:8px;}</style>";
