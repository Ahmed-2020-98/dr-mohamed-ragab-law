{{-- Place inside an element with x-data="videoPlayer". --}}
<div x-cloak x-show="video" x-transition.opacity.duration.250ms @keydown.escape.window="video && stop()"
    class="fixed inset-0 z-[80] flex items-center justify-center bg-navy-3/95 p-4" role="dialog" aria-modal="true" :aria-label="video?.title">
    <button type="button" x-ref="close" @click="stop()"
        class="absolute end-4 top-4 z-10 flex size-12 items-center justify-center border border-paper/40 text-paper transition-colors hover:bg-paper hover:text-ink">
        <span class="sr-only">إغلاق الفيديو</span>
        <x-glyph name="x" class="size-6" />
    </button>

    <div class="flex max-h-full w-full max-w-4xl flex-col items-center" @click.outside="stop()">
        <template x-if="video && video.src">
            <video x-ref="player" :src="video.src" controls autoplay playsinline class="max-h-[80dvh] max-w-full bg-black"></video>
        </template>
        <template x-if="video && !video.src && video.embed">
            <iframe :src="video.embed + '?autoplay=1&rel=0'" :title="video.title" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen
                class="aspect-video w-full bg-black"></iframe>
        </template>
        <p class="mt-4 text-center font-display text-xl text-paper" x-text="video?.title"></p>
    </div>
</div>
