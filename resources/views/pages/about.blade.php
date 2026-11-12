<x-site-layout :seo="$seo">
    <x-page-header title="من نحن" kicker="مؤسسة المستشار محمد رجب للمحاماة" :lead="setting('site_tagline')" :trail="[['من نحن', route('about')]]" />

    <section class="py-20 lg:py-28">
        <div class="wrap grid gap-14 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-32" data-reveal>
                    <figure class="plate mx-auto aspect-[3/4] max-w-sm lg:max-w-none">
                        <img src="{{ media_url(setting('about_image')) }}" alt="المستشار محمد رجب" width="781" height="1040">
                    </figure>
                    @if ($credentials = setting('hero_credentials', []))
                        <ul class="mt-12 space-y-3 border-t border-ink pt-6">
                            @foreach ($credentials as $credential)
                                <li class="flex items-center gap-3 text-ink-2"><span class="diamond"></span>{{ $credential }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="lg:col-span-7">
                <x-section-heading kicker="نبذة" :title="setting('about_title')" />
                <p class="mt-8 font-display text-[1.45rem] leading-[1.95] text-ink lg:text-[1.6rem]" data-reveal>{{ setting('about_lead') }}</p>
                <div class="prose-ar mt-6" data-reveal>{!! setting('about_body') !!}</div>

                <h2 class="mt-16 text-3xl" data-reveal>مجالات الممارسة</h2>
                <ol class="mt-6 border-b border-line-strong">
                    @foreach ($services as $service)
                        <x-service-row :service="$service" :number="$loop->iteration" data-reveal />
                    @endforeach
                </ol>

                @if ($coverage = setting('coverage_text'))
                    <div class="mt-16 bg-paper-2 p-8" data-reveal>
                        <p class="kicker">نطاق العمل</p>
                        <p class="mt-4 text-lg leading-loose text-ink-2">{{ $coverage }}</p>
                        <p class="mt-5 flex flex-wrap gap-x-6 gap-y-2 font-display text-2xl font-bold">
                            @foreach (setting('jurisdictions', []) as $country)
                                <span class="flex items-center gap-3">@unless ($loop->first)<span class="diamond"></span>@endunless{{ $country }}</span>
                            @endforeach
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="border-t border-line-strong py-20 lg:py-28">
        <div class="wrap grid gap-12 md:grid-cols-2">
            @foreach ([['الرؤية', setting('vision')], ['الرسالة', setting('mission')]] as [$label, $text])
                <article data-reveal>
                    <p class="kicker">{{ $label }}</p>
                    <p class="mt-6 font-display text-[clamp(1.5rem,2.4vw,2rem)] leading-[1.8]">{{ $text }}</p>
                </article>
            @endforeach
        </div>
        @if ($values = setting('values', []))
            <div class="wrap mt-20">
                <h2 class="text-3xl" data-reveal>قيمنا</h2>
                <dl class="mt-8 grid gap-x-10 border-t border-ink sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($values as $value)
                        <div class="border-b border-line-strong py-6" data-reveal>
                            <dt class="flex items-center gap-3 font-display text-2xl font-bold"><span class="text-lg font-normal text-gold-deep">{{ ar_num($loop->iteration, 2) }}</span>{{ $value['title'] }}</dt>
                            @if (! empty($value['text']))<dd class="mt-2 text-ink-3">{{ $value['text'] }}</dd>@endif
                        </div>
                    @endforeach
                </dl>
            </div>
        @endif
    </section>

    <x-cta-band />
</x-site-layout>
