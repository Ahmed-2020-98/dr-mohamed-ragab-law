@php
    $phone = collect(setting('phones', []))->first();
    $whatsapp = setting('whatsapp_eg', $phone);
    $waUrl = wa_link($whatsapp, 'مرحبًا، أرغب في حجز استشارة قانونية.');
@endphp
@if ($whatsapp)
    {{-- Desktop: floating WhatsApp button --}}
    <a href="{{ $waUrl }}" target="_blank" rel="noopener" aria-label="تواصل معنا عبر واتساب"
        class="group fixed bottom-6 end-6 z-40 hidden h-14 items-center gap-3 bg-whatsapp pe-5 ps-4 text-white shadow-[0_14px_34px_-10px_rgb(18_140_74/0.7)] transition-transform duration-300 ease-out-expo hover:-translate-y-1 lg:flex">
        <x-glyph name="whatsapp" class="size-6" />
        <span class="text-[0.95rem] font-semibold">استشارة عبر واتساب</span>
    </a>

    {{-- Mobile: call + WhatsApp bar --}}
    <div class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-2 border-t border-paper/15 bg-navy-3 text-paper lg:hidden">
        @if ($phone)
            <a href="{{ tel_link($phone) }}" class="flex h-16 items-center justify-center gap-2.5 font-semibold">
                <x-glyph name="phone" class="size-5 text-gold-soft" /> اتصل الآن
            </a>
        @endif
        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="flex h-16 items-center justify-center gap-2.5 bg-whatsapp font-semibold text-white {{ $phone ? '' : 'col-span-2' }}">
            <x-glyph name="whatsapp" class="size-5" /> واتساب
        </a>
    </div>
@endif
