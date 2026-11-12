@php
    $phone = collect(setting('phones', []))->first();
    $whatsapp = setting('whatsapp_eg', $phone);
@endphp
<x-site-layout :seo="$seo">
    <x-page-header :title="$service->title" kicker="خدماتنا القانونية" :lead="$service->excerpt"
        :trail="[['الخدمات', route('services.index')], [$service->title, $service->url]]" />

    <section class="py-16 lg:py-24">
        <div class="wrap grid gap-14 lg:grid-cols-12 lg:gap-16">
            <article class="lg:col-span-8">
                @if ($image = $service->mediaUrl())
                    <figure class="plate mb-14 aspect-[16/9]" data-reveal>
                        <img src="{{ $image }}" alt="{{ $service->title }}" width="1200" height="800">
                    </figure>
                @endif

                <div class="prose-ar" data-reveal>{!! $service->body !!}</div>

                @if ($service->highlights)
                    <h2 class="mt-14 text-3xl" data-reveal>ما تشمله الخدمة</h2>
                    <ul class="mt-6 grid gap-x-8 border-t border-ink sm:grid-cols-2">
                        @foreach ($service->highlights as $item)
                            <li class="flex items-start gap-4 border-b border-line-strong py-4 text-ink-2" data-reveal>
                                <x-glyph name="check" class="mt-1.5 size-4 text-gold-deep" />{{ $item }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </article>

            <aside class="lg:col-span-4">
                <div class="space-y-8 lg:sticky lg:top-32">
                    <div class="bg-navy p-8 text-paper">
                        <p class="kicker kicker-light">استشارة بخصوص هذه الخدمة</p>
                        <p class="mt-4 font-display text-2xl leading-relaxed">اعرض موضوعك على المستشار محمد رجب</p>
                        <div class="mt-7 space-y-3">
                            @if ($whatsapp)
                                <a href="{{ wa_link($whatsapp, 'مرحبًا، أرغب في استشارة بخصوص: '.$service->title) }}" target="_blank" rel="noopener" class="btn btn-gold w-full">
                                    <x-glyph name="whatsapp" class="size-5" /> واتساب
                                </a>
                            @endif
                            <a href="{{ route('contact') }}#consultation-form" class="btn btn-outline-light w-full">نموذج طلب استشارة</a>
                        </div>
                    </div>

                    <nav aria-label="خدمات أخرى">
                        <p class="kicker">خدمات أخرى</p>
                        <ul class="mt-4 border-t border-ink">
                            @foreach ($others as $other)
                                <li>
                                    <a href="{{ $other->url }}" class="group flex items-center justify-between gap-4 border-b border-line-strong py-3.5 text-ink-2 transition-colors hover:text-ink">
                                        {{ $other->title }}
                                        <x-glyph name="arrow-left" class="size-4 text-gold-deep transition-transform group-hover:-translate-x-1" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </aside>
        </div>
    </section>

    <x-cta-band />
</x-site-layout>
