@php
    $phones = setting('phones', []);
    $whatsapp = setting('whatsapp_eg', $phones[0] ?? null);
    $saudi = setting('whatsapp_sa');
@endphp
<x-site-layout :seo="$seo">
    <x-page-header title="تواصل معنا" kicker="الاتصال" lead="اتصل بنا أو راسلنا عبر واتساب، أو اترك بياناتك في النموذج وسيتواصل معك المكتب في أقرب وقت." :trail="[['الاتصال', route('contact')]]" />

    <section class="py-16 lg:py-24">
        <div class="wrap grid gap-14 lg:grid-cols-12 lg:gap-16">
            {{-- Contact details --}}
            <div class="lg:col-span-5">
                <dl class="border-t border-ink">
                    @if ($phones)
                        <div class="border-b border-line-strong py-6" data-reveal>
                            <dt class="kicker">الهاتف</dt>
                            <dd class="mt-3 space-y-1">
                                @foreach ($phones as $phone)
                                    <a href="{{ tel_link($phone) }}" dir="ltr" class="block font-latin text-[1.9rem] font-semibold tracking-wide text-ink transition-colors hover:text-gold-deep">{{ $phone }}</a>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    <div class="border-b border-line-strong py-6" data-reveal>
                        <dt class="kicker">واتساب</dt>
                        <dd class="mt-4 flex flex-wrap gap-3">
                            @if ($whatsapp)
                                <a href="{{ wa_link($whatsapp, 'مرحبًا، أرغب في حجز استشارة قانونية.') }}" target="_blank" rel="noopener" class="btn btn-outline !min-h-12"><x-glyph name="whatsapp" class="size-5 text-whatsapp" /> مصر</a>
                            @endif
                            @if ($saudi)
                                <a href="{{ wa_link($saudi, 'مرحبًا، أرغب في حجز استشارة قانونية.') }}" target="_blank" rel="noopener" class="btn btn-outline !min-h-12"><x-glyph name="whatsapp" class="size-5 text-whatsapp" /> السعودية</a>
                            @endif
                        </dd>
                    </div>
                    @if ($email = setting('email'))
                        <div class="border-b border-line-strong py-6" data-reveal>
                            <dt class="kicker">البريد الإلكتروني</dt>
                            <dd class="mt-3"><a href="mailto:{{ $email }}" dir="ltr" class="text-xl text-ink transition-colors hover:text-gold-deep">{{ $email }}</a></dd>
                        </div>
                    @endif
                    @if ($address = setting('address'))
                        <div class="border-b border-line-strong py-6" data-reveal>
                            <dt class="kicker">العنوان</dt>
                            <dd class="mt-3 text-lg leading-loose text-ink">
                                {{ $address }}
                                @if ($map = setting('map_url'))
                                    <a href="{{ $map }}" target="_blank" rel="noopener" class="link-arrow mt-2 flex w-fit text-base">افتح على خرائط جوجل <x-glyph name="external" class="size-4" /></a>
                                @endif
                            </dd>
                        </div>
                    @endif
                    @if ($hours = setting('working_hours'))
                        <div class="border-b border-line-strong py-6" data-reveal>
                            <dt class="kicker">مواعيد العمل</dt>
                            <dd class="mt-3 text-lg text-ink">{{ $hours }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Consultation form --}}
            <div id="consultation-form" class="lg:col-span-7">
                <div class="relative bg-white/60 p-6 shadow-[10px_10px_0_var(--color-paper-3)] ring-1 ring-line-strong sm:p-10" data-reveal>
                    @if (session('consultation_sent'))
                        <div class="py-10 text-center" role="status">
                            <span class="mx-auto flex size-16 items-center justify-center bg-ink text-gold-soft"><x-glyph name="check" class="size-8" /></span>
                            <h2 class="mt-6 text-3xl">تم استلام طلبك</h2>
                            <p class="mx-auto mt-4 max-w-md leading-loose text-ink-3">شكرًا لتواصلك مع مكتب المستشار محمد رجب. سنتواصل معك على رقم الهاتف الذي أرسلته في أقرب وقت.</p>
                            <a href="{{ route('home') }}" class="link-arrow mt-8">العودة إلى الرئيسية <x-glyph name="arrow-left" class="size-4" /></a>
                        </div>
                    @else
                        <p class="kicker">نموذج طلب استشارة</p>
                        <h2 class="mt-3 text-[2rem]">اترك بياناتك ونتواصل معك</h2>

                        @if ($errors->any())
                            <div class="mt-6 border-s-2 border-[#a3271b] bg-[#a3271b]/5 p-4 text-sm text-[#a3271b]" role="alert">
                                من فضلك راجع الحقول المحددة بالأسفل.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" class="mt-8 grid gap-6 sm:grid-cols-2" novalidate>
                            @csrf
                            <div class="absolute -start-[9999px]" aria-hidden="true">
                                <label for="website">اترك هذا الحقل فارغًا</label>
                                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                            </div>

                            <div>
                                <label for="name" class="field-label">الاسم <span class="text-gold-deep">*</span></label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" class="field" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                                @error('name')<p id="name-error" class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="phone" class="field-label">رقم الهاتف / واتساب <span class="text-gold-deep">*</span></label>
                                <input id="phone" name="phone" type="tel" dir="ltr" value="{{ old('phone') }}" required autocomplete="tel" inputmode="tel" placeholder="01xxxxxxxxx" class="field text-end" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
                                @error('phone')<p id="phone-error" class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="field-label">البريد الإلكتروني <span class="text-sm font-normal text-ink-3">(اختياري)</span></label>
                                <input id="email" name="email" type="email" dir="ltr" value="{{ old('email') }}" autocomplete="email" class="field text-end" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                                @error('email')<p id="email-error" class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="service_id" class="field-label">نوع الخدمة <span class="text-sm font-normal text-ink-3">(اختياري)</span></label>
                                <select id="service_id" name="service_id" class="field">
                                    <option value="">— اختر —</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" @selected(old('service_id', request('service')) == $service->id)>{{ $service->title }}</option>
                                    @endforeach
                                </select>
                                @error('service_id')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="message" class="field-label">تفاصيل موضوعك <span class="text-sm font-normal text-ink-3">(اختياري)</span></label>
                                <textarea id="message" name="message" rows="5" class="field" @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                                @error('message')<p id="message-error" class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col gap-4 sm:col-span-2 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm text-ink-3">بياناتك سرية ولا تُستخدم إلا للتواصل معك.</p>
                                <button type="submit" class="btn btn-primary">إرسال الطلب</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($embed = setting('map_embed'))
        <section class="border-t border-line-strong">
            <iframe src="{{ $embed }}" title="موقع المكتب على الخريطة" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="h-[420px] w-full grayscale-[60%]"></iframe>
        </section>
    @endif
</x-site-layout>
