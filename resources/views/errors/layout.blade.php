<x-site-layout :seo="(new \App\Support\Seo($title))->noindex()">
    <section class="relative overflow-hidden">
        <x-scales class="pointer-events-none absolute inset-0 m-auto size-[30rem] text-gold opacity-[0.1]" />
        <div class="wrap relative py-28 text-center lg:py-40">
            <p class="font-latin text-[7rem] font-semibold leading-none text-gold-deep">{{ $code }}</p>
            <h1 class="mt-6 text-[clamp(2rem,5vw,3.5rem)]">{{ $title }}</h1>
            <p class="mx-auto mt-5 max-w-lg text-lg leading-loose text-ink-3">{{ $text }}</p>
            <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
                <a href="{{ route('home') }}" class="btn btn-primary">الصفحة الرئيسية</a>
                <a href="{{ route('services.index') }}" class="btn btn-outline">الخدمات القانونية</a>
            </div>
        </div>
    </section>
</x-site-layout>
