<x-site-layout :seo="$seo">
    <x-page-header title="الفيديوهات" kicker="توعية قانونية"
        lead="مقاطع قصيرة يشرح فيها المستشار محمد رجب مسائل قانونية تهمّك في حياتك اليومية وأعمالك."
        :trail="[['الفيديوهات', route('videos')]]" />

    <section class="py-16 lg:py-24" x-data="videoPlayer">
        <div class="wrap">
            @if ($videos->isEmpty())
                <p class="py-16 text-center font-display text-2xl text-ink-3">لا توجد فيديوهات بعد.</p>
            @else
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 lg:gap-6">
                    @foreach ($videos as $video)
                        <x-video-card :video="$video" data-reveal style="--reveal-delay: {{ ($loop->index % 4) * 70 }}ms" />
                    @endforeach
                </div>
            @endif
        </div>
        <x-video-modal />
    </section>

    @if ($tiktok = setting('tiktok'))
        <section class="border-t border-line-strong py-14">
            <div class="wrap flex flex-col items-center justify-between gap-6 text-center md:flex-row md:text-start">
                <p class="font-display text-2xl">تابع أحدث الفيديوهات على حساباتنا</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ $tiktok }}" target="_blank" rel="noopener" class="btn btn-outline"><x-glyph name="tiktok" class="size-5" /> تيك توك</a>
                    @if ($facebook = setting('facebook'))
                        <a href="{{ $facebook }}" target="_blank" rel="noopener" class="btn btn-outline"><x-glyph name="facebook" class="size-5" /> فيسبوك</a>
                    @endif
                </div>
            </div>
        </section>
    @endif
</x-site-layout>
