@extends('layouts.app')

@section('body-class', 'home-services-page')

@section('content')
<section class="home-clean-hero">
    <div class="container-shell home-clean-hero-grid">
        <div class="home-clean-copy" data-reveal>
            <p class="home-clean-eyebrow">{{ $siteSettings['hero_eyebrow'] ?? 'هندسة المساحات الخارجية' }}</p>
            <h1>{{ $siteSettings['hero_title'] ?? 'حلول خارجية مصممة لتدوم' }}</h1>
            <p>{{ $siteSettings['hero_description'] ?? 'ننفذ المظلات والسواتر والبرجولات والهناجر والجلسات بخيارات واضحة تناسب الموقع والاستخدام.' }}</p>
            <div class="home-clean-actions">
                <a class="button button-primary" href="{{ route('quote') }}">اطلب معاينة للموقع</a>
                <a class="button button-outline" href="#all-services">استعرض الخدمات</a>
            </div>
            <ul class="home-clean-points" aria-label="مزايا التنفيذ">
                <li>خبرة ميدانية منذ 1999</li>
                <li>تنفيذ داخل الرياض</li>
                <li>خامات حسب طبيعة الموقع</li>
            </ul>
        </div>

        <div class="home-main-visuals" aria-label="نماذج من خدماتنا الرئيسية">
            @foreach($mainServices->take(5) as $service)
                @php($cover = $service->images->firstWhere('is_cover', true) ?: $service->images->first())
                <a href="{{ route('services.show', $service->slug) }}" @class(['home-main-visual', 'is-primary' => $loop->first]) aria-label="عرض {{ $service->name }}">
                    <x-service-cover :service="$service" :image="$cover" :alt="$service->featured_image_alt ?: $service->name" :loading="$loop->first ? 'eager' : 'lazy'" :fetchpriority="$loop->first ? 'high' : 'auto'" sizes="(max-width: 720px) 82vw, 36vw" />
                    <span>{{ $service->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section id="all-services" class="home-all-services">
    <div class="container-shell">
        <header class="home-section-heading" data-reveal>
            <p>خدماتنا</p>
            <h2>جميع الخدمات بصور واضحة وتفاصيل مختصرة</h2>
            <span>اختر الخدمة لمشاهدة صور أعمالها كاملة وطلب المعاينة.</span>
        </header>
        <div class="home-service-grid">
            @foreach($allServices as $service)
                @php($cover = $service->images->firstWhere('is_cover', true) ?: $service->images->first())
                <article class="home-service-card" data-reveal>
                    <a class="home-service-image" href="{{ route('services.show', $service->slug) }}" aria-label="عرض {{ $service->name }}">
                        <x-service-cover :service="$service" :image="$cover" :alt="$service->featured_image_alt ?: $service->name" loading="lazy" sizes="(max-width: 720px) 100vw, (max-width: 1100px) 50vw, 33vw" />
                    </a>
                    <div class="home-service-content">
                        <small>{{ $service->parent?->name ?: $service->category->name }}</small>
                        <h3><a href="{{ route('services.show', $service->slug) }}">{{ $service->name }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit($service->excerpt, 105) }}</p>
                        <a class="home-service-link" href="{{ route('services.show', $service->slug) }}">شاهد الصور والتفاصيل <span aria-hidden="true">←</span></a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

@if($trustItems->isNotEmpty())
<section class="home-clean-proof" aria-label="مؤشرات موثقة">
    <div class="container-shell">
        @foreach($trustItems as $item)
            <article><strong>{{ $item->value }}</strong><span>{{ $item->label }}</span>@if($item->description)<small>{{ $item->description }}</small>@endif</article>
        @endforeach
    </div>
</section>
@endif

<section class="home-clean-cta">
    <div class="container-shell home-clean-cta-box" data-reveal>
        <div><p>ابدأ من موقعك واحتياجك</p><h2>نساعدك على اختيار الحل المناسب قبل التنفيذ</h2></div>
        <div><a class="button button-primary" href="{{ route('quote') }}">اطلب عرض سعر</a><a class="button button-outline" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">تواصل واتساب</a></div>
    </div>
</section>
@endsection
