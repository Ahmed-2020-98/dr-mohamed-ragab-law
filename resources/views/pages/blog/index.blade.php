<x-site-layout :seo="$seo">
    <x-page-header :title="$category ? $category->name : 'المدونة القانونية'" kicker="المدونة"
        lead="مقالات توعية قانونية مبسّطة تساعدك على فهم حقوقك قبل أن تحتاج إليها."
        :trail="$category ? [['المدونة', route('blog.index')], [$category->name, route('blog.category', $category)]] : [['المدونة', route('blog.index')]]">
        @if ($categories->isNotEmpty())
            <nav class="mt-10 flex flex-wrap gap-2" aria-label="تصنيفات المقالات">
                <a href="{{ route('blog.index') }}" @class(['inline-flex min-h-11 items-center border px-5 text-sm font-semibold transition-colors', 'border-ink bg-ink text-paper' => ! $category, 'border-line-strong hover:border-ink' => $category])>الكل</a>
                @foreach ($categories as $item)
                    <a href="{{ route('blog.category', $item) }}" @class(['inline-flex min-h-11 items-center border px-5 text-sm font-semibold transition-colors', 'border-ink bg-ink text-paper' => $category?->is($item), 'border-line-strong hover:border-ink' => ! $category?->is($item)])>{{ $item->name }}</a>
                @endforeach
            </nav>
        @endif
    </x-page-header>

    <section class="py-16 lg:py-24">
        <div class="wrap">
            @if ($posts->isEmpty())
                <p class="py-16 text-center font-display text-2xl text-ink-3">لا توجد مقالات منشورة بعد.</p>
            @else
                <div class="grid gap-x-10 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" data-reveal />
                    @endforeach
                </div>
                <div class="mt-16">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>
</x-site-layout>
