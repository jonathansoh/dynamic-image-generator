<?php
/**
 * Image Upload Handler
 * Handles uploading images to the images directory
 */

require_once 'auth.php';

header('Content-Type: application/json');

$imagesDir = __DIR__ . '/images/';
$method = $_SERVER['REQUEST_METHOD'];

// Ensure images directory exists
if (!file_exists($imagesDir)) {
    mkdir($imagesDir, 0755, true);
}

try {
    if ($method === 'POST' && isset($_FILES['image'])) {
        $file = $_FILES['image'];

        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error: ' . $file['error']);
        }

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
        }

        // Get file extension
        $extension = '';
        switch ($mime) {
            case 'image/jpeg':
                $extension = '.jpg';
                break;
            case 'image/png':
                $extension = '.png';
                break;
            case 'image/gif':
                $extension = '.gif';
                break;
        }

        // Find next available image ID (check all extensions)
        $imageId = 1;
        $extensions = ['.jpg', '.jpeg', '.png', '.gif'];
        while (true) {
            $exists = false;
            foreach ($extensions as $ext) {
                if (file_exists($imagesDir . $imageId . $ext)) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                break;
            }
            $imageId++;
        }

        // Move uploaded file
        $destination = $imagesDir . $imageId . $extension;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Failed to move uploaded file');
        }

        // Optimize image if it's a JPEG
        if ($extension === '.jpg') {
            $image = imagecreatefromjpeg($destination);
            if ($image) {
                // Resize if too large (max 1920px width)
                $width = imagesx($image);
                $height = imagesy($image);

                if ($width > 1920) {
                    $newWidth = 1920;
                    $newHeight = ($height / $width) * $newWidth;
                    $newImage = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagejpeg($newImage, $destination, 85);
                    imagedestroy($newImage);
                } else {
                    imagejpeg($image, $destination, 85);
                }
                imagedestroy($image);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'imageId' => $imageId,
            'filename' => $imageId . $extension,
            'path' => $destination
        ]);
    } else {
        throw new Exception('Invalid request');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => False,
        'error' => $e->getMessage()
    ]);
}
