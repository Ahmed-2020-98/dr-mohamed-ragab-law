@php
    $items = $images->map(fn ($image) => ['src' => $image->mediaUrl(), 'caption' => $image->caption])->values();
@endphp
<x-site-layout :seo="$seo">
    <x-page-header title="معرض الصور" kicker="من المكتب والمحاكم والمحاضرات" :trail="[['معرض الصور', route('gallery')]]" />

    <section class="py-16 lg:py-24" x-data="lightbox({{ Js::from($items) }})" @keydown.window="if (index !== null) { if ($event.key === 'Escape') close(); if ($event.key === 'ArrowLeft') step(1); if ($event.key === 'ArrowRight') step(-1); }">
        <div class="wrap columns-2 gap-4 md:columns-3 lg:gap-6">
            @foreach ($images as $image)
                <button type="button" @click="open({{ $loop->index }}, $event)" class="group relative mb-4 block w-full overflow-hidden bg-paper-2 lg:mb-6" data-reveal>
                    <img src="{{ $image->mediaUrl() }}" alt="{{ $image->caption ?: 'صورة من معرض المستشار محمد رجب' }}" loading="lazy" decoding="async"
                        class="w-full transition-transform duration-[900ms] ease-out-expo group-hover:scale-[1.04]">
                    <span class="absolute inset-0 bg-navy/0 transition-colors duration-300 group-hover:bg-navy/25"></span>
                    <span class="sr-only">عرض الصورة بحجم كبير</span>
                </button>
            @endforeach
        </div>

        <div x-cloak x-show="index !== null" x-transition.opacity.duration.250ms class="fixed inset-0 z-[80] flex flex-col bg-navy-3/97" role="dialog" aria-modal="true" aria-label="عرض الصورة">
            <div class="flex items-center justify-between p-4 text-paper">
                <p class="font-latin text-lg" x-text="index !== null ? (index + 1) + ' / ' + items.length : ''" dir="ltr"></p>
                <button type="button" x-ref="close" @click="close()" class="flex size-12 items-center justify-center border border-paper/40 transition-colors hover:bg-paper hover:text-ink">
                    <span class="sr-only">إغلاق</span><x-glyph name="x" class="size-6" />
                </button>
            </div>
            <div class="relative flex flex-1 items-center justify-center px-4 pb-6 sm:px-20">
                <img :src="current?.src" :alt="current?.caption || ''" class="max-h-[78dvh] max-w-full object-contain">
                <button type="button" @click="step(-1)" class="absolute start-2 top-1/2 flex size-12 -translate-y-1/2 items-center justify-center bg-paper/10 text-paper hover:bg-paper hover:text-ink sm:start-4">
                    <span class="sr-only">الصورة السابقة</span><x-glyph name="chevron-right" class="size-6" />
                </button>
                <button type="button" @click="step(1)" class="absolute end-2 top-1/2 flex size-12 -translate-y-1/2 items-center justify-center bg-paper/10 text-paper hover:bg-paper hover:text-ink sm:end-4">
                    <span class="sr-only">الصورة التالية</span><x-glyph name="chevron-left" class="size-6" />
                </button>
            </div>
            <p class="pb-8 text-center font-display text-xl text-paper" x-text="current?.caption"></p>
        </div>
    </section>
</x-site-layout>
