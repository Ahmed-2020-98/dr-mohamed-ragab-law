<x-site-layout :seo="$seo">
    <x-page-header title="الخدمات القانونية" kicker="فهرس الخدمات"
        lead="نتولى القضايا في جميع درجات التقاضي، ونقدّم الاستشارات وصياغة العقود وتأسيس الشركات في مصر والسعودية والإمارات والكويت."
        :trail="[['الخدمات', route('services.index')]]" />

    <section class="py-16 lg:py-24">
        <div class="wrap grid gap-x-10 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                <article class="group flex flex-col" data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 80 }}ms">
                    <a href="{{ $service->url }}" class="relative block aspect-[4/3] overflow-hidden bg-navy" tabindex="-1" aria-hidden="true">
                        @if ($image = $service->mediaUrl())
                            <img src="{{ $image }}" alt="" loading="lazy" decoding="async" class="size-full object-cover opacity-90 transition-transform duration-[900ms] ease-out-expo group-hover:scale-105">
                        @else
                            <span class="flex size-full items-center justify-center [background:radial-gradient(circle_at_30%_20%,var(--color-navy-2),var(--color-navy-3))]">
                                <x-scales class="size-32 text-gold opacity-60" />
                            </span>
                        @endif
                        <span class="absolute start-0 top-0 bg-paper px-4 py-2 font-display text-2xl text-gold-deep">{{ ar_num($loop->iteration, 2) }}</span>
                    </a>
                    <h2 class="mt-6 text-[1.75rem] leading-relaxed">
                        <a href="{{ $service->url }}" class="transition-colors hover:text-gold-deep">{{ $service->title }}</a>
                    </h2>
                    <p class="mt-3 flex-1 leading-loose text-ink-3">{{ $service->excerpt }}</p>
                    <a href="{{ $service->url }}" class="link-arrow mt-5 self-start" aria-label="تفاصيل خدمة {{ $service->title }}">التفاصيل <x-glyph name="arrow-left" class="size-4" /></a>
                </article>
            @endforeach
        </div>
    </section>

    <x-cta-band />
</x-site-layout>
