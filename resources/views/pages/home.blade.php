@extends('layouts.app')

@section('content')
<section class="home-hero">
    <div class="container-shell home-hero-grid">
        <div class="home-hero-copy" data-reveal>
            <p class="eyebrow">مظلات وسواتر مصممة لمناخ الرياض</p>
            <h1>{{ $siteSettings['hero_title'] ?? 'نصمم الظل كجزء من المكان' }}</h1>
            <p>{{ $siteSettings['hero_description'] ?? 'نعاين المساحة ونحدد الخامة وطريقة التثبيت المناسبة قبل التنفيذ، مع عرض واضح يمكن مراجعته.' }}</p>
            <div class="hero-actions"><a class="button button-primary" href="{{ route('quote') }}">ابدأ مشروعك</a><a class="button button-outline" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">تواصل واتساب</a></div>
            <dl class="hero-facts"><div><dt>الموقع الأساسي</dt><dd>{{ $siteSettings['primary_service_area'] ?? 'وسط الرياض' }}</dd></div><div><dt>الخدمات المعروضة</dt><dd>{{ $featuredServices->count() }} خدمات موثقة</dd></div></dl>
        </div>
        @if($heroImage)
            <a class="home-hero-media" href="{{ route('services.show', $heroService->slug) }}" data-reveal aria-label="عرض {{ $heroService->name }}">
                <x-responsive-image :image="$heroImage" :alt="$heroImage->alt_text ?: $heroService->name" variant="cover" loading="eager" fetchpriority="high" sizes="(max-width: 800px) 100vw, 58vw" />
                <span><small>{{ $heroService->category->name }}</small><strong>{{ $heroService->name }}</strong><b>عرض الخدمة ←</b></span>
            </a>
        @endif
    </div>
</section>

<section id="services" class="section-block services-section">
    <div class="container-shell">
        <header class="section-heading" data-reveal><div><p class="eyebrow">خدماتنا</p><h2>اختر الخدمة وشاهد أعمالها الحقيقية</h2></div><p>كل بطاقة مرتبطة بصفحة مستقلة تبدأ بنبذة واضحة ثم معرض صور الخدمة فقط.</p></header>
        <div class="services-card-grid">
            @foreach($featuredServices as $service)
                @php($cover = $service->images->firstWhere('is_cover', true) ?: $service->images->first())
                <article class="service-feature-card" data-reveal>
                    @if($cover)<a class="service-card-media" href="{{ route('services.show', $service->slug) }}"><x-responsive-image :image="$cover" :alt="$cover->alt_text ?: $service->name" variant="thumbnail" sizes="(max-width: 700px) 100vw, (max-width: 1050px) 50vw, 33vw" /></a>@endif
                    <div><p class="card-kicker">{{ $service->category->name }} · {{ $service->images_count }} صورة</p><h3><a href="{{ route('services.show', $service->slug) }}">{{ $service->name }}</a></h3><p>{{ $service->excerpt }}</p><a class="text-link" href="{{ route('services.show', $service->slug) }}">نبذة وصور الأعمال ←</a></div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-block steps-section">
    <div class="container-shell"><header class="section-heading" data-reveal><div><p class="eyebrow">طريقة العمل</p><h2>من صور الموقع إلى بدء التنفيذ</h2></div></header><ol class="work-steps"><li data-reveal><span>01</span><h3>أرسل الصور</h3><p>شارك صور الموقع والمساحة التقريبية.</p></li><li data-reveal><span>02</span><h3>المعاينة والقياس</h3><p>نراجع الأبعاد وطريقة التثبيت والاستخدام.</p></li><li data-reveal><span>03</span><h3>اعتماد العرض</h3><p>تُحدد الخامات والتفاصيل قبل بدء العمل.</p></li><li data-reveal><span>04</span><h3>التنفيذ والتسليم</h3><p>يتم التنفيذ وفق المواصفات المعتمدة.</p></li></ol></div>
</section>

@if($trustItems->isNotEmpty())
<section class="proof-strip"><div class="container-shell">@foreach($trustItems as $item)<article data-reveal><strong>{{ $item->value }}</strong><span>{{ $item->label }}</span>@if($item->description)<small>{{ $item->description }}</small>@endif</article>@endforeach</div></section>
@endif

@if($projects->isNotEmpty())
<section class="section-block"><div class="container-shell"><header class="section-heading" data-reveal><div><p class="eyebrow">مشاريع موثقة</p><h2>أعمال داخل الرياض</h2></div><a class="text-link" href="{{ route('projects.index') }}">كل المشاريع ←</a></header><div class="cards-grid">@foreach($projects->take(3) as $project)<x-project-card :project="$project" />@endforeach</div></div></section>
@endif

<section class="section-block articles-section"><div class="container-shell"><header class="section-heading" data-reveal><div><p class="eyebrow">دليل البناء</p><h2>مقالات تساعدك قبل الاختيار</h2></div><a class="text-link" href="{{ route('guide.index') }}">كل المقالات ←</a></header><div class="cards-grid">@foreach($articles as $article)<x-article-card :article="$article" />@endforeach</div></div></section>

<section class="quote-strip" data-reveal><div class="container-shell"><div><p class="eyebrow">ابدأ بخطوة واضحة</p><h2>أرسل صور موقعك واطلب معاينة</h2><p>اذكر الخدمة والمنطقة والمساحة التقريبية وسنتواصل معك بالطريقة التي تختارها.</p></div><div><a class="button button-primary" href="{{ route('quote') }}">طلب عرض سعر</a><a class="button button-outline" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a></div></div></section>
@endsection
