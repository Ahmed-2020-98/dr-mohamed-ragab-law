<x-site-layout :seo="$seo">
    <x-page-header title="الأسئلة الشائعة" kicker="قبل أن تتصل بنا" lead="إجابات عن أكثر الأسئلة التي تصلنا. لم تجد سؤالك؟ تواصل معنا مباشرة." :trail="[['الأسئلة الشائعة', route('faq')]]" />
    <section class="py-16 lg:py-24">
        <div class="wrap max-w-4xl">
            <x-faq-list :faqs="$faqs" />
        </div>
    </section>
    <x-cta-band title="لديك سؤال آخر؟" />
</x-site-layout>
