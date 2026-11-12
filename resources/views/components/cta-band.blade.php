@props(['title' => null, 'text' => null])
@php
    $phone = collect(setting('phones', []))->first();
    $egypt = setting('whatsapp_eg', $phone);
    $saudi = setting('whatsapp_sa');
    $message = 'مرحبًا، أرغب في حجز استشارة قانونية.';
@endphp
<section class="relative overflow-hidden bg-navy text-paper">
    <div class="pointer-events-none absolute inset-0 opacity-70 [background:radial-gradient(60rem_30rem_at_85%_-10%,rgb(176_141_63/0.22),transparent_60%)]"></div>
    <x-scales class="pointer-events-none absolute -bottom-24 start-[-4rem] size-[30rem] text-gold opacity-[0.12]" />

    <div class="wrap relative py-20 lg:py-28">
        <div class="border border-gold/40 px-6 py-12 text-center sm:px-12 lg:py-16" data-reveal>
            <p class="kicker kicker-light justify-center">احجز استشارتك</p>
            <h2 class="mx-auto mt-5 max-w-3xl text-[clamp(2rem,4.4vw,3.4rem)] leading-[1.45] text-paper">
                {{ $title ?? 'تواصل معنا اليوم لتحصل على استشارة قانونية متخصصة' }}
            </h2>
            <p class="mx-auto mt-5 max-w-xl text-lg leading-loose text-paper/75">
                {{ $text ?? 'اشرح لنا موضوعك، ونوضّح لك موقفك القانوني والخطوات المتاحة أمامك.' }}
            </p>

            <div class="mt-10 flex flex-col items-stretch justify-center gap-4 sm:flex-row sm:items-center">
                @if ($egypt)
                    <a href="{{ wa_link($egypt, $message) }}" target="_blank" rel="noopener" class="btn btn-gold">
                        <x-glyph name="whatsapp" class="size-5" /> واتساب — مصر
                    </a>
                @endif
                @if ($saudi)
                    <a href="{{ wa_link($saudi, $message) }}" target="_blank" rel="noopener" class="btn btn-outline-light">
                        <x-glyph name="whatsapp" class="size-5" /> واتساب — السعودية
                    </a>
                @endif
                @if ($phone)
                    <a href="{{ tel_link($phone) }}" class="btn btn-outline-light">
                        <x-glyph name="phone" class="size-5" /> <span dir="ltr" class="font-latin text-xl font-semibold tracking-wide">{{ $phone }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
