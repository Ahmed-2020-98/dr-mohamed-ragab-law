@props(['service', 'number'])
<li {{ $attributes }}>
    <a href="{{ $service->url }}" class="group relative flex items-start gap-5 border-t border-line-strong py-7 transition-[padding] duration-500 ease-out-expo hover:ps-3 sm:gap-7">
        <span class="absolute inset-y-0 -inset-x-3 -z-10 origin-right scale-x-0 bg-paper-2 transition-transform duration-500 ease-out-expo group-hover:scale-x-100"></span>
        <span class="mt-1 w-10 shrink-0 font-display text-[1.75rem] font-normal leading-none text-gold-deep sm:w-12 sm:text-[2rem]">{{ ar_num($number, 2) }}</span>
        <span class="min-w-0 flex-1">
            <span class="block font-display text-[1.45rem] font-bold leading-relaxed text-ink sm:text-[1.7rem]">{{ $service->title }}</span>
            @if ($service->excerpt)
                <span class="mt-2 line-clamp-2 block text-[0.975rem] leading-loose text-ink-3">{{ $service->excerpt }}</span>
            @endif
        </span>
        <span class="mt-1 flex size-11 shrink-0 items-center justify-center border border-line-strong text-ink transition-colors duration-300 group-hover:border-ink group-hover:bg-ink group-hover:text-paper">
            <x-glyph name="arrow-left" class="size-4" />
        </span>
    </a>
</li>
