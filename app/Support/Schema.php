<?php

namespace App\Support;

use App\Models\Post;
use App\Models\Service;
use App\Models\Video;
use Illuminate\Support\Collection;

/**
 * schema.org JSON-LD builders. Each method returns one node for Seo::graph().
 */
class Schema
{
    public static function organizationId(): string
    {
        return url('/').'#organization';
    }

    public static function organization(): array
    {
        $phones = collect(setting('phones', []))->map(fn ($phone) => '+'.phone_intl($phone))->values();
        $social = collect(['facebook', 'tiktok', 'linkedin', 'youtube', 'instagram', 'x_twitter'])
            ->map(fn ($key) => setting($key))->filter()->values();

        return array_filter([
            '@type' => ['LegalService', 'Attorney'],
            '@id' => self::organizationId(),
            'name' => setting('site_name'),
            'alternateName' => setting('site_short_name'),
            'url' => url('/'),
            'logo' => media_url(setting('logo')),
            'image' => media_url(setting('lawyer_photo')) ?: asset('images/og-default.jpg'),
            'description' => setting('seo_description'),
            'slogan' => setting('site_tagline'),
            'telephone' => $phones->first(),
            'email' => setting('email'),
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => setting('address'),
                'addressLocality' => 'القاهرة',
                'addressCountry' => 'EG',
            ]),
            'hasMap' => setting('map_url'),
            'openingHours' => setting('working_hours'),
            'areaServed' => collect(setting('jurisdictions', []))
                ->map(fn ($country) => ['@type' => 'Country', 'name' => $country])->all(),
            'contactPoint' => $phones->map(fn ($phone) => [
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'contactType' => 'customer service',
                'availableLanguage' => ['ar', 'en'],
            ])->all(),
            'sameAs' => $social->all(),
            'founder' => self::lawyer(),
        ]);
    }

    public static function lawyer(): array
    {
        return array_filter([
            '@type' => 'Person',
            '@id' => url('/about').'#lawyer',
            'name' => 'المستشار محمد رجب',
            'jobTitle' => collect(setting('hero_credentials', []))->first() ?: 'محامٍ',
            'image' => media_url(setting('lawyer_photo')),
            'url' => url('/about'),
        ]);
    }

    public static function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => setting('site_short_name'),
            'inLanguage' => 'ar',
            'publisher' => ['@id' => self::organizationId()],
        ];
    }

    /** @param array<int, array{0: string, 1: string}> $trail */
    public static function breadcrumbs(array $trail): array
    {
        $items = [['الرئيسية', url('/')], ...$trail];

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item[0],
                'item' => $item[1],
            ])->all(),
        ];
    }

    public static function service(Service $service): array
    {
        return array_filter([
            '@type' => 'Service',
            'name' => $service->title,
            'description' => $service->excerpt,
            'url' => $service->url,
            'image' => $service->mediaUrl(),
            'serviceType' => 'خدمات قانونية',
            'provider' => ['@id' => self::organizationId()],
            'areaServed' => collect(setting('jurisdictions', []))
                ->map(fn ($country) => ['@type' => 'Country', 'name' => $country])->all(),
        ]);
    }

    public static function article(Post $post): array
    {
        return array_filter([
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'image' => $post->mediaUrl() ?: asset('images/og-default.jpg'),
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'inLanguage' => 'ar',
            'mainEntityOfPage' => $post->url,
            'author' => ['@id' => url('/about').'#lawyer'],
            'publisher' => ['@id' => self::organizationId()],
        ]);
    }

    public static function faq(Collection $faqs): ?array
    {
        if ($faqs->isEmpty()) {
            return null;
        }

        return [
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
            ])->values()->all(),
        ];
    }

    public static function video(Video $video): ?array
    {
        $source = $video->source_url;

        if (! $source || ! $video->poster) {
            return null;
        }

        return [
            '@type' => 'VideoObject',
            'name' => $video->title,
            'description' => $video->description ?: $video->title.' — '.setting('site_short_name'),
            'thumbnailUrl' => $video->mediaUrl('poster'),
            'contentUrl' => $source,
            'uploadDate' => $video->created_at?->toIso8601String(),
        ];
    }
}
