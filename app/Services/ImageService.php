<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Center-crop an uploaded image into a square and save to public disk.
     */
    public static function cropSquareAndStore(UploadedFile $file, string $directory = 'avatars/users', int $maxDimension = 500): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $mime = $file->getMimeType() ?: 'image/jpeg';

        $srcImage = match (true) {
            str_contains($mime, 'jpeg') || in_array($extension, ['jpg', 'jpeg']) => @imagecreatefromjpeg($file->getRealPath()),
            str_contains($mime, 'png') || $extension === 'png' => @imagecreatefrompng($file->getRealPath()),
            str_contains($mime, 'webp') || $extension === 'webp' => @imagecreatefromwebp($file->getRealPath()),
            default => null,
        };

        if (! $srcImage) {
            return $file->store($directory, 'public');
        }

        $origW = imagesx($srcImage);
        $origH = imagesy($srcImage);
        $minDim = min($origW, $origH);

        $srcX = (int) floor(($origW - $minDim) / 2);
        $srcY = (int) floor(($origH - $minDim) / 2);

        $targetDim = min($minDim, $maxDimension);
        $dstImage = imagecreatetruecolor($targetDim, $targetDim);

        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
        imagefilledrectangle($dstImage, 0, 0, $targetDim, $targetDim, $transparent);

        imagecopyresampled(
            $dstImage,
            $srcImage,
            0,
            0,
            $srcX,
            $srcY,
            $targetDim,
            $targetDim,
            $minDim,
            $minDim
        );

        $ext = function_exists('imagewebp') ? 'webp' : 'jpg';
        $fileName = rtrim($directory, '/').'/'.Str::random(40).'.'.$ext;
        $fullPath = Storage::disk('public')->path($fileName);

        @mkdir(dirname($fullPath), 0755, true);

        if ($ext === 'webp') {
            imagewebp($dstImage, $fullPath, 90);
        } else {
            imagejpeg($dstImage, $fullPath, 90);
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $fileName;
    }
}
