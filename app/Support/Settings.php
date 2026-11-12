<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Throwable;

/**
 * Key/value site settings, cached forever and memoised per request.
 * Registered as a container singleton so each request (and test) gets a fresh memo.
 */
class Settings
{
    public const CACHE_KEY = 'site.settings';

    protected ?array $loaded = null;

    public function all(): array
    {
        if ($this->loaded !== null) {
            return $this->loaded;
        }

        try {
            return $this->loaded = Cache::rememberForever(self::CACHE_KEY, fn () => Setting::query()
                ->pluck('value', 'key')
                ->map(fn ($value) => json_decode((string) $value, true))
                ->all());
        } catch (Throwable) {
            // Settings table not migrated yet (fresh install).
            return $this->loaded = [];
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->all()[$key] ?? null;

        return ($value === null || $value === '' || $value === []) ? $default : $value;
    }

    /** @param array<string, mixed> $values */
    public function put(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)],
            );
        }

        $this->flush();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->loaded = null;
    }
}
