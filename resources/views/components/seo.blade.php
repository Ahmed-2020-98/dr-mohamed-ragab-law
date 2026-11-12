@props(['seo'])
<title>{{ $seo->fullTitle() }}</title>
<meta name="description" content="{{ $seo->metaDescription() }}">
<link rel="canonical" href="{{ $seo->canonicalUrl() }}">
<meta name="robots" content="{{ $seo->shouldIndex() ? 'index, follow, max-image-preview:large' : 'noindex, nofollow' }}">
@if ($verification = setting('google_verification'))
    <meta name="google-site-verification" content="{{ $verification }}">
@endif

<meta property="og:locale" content="ar_EG">
<meta property="og:site_name" content="{{ setting('site_short_name', config('app.name')) }}">
<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:title" content="{{ $seo->fullTitle() }}">
<meta property="og:description" content="{{ $seo->metaDescription() }}">
<meta property="og:url" content="{{ $seo->canonicalUrl() }}">
<meta property="og:image" content="{{ $seo->imageUrl() }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo->fullTitle() }}">
<meta name="twitter:description" content="{{ $seo->metaDescription() }}">
<meta name="twitter:image" content="{{ $seo->imageUrl() }}">

<script type="application/ld+json">{!! json_encode($seo->graph(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
