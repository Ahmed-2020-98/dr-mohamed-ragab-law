<?php

use App\Support\Settings;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    /** Read a site setting (managed from the admin panel). */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(Settings::class)->get($key, $default);
    }
}

if (! function_exists('media_url')) {
    /** Public URL for a file on the public disk; passes absolute URLs through. */
    function media_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (preg_match('~^(https?:)?//~', $path) || str_starts_with($path, '/')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('ar_slug')) {
    /** URL slug that keeps Arabic letters: "تأسيس الشركات" => "تأسيس-الشركات". */
    function ar_slug(string $text): string
    {
        $text = preg_replace('/[\x{064B}-\x{065F}\x{0640}]/u', '', $text); // tashkeel + tatweel
        $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', mb_strtolower(trim($text)));

        return trim($text, '-');
    }
}

if (! function_exists('ar_num')) {
    /** Western digits to Arabic-Indic digits: 07 => ٠٧. */
    function ar_num(int|string $value, int $pad = 0): string
    {
        $value = $pad ? str_pad((string) $value, $pad, '0', STR_PAD_LEFT) : (string) $value;

        return strtr($value, ['0' => '٠', '1' => '١', '2' => '٢', '3' => '٣', '4' => '٤', '5' => '٥', '6' => '٦', '7' => '٧', '8' => '٨', '9' => '٩']);
    }
}

if (! function_exists('phone_intl')) {
    /** Normalise an Egyptian/Gulf number to digits with country code: 01004312938 => 201004312938. */
    function phone_intl(?string $phone, string $defaultCountry = '20'): string
    {
        $digits = preg_replace('/\D+/', '', strtr((string) $phone, ['٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9']));

        if (str_starts_with($digits, '00')) {
            return substr($digits, 2);
        }

        return str_starts_with($digits, '0') ? $defaultCountry.substr($digits, 1) : $digits;
    }
}

if (! function_exists('wa_link')) {
    function wa_link(?string $phone, ?string $text = null): string
    {
        return 'https://wa.me/'.phone_intl($phone).($text ? '?text='.rawurlencode($text) : '');
    }
}

if (! function_exists('tel_link')) {
    function tel_link(?string $phone): string
    {
        return 'tel:+'.phone_intl($phone);
    }
}
