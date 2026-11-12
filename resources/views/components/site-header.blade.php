@php
    $links = array_filter([
        ['الرئيسية', route('home'), request()->routeIs('home')],
        ['من نحن', route('about'), request()->routeIs('about')],
        ['الخدمات', route('services.index'), request()->routeIs('services.*')],
        ['الفيديوهات', route('videos'), request()->routeIs('videos')],
        ['معرض الصور', route('gallery'), request()->routeIs('gallery')],
        $hasPosts ? ['المدونة', route('blog.index'), request()->routeIs('blog.*')] : null,
        ['الاتصال', route('contact'), request()->routeIs('contact')],
    ]);
    $phone = collect(setting('phones', []))->first();
    $whatsapp = setting('whatsapp_eg', $phone);
    $bookingUrl = wa_link($whatsapp, 'مرحبًا، أرغب في حجز استشارة قانونية.');
@endphp

{{-- Utility strip --}}
<div class="hidden bg-navy-3 text-[0.8125rem] text-paper/80 lg:block">
    <div class="wrap flex h-10 items-center justify-between gap-6">
        <p class="flex items-center gap-3">
            @foreach (setting('jurisdictions', []) as $country)
                @unless ($loop->first)<span class="diamond !size-1 opacity-70"></span>@endunless
                <span>{{ $country }}</span>
            @endforeach
        </p>
        <div class="flex items-center gap-6">
            @if ($address = setting('address'))
                <span class="flex items-center gap-2"><x-glyph name="map-pin" class="size-3.5 text-gold-soft" />{{ $address }}</span>
            @endif
            @if ($phone)
                <a href="{{ tel_link($phone) }}" class="flex items-center gap-2 transition-colors hover:text-gold-soft" dir="ltr">
                    <span class="font-latin text-base font-semibold tracking-wide">{{ $phone }}</span>
                    <x-glyph name="phone" class="size-3.5 text-gold-soft" />
                </a>
            @endif
        </div>
    </div>
</div>

<header x-data="siteHeader" :class="scrolled && 'shadow-[0_1px_0_var(--color-line-strong)]'"
    class="sticky top-0 z-50 bg-paper/92 backdrop-blur-md transition-shadow duration-300">
    <div class="wrap flex h-[4.5rem] items-center justify-between gap-6 lg:h-[5.25rem]">
        {{-- Logo on a navy plaque that hangs below the bar --}}
        <a href="{{ route('home') }}" aria-label="{{ setting('site_name') }} — الصفحة الرئيسية"
            :class="scrolled ? 'lg:h-[5.75rem]' : 'lg:h-[7.25rem]'"
            class="relative z-10 flex h-[5.5rem] shrink-0 items-center self-start bg-navy px-3.5 pb-1 shadow-[0_10px_30px_-12px_rgb(14_27_46/0.55)] transition-[height] duration-500 ease-out-expo after:absolute after:inset-x-0 after:bottom-0 after:h-[3px] after:bg-gold lg:px-5">
            <img src="{{ media_url(setting('logo')) }}" alt="{{ setting('site_name') }}" width="640" height="458"
                class="h-[4.25rem] w-auto lg:h-[5.25rem]" fetchpriority="high">
        </a>

        <nav class="hidden lg:block" aria-label="القائمة الرئيسية">
            <ul class="flex items-center gap-1">
                @foreach ($links as [$label, $url, $active])
                    <li>
                        <a href="{{ $url }}" @if ($active) aria-current="page" @endif
                            class="group relative flex h-11 items-center px-3.5 text-[0.97rem] font-medium transition-colors xl:px-4 {{ $active ? 'text-ink' : 'text-ink-2 hover:text-ink' }}">
                            {{ $label }}
                            <span class="absolute inset-x-3.5 bottom-1 h-px origin-right bg-gold transition-transform duration-500 ease-out-expo {{ $active ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ $bookingUrl }}" target="_blank" rel="noopener" class="btn btn-primary hidden !min-h-12 !px-6 !text-[0.95rem] sm:inline-flex">
                احجز استشارة
            </a>
            <button type="button" @click="menuOpen = true" :aria-expanded="menuOpen" aria-controls="mobile-menu"
                class="flex size-12 items-center justify-center border border-line-strong text-ink transition-colors hover:bg-ink hover:text-paper lg:hidden">
                <span class="sr-only">فتح القائمة</span>
                <x-glyph name="menu" class="size-6" />
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" x-cloak x-show="menuOpen" x-transition.opacity.duration.300ms @keydown.escape.window="menuOpen = false"
        class="fixed inset-0 z-[70] flex h-dvh flex-col overflow-y-auto bg-navy-3 text-paper lg:hidden" role="dialog" aria-modal="true" aria-label="القائمة">
        <div class="wrap flex h-[4.5rem] shrink-0 items-center justify-between">
            <img src="{{ media_url(setting('logo')) }}" alt="" width="640" height="458" class="h-14 w-auto">
            <button type="button" @click="menuOpen = false" class="flex size-12 items-center justify-center border border-paper/30 transition-colors hover:bg-paper hover:text-ink">
                <span class="sr-only">إغلاق القائمة</span>
                <x-glyph name="x" class="size-6" />
            </button>
        </div>

        <nav class="wrap mt-4 flex-1" aria-label="قائمة الجوال">
            <ul>
                @foreach ($links as [$label, $url, $active])
                    <li class="border-b border-paper/10">
                        <a href="{{ $url }}" @if ($active) aria-current="page" @endif
                            class="flex items-baseline justify-between py-4 font-display text-[1.75rem] font-bold {{ $active ? 'text-gold-soft' : 'text-paper' }}">
                            {{ $label }}
                            <span class="font-display text-base font-normal text-gold-soft/70">{{ ar_num($loop->iteration, 2) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="wrap space-y-3 py-8">
            <a href="{{ $bookingUrl }}" target="_blank" rel="noopener" class="btn btn-gold w-full">
                <x-glyph name="whatsapp" class="size-5" /> احجز استشارة عبر واتساب
            </a>
            @if ($phone)
                <a href="{{ tel_link($phone) }}" class="btn btn-outline-light w-full">
                    <x-glyph name="phone" class="size-5" /> <span dir="ltr" class="font-latin text-xl font-semibold">{{ $phone }}</span>
                </a>
            @endif
        </div>
    </div>
</header>
