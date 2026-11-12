<?php

namespace App\Models;

use App\Support\Settings;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::saved(fn () => app(Settings::class)->flush());
        static::deleted(fn () => app(Settings::class)->flush());
    }
}
