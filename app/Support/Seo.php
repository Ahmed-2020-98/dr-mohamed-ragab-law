<?php

namespace App\Support;

/**
 * Per-page SEO data handed to the layout: title, description, canonical, share image,
 * breadcrumbs and any extra JSON-LD blocks.
 */
class Seo
{
    /**
     * @param  array<int, array{0: string, 1: string}>  $breadcrumbs  [label, url] pairs after "الرئيسية"
     * @param  array<int, array<string, mixed>>  $schema  extra JSON-LD nodes
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $image = null,
        public string $type = 'website',
        public ?string $canonical = null,
        public array $breadcrumbs = [],
        public array $schema = [],
        public bool $noindex = false,
    ) {}

    public static function make(?string $title = null, ?string $description = null): static
    {
        return new static($title, $description);
    }

    public function image(?string $path): static
    {
        $this->image = $path ? media_url($path) : $this->image;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function breadcrumbs(array $breadcrumbs): static
    {
        $this->breadcrumbs = $breadcrumbs;

        return $this;
    }

    public function schema(array ...$nodes): static
    {
        $this->schema = [...$this->schema, ...array_filter($nodes)];

        return $this;
    }

    public function noindex(bool $noindex = true): static
    {
        $this->noindex = $noindex;

        return $this;
    }

    /** "<page> | <firm>" for inner pages, the configured home title otherwise. */
    public function fullTitle(): string
    {
        $brand = setting('site_short_name', config('app.name'));

        return $this->title
            ? $this->title.' | '.$brand
            : setting('seo_title', $brand);
    }

    public function metaDescription(): string
    {
        $text = $this->description ?: setting('seo_description', setting('site_tagline', ''));
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $text)));

        return mb_strlen($text) > 165 ? rtrim(mb_substr($text, 0, 162)).'…' : $text;
    }

    public function canonicalUrl(): string
    {
        return $this->canonical ?: url()->current();
    }

    public function imageUrl(): string
    {
        $image = $this->image ?: media_url(setting('seo_og_image')) ?: asset('images/og-default.jpg');

        return str_starts_with($image, 'http') ? $image : url($image);
    }

    public function shouldIndex(): bool
    {
        return ! $this->noindex && ! setting('noindex', false);
    }

    /** All JSON-LD nodes for the page as one @graph. */
    public function graph(): array
    {
        $nodes = [Schema::organization(), Schema::website()];

        if ($this->breadcrumbs) {
            $nodes[] = Schema::breadcrumbs($this->breadcrumbs);
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => [...$nodes, ...$this->schema],
        ];
    }
}
