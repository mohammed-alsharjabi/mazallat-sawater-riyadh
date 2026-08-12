@extends('layouts.app')

@section('body-class', 'service-page-body')

@php
    $orderedImages = $service->images->where('processing_status', 'processed')->sortBy('sort_order')->values();
    $heroImage = $orderedImages->firstWhere('is_cover', true) ?: $orderedImages->first();
    $galleryImages = collect([$heroImage])->filter()->concat($orderedImages->reject(fn ($image) => $image->id === $heroImage?->id))->values();
    $aboutImage = $galleryImages->first(fn ($image) => $image->id !== $heroImage?->id && ($image->width ?? 0) >= ($image->height ?? 0)) ?: $galleryImages->get(1) ?: $heroImage;
    $lines = fn ($value) => collect(preg_split('/\R/u', (string) $value))->map('trim')->filter()->values();
    $types = $lines($service->types);
    $useCases = $lines($service->use_cases);
    $materialsDetails = $lines($service->materials_details);
    $advantages = $lines($service->advantages);
    $installationSteps = $lines($service->installation_steps);
    $benefits = collect(range(0, 2))->map(fn ($index) => [
        'title' => $advantages->get($index) ?: ($types->get($index) ?: $service->name),
        'description' => $useCases->get($index) ?: ($materialsDetails->get($index) ?: $service->excerpt),
    ]);
    $materialChips = $types->concat($service->materials->pluck('name'))->concat($materialsDetails)->unique()->take(4)->values();
    foreach (['خامة معتمدة', 'هيكل مناسب', 'خيارات متعددة', 'تنفيذ مدروس'] as $fallback) {
        if ($materialChips->count() >= 4) break;
        $materialChips->push($fallback);
    }
    $processTitles = collect(['معاينة الموقع', 'التصميم والقياس', 'التصنيع', 'التركيب والتسليم']);
    $processSteps = collect(range(0, 3))->map(fn ($index) => [
        'title' => $processTitles[$index],
        'description' => $installationSteps->get($index) ?: 'تنفيذ المرحلة وفق المقاسات والمواصفات المعتمدة للموقع.',
    ]);
    $serviceWhatsapp = $siteSettings['whatsapp_url'].'?text='.rawurlencode('مرحبًا، أود طلب معاينة لخدمة '.$service->name.' في الرياض: '.route('services.show', $service->slug));
@endphp

@section('content')
<div class="service-reference-page">
    <section class="srvc-hero">
        <nav class="srvc-breadcrumbs" aria-label="مسار الصفحة">
            <a href="{{ route('home') }}">الرئيسية</a><span>/</span><a href="{{ route('services.index') }}">{{ $service->category->name }}</a><span>/</span><b aria-current="page">{{ $service->name }}</b>
        </nav>
        <div class="srvc-hero-grid">
            @if($heroImage)
                @php($heroLarge = $heroImage->variant('gallery')['path'] ?? $heroImage->optimized_path)
                <div class="srvc-hero-art" data-reveal>
                        <button type="button" class="srvc-hero-image" data-lightbox-item data-lightbox-src="{{ asset('storage/'.$heroLarge).'?v='.($heroImage->updated_at?->timestamp ?? 1) }}" data-lightbox-alt="{{ $heroImage->alt_text }}" data-lightbox-caption="{{ $heroImage->caption }}" aria-label="تكبير صورة {{ $service->name }}">
                        <x-responsive-image :image="$heroImage" :alt="$heroImage->alt_text ?: $service->name" variant="cover" loading="eager" fetchpriority="high" sizes="(max-width: 560px) 100vw, 62vw" />
                    </button>
                </div>
            @endif
            <div class="srvc-hero-copy" data-reveal>
                <h1 class="srvc-page-title">@if(str_contains($service->name, 'الرياض'))<span>{{ $service->name }}</span>@else<span>{{ $service->name }}</span><span>في الرياض</span>@endif</h1>
                <i class="srvc-title-line" aria-hidden="true"></i>
                <p>{{ \Illuminate\Support\Str::limit($service->excerpt, 120) }}</p>
                <div class="srvc-hero-actions">
                    <a class="srvc-button srvc-button-primary" href="{{ route('quote', ['service' => $service->id]) }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h8l4 4v14H7zM15 3v5h4M10 12h6M10 16h6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>اطلب عرض سعر</a>
                    <a class="srvc-button srvc-button-outline" href="{{ $serviceWhatsapp }}" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 11.6a8.5 8.5 0 0 1-12.6 7.5L3 20.4l1.3-4.7A8.5 8.5 0 1 1 20.5 11.6Z" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8.3 7.3c.3-.4.7-.3.9.1l1 2c.2.4.1.7-.2 1l-.6.5c.7 1.4 1.8 2.5 3.2 3.2l.5-.6c.3-.3.6-.4 1-.2l2 1c.4.2.5.6.2.9-.5.8-1.3 1.2-2.2 1.1-3.4-.5-6.1-3.2-6.6-6.6-.1-.9.2-1.8.8-2.4Z" fill="currentColor"/></svg>واتساب</a>
                </div>
            </div>
        </div>
    </section>

    @if($galleryImages->isNotEmpty())
    <section class="srvc-section srvc-works" id="works">
        <div class="srvc-shell">
            <header class="srvc-heading" data-reveal><h2>أعمالنا لنفس الخدمة</h2><i aria-hidden="true"></i></header>
            <div class="srvc-gallery" data-service-gallery>
                @foreach($galleryImages as $image)
                    @php($large = $image->variant('gallery')['path'] ?? $image->optimized_path)
                    <figure @if($loop->index >= 6) hidden data-gallery-extra @endif data-reveal>
                        <button type="button" data-lightbox-item data-lightbox-src="{{ asset('storage/'.$large).'?v='.($image->updated_at?->timestamp ?? 1) }}" data-lightbox-alt="{{ $image->alt_text }}" data-lightbox-caption="{{ $image->caption }}">
                            <x-responsive-image :image="$image" :alt="$image->alt_text ?: $service->name" variant="thumbnail" sizes="(max-width: 560px) 50vw, 46vw" />
                            <span class="srvc-location"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s6-5.5 6-11a6 6 0 1 0-12 0c0 5.5 6 11 6 11Z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="10" r="2" fill="currentColor"/></svg>{{ $siteSettings['city'] }}</span>
                        </button>
                    </figure>
                @endforeach
            </div>
            @if($galleryImages->count() > 6)
                <button class="srvc-show-all" type="button" data-gallery-toggle data-collapsed-label="عرض جميع الأعمال" data-expanded-label="عرض الأعمال المختارة">عرض جميع الأعمال <span aria-hidden="true">←</span></button>
            @endif
        </div>
    </section>
    @endif

    <section class="srvc-section srvc-benefits">
        <div class="srvc-shell">
            <header class="srvc-heading" data-reveal><h2>لماذا تختار هذه الخدمة؟</h2><i aria-hidden="true"></i></header>
            <div class="srvc-benefit-grid">
                @foreach($benefits as $benefit)
                    <article data-reveal>
                        <span class="srvc-round-icon" aria-hidden="true">
                            @if($loop->index === 0)<svg viewBox="0 0 48 48"><path d="M13 13h9M13 13v9M35 13h-9M35 13v9M13 35h9M13 35v-9M35 35h-9M35 35v-9M18 18h12v12H18z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            @elseif($loop->index === 1)<svg viewBox="0 0 48 48"><circle cx="24" cy="16" r="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="m22 20-8 20M26 20l8 20M17 33h14M24 20v20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            @else<svg viewBox="0 0 48 48"><path d="M8 18h21c5 0 5-8 0-8-3 0-4 2-4 4M8 25h27c6 0 6 9 0 9-3 0-4-2-4-4M8 32h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>@endif
                        </span>
                        <h3>{{ \Illuminate\Support\Str::limit($benefit['title'], 46) }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($benefit['description'], 84) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="srvc-section srvc-about" id="about">
        <div class="srvc-shell srvc-about-grid">
            <div class="srvc-about-copy" data-reveal>
                <header class="srvc-heading srvc-heading-inline"><h2>عن الخدمة</h2><i aria-hidden="true"></i></header>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($service->content), 330) }}</p>
                <div class="srvc-materials">
                    @foreach($materialChips as $chip)
                        <article><span aria-hidden="true">
                            @if($loop->index === 0)<svg viewBox="0 0 32 32"><path d="M7 8h18v16H7zM10 11h12v10H10z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-dasharray="2 1"/></svg>
                            @elseif($loop->index === 1)<svg viewBox="0 0 32 32"><path d="M6 8h20M6 24h20M9 8l14 16M23 8 9 24" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>
                            @elseif($loop->index === 2)<svg viewBox="0 0 32 32"><circle cx="16" cy="16" r="10" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="12" r="1.5" fill="currentColor"/><circle cx="19" cy="11" r="1.5" fill="currentColor"/><circle cx="11" cy="19" r="1.5" fill="currentColor"/></svg>
                            @else<svg viewBox="0 0 32 32"><path d="m16 4 9 4v7c0 6-4 10-9 13-5-3-9-7-9-13V8l9-4Z" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="m12 16 3 3 6-7" fill="none" stroke="currentColor" stroke-width="1.7"/></svg>@endif
                        </span><b>{{ \Illuminate\Support\Str::limit($chip, 24) }}</b></article>
                    @endforeach
                </div>
            </div>
            @if($aboutImage)<button class="srvc-about-image" type="button" data-lightbox-item data-lightbox-src="{{ asset('storage/'.(($aboutImage->variant('gallery')['path'] ?? null) ?: $aboutImage->optimized_path)).'?v='.($aboutImage->updated_at?->timestamp ?? 1) }}" data-lightbox-alt="{{ $aboutImage->alt_text }}" data-lightbox-caption="{{ $aboutImage->caption }}" aria-label="تكبير صورة عن {{ $service->name }}" data-reveal><x-responsive-image :image="$aboutImage" :alt="$aboutImage->alt_text ?: $service->name" variant="cover" sizes="(max-width: 560px) 100vw, 38vw" /></button>@endif
        </div>
    </section>

    <section class="srvc-section srvc-process">
        <div class="srvc-shell">
            <header class="srvc-heading" data-reveal><h2>كيف ننفذها؟</h2><i aria-hidden="true"></i></header>
            <ol class="srvc-process-grid">
                @foreach($processSteps as $step)
                    <li data-reveal><span>{{ $loop->iteration }}</span><div class="srvc-process-icon" aria-hidden="true">
                        @if($loop->index === 0)<svg viewBox="0 0 40 40"><path d="M20 35s10-9 10-18a10 10 0 1 0-20 0c0 9 10 18 10 18Z" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="20" cy="17" r="3" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                        @elseif($loop->index === 1)<svg viewBox="0 0 40 40"><path d="M8 28 26 10l6 6-18 18H8v-6ZM22 14l6 6M10 31h8" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                        @elseif($loop->index === 2)<svg viewBox="0 0 40 40"><path d="M8 33V15l8 4v-8l8 5V8h8v25H8ZM13 27h3M21 27h3M29 27h3" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                        @else<svg viewBox="0 0 40 40"><path d="M7 29h26M11 29V16h18v13M14 16l6-7 6 7M14 24h12" fill="none" stroke="currentColor" stroke-width="2"/><path d="m28 9 3 3 5-6" fill="none" stroke="currentColor" stroke-width="2"/></svg>@endif
                    </div><h3>{{ $step['title'] }}</h3><p>{{ \Illuminate\Support\Str::limit($step['description'], 72) }}</p></li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="srvc-section srvc-cta">
        <div class="srvc-shell srvc-cta-box" data-reveal>
            <div class="srvc-cta-copy"><h2>هل لديك مساحة تريد تغطيتها؟</h2><i class="srvc-title-line" aria-hidden="true"></i><p>{{ $service->cta ?: 'أرسل المقاسات أو صورة الموقع لنقترح الحل المناسب.' }}</p><div><a class="srvc-button srvc-button-primary" href="{{ route('quote', ['service' => $service->id]) }}">اطلب معاينة</a><a class="srvc-button srvc-button-outline" href="{{ $serviceWhatsapp }}" target="_blank" rel="noopener">تواصل واتساب</a></div></div>
            <svg class="srvc-cta-drawing" viewBox="0 0 260 120" aria-hidden="true"><g fill="none" stroke="currentColor" stroke-width="1"><path d="M14 101h230M31 101V30M225 101V30M31 30l95-20 99 20M31 30l57 31 38-51 42 51 57-31M88 61h80M51 101V62M207 101V62M88 61v40M168 61v40"/><path d="M14 109h230M24 105v8M234 105v8" stroke-dasharray="3 3"/></g></svg>
        </div>
    </section>

    @if($related->isNotEmpty())
    <section class="srvc-section srvc-related" id="related" data-related-carousel>
        <div class="srvc-shell">
            <header class="srvc-heading" data-reveal><h2>خدمات قد تناسبك</h2><i aria-hidden="true"></i></header>
            <div class="srvc-related-wrap">
                <button type="button" class="srvc-carousel-arrow srvc-carousel-prev" data-related-prev aria-label="الخدمة السابقة">‹</button>
                <div class="srvc-related-track" data-related-track>
                    @foreach($related->take(3) as $item)
                        @php($cardImage = $item->images->firstWhere('is_cover', true) ?: $item->images->first())
                        <article data-reveal>
                            <a href="{{ route('services.show', $item->slug) }}">
                                @if($cardImage)<x-responsive-image :image="$cardImage" :alt="$cardImage->alt_text ?: $item->name" variant="thumbnail" sizes="(max-width: 560px) 72vw, 31vw" />@endif
                                <h3>{{ $item->name }}</h3>
                            </a>
                        </article>
                    @endforeach
                </div>
                <button type="button" class="srvc-carousel-arrow srvc-carousel-next" data-related-next aria-label="الخدمة التالية">›</button>
            </div>
        </div>
    </section>
    @endif
</div>

<div class="lightbox" data-lightbox hidden role="dialog" aria-modal="true" aria-label="معاينة الصورة"><button type="button" data-lightbox-close aria-label="إغلاق">×</button><button type="button" data-lightbox-prev aria-label="الصورة السابقة">→</button><figure><img alt=""><figcaption></figcaption></figure><button type="button" data-lightbox-next aria-label="الصورة التالية">←</button></div>
@endsection
