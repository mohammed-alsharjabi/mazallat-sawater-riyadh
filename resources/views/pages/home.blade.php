@extends('layouts.app')

@section('body-class', 'home-aura-page')

@php
    $homeServices = $featuredServices->values();
    $primaryService = $homeServices->firstWhere('name', 'مظلات سيارات PVC') ?: $homeServices->first();
    $primaryImage = $primaryService?->images->firstWhere('is_cover', true) ?: $primaryService?->images->first();
    $spotlightService = $homeServices->firstWhere('name', 'مظلات شد إنشائي') ?: $primaryService;
    $spotlightImage = $spotlightService?->images->firstWhere('is_cover', true) ?: $spotlightService?->images->first();
    $showcaseServices = collect([$primaryService])->filter()->concat($homeServices->where('id', '!=', $primaryService?->id))->take(5)->values();
    $workServices = $homeServices->filter(fn ($service) => $service->images->isNotEmpty())->take(4)->values();
    $materialServices = $homeServices->filter(fn ($service) => $service->images->isNotEmpty())->take(3)->values();
@endphp

@section('content')
<section class="aura-home-hero" data-aura-hero>
    <span class="aura-glow aura-glow-one" aria-hidden="true"></span>
    <span class="aura-glow aura-glow-two" aria-hidden="true"></span>
    <div class="aura-home-hero-grid">
        <div class="aura-home-copy" data-aura-copy>
            <p class="aura-eyebrow">{{ $siteSettings['hero_eyebrow'] ?? 'هندسة المساحات الخارجية' }}</p>
            <h1>{{ $siteSettings['hero_title'] ?? 'نصنع الظل الذي يغيّر المكان' }}</h1>
            <p>{{ $siteSettings['hero_description'] ?? 'مظلات وسواتر وبرجولات بتصميم هندسي وتنفيذ يليق بمشروعك.' }}</p>
            <div class="aura-hero-actions">
                <a class="aura-button aura-button-primary" href="{{ route('quote') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h10v18H7zM9 3V1M15 3V1M10 8h4M10 12h4M10 16h4" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>اطلب معاينة لموقعك</a>
                <a class="aura-button aura-button-outline" href="#home-services">استكشف أعمالنا <span aria-hidden="true">←</span></a>
            </div>
            <p class="aura-assurance"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2 8 4v6c0 5-3.4 8.3-8 10-4.6-1.7-8-5-8-10V6l8-4Z" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="m8.5 12 2.2 2.2 4.8-5" fill="none" stroke="currentColor" stroke-width="1.7"/></svg>من التصميم والقياس حتى التنفيذ والتسليم</p>
        </div>

        @if($heroImage)
            <a class="aura-hero-visual" href="{{ route('services.show', $heroService->slug) }}" data-aura-mask aria-label="استكشف {{ $heroService->name }}">
                <x-responsive-image :image="$heroImage" :alt="$heroImage->alt_text ?: $heroService->name" variant="cover" loading="eager" fetchpriority="high" sizes="(max-width: 560px) 100vw, 58vw" />
                <span class="aura-hero-curve" aria-hidden="true"></span>
                <span class="aura-hero-caption"><small>هندسة المساحات الخارجية</small><strong>{{ $heroService->name }}</strong></span>
            </a>
        @endif
    </div>
    <section class="aura-trust-strip" aria-label="مزايا التنفيذ">
        <div class="aura-shell">
            <article><svg viewBox="0 0 32 32" aria-hidden="true"><path d="M5 6h22v20H5zM9 10h14v12H9zM5 15h4M23 15h4" fill="none" stroke="currentColor" stroke-width="1.7"/></svg><span>تصميم حسب الموقع</span></article>
            <article><svg viewBox="0 0 32 32" aria-hidden="true"><path d="m16 3 11 5v8c0 7-5 11-11 14C10 27 5 23 5 16V8l11-5Z" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="m11 16 3 3 7-8" fill="none" stroke="currentColor" stroke-width="1.7"/></svg><span>إشراف متخصص</span></article>
            <article><svg viewBox="0 0 32 32" aria-hidden="true"><circle cx="16" cy="10" r="5" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="M7 29c1-7 4-11 9-11s8 4 9 11M16 18v11" fill="none" stroke="currentColor" stroke-width="1.7"/></svg><span>تنفيذ داخل الرياض</span></article>
        </div>
    </section>
</section>

<section id="home-services" class="aura-section aura-services-section">
    <div class="aura-shell">
        <header class="aura-section-heading" data-home-reveal><p>خدماتنا</p><h2>حلول متكاملة للمنازل والمشاريع</h2><i aria-hidden="true"></i></header>
        <div class="aura-services-grid" data-flip-services>
            @foreach($showcaseServices as $service)
                @php($cover = $service->images->firstWhere('is_cover', true) ?: $service->images->first())
                <article @class(['aura-service-card', 'is-featured' => $loop->first]) data-flip-service data-service-name="{{ $service->name }}">
                    @if($cover)
                        <a class="aura-service-image" href="{{ route('services.show', $service->slug) }}" data-mask-reveal aria-label="عرض {{ $service->name }}">
                            <x-responsive-image :image="$cover" :alt="$cover->alt_text ?: $service->name" variant="thumbnail" sizes="(max-width: 560px) 100vw, 48vw" />
                        </a>
                    @endif
                    <div class="aura-service-copy">
                        <p>{{ $service->category->name }}</p>
                        <h3><a href="{{ route('services.show', $service->slug) }}">{{ $service->name }}</a></h3>
                        <span>{{ \Illuminate\Support\Str::limit($service->excerpt, 88) }}</span>
                        <div class="aura-service-links"><a href="{{ route('services.show', $service->slug) }}">عرض الخدمة</a><button type="button" data-feature-service aria-label="إبراز {{ $service->name }}"><b aria-hidden="true">←</b></button></div>
                    </div>
                </article>
            @endforeach
        </div>
        <a class="aura-inline-link" href="{{ route('services.index') }}">استعرض جميع الخدمات <span aria-hidden="true">←</span></a>
    </div>
</section>

@if($workServices->isNotEmpty())
<section class="aura-section aura-work-section">
    <div class="aura-shell">
        <header class="aura-centered-heading" data-home-reveal><h2>من أعمالنا في الرياض</h2><i aria-hidden="true"></i></header>
        <div class="aura-work-grid" data-home-stagger>
            @foreach($workServices as $service)
                @php($workImage = $service->images->firstWhere('is_cover', true) ?: $service->images->first())
                @if($workImage)
                    <a href="{{ route('services.show', $service->slug) }}" data-mask-reveal>
                        <x-responsive-image :image="$workImage" :alt="$workImage->alt_text ?: $service->name" variant="thumbnail" sizes="(max-width: 560px) 50vw, 46vw" />
                        <strong>{{ $service->name }}</strong>
                    </a>
                @endif
            @endforeach
        </div>
        <a class="aura-button aura-button-outline aura-work-button" href="{{ route('projects.index') }}">شاهد جميع الأعمال <span aria-hidden="true">←</span></a>
    </div>
</section>
@endif

<section class="aura-section aura-benefits-section">
    <div class="aura-shell aura-benefits-panel" data-home-reveal>
        <header class="aura-centered-heading aura-heading-light"><h2>لماذا يختارنا عملاؤنا؟</h2><i aria-hidden="true"></i></header>
        <div class="aura-benefits-grid" data-home-stagger>
            <article><svg viewBox="0 0 40 40" aria-hidden="true"><path d="M8 34V13l12-7 12 7v21M14 34V22h12v12M5 34h30" fill="none" stroke="currentColor" stroke-width="1.8"/></svg><h3>تصميم يناسب موقعك</h3><p>حلول مدروسة تتوافق مع أجواء ومساحة الموقع.</p></article>
            <article><svg viewBox="0 0 40 40" aria-hidden="true"><path d="M7 33V12h26v21M12 12 17 6h6l5 6M12 20h16M12 27h16" fill="none" stroke="currentColor" stroke-width="1.8"/></svg><h3>تنفيذ بإشراف متخصص</h3><p>فريق يراجع التفاصيل والجودة في كل مرحلة.</p></article>
            <article><svg viewBox="0 0 40 40" aria-hidden="true"><path d="M20 4v5M8.5 8.5l3.5 3.5M31.5 8.5 28 12M5 20h5M30 20h5M13 31h14M15 27h10c0-5 4-6 4-12a9 9 0 0 0-18 0c0 6 4 7 4 12Z" fill="none" stroke="currentColor" stroke-width="1.8"/></svg><h3>خامات ملائمة للأجواء</h3><p>خيارات عملية تتحمل حرارة الشمس والأمطار.</p></article>
            <article><svg viewBox="0 0 40 40" aria-hidden="true"><path d="M11 31h18M14 31v-7h12v7M9 20h22M12 20V9h16v11M16 9V5h8v4" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="20" cy="15" r="2" fill="currentColor"/></svg><h3>وضوح في التفاصيل</h3><p>تواصل مستمر ومتابعة واضحة حتى التسليم.</p></article>
        </div>
    </div>
</section>

@if($trustItems->isNotEmpty())
<section class="aura-proof-numbers" aria-label="مؤشرات موثقة"><div class="aura-shell">@foreach($trustItems as $item)<article data-home-reveal><strong>{{ $item->value }}</strong><span>{{ $item->label }}</span>@if($item->description)<small>{{ $item->description }}</small>@endif</article>@endforeach</div></section>
@endif

<section class="aura-section aura-process-section">
    <div class="aura-shell">
        <header class="aura-centered-heading" data-home-reveal><h2>من الفكرة إلى التنفيذ</h2><i aria-hidden="true"></i></header>
        <ol class="aura-process-grid" data-home-stagger>
            <li><span>1</span><svg viewBox="0 0 44 44" aria-hidden="true"><path d="M12 30c-5-7-2-18 10-18s15 11 10 18M17 31v4h10v-4M9 17H5M39 17h-4M10 7 7 4M34 7l3-3" fill="none" stroke="currentColor" stroke-width="1.8"/></svg><h3>نسمع احتياجك</h3><p>نفهم متطلباتك ونقترح أفضل الحلول.</p></li>
            <li><span>2</span><svg viewBox="0 0 44 44" aria-hidden="true"><path d="m8 32 23-23 5 5-23 23H8v-5ZM27 13l5 5M7 19h10M12 14v10" fill="none" stroke="currentColor" stroke-width="1.8"/></svg><h3>نعاين ونقيس</h3><p>زيارة الموقع وأخذ القياسات بدقة.</p></li>
            <li><span>3</span><svg viewBox="0 0 44 44" aria-hidden="true"><path d="M8 8h28v28H8zM13 13h18v18H13zM8 18h5M31 18h5M18 8v5M18 31v5" fill="none" stroke="currentColor" stroke-width="1.8"/></svg><h3>نصمم وننفذ</h3><p>تصميم هندسي وتنفيذ بالخامات المعتمدة.</p></li>
            <li><span>4</span><svg viewBox="0 0 44 44" aria-hidden="true"><circle cx="22" cy="22" r="15" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="m14 22 5 5 11-12M22 3v5M22 36v5" fill="none" stroke="currentColor" stroke-width="1.8"/></svg><h3>نسلمك المشروع</h3><p>تسليم واضح ومتابعة تفاصيل التنفيذ.</p></li>
        </ol>
    </div>
</section>

@if($spotlightImage)
<section class="aura-section aura-spotlight-section">
    <div class="aura-shell">
        <a class="aura-spotlight" href="{{ route('services.show', $spotlightService->slug) }}" data-mask-reveal>
            <x-responsive-image :image="$spotlightImage" :alt="$spotlightImage->alt_text ?: $spotlightService->name" variant="cover" sizes="(max-width: 560px) 100vw, 92vw" />
            <span><strong>تصميم يغيّر<br>شكل المكان</strong><b>اكتشف {{ $spotlightService->name }} <i aria-hidden="true">←</i></b></span>
        </a>
    </div>
</section>
@endif

@if($materialServices->isNotEmpty())
<section class="aura-section aura-materials-section">
    <div class="aura-shell">
        <header class="aura-centered-heading" data-home-reveal><h2>الخامات والتفاصيل</h2><i aria-hidden="true"></i></header>
        <div class="aura-materials-grid" data-home-stagger>
            @foreach($materialServices as $service)
                @php($materialImage = $service->images->firstWhere('is_cover', true) ?: $service->images->first())
                <a href="{{ route('services.show', $service->slug) }}">
                    @if($materialImage)<x-responsive-image :image="$materialImage" :alt="$materialImage->alt_text ?: $service->name" variant="thumbnail" sizes="(max-width: 560px) 33vw, 30vw" />@endif
                    <span><strong>{{ $service->name }}</strong><small>{{ $service->materials->pluck('name')->take(2)->implode(' · ') ?: 'خامات مختارة حسب طبيعة الموقع' }}</small></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="aura-contact-strip">
    <div class="aura-shell aura-contact-box" data-home-reveal>
        @if($primaryImage)<a class="aura-contact-image" href="{{ route('services.show', $primaryService->slug) }}"><x-responsive-image :image="$primaryImage" :alt="$primaryImage->alt_text ?: $primaryService->name" variant="thumbnail" sizes="(max-width: 560px) 100vw, 28vw" /></a>@endif
        <div class="aura-contact-copy"><h2>ابدأ مشروعك معنا</h2><p>أرسل صورة الموقع أو المقاسات وسنقترح عليك الحل الأنسب.</p><form method="get" action="{{ route('quote') }}"><label><span class="sr-only">نوع الخدمة</span><select name="service"><option value="">نوع الخدمة</option>@foreach($homeServices as $service)<option value="{{ $service->id }}">{{ $service->name }}</option>@endforeach</select></label><label><span class="sr-only">الحي في الرياض</span><input name="area" placeholder="حيّك في الرياض"></label><button class="aura-button aura-button-primary" type="submit">اطلب عرض سعر</button><a class="aura-button aura-whatsapp-button" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">تواصل واتساب</a></form></div>
        <nav class="aura-quick-links" aria-label="روابط سريعة"><strong>روابط سريعة</strong><a href="{{ route('services.index') }}">الخدمات</a><a href="{{ route('projects.index') }}">أعمالنا</a><a href="{{ route('about') }}">من نحن</a><a href="{{ route('contact') }}">تواصل معنا</a></nav>
    </div>
</section>
@endsection

@push('scripts')
    @vite('resources/js/home.js')
@endpush
