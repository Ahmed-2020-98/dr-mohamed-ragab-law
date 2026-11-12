<x-site-layout :seo="$seo">
    <article>
        <x-page-header :title="$post->title" :kicker="$post->category?->name ?? 'المدونة'" :lead="$post->excerpt"
            :trail="[['المدونة', route('blog.index')], [$post->title, $post->url]]">
            <p class="mt-8 flex flex-wrap items-center gap-3 text-sm text-ink-3">
                <span>بقلم <span class="font-semibold text-ink">المستشار محمد رجب</span></span>
                <span class="diamond !size-1"></span>
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('j F Y') }}</time>
                <span class="diamond !size-1"></span>
                <span>{{ ar_num($post->reading_minutes) }} دقائق قراءة</span>
            </p>
        </x-page-header>

        <div class="wrap py-16 lg:py-24">
            <div class="mx-auto max-w-3xl">
                @if ($image = $post->mediaUrl())
                    <figure class="plate mb-14 aspect-[16/9]">
                        <img src="{{ $image }}" alt="{{ $post->title }}" width="1200" height="675">
                    </figure>
                @endif
                <div class="prose-ar">{!! $post->body !!}</div>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="border-t border-line-strong py-16 lg:py-24">
            <div class="wrap">
                <h2 class="text-3xl">اقرأ أيضًا</h2>
                <div class="mt-10 grid gap-10 md:grid-cols-3">
                    @foreach ($related as $item)
                        <x-post-card :post="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />
</x-site-layout>
