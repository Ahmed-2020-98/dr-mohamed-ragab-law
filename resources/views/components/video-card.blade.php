@props(['video'])
@php
    $playable = $video->source_url || $video->embed_url;
    $payload = ['title' => $video->title, 'src' => $video->source_url, 'embed' => $video->embed_url];
@endphp
<article {{ $attributes->class(['group relative']) }}>
    <{{ $playable ? 'button' : 'a' }}
        @if ($playable) type="button" @click="play({{ Js::from($payload) }}, $event)" @else href="{{ $video->url }}" target="_blank" rel="noopener" @endif
        class="relative block aspect-[9/16] w-full overflow-hidden bg-navy text-start">
        @if ($poster = $video->mediaUrl('poster'))
            <img src="{{ $poster }}" alt="" loading="lazy" decoding="async"
                class="absolute inset-0 size-full object-cover transition-transform duration-[900ms] ease-out-expo group-hover:scale-105">
        @else
            <x-scales class="absolute inset-0 m-auto size-28 text-gold opacity-40" />
        @endif
        <span class="absolute inset-0 bg-gradient-to-t from-navy-3 via-navy-3/25 to-transparent"></span>

        <span class="absolute start-4 top-4 flex size-12 items-center justify-center bg-gold text-navy-3 transition-transform duration-500 ease-out-expo group-hover:scale-110">
            <x-glyph name="play" class="size-5" />
        </span>

        <span class="absolute inset-x-0 bottom-0 p-4 sm:p-5">
            <span class="block font-display text-lg font-bold leading-relaxed text-paper sm:text-xl">{{ $video->title }}</span>
            <span class="sr-only">— تشغيل الفيديو</span>
        </span>
    </{{ $playable ? 'button' : 'a' }}>
</article>
