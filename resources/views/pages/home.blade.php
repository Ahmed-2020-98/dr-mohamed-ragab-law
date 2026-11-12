@php
    $phone = collect(setting('phones', []))->first();
    $whatsapp = setting('whatsapp_eg', $phone);
    $bookingUrl = wa_link($whatsapp, 'مرحبًا، أرغب في حجز استشارة قانونية.');
    $values = setting('values', []);
    $stats = setting('stats', []);
    $jurisdictions = setting('jurisdictions', []);
@endphp
<x-site-layout :seo="$seo">
    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden">
        <x-scales class="pointer-events-none absolute -top-10 start-[38%] hidden size-[34rem] text-gold opacity-[0.10] lg:block" />

        <div class="wrap relative grid items-center gap-14 pb-20 pt-14 lg:grid-cols-12 lg:gap-10 lg:pb-28 lg:pt-24">
            <div class="lg:col-span-7">
                <p class="kicker animate-rise">{{ setting('hero_kicker') }}</p>
                <h1 class="mt-6 animate-rise text-[clamp(2.9rem,7.4vw,6.4rem)] leading-[1.18] text-ink [animation-delay:90ms]">
                    {{ setting('hero_title') }}
                </h1>
                <div class="rule-gold mt-8 max-w-md animate-fade [animation-delay:250ms]"></div>
                <p class="mt-8 max-w-2xl animate-rise text-lg leading-loose text-ink-2 [animation-delay:200ms] lg:text-xl lg:leading-[2.05]">
                    {{ setting('hero_subtitle') }}
                </p>

                <div class="mt-10 flex animate-rise flex-col gap-4 [animation-delay:300ms] sm:flex-row sm:items-center">
                    <a href="{{ $bookingUrl }}" target="_blank" rel="noopener" class="btn btn-primary">
                        <x-glyph name="whatsapp" class="size-5" /> احجز استشارة الآن
                    </a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline">تعرّف على خدماتنا</a>
                </div>

                @if ($credentials = setting('hero_credentials', []))
                    <ul class="mt-12 flex animate-fade flex-wrap gap-x-7 gap-y-3 border-t border-line pt-6 text-[0.95rem] text-ink-2 [animation-delay:450ms]">
                        @foreach ($credentials as $credential)
                            <li class="flex items-center gap-3"><span class="diamond"></span>{{ $credential }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="relative mx-auto w-full max-w-md animate-rise [animation-delay:200ms] lg:col-span-5 lg:max-w-none">
                <figure class="plate aspect-[4/5]">
                    <img src="{{ media_url(setting('lawyer_photo')) }}" alt="{{ setting('hero_title') }}" width="1280" height="1280" fetchpriority="high"
                        class="object-[50%_20%]">
                </figure>
                <div class="absolute -bottom-7 end-[-0.5rem] bg-navy px-6 py-4 text-paper shadow-[0_20px_40px_-18px_rgb(14_27_46/0.7)] sm:end-[-1.5rem]">
                    <p class="font-display text-2xl font-bold leading-snug">محمد رجب</p>
                    <p class="text-sm text-gold-soft">المحامي والمحكّم الدولي</p>
                </div>
            </div>
        </div>

        {{-- Jurisdictions marquee --}}
        @if ($jurisdictions)
            <div class="overflow-hidden border-y border-ink bg-ink py-4 text-paper" aria-label="نطاق العمل: {{ implode('، ', $jurisdictions) }}">
                <div class="flex w-max animate-marquee gap-10 motion-reduce:animate-none" aria-hidden="true">
                    @for ($i = 0; $i < 6; $i++)
                        @foreach ($jurisdictions as $country)
                            <span class="flex items-center gap-10 font-display text-2xl font-bold lg:text-3xl">{{ $country }} <span class="diamond"></span></span>
                        @endforeach
                        <span class="flex items-center gap-10 font-display text-2xl text-gold-soft lg:text-3xl">محاماة · تحكيم · تأسيس شركات <span class="diamond"></span></span>
                    @endfor
                </div>
            </div>
        @endif
    </section>

    {{-- ============ ABOUT ============ --}}
    <section class="py-20 lg:py-32">
        <div class="wrap grid gap-14 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5" data-reveal>
                <figure class="plate mx-auto aspect-[3/4] max-w-sm lg:max-w-none">
                    <img src="{{ media_url(setting('about_image')) }}" alt="المستشار محمد رجب" width="781" height="1040" loading="lazy">
                </figure>
            </div>
            <div class="lg:col-span-7 lg:pt-6">
                <x-section-heading kicker="من نحن" :title="setting('about_title')" />
                <p class="mt-8 font-display text-[1.45rem] leading-[1.95] text-ink lg:text-[1.6rem]" data-reveal>{{ setting('about_lead') }}</p>
                <div class="prose-ar mt-6" data-reveal>{!! setting('about_body') !!}</div>
                <a href="{{ route('about') }}" class="link-arrow mt-9" data-reveal>المزيد عن المكتب <x-glyph name="arrow-left" class="size-4" /></a>
            </div>
        </div>
    </section>

    {{-- ============ STATS (only when real numbers are entered) ============ --}}
    @if ($stats)
        <section class="border-y border-line-strong bg-paper-2">
            <div class="wrap grid grid-cols-2 lg:grid-cols-{{ min(count($stats), 4) }}">
                @foreach ($stats as $stat)
                    <div class="border-line-strong px-4 py-10 text-center lg:border-s lg:first:border-s-0" data-reveal>
                        <p class="font-display text-5xl font-bold text-ink">{{ $stat['value'] }}</p>
                        <p class="mt-2 text-ink-3">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ SERVICES INDEX ============ --}}
    <section class="relative bg-paper-2/60 py-20 lg:py-32">
        <div class="wrap grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-32">
                    <x-section-heading kicker="خدماتنا القانونية" title="فهرس الخدمات" lead="نتولى القضايا في جميع درجات التقاضي، ونقدّم الاستشارات والعقود وتأسيس الشركات للأفراد والمستثمرين." />
                    <a href="{{ route('services.index') }}" class="btn btn-outline mt-9" data-reveal>جميع الخدمات</a>
                </div>
            </div>
            <ol class="border-b border-line-strong lg:col-span-8">
                @foreach ($services as $service)
                    <x-service-row :service="$service" :number="$loop->iteration" data-reveal style="--reveal-delay: {{ min($loop->index, 5) * 60 }}ms" />
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============ VISION / MISSION ============ --}}
    <section class="py-20 lg:py-32">
        <div class="wrap">
            <div class="rule-double"></div>
            <div class="grid md:grid-cols-2">
                @foreach ([['الرؤية', setting('vision')], ['الرسالة', setting('mission')]] as [$label, $text])
                    <article class="border-line-strong py-12 md:px-12 md:first:ps-0 md:last:border-s md:last:pe-0" data-reveal>
                        <p class="kicker">{{ $label }}</p>
                        <p class="mt-6 font-display text-[clamp(1.55rem,2.6vw,2.15rem)] leading-[1.75] text-ink">{{ $text }}</p>
                    </article>
                @endforeach
            </div>
            <div class="rule"></div>
        </div>
    </section>

    {{-- ============ VALUES ============ --}}
    @if ($values)
        <section class="relative overflow-hidden bg-navy py-20 text-paper lg:py-32">
            <x-scales class="pointer-events-none absolute -top-20 end-[-6rem] size-[34rem] text-gold opacity-[0.08]" />
            <div class="wrap relative">
                <x-section-heading kicker="أهدافنا" title="خمسة مبادئ نلتزم بها مع كل موكل" light />
                <ol class="mt-16 grid gap-px bg-paper/10 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($values as $value)
                        <li class="bg-navy p-7 lg:p-8" data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms">
                            <span class="font-display text-4xl text-gold">{{ ar_num($loop->iteration, 2) }}</span>
                            <h3 class="mt-6 text-2xl text-paper">{{ $value['title'] }}</h3>
                            @if (! empty($value['text']))
                                <p class="mt-3 text-[0.95rem] leading-loose text-paper/70">{{ $value['text'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- ============ VIDEOS ============ --}}
    @if ($videos->isNotEmpty())
        <section class="py-20 lg:py-32" x-data="videoPlayer">
            <div class="wrap">
                <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <x-section-heading kicker="توعية قانونية" title="معلومة قانونية في دقيقة" />
                    <a href="{{ route('videos') }}" class="link-arrow shrink-0" data-reveal>كل الفيديوهات <x-glyph name="arrow-left" class="size-4" /></a>
                </div>
                <div class="mt-12 grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-6">
                    @foreach ($videos as $video)
                        <x-video-card :video="$video" data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms" />
                    @endforeach
                </div>
            </div>
            <x-video-modal />
        </section>
    @endif

    {{-- ============ TESTIMONIALS (hidden until real ones are added) ============ --}}
    @if ($testimonials->isNotEmpty())
        <section class="border-t border-line-strong bg-paper-2/60 py-20 lg:py-32">
            <div class="wrap">
                <x-section-heading kicker="آراء الموكلين" title="ثقة نعتز بها" />
                <div class="mt-14 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <figure class="flex flex-col border-t-2 border-gold pt-7" data-reveal>
                            <span class="font-display text-6xl leading-none text-gold" aria-hidden="true">”</span>
                            <blockquote class="mt-2 flex-1 font-display text-xl leading-loose text-ink">{{ $testimonial->body }}</blockquote>
                            <figcaption class="mt-6 text-sm text-ink-3"><span class="font-semibold text-ink">{{ $testimonial->name }}</span>@if ($testimonial->role) — {{ $testimonial->role }}@endif</figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ FAQ ============ --}}
    @if ($faqs->isNotEmpty())
        <section class="py-20 lg:py-32">
            <div class="wrap grid gap-12 lg:grid-cols-12 lg:gap-16">
                <div class="lg:col-span-4">
                    <x-section-heading kicker="أسئلة شائعة" title="قبل أن تتصل بنا" lead="إجابات مختصرة عن أكثر ما يسألنا عنه الموكلون." />
                    <a href="{{ route('faq') }}" class="link-arrow mt-8" data-reveal>جميع الأسئلة <x-glyph name="arrow-left" class="size-4" /></a>
                </div>
                <x-faq-list :faqs="$faqs" class="lg:col-span-8" data-reveal />
            </div>
        </section>
    @endif

    {{-- ============ POSTS ============ --}}
    @if ($posts->isNotEmpty())
        <section class="border-t border-line-strong py-20 lg:py-32">
            <div class="wrap">
                <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <x-section-heading kicker="المدونة" title="أحدث المقالات القانونية" />
                    <a href="{{ route('blog.index') }}" class="link-arrow shrink-0" data-reveal>كل المقالات <x-glyph name="arrow-left" class="size-4" /></a>
                </div>
                <div class="mt-12 grid gap-10 md:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" data-reveal />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />
</x-site-layout>
