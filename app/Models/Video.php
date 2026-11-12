<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasMedia;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /** Embeddable URL for YouTube links; null for anything else. */
    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->url) {
            return null;
        }

        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([\w-]{11})~', $this->url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/'.$m[1];
        }

        return null;
    }

    /** Direct playable source (uploaded file, or an external .mp4/.webm link). */
    public function getSourceUrlAttribute(): ?string
    {
        if ($this->file) {
            return media_url($this->file);
        }

        return $this->url && preg_match('~\.(mp4|webm|mov)(\?.*)?$~i', $this->url) ? $this->url : null;
    }
}
