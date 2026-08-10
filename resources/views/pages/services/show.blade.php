@extends('layouts.app')

@php
    $galleryImages = $service->images->sortByDesc('is_cover')->values()->take(12);
    $heroImage = $service->images->firstWhere('is_cover', true) ?: $galleryImages->first();
    $detailImages = $service->images->where('id', '!=', $heroImage?->id)->sortByDesc(fn ($image) => $image->height ? $image->width / $image->height : 0)->take(2);
    $types = collect(preg_split('/\R/u', (string) $service->types))->map('trim')->filter()->values();
    $useCases = collect(preg_split('/\R/u', (string) $service->use_cases))->map('trim')->filter()->values();
    $materialsDetails = collect(preg_split('/\R/u', (string) $service->materials_details))->map('trim')->filter()->values();
    $advantages = collect(preg_split('/\R/u', (string) $service->advantages))->map('trim')->filter()->values();
    $disadvantages = collect(preg_split('/\R/u', (string) $service->disadvantages))->map('trim')->filter()->values();
    $priceFactors = collect(preg_split('/\R/u', (string) $service->price_factors))->map('trim')->filter()->values();
    $installationSteps = collect(preg_split('/\R/u', (string) $service->installation_steps))->map('trim')->filter()->values();
    $selectionTips = collect(preg_split('/\R/u', (string) $service->selection_tips))->map('trim')->filter()->values();
    $beforeAfterProject = $service->projects->first(fn ($project) => $project->images->contains('stage', 'before') && $project->images->contains('stage', 'after'));
    $serviceWhatsapp = $siteSettings['whatsapp_url'].'?text='.rawurlencode('مرحبًا، أود طلب معاينة لخدمة '.$service->name.' في الرياض: '.route('services.show', $service->slug));
@endphp

@section('content')
<section class="service-reference-hero section-indexed">
    <div class="container-shell">
        <nav class="breadcrumbs" aria-label="مسار الصفحة"><a href="{{ route('home') }}">الرئيسية</a><span>/</span><a href="{{ route('services.index') }}">الخدمات</a><span>/</span><b aria-current="page">{{ $service->name }}</b></nav>
        <div class="service-reference-grid">
            <div class="service-reference-copy" data-hero-copy>
                <p class="eyebrow">{{ $service->category->name }}</p>
                <h1>{{ $service->name }} في الرياض</h1>
                <p>{{ $service->excerpt }}</p>
                <div class="hero-actions"><a class="button button-primary" href="{{ route('quote', ['service' => $service->id]) }}">اطلب معاينة</a><a class="text-link" href="{{ $serviceWhatsapp }}" target="_blank" rel="noopener">واتساب ◉</a></div>
            </div>
            <div class="service-reference-media technical-image" data-hero-visual>
                @if($heroImage)<x-responsive-image :image="$heroImage" :alt="$heroImage->alt_text ?: $service->name" variant="cover" loading="eager" fetchpriority="high" sizes="(max-width: 780px) 100vw, 56vw" /><span class="measure measure-top" aria-hidden="true">المقاس يحدد بعد المعاينة</span>@endif
            </div>
            @if($detailImages->isNotEmpty())<div class="hero-detail-images">@foreach($detailImages as $image)<figure><x-responsive-image :image="$image" :alt="$image->alt_text ?: $service->name" variant="thumbnail" sizes="180px" /><figcaption>{{ $image->caption ?: $image->title }}</figcaption></figure>@endforeach</div>@endif
        </div>
    </div>
</section>

<nav class="service-section-nav" aria-label="أقسام الخدمة"><strong>المعرض</strong><a href="#about-service">عن الخدمة</a>@if($materialsDetails->isNotEmpty())<a href="#materials">الخامات</a>@endif@if($beforeAfterProject)<a href="#before-after">قبل وبعد</a>@endif<a href="#related">خدمات مرتبطة</a></nav>

@if($galleryImages->isNotEmpty())
<section class="section-block service-gallery-reference section-indexed" data-reveal>
    <div class="container-shell"><div class="section-row"><div><p class="eyebrow">صور الخدمة</p><h2>معرض {{ $service->name }}</h2></div><span>{{ $galleryImages->count() }} صورة</span></div>
        <div class="reference-gallery">@foreach($galleryImages->take(6) as $image)<figure><x-responsive-image :image="$image" :alt="$image->alt_text ?: $service->name" sizes="(max-width: 780px) 100vw, 33vw" /><figcaption>{{ $image->caption ?: $image->title ?: $service->name }}</figcaption></figure>@endforeach</div>
    </div>
</section>
@endif

<section id="about-service" class="section-block solution-steps section-indexed" data-reveal>
    <div class="container-shell"><div class="section-row"><div><p class="eyebrow">من الموقع إلى الحل</p><h2>كيف نحدد الحل المناسب؟</h2></div></div>
        <ol class="solution-step-grid">
            @foreach($installationSteps->take(4) as $step)<li><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $step }}</h3><p>{{ $loop->first ? 'نراجع المساحة والاستخدام والعناصر القائمة قبل اقتراح التفاصيل.' : 'تُثبت التفاصيل والمواصفات مع صاحب المشروع قبل الانتقال للمرحلة التالية.' }}</p></li>@endforeach
        </ol>
    </div>
</section>

<section class="section-block service-description-reference section-indexed" data-reveal>
    <div class="container-shell service-description-grid">
        <article><p class="eyebrow">تعريف الخدمة</p><h2>{{ $service->name }} للمواقع المختلفة</h2><p>{!! nl2br(e($service->content)) !!}</p></article>
        <aside class="quote-aside"><p class="eyebrow">طلب سريع</p><h2>أرسل موقعك وصور المساحة</h2><p>{{ $service->cta }}</p><a class="button button-primary" href="{{ route('quote', ['service' => $service->id]) }}">ابدأ الطلب</a><a class="button button-outline" href="{{ $serviceWhatsapp }}" target="_blank" rel="noopener">واتساب</a></aside>
    </div>
</section>

<section id="materials" class="section-block service-information section-indexed" data-reveal>
    <div class="container-shell information-grid">
        @if($types->isNotEmpty())<section><p class="eyebrow">الأنواع</p><h2>الخيارات المتاحة</h2><ul class="numbered-spec-list">@foreach($types as $item)<li><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul></section>@endif
        @if($useCases->isNotEmpty())<section><p class="eyebrow">الاستخدامات</p><h2>الحالات المناسبة</h2><ul class="numbered-spec-list">@foreach($useCases as $item)<li><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul></section>@endif
        @if($materialsDetails->isNotEmpty())<section><p class="eyebrow">الخامات</p><h2>مواصفات يجب مراجعتها</h2><ul class="numbered-spec-list">@foreach($materialsDetails as $item)<li><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul></section>@endif
        @if($advantages->isNotEmpty() || $disadvantages->isNotEmpty())<section><p class="eyebrow">مقارنة متوازنة</p><h2>المميزات والقيود</h2><div class="pros-cons"><div><strong>المميزات</strong>@foreach($advantages as $item)<p>✓ {{ $item }}</p>@endforeach</div><div><strong>نقاط تحتاج انتباهًا</strong>@foreach($disadvantages as $item)<p>— {{ $item }}</p>@endforeach</div></div></section>@endif
    </div>
</section>

<section class="section-block price-selection-reference section-indexed" data-reveal>
    <div class="container-shell information-grid">
        <section><p class="eyebrow">ما الذي يحدد السعر؟</p><h2>عوامل تتغير من موقع لآخر</h2><ol class="price-factor-list">@foreach($priceFactors as $factor)<li><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $factor }}</li>@endforeach</ol>@if($service->is_price_published && $service->price_from)<p class="verified-price"><strong>السعر المعتمد يبدأ من {{ number_format($service->price_from, 0) }} ر.س {{ $service->price_unit }}</strong><small>{{ $service->price_note }}</small></p>@else<p class="price-disclaimer">لا يظهر رقم قبل اعتماده من الإدارة؛ السعر النهائي يتحدد بعد المقاسات والمعاينة.</p>@endif</section>
        <section><p class="eyebrow">قبل الاعتماد</p><h2>نصائح الاختيار</h2><ul class="numbered-spec-list">@foreach($selectionTips as $tip)<li><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $tip }}</li>@endforeach</ul></section>
    </div>
</section>

@if($beforeAfterProject)
    @php($beforeImage = $beforeAfterProject->images->firstWhere('stage', 'before'))
    @php($afterImage = $beforeAfterProject->images->firstWhere('stage', 'after'))
    <section id="before-after" class="section-block before-after-reference section-indexed" data-reveal><div class="container-shell reference-two-column"><div class="section-copy"><p class="eyebrow">مشروع حقيقي</p><h2>المكان قبلنا وبعدنا</h2><p>{{ $beforeAfterProject->title }}</p></div><div class="before-after-slider" data-before-after style="--position:50%"><x-responsive-image :image="$beforeImage" :alt="$beforeImage->alt_text ?: 'الموقع قبل التنفيذ'" /><div class="after-layer"><x-responsive-image :image="$afterImage" :alt="$afterImage->alt_text ?: 'الموقع بعد التنفيذ'" /></div><span class="before-label">قبلنا</span><span class="after-label">بعدنا</span><i aria-hidden="true">‹›</i><input type="range" min="12" max="88" value="50" aria-label="مقارنة قبل وبعد" data-before-after-range></div></div></section>
@endif

@if($service->projects->isNotEmpty())
<section class="section-block service-project-record section-indexed" data-reveal><div class="container-shell"><div class="section-row"><div><p class="eyebrow">سجل المشاريع</p><h2>أعمال موثقة مرتبطة بالخدمة</h2></div></div><div class="project-reference-grid">@foreach($service->projects as $project)<x-project-card :project="$project" />@endforeach</div></div></section>
@endif

@if($testimonials->isNotEmpty())
<section class="section-block reference-testimonials section-indexed" data-reveal><div class="container-shell">@foreach($testimonials as $testimonial)<blockquote><span aria-hidden="true">”</span><p>{{ $testimonial->quote }}</p><footer>{{ $testimonial->customer_name }}@if($testimonial->area) — {{ $testimonial->area->name }}@endif</footer>@if($testimonial->rating)<div aria-label="{{ $testimonial->rating }} من 5">{{ str_repeat('★', $testimonial->rating) }}</div>@endif</blockquote>@endforeach</div></section>
@endif

<section id="related" class="section-block related-reference section-indexed" data-reveal><div class="container-shell"><div class="section-row"><div><p class="eyebrow">خيارات تكمل مشروعك</p><h2>خدمات مرتبطة</h2></div></div><div class="related-service-grid">@foreach($related as $item)<x-service-card :service="$item" />@endforeach</div></div></section>

@if($service->articles->isNotEmpty())<section class="section-block editorial-reference section-indexed" data-reveal><div class="container-shell"><div class="section-row"><div><p class="eyebrow">اقرأ قبل أن تقرر</p><h2>مقالات مرتبطة بالخدمة</h2></div></div><div class="editorial-reference-grid">@foreach($service->articles as $article)<x-article-card :article="$article" />@endforeach</div></div></section>@endif

@if($service->faqs->isNotEmpty())<x-faqs :faqs="$service->faqs" />@endif

<section class="service-quote-reference section-indexed" data-reveal><div class="container-shell service-quote-grid"><div><p class="eyebrow">أرسل صور موقعك</p><h2>اطلب معاينة {{ $service->name }}</h2><p>أضف المساحة التقريبية وصور الموقع لنفهم الطلب قبل التواصل.</p></div><div><x-lead-form :services="collect([$service])" default-message="أرغب في معاينة لخدمة {{ $service->name }}." /></div></div></section>
@endsection
