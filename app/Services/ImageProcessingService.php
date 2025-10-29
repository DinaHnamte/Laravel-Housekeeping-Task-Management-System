<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageProcessingService
{
    /**
     * Process and store image with timestamp overlay (simplified version using GD)
     */
    public function processAndStore(UploadedFile $file, string $folder = 'checklist-images', ?string $customTimestamp = null): string
    {
        $timestamp = $customTimestamp ?? now()->format('Y-m-d H:i:s');

        // Load image using GD
        $imagePath = $file->getRealPath();
        $imageInfo = getimagesize($imagePath);

        if ($imageInfo === false) {
            throw new \Exception('Invalid image file');
        }

        $mimeType = $imageInfo['mime'];

        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        if ($image === false) {
            throw new \Exception('Failed to load image');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Create semi-transparent overlay box
        $overlayHeight = 50;
        $overlayY = $height - $overlayHeight;
        $overlay = imagecreatetruecolor($width, $overlayHeight);
        $black = imagecolorallocatealpha($overlay, 0, 0, 0, 70); // 70/127 = ~55% opacity
        imagefilledrectangle($overlay, 0, 0, $width, $overlayHeight, $black);
        imagecopymerge($image, $overlay, 0, $overlayY, 0, 0, $width, $overlayHeight, 55);

        // Add timestamp text
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $fontSize = 5; // GD built-in font (1-5)
        $textX = max(10, $width - strlen($timestamp) * 10 - 15);
        $textY = $height - 20;
        imagestring($image, $fontSize, $textX, $textY, $timestamp, $textColor);

        // Generate filename and save
        $filename = uniqid('photo_') . '_' . time() . '.jpg';
        $fullPath = $folder . '/' . $filename;
        $savePath = storage_path('app/public/' . $fullPath);

        // Ensure directory exists
        $directory = dirname($savePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        imagejpeg($image, $savePath, 90);
        imagedestroy($image);
        imagedestroy($overlay);

        return $fullPath;
    }
}

