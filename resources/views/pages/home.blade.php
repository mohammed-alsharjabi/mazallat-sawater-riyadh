@extends('layouts.app')

@section('content')
<section class="home-hero home-hero-premium">
    <div class="container-shell premium-hero-grid">
        <div class="hero-copy" data-hero-copy>
            <p class="eyebrow eyebrow-light">{{ $siteSettings['hero_eyebrow'] ?? 'حلول تظليل وخصوصية داخل الرياض' }}</p>
            <h1>{{ $siteSettings['hero_title'] ?? 'مظلات وسواتر الرياض بتصاميم عصرية وتنفيذ احترافي' }}</h1>
            <p class="hero-lead">{{ $siteSettings['hero_description'] ?? 'خدمات المظلات والسواتر لجميع أحياء الرياض، تبدأ بمعاينة الموقع والمقاسات.' }}</p>
            <div class="hero-actions">
                <a class="button button-accent" href="{{ route('quote') }}">{{ $siteSettings['inspection_cta_label'] ?? 'اطلب معاينة مجانية' }}</a>
                <a class="button button-ghost" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">تواصل عبر واتساب</a>
            </div>
            <p class="hero-location"><span aria-hidden="true">⌖</span> نطاق الخدمة: {{ $siteSettings['city'] }} بجميع أحيائها</p>
        </div>
        <div class="hero-media" data-hero-visual data-parallax>
            @if($heroProject)
                @php($heroImage=$heroProject->images->firstWhere('is_cover',true) ?? $heroProject->images->first())
                <x-responsive-image :image="$heroImage" :alt="$heroImage->alt_text ?: $heroProject->title" loading="eager" fetchpriority="high" sizes="(max-width: 900px) 100vw, 52vw" />
                <a class="hero-project-caption" href="{{ route('projects.show',$heroProject->slug) }}"><small>مشروع موثق · {{ $heroProject->area->name }}</small><strong>{{ $heroProject->title }}</strong><span>عرض المشروع ←</span></a>
            @else
                <div class="architectural-fallback" aria-label="مساحة مخصصة لصورة مشروع حقيقي">
                    <div class="structure-lines"><i></i><i></i><i></i><span></span></div>
                    <p><small>الصورة الرئيسية</small><strong>تُعرض تلقائيًا من مشروع حقيقي معتمد</strong></p>
                </div>
            @endif
        </div>
    </div>
</section>

@if($trustItems->isNotEmpty())
<section class="trust-strip" aria-label="بيانات موثقة عن النشاط">
    <div class="container-shell trust-grid">@foreach($trustItems as $item)<article data-counter-item><strong @if(is_numeric($item->value)) data-counter="{{ $item->value }}" @endif>{{ $item->value }}</strong><div><h2>{{ $item->label }}</h2>@if($item->description)<p>{{ $item->description }}</p>@endif</div></article>@endforeach</div>
</section>
@endif

<section class="section-block service-categories-section" data-reveal>
    <div class="container-shell">
        <div class="section-row"><x-section-heading eyebrow="التصنيفات الرئيسية" title="حلول للمساحة والخصوصية والظل" description="ابدأ من الاستخدام المطلوب، ثم راجع التفاصيل والخامات والعوامل المؤثرة في التنفيذ." /><span class="section-number">01</span></div>
        <div class="premium-category-grid mt-10">
            @forelse($categories as $category)
                <article><div class="category-structure" aria-hidden="true"><i></i><i></i><i></i></div><span>0{{ $loop->iteration }}</span><h3><a href="{{ route('services.category',$category->slug) }}">{{ $category->name }}</a></h3><p>{{ $category->excerpt }}</p><ul>@foreach($category->services->take(3) as $service)<li><a href="{{ route('services.show',$service->slug) }}">{{ $service->name }}</a></li>@endforeach</ul><a class="text-link" href="{{ route('services.category',$category->slug) }}">استعرض التصنيف ←</a></article>
            @empty
                <div class="empty-state"><h3>لا توجد تصنيفات منشورة</h3><p>ستظهر التصنيفات بعد اعتمادها من لوحة الإدارة.</p></div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-block sand-section" data-reveal>
    <div class="container-shell">
        <div class="section-row"><x-section-heading eyebrow="وفق بيانات الطلبات" title="الخدمات الأكثر طلبًا" description="لا تظهر خدمة في هذه القائمة إلا بعد اعتمادها من لوحة الإدارة بناءً على بيانات فعلية." /><span class="section-number">02</span></div>
        <div class="cards-grid mt-10">@forelse($popularServices as $service)<x-service-card :service="$service" />@empty<div class="empty-state"><h3>لم تُعتمد خدمات كأكثر طلبًا بعد</h3><p>تظل هذه المساحة بلا ادعاءات حتى تتوفر بيانات طلبات فعلية.</p></div>@endforelse</div>
    </div>
</section>

<section class="section-block projects-showcase" data-projects-section data-reveal>
    <div class="container-shell">
        <div class="section-row"><x-section-heading eyebrow="أحدث الأعمال" title="مشاريع حقيقية داخل الرياض" description="فلتر المشاريع المنشورة حسب الخدمة أو المنطقة، من دون تحميل المحتوى بعد فتح الصفحة." /><a class="text-link" href="{{ route('projects.index') }}">كل المشاريع ←</a></div>
        @if($projects->isNotEmpty())
            <div class="project-filter-bar" role="group" aria-label="فلترة المشاريع">
                <button class="active" type="button" data-filter="all">الكل</button>
                @foreach($projects->pluck('service')->unique('id')->values() as $service)<button type="button" data-filter-type="service" data-filter="{{ $service->id }}">{{ $service->name }}</button>@endforeach
                @foreach($projects->pluck('area')->unique('id')->values() as $area)<button type="button" data-filter-type="area" data-filter="{{ $area->id }}">{{ $area->name }}</button>@endforeach
            </div>
            <div class="projects-grid premium-projects-grid" data-project-grid>@foreach($projects as $project)<div data-project-card data-service="{{ $project->service_id }}" data-area="{{ $project->area_id }}"><x-project-card :project="$project" /></div>@endforeach</div>
            <p class="project-filter-empty" hidden data-filter-empty>لا توجد مشاريع ضمن هذا الفلتر.</p>
        @else
            <div class="empty-state mt-10"><h3>لا توجد مشاريع منشورة حاليًا</h3><p>تظهر هنا فقط صور ومعلومات أعمال حقيقية بعد توثيقها واعتمادها.</p></div>
        @endif
    </div>
</section>

<section class="section-block before-after-section" data-reveal>
    <div class="container-shell before-after-layout">
        <div><p class="eyebrow eyebrow-light">قبل وبعد</p><h2>الفرق يظهر في التفاصيل</h2><p>مقارنة بصرية لا تُعرض إلا عند رفع صورتين حقيقيتين لمشروع واحد وتحديدهما «قبل» و«بعد» من لوحة الإدارة.</p></div>
        @if($beforeAfterProject)
            @php($before=$beforeAfterProject->images->firstWhere('stage','before'))
            @php($after=$beforeAfterProject->images->firstWhere('stage','after'))
            <div class="before-after" data-before-after style="--position:50%">
                <x-responsive-image :image="$after" :alt="'بعد تنفيذ '.$beforeAfterProject->title" sizes="(max-width: 900px) 100vw, 62vw" class="after-image" />
                <div class="before-layer"><x-responsive-image :image="$before" :alt="'قبل تنفيذ '.$beforeAfterProject->title" sizes="(max-width: 900px) 100vw, 62vw" class="before-image" /></div>
                <span class="before-label">قبل</span><span class="after-label">بعد</span><span class="slider-line" aria-hidden="true"></span>
                <input type="range" min="0" max="100" value="50" aria-label="حرّك للمقارنة بين قبل وبعد" data-before-after-range>
            </div>
        @else
            <div class="empty-visual"><div class="split-placeholder"><span>قبل</span><span>بعد</span></div><p>بانتظار صور مقارنة موثقة.</p></div>
        @endif
    </div>
</section>

<section class="section-block materials-section" data-reveal>
    <div class="container-shell"><div class="section-row"><x-section-heading eyebrow="مقارنة واعية" title="الخامات والأنواع المتاحة" description="تظهر الخامات التي تعتمدها الإدارة فقط، مع وصف يساعد على المقارنة من دون أرقام أو وعود غير موثقة." /><span class="section-number">03</span></div>
        <div class="materials-compare mt-10">@forelse($materials as $material)<article><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><h3>{{ $material->name }}</h3><p>{{ $material->excerpt ?: $material->description }}</p><dl><div><dt>مرتبطة بـ</dt><dd>{{ $material->services_count }} خدمات</dd></div>@if($material->is_price_published && $material->price_from)<div><dt>السعر المعتمد</dt><dd>من {{ number_format($material->price_from,0) }} ر.س {{ $material->price_unit }}</dd></div>@endif</dl></article>@empty<div class="empty-state"><h3>الخامات لم تُعتمد للنشر بعد</h3><p>يمكن إضافتها ومقارنتها من لوحة الإدارة عند توفر بيانات صحيحة.</p></div>@endforelse</div>
    </div>
</section>

<section class="section-block estimator-section" data-reveal>
    <div class="container-shell estimator-layout">
        <div><p class="eyebrow eyebrow-light">حاسبة مساحة</p><h2>قدّر المساحة، واترك السعر للمعاينة</h2><p>احسب مساحة تقريبية لتسهيل وصف الطلب. لا تعرض الحاسبة سعرًا لأن الخامة والتثبيت وظروف الموقع عناصر لا يحددها رقم المساحة وحده.</p></div>
        <form class="area-calculator" data-area-calculator>
            <div><label for="calc-width">العرض التقريبي بالمتر</label><input id="calc-width" type="number" inputmode="decimal" min="0.5" max="100" step="0.1" placeholder="مثال: 5" data-calc-width></div>
            <span aria-hidden="true">×</span>
            <div><label for="calc-length">الطول التقريبي بالمتر</label><input id="calc-length" type="number" inputmode="decimal" min="0.5" max="100" step="0.1" placeholder="مثال: 6" data-calc-length></div>
            <output aria-live="polite"><small>المساحة التقديرية</small><strong data-calc-output>— م²</strong></output>
            <a class="button button-accent is-disabled" href="{{ route('quote') }}" aria-disabled="true" data-calc-submit>اطلب عرضًا لهذه المساحة</a>
        </form>
    </div>
</section>

<section class="section-block workflow-section" data-reveal>
    <div class="container-shell"><x-section-heading eyebrow="من الموقع إلى التركيب" title="مراحل العمل" description="مسار واضح يساعد على تثبيت المتطلبات قبل التنفيذ." />
        <ol class="workflow-grid mt-10"><li><span>01</span><h3>معاينة</h3><p>فهم الموقع والاستخدام والعناصر القائمة.</p></li><li><span>02</span><h3>قياس</h3><p>تثبيت الأبعاد ونطاق التغطية المطلوب.</p></li><li><span>03</span><h3>تصميم</h3><p>اختيار الشكل والخامة والتفاصيل المناسبة.</p></li><li><span>04</span><h3>تصنيع</h3><p>تجهيز العناصر وفق المواصفات المعتمدة.</p></li><li><span>05</span><h3>تركيب</h3><p>تنفيذ النطاق ومراجعة التفاصيل النهائية.</p></li></ol>
    </div>
</section>

<section class="section-block coverage-section" data-reveal>
    <div class="container-shell coverage-layout"><div class="riyadh-grid" aria-hidden="true"><span>وسط</span><span>شمال</span><span>شرق</span><span>غرب</span><span>جنوب</span><strong>الرياض</strong></div><div><x-section-heading eyebrow="نطاق التغطية" title="خدمة جميع أحياء الرياض" description="صفحات المناطق تربط كل نطاق بمشاريعه الحقيقية عند توفرها." /><div class="area-chips mt-7">@foreach($areas as $area)<a href="{{ route('areas.show',$area->slug) }}">{{ $area->name }} @if($area->projects_count)<small>{{ $area->projects_count }}</small>@endif</a>@endforeach</div><a class="button button-outline mt-8" href="{{ route('areas.index') }}">كل مناطق الخدمة</a></div></div>
</section>

<section class="section-block testimonials-section" data-reveal>
    <div class="container-shell"><x-section-heading eyebrow="تقييمات موثقة" title="تجارب العملاء الحقيقية" description="لا يظهر رأي قبل اعتماده وربطه ببيانات صحيحة من الإدارة." />
        <div class="testimonials-grid mt-10">@forelse($testimonials as $testimonial)<blockquote>@if($testimonial->rating)<div class="rating" aria-label="{{ $testimonial->rating }} من 5">{{ str_repeat('★',$testimonial->rating) }}</div>@endif<p>“{{ $testimonial->quote }}”</p><footer><strong>{{ $testimonial->customer_name }}</strong>@if($testimonial->area)<span>{{ $testimonial->area->name }}</span>@endif</footer></blockquote>@empty<div class="empty-state"><h3>لا توجد تقييمات معتمدة للنشر</h3><p>لن يُعرض أي تقييم تجريبي أو غير موثق.</p></div>@endforelse</div>
    </div>
</section>

<x-faqs :faqs="$faqs" />

<section class="section-block editorial-section" data-reveal>
    <div class="container-shell"><div class="section-row"><x-section-heading eyebrow="مقالات وأدلة أسعار" title="معلومة تساعدك قبل القرار" description="إرشادات عربية أصلية لفهم الخيارات وتجهيز بيانات المعاينة." /><a class="text-link" href="{{ route('guide.index') }}">كل الدليل ←</a></div><div class="articles-grid mt-10">@forelse($articles as $article)<x-article-card :article="$article" />@empty<div class="empty-state">لا توجد مقالات منشورة.</div>@endforelse</div><a class="button button-outline mt-8" href="{{ route('prices') }}">استعرض دليل الأسعار المعتمدة</a></div>
</section>

<section class="cta-band premium-cta"><div class="container-shell"><div><p class="eyebrow eyebrow-light">الخطوة التالية</p><h2>ابدأ بمعاينة موقعك داخل الرياض</h2><p>أرسل الموقع ونوع الاستخدام والمقاسات التقريبية إن توفرت.</p></div><div><a class="button button-accent" href="{{ route('quote') }}">{{ $siteSettings['inspection_cta_label'] ?? 'اطلب معاينة مجانية' }}</a><a class="button button-ghost" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a><a class="button button-ghost" href="{{ $siteSettings['phone_tel'] }}">اتصال</a></div></div></section>
@endsection

@push('scripts')
    @vite('resources/js/home.js')
@endpush
