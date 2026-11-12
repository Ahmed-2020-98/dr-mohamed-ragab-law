@props(['post'])
<article {{ $attributes->class(['group flex flex-col border-t border-ink pt-6']) }}>
    <p class="flex items-center gap-3 text-sm text-ink-3">
        @if ($post->category)
            <a href="{{ route('blog.category', $post->category) }}" class="font-semibold text-gold-deep hover:underline">{{ $post->category->name }}</a>
            <span class="diamond !size-1"></span>
        @endif
        <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('j F Y') }}</time>
    </p>
    @if ($image = $post->mediaUrl())
        <a href="{{ $post->url }}" class="mt-5 block aspect-[16/10] overflow-hidden bg-paper-2" tabindex="-1" aria-hidden="true">
            <img src="{{ $image }}" alt="" loading="lazy" decoding="async" class="size-full object-cover transition-transform duration-[900ms] ease-out-expo group-hover:scale-105">
        </a>
    @endif
    <h3 class="mt-5 text-[1.6rem] leading-relaxed">
        <a href="{{ $post->url }}" class="transition-colors hover:text-gold-deep">{{ $post->title }}</a>
    </h3>
    @if ($post->excerpt)
        <p class="mt-3 line-clamp-3 text-ink-3">{{ $post->excerpt }}</p>
    @endif
    <a href="{{ $post->url }}" class="link-arrow mt-5 self-start text-[0.95rem]" aria-label="اقرأ المقال: {{ $post->title }}">
        اقرأ المقال <x-glyph name="arrow-left" class="size-4" />
    </a>
</article>
