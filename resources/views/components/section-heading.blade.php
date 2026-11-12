@props(['kicker' => null, 'title', 'lead' => null, 'light' => false, 'center' => false, 'tag' => 'h2'])
<div {{ $attributes->class(['max-w-3xl', 'mx-auto text-center' => $center]) }} data-reveal>
    @if ($kicker)
        <p @class(['kicker', 'kicker-light' => $light])>{{ $kicker }}</p>
    @endif
    <{{ $tag }} @class(['mt-4 text-[clamp(2.1rem,4.6vw,3.6rem)] leading-[1.3]', 'text-paper' => $light, 'text-ink' => ! $light])>{{ $title }}</{{ $tag }}>
    @if ($lead)
        <p @class(['mt-5 text-lg leading-loose', 'text-paper/75' => $light, 'text-ink-3' => ! $light])>{{ $lead }}</p>
    @endif
</div>
