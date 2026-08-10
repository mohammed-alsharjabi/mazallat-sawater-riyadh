@extends('layouts.app')

@section('content')
<section class="reference-hero section-indexed">
    <div class="container-shell reference-hero-grid">
        <div class="reference-hero-copy" data-hero-copy>
            <p class="eyebrow">{{ $siteSettings['hero_eyebrow'] ?? 'حلول هندسية للمساحات الخارجية' }}</p>
            <h1>{{ $siteSettings['hero_title'] ?? 'نصمم الظل كجزء من المكان' }}</h1>
            <p>{{ $siteSettings['hero_description'] ?? 'مظلات وسواتر وهياكل مصممة لمناخ الرياض ومساحة مشروعك.' }}</p>
            <div class="hero-actions"><a class="button button-primary" href="{{ route('quote') }}">ابدأ مشروعك</a><a class="text-link underline-link" href="#services">استعرض الأعمال</a></div>
        </div>
        <div class="technical-image hero-reference-image" data-hero-visual>
            @if($heroImage)
                <x-responsive-image :image="$heroImage" :alt="$heroImage->alt_text ?: ($heroService?->name ?? 'مظلات وسواتر في الرياض')" variant="cover" loading="eager" fetchpriority="high" sizes="(max-width: 780px) 100vw, 82vw" />
                <span class="measure measure-top" aria-hidden="true">عرض بحسب الموقع</span><span class="measure measure-side" aria-hidden="true">ارتفاع المعاينة</span>
            @endif
        </div>
        @if($heroService)
            <dl class="hero-facts">
                <div><dt>الموقع</dt><dd>{{ $siteSettings['city'] }}</dd></div>
                <div><dt>الخامة</dt><dd>{{ $heroService->category->name }}</dd></div>
                @if($heroService->images_count)<div><dt>المعرض</dt><dd>{{ $heroService->images_count }} صورة حقيقية</dd></div>@endif
            </dl>
        @endif
    </div>
</section>

<section id="services" class="section-block section-indexed services-accordion-section" data-service-accordion data-reveal>
    <div class="container-shell reference-two-column">
        <div class="section-copy"><p class="eyebrow">الخدمات الأساسية</p><h2>اختر ما تريد تنفيذه</h2><p>الصور والمحتوى في كل خدمة مرتبطان مباشرة بقاعدة البيانات ومعرضها المعتمد.</p></div>
        <div class="services-accordion">
            @foreach($featuredServices as $item)
                @php($active = $item->name === 'مظلات شد إنشائي' || ($loop->first && !$featuredServices->contains('name', 'مظلات شد إنشائي')))
                @php($accordionImage = $item->images->firstWhere('is_cover', true) ?: $item->images->first())
                <article @class(['service-accordion-item', 'active' => $active]) data-accordion-item>
                    <button type="button" aria-expanded="{{ $active ? 'true' : 'false' }}" data-accordion-trigger>
                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <strong>{{ $item->name }}</strong>
                        @if($item->images_count)<small>{{ $item->images_count }} صورة</small>@endif
                        <i aria-hidden="true">⌄</i>
                    </button>
                    <div class="service-accordion-panel" @if(!$active) hidden @endif>
                        @if($accordionImage)<a href="{{ route('services.show', $item->slug) }}"><x-responsive-image :image="$accordionImage" :alt="$accordionImage->alt_text ?: $item->name" variant="gallery" sizes="(max-width: 780px) 100vw, 55vw" /></a>@endif
                        <div><p>{{ $item->excerpt }}</p><a class="text-link" href="{{ route('services.show', $item->slug) }}">تفاصيل الخدمة ←</a></div>
                    </div>
                </article>
            @endforeach
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
