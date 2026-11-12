<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Copies the bundled brand, service and gallery images onto the public disk.
 * Existing files are left alone so admin uploads are never overwritten.
 */
class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $source = database_path('seeders/media');
        $disk = Storage::disk('public');

        foreach (File::allFiles($source) as $file) {
            $target = str_replace(DIRECTORY_SEPARATOR, '/', $file->getRelativePathname());

            if (! $disk->exists($target)) {
                $disk->put($target, File::get($file->getPathname()));
            }
        }
    }
}
