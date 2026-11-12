<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasMedia;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if ($post->is_published && ! $post->published_at) {
                $post->published_at = now();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');
    }

    public function getUrlAttribute(): string
    {
        return route('blog.show', $this);
    }

    /** Estimated reading time in minutes. */
    public function getReadingMinutesAttribute(): int
    {
        $words = count(preg_split('/\s+/u', trim(strip_tags((string) $this->body)), -1, PREG_SPLIT_NO_EMPTY));

        return max(1, (int) ceil($words / 180));
    }
}
