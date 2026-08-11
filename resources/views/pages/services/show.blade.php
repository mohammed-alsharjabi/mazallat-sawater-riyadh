@extends('layouts.app')

@php
    $galleryImages = $service->images->sortByDesc('is_cover')->sortBy('sort_order')->values();
    $heroImage = $service->images->firstWhere('is_cover', true) ?: $galleryImages->first();
    $lines = fn ($value) => collect(preg_split('/\R/u', (string) $value))->map('trim')->filter()->values();
    $types = $lines($service->types);
    $useCases = $lines($service->use_cases);
    $materialsDetails = $lines($service->materials_details);
    $advantages = $lines($service->advantages);
    $disadvantages = $lines($service->disadvantages);
    $priceFactors = $lines($service->price_factors);
    $installationSteps = $lines($service->installation_steps);
    $selectionTips = $lines($service->selection_tips);
    $serviceWhatsapp = $siteSettings['whatsapp_url'].'?text='.rawurlencode('مرحبًا، أود طلب معاينة لخدمة '.$service->name.' في الرياض: '.route('services.show', $service->slug));
@endphp

@section('content')
<section class="service-hero">
    <div class="container-shell">
        <nav class="breadcrumbs" aria-label="مسار الصفحة"><a href="{{ route('home') }}">الرئيسية</a><span>←</span><a href="{{ route('services.index') }}">الخدمات</a><span>←</span><b aria-current="page">{{ $service->name }}</b></nav>
        <div class="service-hero-grid">
            <div class="service-hero-copy" data-reveal>
                <p class="eyebrow">{{ $service->category->name }}</p>
                <h1>{{ $service->name }} في الرياض</h1>
                <p>{{ $service->excerpt }}</p>
                <div class="hero-actions"><a class="button button-primary" href="{{ route('quote', ['service' => $service->id]) }}">اطلب عرض سعر</a><a class="button button-whatsapp" href="{{ $serviceWhatsapp }}" target="_blank" rel="noopener">واتساب</a></div>
                <dl class="service-summary"><div><dt>المعرض</dt><dd>{{ $galleryImages->count() }} صورة حقيقية</dd></div><div><dt>نطاق العمل</dt><dd>{{ $siteSettings['city'] }}</dd></div><div><dt>التصنيف</dt><dd>{{ $service->category->name }}</dd></div></dl>
            </div>
            @if($heroImage)<button class="service-hero-media" type="button" data-lightbox-item data-lightbox-src="{{ asset('storage/'.(($heroImage->variant('gallery')['path'] ?? null) ?: $heroImage->optimized_path)) }}" data-lightbox-alt="{{ $heroImage->alt_text }}" aria-label="تكبير صورة {{ $service->name }}"><x-responsive-image :image="$heroImage" :alt="$heroImage->alt_text ?: $service->name" variant="cover" loading="eager" fetchpriority="high" sizes="(max-width: 800px) 100vw, 56vw" /><span>تكبير الصورة</span></button>@endif
        </div>
    </div>
</section>

<nav class="service-anchor-nav" aria-label="أقسام الخدمة"><div class="container-shell"><a href="#works">صور الأعمال</a><a href="#about">عن الخدمة</a><a href="#specifications">الخامات والمواصفات</a><a href="#related">خدمات مرتبطة</a><a href="#request">طلب معاينة</a></div></nav>

@if($galleryImages->isNotEmpty())
<section id="works" class="section-block gallery-section">
    <div class="container-shell">
        <header class="section-heading" data-reveal><div><p class="eyebrow">أعمال هذه الخدمة فقط</p><h2>معرض {{ $service->name }}</h2></div><p>{{ $galleryImages->count() }} صورة مرتبطة بالخدمة ومحفوظة بالترتيب نفسه في لوحة التحكم.</p></header>
        <div class="service-gallery">
            @foreach($galleryImages as $image)
                @php($large = $image->variant('gallery')['path'] ?? $image->optimized_path)
                <figure data-reveal><button type="button" data-lightbox-item data-lightbox-src="{{ asset('storage/'.$large) }}" data-lightbox-alt="{{ $image->alt_text }}"><x-responsive-image :image="$image" :alt="$image->alt_text ?: $service->name" variant="thumbnail" sizes="(max-width: 600px) 50vw, (max-width: 1000px) 33vw, 25vw" /><span aria-hidden="true">↗</span></button><figcaption>{{ $image->caption ?: $image->title }}</figcaption></figure>
            @endforeach
        </div>
    </div>
</section>
@endif

<section id="about" class="section-block service-about">
    <div class="container-shell service-content-grid">
        <article data-reveal><p class="eyebrow">نبذة عن الخدمة</p><h2>ما الذي يميز {{ $service->name }}؟</h2><div class="richtext">{!! nl2br(e($service->content)) !!}</div></article>
        <aside data-reveal><p class="eyebrow">طلب سريع</p><h2>هل لديك موقع يحتاج معاينة؟</h2><p>{{ $service->cta ?: 'أرسل صور الموقع والمساحة التقريبية لنفهم متطلبات المشروع قبل التواصل.' }}</p><a class="button button-primary" href="{{ route('quote', ['service' => $service->id]) }}">اطلب معاينة</a><a class="button button-outline" href="{{ $serviceWhatsapp }}" target="_blank" rel="noopener">أرسل عبر واتساب</a></aside>
    </div>
</section>

<section id="specifications" class="section-block specifications-section">
    <div class="container-shell specifications-grid">
        @foreach([['الأنواع المتاحة',$types],['الحالات المناسبة',$useCases],['الخامات والمواصفات',$materialsDetails],['عوامل تحديد السعر',$priceFactors],['خطوات المعاينة والتركيب',$installationSteps],['نصائح الاختيار',$selectionTips]] as [$heading,$items])
            @if($items->isNotEmpty())<section data-reveal><h2>{{ $heading }}</h2><ul>@foreach($items as $item)<li><span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul></section>@endif
        @endforeach
        @if($advantages->isNotEmpty() || $disadvantages->isNotEmpty())<section class="pros-cons-block" data-reveal><h2>المميزات والقيود</h2><div><article><h3>المميزات</h3>@foreach($advantages as $item)<p>✓ {{ $item }}</p>@endforeach</article><article><h3>نقاط تحتاج انتباهًا</h3>@foreach($disadvantages as $item)<p>— {{ $item }}</p>@endforeach</article></div></section>@endif
    </div>
</section>

<section id="related" class="section-block"><div class="container-shell"><header class="section-heading" data-reveal><div><p class="eyebrow">روابط داخلية مفيدة</p><h2>خدمات مرتبطة</h2></div></header><div class="related-service-grid">@foreach($related as $item)<x-service-card :service="$item" />@endforeach</div></div></section>

@if($service->articles->isNotEmpty())<section class="section-block articles-section"><div class="container-shell"><header class="section-heading"><div><p class="eyebrow">اقرأ قبل الاختيار</p><h2>مقالات مرتبطة بالخدمة</h2></div></header><div class="cards-grid">@foreach($service->articles as $article)<x-article-card :article="$article" />@endforeach</div></div></section>@endif
@if($service->faqs->isNotEmpty())<x-faqs :faqs="$service->faqs" />@endif

<section id="request" class="service-request"><div class="container-shell service-request-grid"><div><p class="eyebrow">طلب معاينة</p><h2>أرسل تفاصيل {{ $service->name }}</h2><p>ارفق صور الموقع وأضف المنطقة والمساحة التقريبية لتكوين تصور أولي واضح.</p></div><x-lead-form :services="collect([$service])" default-message="أرغب في معاينة لخدمة {{ $service->name }}." /></div></section>

<div class="lightbox" data-lightbox hidden role="dialog" aria-modal="true" aria-label="معاينة الصورة"><button type="button" data-lightbox-close aria-label="إغلاق">×</button><button type="button" data-lightbox-prev aria-label="الصورة السابقة">→</button><figure><img alt=""><figcaption></figcaption></figure><button type="button" data-lightbox-next aria-label="الصورة التالية">←</button></div>
@endsection
