<x-site-layout :seo="$seo">
    <x-page-header :title="$page->title" :trail="[[$page->title, $page->url]]" />
    <section class="py-16 lg:py-24">
        <div class="wrap"><div class="prose-ar mx-auto max-w-3xl">{!! $page->body !!}</div></div>
    </section>
</x-site-layout>
