@extends('layouts.app')

@section('body-class', 'home-services-page')

@section('content')
@php($heroImage = $siteSettings['hero_image'] ?? config('site.hero_image'))
<section class="home-hero">
    <div class="home-hero-media">
        <span class="image-shell" data-image-shell>
            <img src="{{ asset('storage/'.$heroImage) }}" alt="مظلة شد إنشائي منفذة في الرياض" width="1536" height="1024" loading="eager" decoding="async" fetchpriority="high">
        </span>
    </div>

    <div class="container-shell home-hero-inner">
        <div class="home-hero-copy">
            <h1>{{ $siteSettings['hero_title'] ?? 'نصنع الظل الذي يغيّر المكان' }}</h1>
            <p class="home-hero-lede">{{ $siteSettings['hero_description'] ?? 'مظلات وسواتر وبرجولات بتصميم هندسي وتنفيذ يليق بمشروعك.' }}</p>

            <div class="home-hero-actions">
                <a class="button button-primary" href="{{ route('quote') }}">{{ $siteSettings['inspection_cta_label'] ?? 'اطلب معاينة' }}</a>
                <a class="button home-hero-ghost" href="#all-services">استعرض الخدمات</a>
            </div>

            <div class="home-hero-direct">
                <a href="{{ $siteSettings['phone_tel'] }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.3 3.8 9.6 8c.3.6.2 1.2-.3 1.6l-1.4 1.1a15 15 0 0 0 5.4 5.4l1.1-1.4c.4-.5 1.1-.6 1.6-.3l4.2 2.3c.6.3.9 1 .7 1.7l-.6 2.1c-.2.8-.9 1.3-1.7 1.4C9.8 22.1 1.9 14.2 2.1 5.4c0-.8.6-1.5 1.4-1.7l2.1-.6c.7-.2 1.4.1 1.7.7Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span dir="ltr">{{ $siteSettings['phone_display'] }}</span>
                </a>
                <a href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 11.6a8.5 8.5 0 0 1-12.6 7.5L3 20.4l1.3-4.7A8.5 8.5 0 1 1 20.5 11.6Z" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8.3 7.3c.3-.4.7-.3.9.1l1 2c.2.4.1.7-.2 1l-.6.5c.7 1.4 1.8 2.5 3.2 3.2l.5-.6c.3-.3.6-.4 1-.2l2 1c.4.2.5.6.2.9-.5.8-1.3 1.2-2.2 1.1-3.4-.5-6.1-3.2-6.6-6.6-.1-.9.2-1.8.8-2.4Z" fill="currentColor"/></svg>
                    <span>تواصل واتساب</span>
                </a>
            </div>
        </div>
    </div>
</section>

<x-geo-divider variant="bowtie" class="home-divider" />

<section id="all-services" class="home-services">
    <div class="container-shell">
        <header class="home-section-heading" data-reveal>
            <p>خدماتنا</p>
            <h2>مظلات وسواتر وبرجولات بصور حقيقية ووصف واضح</h2>
            <span>كل خدمة تعرض صورها من أعمالنا داخل الرياض ووصفًا مختصرًا لاستخدامها. اختر الخدمة لمشاهدة المعرض الكامل وطلب المعاينة.</span>
        </header>

        <div class="home-service-grid">
            @foreach($serviceCards as $service)
                @php($cover = $service->images->firstWhere('is_cover', true) ?: $service->images->first())
                <a class="home-service-card" href="{{ route('services.show', $service->slug) }}" data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 70 }}ms">
                    <span class="home-service-media">
                        <x-service-cover :service="$service" :image="$cover" :alt="$service->featured_image_alt ?: $service->name" loading="lazy" sizes="(max-width: 560px) 100vw, (max-width: 1050px) 50vw, 33vw" />
                    </span>
                    <span class="home-service-body">
                        <h3>{{ $service->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($service->excerpt, 132) }}</p>
                        <span class="home-service-more">شاهد الصور والتفاصيل
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<x-geo-divider variant="chevron" class="home-divider home-divider-tight" />

<section class="home-bridge" aria-label="طريقة عملنا في سطور">
    <div class="container-shell">
        <ul class="home-bridge-rail">
            <li data-reveal>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 20 20 3M8 4.5 4.5 8M12 8.5 8.5 12M15.5 12 12 15.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M2.4 17.9 6.1 21.6a1 1 0 0 0 1.4 0l14.1-14.1a1 1 0 0 0 0-1.4L17.9 2.4a1 1 0 0 0-1.4 0L2.4 16.5a1 1 0 0 0 0 1.4Z" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                <span><strong>معاينة وقياس قبل العرض</strong><small>نفهم المساحة والاستخدام قبل ترشيح الحل</small></span>
            </li>
            <li data-reveal style="--reveal-delay: 80ms">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.8 20 6v5.6c0 4.3-3.2 8.2-8 9.6-4.8-1.4-8-5.3-8-9.6V6l8-3.2Z" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="m8.6 12 2.4 2.4 4.4-4.7" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span><strong>خامات تُختار حسب الموقع</strong><small>حماية من الشمس والغبار وحركة الرياح</small></span>
            </li>
            <li data-reveal style="--reveal-delay: 160ms">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 21h18M5 21V9.5l7-5.5 7 5.5V21" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M9.5 21v-5.5h5V21" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                <span><strong>تنفيذ منظم داخل الرياض</strong><small>فريق ميداني ومتابعة حتى التسليم</small></span>
            </li>
            <li data-reveal style="--reveal-delay: 240ms">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="9.5" r="6" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="m9 14.6-1.4 6.6L12 19l4.4 2.2-1.4-6.6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                <span><strong>خبرة ميدانية منذ 1999</strong><small>أعمال لمشاريع حكومية وشركات كبيرة</small></span>
            </li>
        </ul>
    </div>
</section>

@if($galleryImages->isNotEmpty())
<x-geo-divider variant="bowtie" class="home-divider" />

<section class="home-gallery" id="gallery" aria-label="معرض أعمالنا">
    <div class="container-shell">
        <header class="home-section-heading" data-reveal>
            <p>المعرض</p>
            <h2>معرض أعمالنا من جميع الخدمات</h2>
            <span>صور حقيقية من مشاريع نُفذت داخل الرياض. اضغط على أي صورة لتكبيرها.</span>
        </header>

        <div class="home-gallery-grid">
            @foreach($galleryImages as $image)
                @php($large = $image->variant('gallery')['path'] ?? $image->optimized_path)
                <figure>
                    <button type="button" data-lightbox-item data-lightbox-src="{{ asset('storage/'.$large).'?v='.($image->updated_at?->timestamp ?? 1) }}" data-lightbox-alt="{{ $image->alt_text ?: $image->service?->name }}" data-lightbox-caption="{{ $image->caption ?: $image->service?->name }}" aria-label="تكبير صورة {{ $image->service?->name }}">
                        <x-responsive-image :image="$image" :alt="$image->alt_text ?: $image->service?->name" variant="thumbnail" loading="lazy" sizes="(max-width: 560px) 50vw, (max-width: 1050px) 33vw, 25vw" />
                    </button>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="home-story">
    <div class="container-shell home-story-grid">
        <figure class="home-story-figure" data-reveal>
            <img src="{{ asset('storage/'.($siteSettings['about_image'] ?? config('site.about_image'))) }}" alt="فريق مظلات وسواتر الرياض أثناء تنفيذ مشروع" width="1448" height="1086" loading="lazy" decoding="async">
        </figure>
        <div class="home-story-copy" data-reveal style="--reveal-delay: 90ms">
            <p class="home-section-kicker">من نحن</p>
            <h2>خبرة تُقرأ في تفاصيل التنفيذ لا في الوعود</h2>
            <p>بدأ عملنا في السواتر عام 1999، ثم اتسع ليشمل المظلات والخيام وبيوت الشعر والهناجر والبرجولات والشترات والأبواب الكهربائية. نتعامل مع كل مشروع بالمنهج نفسه: معاينة دقيقة، اختيار خامة مناسبة للموقع، تنفيذ منظم، ومتابعة حتى التسليم.</p>
            <ul class="home-story-points">
                <li>معاينة وقياس ميداني داخل مدينة الرياض</li>
                <li>توضيح الخامة والمقاسات ونطاق العمل قبل البدء</li>
                <li>تنفيذ بمراحل واضحة ومتابعة بعد التسليم</li>
            </ul>
            <a class="button button-outline" href="{{ route('about') }}">تعرف علينا أكثر</a>
        </div>
    </div>
</section>

<section class="home-process">
    <div class="container-shell">
        <header class="home-section-heading" data-reveal>
            <p>خطوات العمل</p>
            <h2>كيف ننفذ مشروعك</h2>
            <span>مسار واضح من أول تواصل حتى التسليم، بلا مفاجآت في التفاصيل أو نطاق العمل.</span>
        </header>
        <ol class="home-process-steps">
            <li data-reveal><b>01</b><h3>تواصل ومعاينة</h3><p>نستمع إلى احتياجك ونعاين الموقع لتحديد المساحة والعوائق.</p></li>
            <li data-reveal style="--reveal-delay: 80ms"><b>02</b><h3>تصميم وخامة</h3><p>نرشّح الشكل والخامة والمقاسات المناسبة لطبيعة المكان.</p></li>
            <li data-reveal style="--reveal-delay: 160ms"><b>03</b><h3>تنفيذ ميداني</h3><p>تجهيز الهيكل والتركيب بمراحل منظمة وفريق مختص.</p></li>
            <li data-reveal style="--reveal-delay: 240ms"><b>04</b><h3>تسليم ومتابعة</h3><p>مراجعة النتيجة معك والاتفاق على المتابعة والصيانة.</p></li>
        </ol>
    </div>
</section>

@if($trustItems->isNotEmpty())
<section class="home-proof" aria-label="مؤشرات موثقة">
    <div class="container-shell">
        @foreach($trustItems as $item)
            <article data-reveal style="--reveal-delay: {{ ($loop->index % 4) * 70 }}ms"><strong>{{ $item->value }}</strong><span>{{ $item->label }}</span>@if($item->description)<small>{{ $item->description }}</small>@endif</article>
        @endforeach
    </div>
</section>
@endif

<section class="home-cta">
    <div class="container-shell">
        <div class="home-cta-box" data-reveal>
            <div>
                <p class="home-section-kicker">ابدأ من موقعك واحتياجك</p>
                <h2>أخبرنا عن المكان ونساعدك على اختيار الحل المناسب</h2>
                <p>أرسل صورة الموقع والمقاسات التقريبية، أو اطلب معاينة لتحديد نطاق العمل بدقة قبل العرض.</p>
            </div>
            <div class="home-cta-actions">
                <a class="button home-cta-primary" href="{{ route('quote') }}">اطلب عرض سعر</a>
                <a class="button home-cta-ghost" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">تواصل واتساب</a>
                <a class="home-cta-phone" href="{{ $siteSettings['phone_tel'] }}" dir="ltr">{{ $siteSettings['phone_display'] }}</a>
            </div>
        </div>
    </div>
</section>

<div class="lightbox" data-lightbox hidden role="dialog" aria-modal="true" aria-label="معاينة الصورة"><button type="button" data-lightbox-close aria-label="إغلاق">×</button><button type="button" data-lightbox-prev aria-label="الصورة السابقة">→</button><figure><img alt=""><figcaption></figcaption></figure><button type="button" data-lightbox-next aria-label="الصورة التالية">←</button></div>
@endsection
