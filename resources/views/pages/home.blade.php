@extends('layouts.app')

@section('content')
@php
    $firstHeroSlide = $heroSlides->first();
    $showcaseServices = collect([$heroService])->filter()->concat($featuredServices)->unique('id')->values();
@endphp
<section class="immersive-hero" data-hero-slider aria-label="أعمال وخدمات مظلات وسواتر الرياض">
    <div class="immersive-hero-slides" aria-live="off">
        @foreach($heroSlides as $slide)
            <article class="immersive-hero-slide {{ $loop->first ? 'active' : '' }}" data-hero-slide data-service-name="{{ $slide['service']->name }}" data-service-url="{{ route('services.show', $slide['service']->slug) }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                <x-responsive-image :image="$slide['image']" :alt="$slide['image']->alt_text ?: $slide['service']->name" variant="cover" :loading="$loop->first ? 'eager' : 'lazy'" :fetchpriority="$loop->first ? 'high' : 'auto'" sizes="100vw" />
            </article>
        @endforeach
    </div>
    <div class="immersive-hero-shade" aria-hidden="true"></div>
    <div class="container-shell immersive-hero-content">
        <div data-hero-copy>
            <p class="hero-kicker"><span></span>{{ $siteSettings['hero_eyebrow'] ?? 'حلول هندسية للمساحات الخارجية' }}</p>
            <h1>{{ $siteSettings['hero_title'] ?? 'نصمم الظل كجزء من المكان' }}</h1>
            <p>{{ $siteSettings['hero_description'] ?? 'مظلات وسواتر وهياكل مصممة لمناخ الرياض ومساحة مشروعك.' }}</p>
            <div class="hero-actions">
                <a class="button hero-primary-action" href="{{ route('quote') }}">ابدأ مشروعك <span aria-hidden="true">←</span></a>
                <a class="hero-service-link" href="{{ $firstHeroSlide ? route('services.show', $firstHeroSlide['service']->slug) : route('services.index') }}" data-hero-service-link>شاهد <strong data-hero-service-name>{{ $firstHeroSlide['service']->name ?? 'خدماتنا' }}</strong></a>
            </div>
        </div>
        <div class="hero-slider-controls" aria-label="التحكم في صور الهيرو">
            <button type="button" data-hero-prev aria-label="الصورة السابقة">→</button>
            <div class="hero-slider-dots" role="tablist" aria-label="صور الأعمال">
                @foreach($heroSlides as $slide)
                    <button type="button" @class(['active' => $loop->first]) data-hero-dot="{{ $loop->index }}" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="عرض {{ $slide['service']->name }}"><span></span></button>
                @endforeach
            </div>
            <button type="button" data-hero-next aria-label="الصورة التالية">←</button>
            <span class="hero-slider-count"><b data-hero-current>01</b> / {{ str_pad($heroSlides->count(), 2, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>
    <a class="hero-scroll-cue" href="#services"><span>استكشف الخدمات</span><i aria-hidden="true"></i></a>
</section>

<section id="services" class="section-block service-showcase section-indexed" data-service-showcase data-reveal>
    <div class="container-shell">
        <div class="service-showcase-heading">
            <div><p class="eyebrow">خدمات مرتبطة بصورها الحقيقية</p><h2>اختر الخدمة وشاهد نبذتها وأعمالها</h2></div>
            <p>اضغط على أي خدمة لعرض تعريفها، طلب المعاينة، وصور الأعمال المرتبطة بها فقط.</p>
        </div>
        <div class="service-showcase-layout">
            <div class="service-showcase-tabs" role="tablist" aria-label="الخدمات الأساسية">
                @foreach($showcaseServices as $item)
                    <button type="button" @class(['active' => $loop->first]) id="service-tab-{{ $item->id }}" data-service-tab="{{ $loop->index }}" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="service-panel-{{ $item->id }}">
                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span><small>{{ $item->category->name }}</small><strong>{{ $item->name }}</strong></span>
                        <em>{{ $item->images_count }} صورة</em><i aria-hidden="true">←</i>
                    </button>
                @endforeach
            </div>
            <div class="service-showcase-panels">
                @foreach($showcaseServices as $item)
                    @php($panelImages = $item->images->take(5))
                    @php($panelWhatsapp = $siteSettings['whatsapp_url'].'?text='.rawurlencode('مرحبًا، أود الاستفسار عن خدمة '.$item->name.' في الرياض: '.route('services.show', $item->slug)))
                    <article id="service-panel-{{ $item->id }}" @class(['service-showcase-panel', 'active' => $loop->first]) data-service-panel="{{ $loop->index }}" role="tabpanel" aria-labelledby="service-tab-{{ $item->id }}" @if(!$loop->first) hidden @endif>
                        <div class="service-showcase-intro">
                            <div><p class="eyebrow">{{ $item->category->name }}</p><h3>{{ $item->name }}</h3><p>{{ $item->excerpt }}</p></div>
                            <div><a class="button button-primary" href="{{ route('quote', ['service' => $item->id]) }}">اطلب معاينة</a><a class="button button-outline" href="{{ $panelWhatsapp }}" target="_blank" rel="noopener">تواصل واتساب</a><a class="text-link" href="{{ route('services.show', $item->slug) }}">كل تفاصيل الخدمة ←</a></div>
                        </div>
                        @if($panelImages->isNotEmpty())
                            <div class="service-showcase-gallery" aria-label="صور أعمال {{ $item->name }}">
                                @foreach($panelImages as $image)
                                    <figure><x-responsive-image :image="$image" :alt="$image->alt_text ?: $item->name" :variant="$loop->first ? 'gallery' : 'thumbnail'" sizes="(max-width: 780px) 85vw, 32vw" /><figcaption>{{ $image->caption ?: $image->title ?: $item->name }}</figcaption></figure>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

@if($beforeAfterProject)
    @php($beforeImage = $beforeAfterProject->images->firstWhere('stage', 'before'))
    @php($afterImage = $beforeAfterProject->images->firstWhere('stage', 'after'))
    <section class="section-block before-after-reference section-indexed" data-reveal>
        <div class="container-shell reference-two-column">
            <div class="section-copy"><p class="eyebrow">مشروع موثّق</p><h2>المكان قبلنا وبعدنا</h2><p>{{ $beforeAfterProject->excerpt }}</p><a class="text-link" href="{{ route('projects.show', $beforeAfterProject->slug) }}">عرض المشروع ←</a></div>
            <div class="before-after-slider" data-before-after style="--position:50%">
                <x-responsive-image :image="$beforeImage" :alt="$beforeImage->alt_text ?: 'الموقع قبل التنفيذ'" />
                <div class="after-layer"><x-responsive-image :image="$afterImage" :alt="$afterImage->alt_text ?: 'الموقع بعد التنفيذ'" /></div>
                <span class="before-label">قبلنا</span><span class="after-label">بعدنا</span><i aria-hidden="true">‹›</i>
                <input type="range" min="12" max="88" value="50" aria-label="مقارنة الصورة قبل وبعد" data-before-after-range>
            </div>
        </div>
    </section>
@endif

@if($projects->isNotEmpty())
<section class="section-block documented-projects section-indexed" data-reveal>
    <div class="container-shell"><div class="section-row"><div><p class="eyebrow">مشاريع موثّقة</p><h2>أعمال تمت في أحياء الرياض</h2></div><a class="text-link" href="{{ route('projects.index') }}">جميع المشاريع ←</a></div>
        <div class="project-reference-grid">@foreach($projects as $project)<x-project-card :project="$project" />@endforeach</div>
    </div>
</section>
@endif

@if($trustItems->isNotEmpty() || $testimonials->isNotEmpty())
<section class="section-block verified-proof section-indexed" data-reveal>
    <div class="container-shell">
        @if($trustItems->isNotEmpty())<div class="verified-metrics">@foreach($trustItems as $item)<article><strong>{{ $item->value }}</strong><span>{{ $item->label }}</span>@if($item->description)<small>{{ $item->description }}</small>@endif</article>@endforeach</div>@endif
        @if($testimonials->isNotEmpty())<div class="reference-testimonials">@foreach($testimonials as $testimonial)<blockquote><span aria-hidden="true">”</span><p>{{ $testimonial->quote }}</p><footer>{{ $testimonial->customer_name }}@if($testimonial->area) — {{ $testimonial->area->name }}@endif</footer>@if($testimonial->rating)<div aria-label="{{ $testimonial->rating }} من 5">{{ str_repeat('★', $testimonial->rating) }}</div>@endif</blockquote>@endforeach</div>@endif
    </div>
</section>
@endif

<section class="section-block process-reference section-indexed" data-reveal>
    <div class="container-shell"><div class="section-row"><div><p class="eyebrow">مسار واضح</p><h2>من صورتك إلى التنفيذ</h2></div></div>
        <ol class="process-line"><li><span>01</span><i aria-hidden="true">▣</i><strong>أرسل الصور</strong></li><li><span>02</span><i aria-hidden="true">⌁</i><strong>المعاينة والقياس</strong></li><li><span>03</span><i aria-hidden="true">✓</i><strong>اعتماد العرض</strong></li><li><span>04</span><i aria-hidden="true">▰</i><strong>التنفيذ والتسليم</strong></li></ol>
    </div>
</section>

<section class="section-block editorial-reference section-indexed" data-reveal>
    <div class="container-shell"><div class="section-row"><div><p class="eyebrow">قبل أن تبدأ مشروعك</p><h2>مقالات تساعدك على الاختيار</h2></div><a class="text-link" href="{{ route('guide.index') }}">كل المقالات ←</a></div>
        <div class="editorial-reference-grid">@foreach($articles as $article)<x-article-card :article="$article" />@endforeach</div>
    </div>
</section>

@if($faqs->isNotEmpty())<x-faqs :faqs="$faqs" />@endif

<section class="quick-quote-reference" data-reveal>
    <div class="container-shell quick-quote-shell"><div><p class="eyebrow">خطوة أولى</p><h2>خذ تقديرًا أوليًا لمشروعك</h2><small>املأ البيانات وسنتواصل معك خلال وقت قصير.</small></div>
        <form method="POST" action="{{ route('leads.store') }}" class="compact-lead-form">
            @csrf<input type="hidden" name="type" value="quote"><input type="hidden" name="preferred_contact" value="phone">
            <div class="honeypot" aria-hidden="true"><label>الموقع <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
            <label><span class="sr-only">الاسم</span><input name="name" value="{{ old('name') }}" required maxlength="100" placeholder="الاسم"></label>
            <label><span class="sr-only">رقم الجوال</span><input name="phone" value="{{ old('phone') }}" required maxlength="25" dir="ltr" inputmode="tel" placeholder="رقم الجوال"></label>
            <label><span class="sr-only">الحي</span><input name="area" value="{{ old('area') }}" maxlength="120" placeholder="الحي"></label>
            <label><span class="sr-only">نوع الخدمة</span><select name="service_id"><option value="">نوع الخدمة</option>@foreach($featuredServices as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach</select></label>
            <button class="button button-primary" type="submit">إرسال الطلب</button>
        </form>
        @if($errors->any())<p class="compact-form-error" role="alert">تحقق من الاسم ورقم الجوال ثم أرسل الطلب مرة أخرى.</p>@endif
    </div>
</section>
@endsection

@push('scripts')
    @vite('resources/js/home.js')
@endpush
