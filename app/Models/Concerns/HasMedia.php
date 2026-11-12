<?php

namespace App\Models\Concerns;

trait HasMedia
{
    /** Public URL for a stored media attribute (image, poster, file...). */
    public function mediaUrl(string $attribute = 'image'): ?string
    {
        return media_url($this->{$attribute});
    }
}
