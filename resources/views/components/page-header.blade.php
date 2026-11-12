@props(['title', 'lead' => null, 'kicker' => null, 'trail' => []])
<section class="relative overflow-hidden border-b border-line-strong">
    <x-scales class="pointer-events-none absolute -bottom-16 end-[-3rem] hidden size-[26rem] text-gold opacity-[0.16] md:block" />
    <div class="wrap relative pb-14 pt-16 lg:pb-20 lg:pt-24">
        <nav aria-label="مسار التصفح" class="animate-fade text-sm text-ink-3">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-gold-deep">الرئيسية</a></li>
                @foreach ($trail as [$label, $url])
                    <li class="flex items-center gap-2">
                        <span class="diamond !size-1"></span>
                        @if ($loop->last)
                            <span aria-current="page" class="text-ink">{{ $label }}</span>
                        @else
                            <a href="{{ $url }}" class="transition-colors hover:text-gold-deep">{{ $label }}</a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>

        @if ($kicker)
            <p class="kicker mt-8 animate-rise">{{ $kicker }}</p>
        @endif
        <h1 class="mt-5 max-w-4xl animate-rise text-[clamp(2.5rem,6vw,4.75rem)] leading-[1.3] [animation-delay:80ms]">{{ $title }}</h1>
        @if ($lead)
            <p class="mt-6 max-w-2xl animate-rise text-lg leading-loose text-ink-2 [animation-delay:160ms] lg:text-xl lg:leading-loose">{{ $lead }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
