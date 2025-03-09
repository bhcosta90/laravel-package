<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadFile
{
    public function upload(UploadedFile | string $file, string $path): UploadFileOutput
    {
        if ($file instanceof UploadedFile) {
            return new UploadFileOutput(
                path: $file->store($path . '/' . str()->uuid() . '.' . $file->extension()),
            );
        }

        if (str_contains($file, 'base64')) {
            Storage::put($path, $file);

            return new UploadFileOutput(
                path: $path . '/' . str()->uuid(),
            );
        }
    }
}
