import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

const lockScroll = (locked) => document.documentElement.classList.toggle('overflow-hidden', locked);

/* Sticky header + mobile menu */
Alpine.data('siteHeader', () => ({
    menuOpen: false,
    scrolled: false,

    init() {
        const onScroll = () => (this.scrolled = window.scrollY > 24);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        this.$watch('menuOpen', (open) => lockScroll(open));
    },
}));

/* Gallery lightbox: arrow keys navigate, Escape closes, focus returns to the thumbnail. */
Alpine.data('lightbox', (items = []) => ({
    items,
    index: null,
    opener: null,

    get current() {
        return this.index === null ? null : this.items[this.index];
    },

    open(index, event) {
        this.opener = event?.currentTarget ?? null;
        this.index = index;
        lockScroll(true);
        this.$nextTick(() => this.$refs.close?.focus());
    },

    close() {
        this.index = null;
        lockScroll(false);
        this.opener?.focus();
    },

    step(delta) {
        if (this.index === null) return;
        this.index = (this.index + delta + this.items.length) % this.items.length;
    },
}));

/* Video player modal shared by every video card on the page. */
Alpine.data('videoPlayer', () => ({
    video: null,
    opener: null,

    play(video, event) {
        this.opener = event?.currentTarget ?? null;
        this.video = video;
        lockScroll(true);
        this.$nextTick(() => this.$refs.close?.focus());
    },

    stop() {
        this.$refs.player?.pause();
        this.video = null;
        lockScroll(false);
        this.opener?.focus();
    },
}));

window.Alpine = Alpine;
Alpine.start();

/* Scroll reveal */
const revealTargets = document.querySelectorAll('[data-reveal]');

if ('IntersectionObserver' in window && revealTargets.length) {
    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-in');
                    observer.unobserve(entry.target);
                }
            }
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.08 },
    );

    revealTargets.forEach((el) => observer.observe(el));
} else {
    revealTargets.forEach((el) => el.classList.add('is-in'));
}
