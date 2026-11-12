@props(['faqs'])
<div x-data="{ open: 0 }" {{ $attributes->class(['border-t border-ink']) }}>
    @foreach ($faqs as $faq)
        <div class="border-b border-line-strong">
            <h3 class="font-sans">
                <button type="button" @click="open = open === {{ $loop->index }} ? null : {{ $loop->index }}"
                    :aria-expanded="open === {{ $loop->index }}" aria-controls="faq-{{ $faq->id }}"
                    class="group flex w-full items-start gap-5 py-6 text-start">
                    <span class="mt-1 font-display text-lg font-normal text-gold-deep">{{ ar_num($loop->iteration, 2) }}</span>
                    <span class="flex-1 font-display text-[1.4rem] font-bold leading-relaxed text-ink transition-colors group-hover:text-gold-deep lg:text-[1.6rem]">{{ $faq->question }}</span>
                    <span class="mt-1.5 flex size-9 shrink-0 items-center justify-center border border-line-strong transition-all duration-300 group-hover:border-ink"
                        :class="open === {{ $loop->index }} && 'rotate-45 border-ink bg-ink text-paper'">
                        <x-glyph name="plus" class="size-4" />
                    </span>
                </button>
            </h3>
            <div id="faq-{{ $faq->id }}" x-show="open === {{ $loop->index }}" x-collapse @if (! $loop->first) x-cloak @endif>
                <p class="max-w-3xl pb-7 ps-[2.9rem] text-[1.05rem] leading-loose text-ink-2">{{ $faq->answer }}</p>
            </div>
        </div>
    @endforeach
</div>
