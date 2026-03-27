<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SpacesImageUploader
{
    public static function store(UploadedFile $file, string $folder, string $filenameBase, string $disk = 'spaces'): string
    {
        [$binary, $extension] = self::optimize($file);

        $filename = $filenameBase . '.' . $extension;
        $path = trim($folder, '/') . '/' . $filename;

        Storage::disk($disk)->put($path, $binary, 'public');

        return $path;
    }

    public static function optimize(UploadedFile $file): array
    {
        $source = @file_get_contents($file->getRealPath());

        if ($source === false) {
            return [file_get_contents($file), $file->getClientOriginalExtension()];
        }

        $mime = (string) $file->getMimeType();
        if ($mime === 'image/svg+xml') {
            return [$source, 'svg'];
        }

        $image = @imagecreatefromstring($source);
        if (!$image) {
            return [$source, $file->getClientOriginalExtension()];
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $maxWidth = 2200;
        $maxHeight = 2200;
        $ratio = min($maxWidth / max($width, 1), $maxHeight / max($height, 1), 1);
        $targetWidth = max((int) round($width * $ratio), 1);
        $targetHeight = max((int) round($height * $ratio), 1);

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        imageinterlace($canvas, true);

        if (in_array($mime, ['image/png', 'image/webp', 'image/gif'], true)) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        ob_start();
        imagejpeg($canvas, null, 82);
        $binary = ob_get_clean();

        imagedestroy($image);
        imagedestroy($canvas);

        return [$binary ?: $source, 'jpg'];
    }
}
