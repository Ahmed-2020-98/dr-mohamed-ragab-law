@php
    $phones = setting('phones', []);
    $social = array_filter([
        'facebook' => ['فيسبوك', setting('facebook')],
        'tiktok' => ['تيك توك', setting('tiktok')],
        'linkedin' => ['لينكدإن', setting('linkedin')],
        'youtube' => ['يوتيوب', setting('youtube')],
        'instagram' => ['إنستجرام', setting('instagram')],
        'x-twitter' => ['إكس', setting('x_twitter')],
    ], fn ($item) => filled($item[1]));
@endphp
<footer class="relative overflow-hidden bg-navy-3 text-paper/75">
    {{-- Epigraph --}}
    <div class="border-b border-paper/10">
        <div class="wrap py-12 text-center lg:py-16">
            <p class="font-display text-[clamp(2.4rem,7vw,5.5rem)] font-bold leading-[1.4] text-paper" aria-hidden="true">
                العدلُ <span class="text-gold">أساسُ</span> المُلك
            </p>
        </div>
    </div>

    <div class="wrap grid gap-12 py-14 md:grid-cols-2 lg:grid-cols-12 lg:gap-10 lg:py-20">
        <div class="lg:col-span-4">
            <img src="{{ media_url(setting('logo')) }}" alt="{{ setting('site_name') }}" width="640" height="458" loading="lazy" class="h-28 w-auto">
            @if ($about = setting('footer_about'))
                <p class="mt-6 max-w-sm text-[0.975rem] leading-loose">{{ $about }}</p>
            @endif
            @if ($social)
                <ul class="mt-7 flex flex-wrap gap-2.5">
                    @foreach ($social as $icon => [$label, $url])
                        <li>
                            <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $label }}"
                                class="flex size-11 items-center justify-center border border-paper/20 text-paper transition-colors duration-300 hover:border-gold hover:bg-gold hover:text-navy-3">
                                <x-glyph :name="$icon" class="size-[1.05rem]" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <nav class="lg:col-span-2" aria-label="روابط سريعة">
            <h2 class="font-sans text-sm font-semibold tracking-wide text-gold-soft">روابط سريعة</h2>
            <ul class="mt-5 space-y-1">
                @foreach ([
                    ['الرئيسية', route('home')], ['من نحن', route('about')], ['الخدمات', route('services.index')],
                    ['الفيديوهات', route('videos')], ['معرض الصور', route('gallery')],
                    ['الأسئلة الشائعة', route('faq')], ['الاتصال', route('contact')],
                ] as [$label, $url])
                    <li><a href="{{ $url }}" class="inline-flex min-h-9 items-center transition-colors hover:text-gold-soft">{{ $label }}</a></li>
                @endforeach
                @if ($hasPosts)
                    <li><a href="{{ route('blog.index') }}" class="inline-flex min-h-9 items-center transition-colors hover:text-gold-soft">المدونة</a></li>
                @endif
            </ul>
        </nav>

        <nav class="lg:col-span-3" aria-label="الخدمات القانونية">
            <h2 class="font-sans text-sm font-semibold tracking-wide text-gold-soft">الخدمات القانونية</h2>
            <ul class="mt-5 space-y-1">
                @foreach ($footerServices as $service)
                    <li><a href="{{ $service->url }}" class="inline-flex min-h-9 items-center transition-colors hover:text-gold-soft">{{ $service->title }}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="lg:col-span-3">
            <h2 class="font-sans text-sm font-semibold tracking-wide text-gold-soft">تواصل معنا</h2>
            <ul class="mt-5 space-y-4 text-[0.975rem]">
                @if ($address = setting('address'))
                    <li class="flex gap-3">
                        <x-glyph name="map-pin" class="mt-1.5 size-4 text-gold" />
                        <span>
                            {{ $address }}
                            @if ($map = setting('map_url'))
                                <a href="{{ $map }}" target="_blank" rel="noopener" class="mt-1 block text-gold-soft underline underline-offset-4 hover:text-paper">الموقع على الخريطة</a>
                            @endif
                        </span>
                    </li>
                @endif
                @foreach ($phones as $phone)
                    <li class="flex items-center gap-3">
                        <x-glyph name="phone" class="size-4 text-gold" />
                        <a href="{{ tel_link($phone) }}" dir="ltr" class="font-latin text-xl font-semibold tracking-wide text-paper transition-colors hover:text-gold-soft">{{ $phone }}</a>
                    </li>
                @endforeach
                @if ($saudi = setting('whatsapp_sa'))
                    <li class="flex items-center gap-3">
                        <x-glyph name="whatsapp" class="size-4 text-gold" />
                        <a href="{{ wa_link($saudi) }}" target="_blank" rel="noopener" class="transition-colors hover:text-gold-soft">
                            واتساب السعودية <span dir="ltr" class="font-latin text-lg font-semibold text-paper">+{{ phone_intl($saudi) }}</span>
                        </a>
                    </li>
                @endif
                @if ($email = setting('email'))
                    <li class="flex items-center gap-3">
                        <x-glyph name="mail" class="size-4 text-gold" />
                        <a href="mailto:{{ $email }}" class="transition-colors hover:text-gold-soft" dir="ltr">{{ $email }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="border-t border-paper/10">
        <div class="wrap flex flex-col gap-3 py-6 text-sm text-paper/60 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ ar_num(now()->year) }} {{ setting('site_name') }} — جميع الحقوق محفوظة.</p>
            <ul class="flex gap-6">
                @foreach ($footerPages as $page)
                    <li><a href="{{ $page->url }}" class="transition-colors hover:text-gold-soft">{{ $page->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
